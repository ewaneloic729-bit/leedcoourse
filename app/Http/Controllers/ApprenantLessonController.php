<?php

namespace App\Http\Controllers;

use App\Models\CourseChapter;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ApprenantLessonController extends Controller
{
    public function show(Request $request, CourseLesson $lesson)
    {
        $user = $request->user();
        $lesson->loadMissing('chapter.course');
        $course = optional($lesson->chapter)->course;

        if (! $course) {
            abort(404);
        }

        if (Schema::hasTable('course_enrollments')) {
            $isEnrolled = CourseEnrollment::approvedForLearner(optional($user)->id)
                ->where('course_id', $course->id)
                ->exists();

            if (! $isEnrolled) {
                abort(403);
            }
        }

        if (Schema::hasColumn('courses', 'is_available') && ! (bool) $course->is_available) {
            abort(403);
        }

        if (Schema::hasColumn('course_chapters', 'is_published') && ! (bool) optional($lesson->chapter)->is_published) {
            abort(404);
        }

        if (Schema::hasColumn('course_lessons', 'is_published') && ! (bool) $lesson->is_published) {
            abort(404);
        }

        $chapters = CourseChapter::with(['lessons' => function ($query) {
            $query->orderBy('position');
            if (Schema::hasColumn('course_lessons', 'is_published')) {
                $query->where('is_published', true);
            }
        }])
            ->where('course_id', $course->id)
            ->orderBy('position');

        if (Schema::hasColumn('course_chapters', 'is_published')) {
            $chapters->where('is_published', true);
        }

        $chapters = $chapters->get();
        $orderedLessons = $chapters->flatMap(function ($chapter) {
            return $chapter->lessons;
        })->values();

        $currentIndex = $orderedLessons->search(function ($item) use ($lesson) {
            return (int) $item->id === (int) $lesson->id;
        });

        $previousLesson = $currentIndex !== false && $currentIndex > 0 ? $orderedLessons->get($currentIndex - 1) : null;
        $nextLesson = $currentIndex !== false ? $orderedLessons->get($currentIndex + 1) : null;

        $completedLessonIds = collect();
        if (Schema::hasTable('lesson_progress')) {
            $completedLessonIds = LessonProgress::where('eleve_user_id', optional($user)->id)
                ->where('is_completed', true)
                ->pluck('course_lesson_id');
        }

        $lockedLessonIds = collect();
        $firstNotCompletedIndex = $orderedLessons->search(function ($item) use ($completedLessonIds) {
            return ! $completedLessonIds->contains($item->id);
        });
        if ($firstNotCompletedIndex !== false) {
            $lockedLessonIds = $orderedLessons
                ->slice($firstNotCompletedIndex + 1)
                ->pluck('id')
                ->values();
        }

        if ($currentIndex !== false
            && $lockedLessonIds->contains($lesson->id)
            && ! $completedLessonIds->contains($lesson->id)) {
            $targetLesson = $orderedLessons->get($firstNotCompletedIndex);

            return redirect()
                ->route('apprenant.lessons.show', $targetLesson)
                ->withErrors(['lesson' => 'Cette lecon est verrouillee. Terminez d abord les lecons precedentes.']);
        }

        return view('dashboards.apprenant-lesson', [
            'course' => $course,
            'lesson' => $lesson,
            'chapters' => $chapters,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'completedLessonIds' => $completedLessonIds,
            'lockedLessonIds' => $lockedLessonIds,
        ]);
    }
}
