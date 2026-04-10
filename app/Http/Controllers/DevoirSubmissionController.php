<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\DevoirSubmission;
use App\Support\PlatformEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DevoirSubmissionController extends Controller
{
    public function store(Request $request)
    {
        if (! Schema::hasTable('devoir_submissions')) {
            return back()->withErrors([
                'devoir_pdf' => 'Le module des devoirs n est pas encore initialise. Lancez les migrations.',
            ]);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'devoir_pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        if (! $course->is_available) {
            return back()->withErrors([
                'course_id' => 'Ce cours n accepte pas encore les devoirs.',
            ]);
        }

        $user = $request->user();

        if (Schema::hasTable('course_enrollments')) {
            $isEnrolled = CourseEnrollment::approvedForLearner(optional($user)->id)
                ->where('course_id', $course->id)
                ->exists();

            if (! $isEnrolled) {
                return back()->withErrors([
                    'course_id' => 'Inscrivez-vous d abord a ce cours pour envoyer un devoir.',
                ]);
            }
        }

        // Quota simple: max 10 devoirs/jour par apprenant.
        $dailyCount = DevoirSubmission::where('eleve_user_id', optional($user)->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        if ($dailyCount >= 10) {
            return back()->withErrors([
                'devoir_pdf' => 'Quota atteint: 10 devoirs maximum par jour.',
            ]);
        }

        $pdfPath = $request->file('devoir_pdf')->store('devoirs/submissions', 'public');

        DevoirSubmission::create([
            'course_id' => $course->id,
            'eleve_user_id' => optional($user)->id,
            'student_name' => optional($user)->name ?? 'Apprenant',
            'student_email' => optional($user)->email ?? 'inconnu@leedcourse.local',
            'pdf_path' => $pdfPath,
        ]);

        PlatformEvents::log(optional($user)->id, 'devoir.submitted', DevoirSubmission::class, null, ['course_id' => $course->id]);
        if ($course->formateur_user_id) {
            PlatformEvents::notify((int) $course->formateur_user_id, 'Nouveau devoir', 'Un apprenant a soumis un devoir pour '.$course->title);
        }

        return back()->with('success_devoir', 'Votre devoir PDF a ete envoye au formateur.');
    }

    public function update(Request $request, DevoirSubmission $submission)
    {
        if (! Schema::hasTable('devoir_submissions')) {
            return back()->withErrors([
                'status' => 'Le module des devoirs n est pas encore initialise. Lancez les migrations.',
            ]);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_review,corrected'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:20'],
            'correction_note' => ['nullable', 'string', 'max:3000'],
            'corrected_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $user = $request->user();
        if (! $user || ! $user->isEnseignant()) {
            abort(403);
        }

        $submission->loadMissing('course');
        $course = $submission->course;
        $isOwnerFormateur = $course && (int) $course->formateur_user_id === (int) $user->id;
        $isCoFormateur = $course
            && Schema::hasTable('course_co_formateurs')
            && $course->coFormateurs()->where('users.id', $user->id)->exists();

        if (! $isOwnerFormateur && ! $isCoFormateur) {
            abort(403);
        }

        if ($request->hasFile('corrected_pdf')) {
            $submission->corrected_pdf_path = $request->file('corrected_pdf')->store('devoirs/corrections', 'public');
        }

        $submission->status = $validated['status'];
        $submission->score = $validated['score'] ?? null;
        $submission->correction_note = $validated['correction_note'] ?? null;
        $submission->corrected_at = $validated['status'] === 'corrected' ? now() : null;
        $submission->save();

        PlatformEvents::log(optional($user)->id, 'devoir.corrected', DevoirSubmission::class, $submission->id, ['status' => $submission->status]);
        if ($submission->eleve_user_id) {
            PlatformEvents::notify((int) $submission->eleve_user_id, 'Devoir corrigé', 'Votre devoir a ete corrige.');
        }

        return back()->with('success_correction', 'Correction enregistree avec succes.');
    }
}
