<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use App\Models\CourseChapter;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Support\PlatformEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LessonProgressController extends Controller
{
    public function complete(Request $request, CourseLesson $lesson)
    {
        if (! Schema::hasTable('lesson_progress')) {
            return back()->withErrors(['lesson' => 'Le module de progression n est pas initialise.']);
        }

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
                return back()->withErrors(['lesson' => 'Inscrivez-vous au cours avant de valider cette lecon.']);
            }
        }

        if (Schema::hasColumn('courses', 'is_available') && ! (bool) $course->is_available) {
            return back()->withErrors(['lesson' => 'Ce cours est temporairement indisponible.']);
        }

        if (Schema::hasColumn('course_chapters', 'is_published') && ! (bool) optional($lesson->chapter)->is_published) {
            return back()->withErrors(['lesson' => 'Ce chapitre n est pas encore publie.']);
        }

        if (Schema::hasColumn('course_lessons', 'is_published') && ! (bool) $lesson->is_published) {
            return back()->withErrors(['lesson' => 'Cette lecon n est pas encore publiee.']);
        }

        $orderedLessons = collect();
        if (Schema::hasTable('course_chapters') && Schema::hasTable('course_lessons')) {
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

            $orderedLessons = $chapters->get()
                ->flatMap(fn ($chapter) => $chapter->lessons)
                ->values();
        }

        $completedLessonIds = LessonProgress::where('eleve_user_id', $user->id)
            ->where('is_completed', true)
            ->pluck('course_lesson_id');

        $firstNotCompletedIndex = $orderedLessons->search(function ($item) use ($completedLessonIds) {
            return ! $completedLessonIds->contains($item->id);
        });
        if ($firstNotCompletedIndex !== false) {
            $lockedLessonIds = $orderedLessons
                ->slice($firstNotCompletedIndex + 1)
                ->pluck('id');
            if ($lockedLessonIds->contains($lesson->id)) {
                return back()->withErrors(['lesson' => 'Cette lecon est verrouillee. Terminez les lecons precedentes.']);
            }
        }

        $alreadyCompleted = LessonProgress::where('course_lesson_id', $lesson->id)
            ->where('eleve_user_id', $user->id)
            ->where('is_completed', true)
            ->exists();

        LessonProgress::updateOrCreate(
            ['course_lesson_id' => $lesson->id, 'eleve_user_id' => $user->id],
            ['is_completed' => true, 'completed_at' => now()]
        );

        PlatformEvents::log($user->id, 'lesson.completed', CourseLesson::class, $lesson->id, ['chapter_id' => $lesson->course_chapter_id]);

        if (! $alreadyCompleted) {
            $chapterLessons = CourseLesson::where('course_chapter_id', $lesson->course_chapter_id)
                ->when(Schema::hasColumn('course_lessons', 'is_published'), function ($query) {
                    $query->where('is_published', true);
                })
                ->pluck('id');

            $chapterDoneCount = LessonProgress::where('eleve_user_id', $user->id)
                ->where('is_completed', true)
                ->whereIn('course_lesson_id', $chapterLessons)
                ->count();

            if ($chapterLessons->count() > 0 && $chapterDoneCount === $chapterLessons->count()) {
                PlatformEvents::notify(
                    (int) $user->id,
                    'Chapitre termine',
                    'Bravo, vous avez termine le chapitre '.$lesson->chapter->title.'.'
                );
                PlatformEvents::log(
                    $user->id,
                    'chapter.completed',
                    CourseChapter::class,
                    $lesson->course_chapter_id,
                    ['course_id' => $course->id]
                );
            }

            $courseLessonIds = CourseLesson::whereHas('chapter', function ($query) use ($course) {
                $query->where('course_id', $course->id);
                if (Schema::hasColumn('course_chapters', 'is_published')) {
                    $query->where('is_published', true);
                }
            })
                ->when(Schema::hasColumn('course_lessons', 'is_published'), function ($query) {
                    $query->where('is_published', true);
                })
                ->pluck('id');

            $courseDoneCount = LessonProgress::where('eleve_user_id', $user->id)
                ->where('is_completed', true)
                ->whereIn('course_lesson_id', $courseLessonIds)
                ->count();

            if ($courseLessonIds->count() > 0 && $courseDoneCount === $courseLessonIds->count()) {
                PlatformEvents::notify(
                    (int) $user->id,
                    'Cours termine',
                    'Felicitations, vous avez complete le cours '.$course->title.'.'
                );
                PlatformEvents::log($user->id, 'course.completed', CourseLesson::class, $lesson->id, ['course_id' => $course->id]);
            }
        }

        return back()->with('success_progress', 'Lecon marquee comme terminee.');
    }
}
