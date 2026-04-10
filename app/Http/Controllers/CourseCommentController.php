<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseComment;
use App\Models\CourseEnrollment;
use App\Support\PlatformEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CourseCommentController extends Controller
{
    public function store(Request $request)
    {
        if (! Schema::hasTable('course_comments')) {
            return back()->withErrors(['comment' => 'Le module des commentaires n est pas initialise. Lancez les migrations.']);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:8', 'max:2000'],
        ]);

        $user = $request->user();
        $course = Course::findOrFail($validated['course_id']);

        if (Schema::hasTable('course_enrollments')) {
            $isEnrolled = CourseEnrollment::approvedForLearner($user->id)
                ->where('course_id', $course->id)
                ->exists();

            if (! $isEnrolled) {
                return back()->withErrors(['course_id' => 'Inscrivez-vous d abord a ce cours pour laisser un commentaire.']);
            }
        }

        $comment = CourseComment::create([
            'course_id' => $course->id,
            'eleve_user_id' => $user->id,
            'rating' => $validated['rating'] ?? null,
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        PlatformEvents::log($user->id, 'course.comment.created', CourseComment::class, $comment->id, ['course_id' => $course->id]);
        if ($course->formateur_user_id) {
            PlatformEvents::notify((int) $course->formateur_user_id, 'Nouveau commentaire', $user->name.' a laisse un commentaire sur '.$course->title);
        }

        return back()->with('success_comment', 'Commentaire envoye. Il sera visible apres validation du formateur.');
    }

    public function moderate(Request $request, CourseComment $comment)
    {
        if (! Schema::hasTable('course_comments')) {
            return back()->withErrors(['status' => 'Le module des commentaires n est pas initialise. Lancez les migrations.']);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,hidden'],
            'formateur_reply' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        if (! $user || ! $user->isEnseignant()) {
            abort(403);
        }

        $allowedCourseIds = Course::query()
            ->when(Schema::hasColumn('courses', 'formateur_user_id'), function ($query) use ($user) {
                $query->where('formateur_user_id', $user->id);
            }, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->pluck('id');

        if (Schema::hasTable('course_co_formateurs')) {
            $coIds = $user->coFormateurCourses()->pluck('courses.id');
            $allowedCourseIds = $allowedCourseIds->merge($coIds)->unique()->values();
        }

        if (! $allowedCourseIds->contains($comment->course_id)) {
            abort(403);
        }

        $comment->status = $validated['status'];
        $comment->formateur_reply = $validated['formateur_reply'] ?? null;
        $comment->moderated_by_user_id = $user->id;
        $comment->moderated_at = now();
        $comment->save();

        PlatformEvents::log($user->id, 'course.comment.moderated', CourseComment::class, $comment->id, ['status' => $comment->status]);
        if ($comment->eleve_user_id) {
            PlatformEvents::notify((int) $comment->eleve_user_id, 'Commentaire mis a jour', 'Votre commentaire a ete traite par le formateur.');
        }

        return back()->with('success_comment_moderation', 'Commentaire mis a jour.');
    }
}
