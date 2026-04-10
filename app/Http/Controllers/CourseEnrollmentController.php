<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use App\Support\PlatformEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseEnrollmentController extends Controller
{
    public function store(Request $request)
    {
        if (! Schema::hasTable('course_enrollments')) {
            return back()->withErrors(['course_id' => 'Le module des inscriptions n est pas initialise. Lancez les migrations.']);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $user = $request->user();
        $course = Course::findOrFail($validated['course_id']);
        CourseEnrollment::rejectExpiredPending();

        if (Schema::hasColumn('courses', 'is_available') && ! (bool) $course->is_available) {
            return back()->withErrors([
                'course_id' => 'Ce cours n est pas encore disponible a l inscription.',
            ]);
        }

        if (Schema::hasColumn('courses', 'publication_status') && $course->publication_status && $course->publication_status !== 'published') {
            return back()->withErrors([
                'course_id' => 'Ce cours n est pas publie pour les apprenants.',
            ]);
        }

        if (Schema::hasColumn('courses', 'is_promo_only') && (bool) $course->is_promo_only) {
            return back()->withErrors([
                'course_id' => 'Ce cours est une vitrine publicitaire et ne permet pas encore les inscriptions.',
            ]);
        }

        $enrollment = CourseEnrollment::where('course_id', $course->id)
            ->where('eleve_user_id', $user->id)
            ->first();

        if ($enrollment && CourseEnrollment::usesStatusWorkflow()) {
            if ($enrollment->status === CourseEnrollment::STATUS_APPROVED) {
                return back()->with('success_enroll', 'Vous etes deja inscrit a ce cours.');
            }

            if ($enrollment->status === CourseEnrollment::STATUS_PENDING && $enrollment->response_deadline_at && now()->lte($enrollment->response_deadline_at)) {
                return back()->with('success_enroll', 'Votre demande est deja en attente. Reponse au plus tard le '.$enrollment->response_deadline_at->format('d/m/Y H:i').'.');
            }
        }

        if (! $enrollment) {
            $enrollment = new CourseEnrollment();
            $enrollment->course_id = $course->id;
            $enrollment->eleve_user_id = $user->id;
        }

        if (CourseEnrollment::usesStatusWorkflow()) {
            $enrollment->status = CourseEnrollment::STATUS_PENDING;
            $enrollment->requested_at = now();
            $enrollment->response_deadline_at = now()->addDays(3);
            $enrollment->decision_at = null;
            $enrollment->responded_by_user_id = null;
            $enrollment->response_note = null;
            $enrollment->enrolled_at = null;
        } else {
            $enrollment->enrolled_at = now();
        }

        $enrollment->save();

        if (CourseEnrollment::usesStatusWorkflow()) {
            PlatformEvents::log($user->id, 'course.enrollment.requested', Course::class, $course->id, ['course_title' => $course->title]);
            $superadminIds = User::query()
                ->where('role', User::ROLE_SUPERADMIN)
                ->when(Schema::hasColumn('users', 'is_active'), function ($query) {
                    $query->where('is_active', true);
                })
                ->pluck('id');
            foreach ($superadminIds as $superadminId) {
                PlatformEvents::notify(
                    (int) $superadminId,
                    'Demande d inscription',
                    $user->name.' demande l acces au cours '.$course->title.'. Reponse admin attendue sous 3 jours.'
                );
            }

            return back()->with('success_enroll', 'Demande envoyee. L administration doit accepter ou refuser sous 3 jours maximum.');
        }

        PlatformEvents::log($user->id, 'course.enrolled', Course::class, $course->id, ['course_title' => $course->title]);
        if ($course->formateur_user_id) {
            PlatformEvents::notify((int) $course->formateur_user_id, 'Nouvelle inscription', $user->name.' s est inscrit au cours '.$course->title);
        }

        return back()->with('success_enroll', 'Inscription au cours effectuee.');
    }

    public function decide(Request $request, CourseEnrollment $enrollment)
    {
        CourseEnrollment::rejectExpiredPending();

        $user = $request->user();
        if (! $user || ! $user->isSuperadmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'response_reason' => ['nullable', 'in:level_mismatch,prerequisites_missing,incomplete_profile,no_seats,other'],
            'response_note' => ['nullable', 'string', 'max:500'],
        ], [
            'response_reason.in' => 'Le motif choisi est invalide.',
        ]);

        $enrollment->loadMissing(['course', 'eleve']);
        $course = $enrollment->course;
        if (! $course) {
            abort(404);
        }

        if (! CourseEnrollment::usesStatusWorkflow()) {
            return back()->withErrors(['enrollment' => 'Le workflow de validation d inscription n est pas initialise.']);
        }

        if ($enrollment->status !== CourseEnrollment::STATUS_PENDING) {
            return back()->withErrors(['enrollment' => 'Cette demande est deja traitee.']);
        }

        $isApproved = $validated['decision'] === 'approve';
        $enrollment->status = $isApproved ? CourseEnrollment::STATUS_APPROVED : CourseEnrollment::STATUS_REJECTED;
        $enrollment->decision_at = now();
        $enrollment->responded_by_user_id = $user->id;
        $responseNote = $validated['response_note'] ?? null;

        if (! $isApproved) {
            $reasonCode = $validated['response_reason'] ?? null;
            $reasonMap = [
                'level_mismatch' => 'Niveau insuffisant pour ce cours',
                'prerequisites_missing' => 'Prerequis academiques manquants',
                'incomplete_profile' => 'Profil ou dossier incomplet',
                'no_seats' => 'Plus de places disponibles',
                'other' => 'Autre motif',
            ];

            if (! $reasonCode) {
                return back()->withErrors(['enrollment' => 'Veuillez selectionner un motif de refus.']);
            }

            if ($reasonCode === 'other') {
                if (! is_string($responseNote) || mb_strlen(trim($responseNote)) < 8) {
                    return back()->withErrors(['enrollment' => 'Precisez le motif de refus (minimum 8 caracteres).']);
                }
                $responseNote = trim($responseNote);
            } else {
                $baseReason = $reasonMap[$reasonCode];
                $custom = is_string($responseNote) ? trim($responseNote) : '';
                $responseNote = $custom !== '' ? $baseReason.' - '.$custom : $baseReason;
            }
        }

        $enrollment->response_note = $responseNote;
        if ($isApproved) {
            $enrollment->enrolled_at = now();
        }
        $enrollment->save();

        if ($enrollment->eleve_user_id) {
            PlatformEvents::notify(
                (int) $enrollment->eleve_user_id,
                $isApproved ? 'Demande acceptee' : 'Demande refusee',
                $isApproved
                    ? 'Felicitations. Votre demande d inscription au cours '.$course->title.' a ete acceptee.'
                    : 'Votre demande d inscription au cours '.$course->title.' a ete refusee.'
            );
        }

        PlatformEvents::log(
            $user->id,
            $isApproved ? 'course.enrollment.approved' : 'course.enrollment.rejected',
            CourseEnrollment::class,
            $enrollment->id,
            ['course_id' => $course->id, 'eleve_user_id' => $enrollment->eleve_user_id]
        );

        return back()->with('success_enroll', $isApproved ? 'Demande acceptee.' : 'Demande refusee.');
    }
}
