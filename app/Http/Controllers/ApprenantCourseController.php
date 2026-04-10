<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseComment;
use App\Models\CourseEnrollment;
use App\Models\Evaluation;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ApprenantCourseController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = trim((string) $request->query('q'));
        $category = trim((string) $request->query('category'));

        $courses = collect();
        $categories = collect();

        if (Schema::hasTable('course_enrollments')) {
            $courseIds = CourseEnrollment::approvedForLearner(optional($user)->id)->pluck('course_id');

            $eloquent = Course::with(['chapters.lessons'])
                ->whereIn('id', $courseIds)
                ->latest();

            if ($query !== '') {
                $eloquent->where(function ($q) use ($query) {
                    $q->where('title', 'like', '%'.$query.'%')
                        ->orWhere('description', 'like', '%'.$query.'%')
                        ->orWhere('category', 'like', '%'.$query.'%');
                });
            }

            if ($category !== '') {
                $eloquent->where('category', $category);
            }

            $courses = $eloquent->get();

            $baseCategories = Course::whereIn('id', $courseIds)
                ->pluck('category')
                ->filter()
                ->unique()
                ->values();
            $categories = $baseCategories;
        }

        $completedLessonIds = collect();
        if (Schema::hasTable('lesson_progress')) {
            $completedLessonIds = LessonProgress::where('eleve_user_id', optional($user)->id)
                ->where('is_completed', true)
                ->pluck('course_lesson_id');
        }

        $courses = $courses->map(fn ($course) => $this->decorateCourseProgress($course, $completedLessonIds));

        $totalCourses = $courses->count();
        $totalLessons = $courses->sum('total_lessons');
        $doneLessons = $courses->sum('done_lessons');
        $avgProgress = $totalCourses > 0 ? round((float) $courses->avg('progress_percent'), 1) : 0;
        $completedCourses = $courses->filter(function ($course) {
            return (float) $course->progress_percent >= 100;
        })->count();

        return view('dashboards.apprenant-courses', [
            'courses' => $courses,
            'categories' => $categories,
            'query' => $query,
            'selectedCategory' => $category,
            'totalCourses' => $totalCourses,
            'totalLessons' => $totalLessons,
            'doneLessons' => $doneLessons,
            'avgProgress' => $avgProgress,
            'completedCourses' => $completedCourses,
        ]);
    }

    public function show(Request $request, Course $course)
    {
        $user = $request->user();
        $course->loadMissing([
            'chapters' => function ($chapterQuery) {
                if (Schema::hasColumn('course_chapters', 'is_published')) {
                    $chapterQuery->where('is_published', true);
                }
                $chapterQuery->orderBy('position');
            },
            'chapters.lessons' => function ($lessonQuery) {
                if (Schema::hasColumn('course_lessons', 'is_published')) {
                    $lessonQuery->where('is_published', true);
                }
                $lessonQuery->orderBy('position');
            },
            'formateur',
        ]);

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

        $completedLessonIds = collect();
        if (Schema::hasTable('lesson_progress')) {
            $completedLessonIds = LessonProgress::where('eleve_user_id', optional($user)->id)
                ->where('is_completed', true)
                ->pluck('course_lesson_id');
        }

        $course = $this->decorateCourseProgress($course, $completedLessonIds);
        $orderedLessons = $course->chapters->flatMap(fn ($chapter) => $chapter->lessons)->values();
        $firstNotCompleted = $orderedLessons->first(fn ($lesson) => ! $completedLessonIds->contains($lesson->id));
        $lockedLessonIds = collect();
        if ($firstNotCompleted) {
            $firstIndex = $orderedLessons->search(fn ($lesson) => (int) $lesson->id === (int) $firstNotCompleted->id);
            $lockedLessonIds = $orderedLessons->slice($firstIndex + 1)->pluck('id')->values();
        }

        $chapters = $course->chapters->map(function ($chapter) use ($completedLessonIds) {
            $lessons = $chapter->lessons;
            $total = $lessons->count();
            $done = $lessons->whereIn('id', $completedLessonIds)->count();
            $chapter->progress_percent = $total > 0 ? round(($done / $total) * 100, 1) : 0;
            $chapter->done_lessons = $done;
            $chapter->total_lessons = $total;

            return $chapter;
        });

        $announcements = collect();
        if (Schema::hasTable('course_announcements')) {
            $announcements = CourseAnnouncement::where('course_id', $course->id)
                ->where('is_published', true)
                ->latest()
                ->take(6)
                ->get();
        }

        $approvedComments = collect();
        if (Schema::hasTable('course_comments')) {
            $approvedComments = CourseComment::with('eleve:id,name')
                ->where('course_id', $course->id)
                ->where('status', 'approved')
                ->latest()
                ->take(8)
                ->get();
        }

        $publishedEvaluationCount = 0;
        if (Schema::hasTable('evaluations')) {
            $publishedEvaluationCount = Evaluation::where('course_id', $course->id)
                ->where('is_published', true)
                ->count();
        }

        return view('dashboards.apprenant-course-show', [
            'course' => $course,
            'chapters' => $chapters,
            'announcements' => $announcements,
            'approvedComments' => $approvedComments,
            'publishedEvaluationCount' => $publishedEvaluationCount,
            'firstNotCompletedLessonId' => optional($firstNotCompleted)->id,
            'completedLessonIds' => $completedLessonIds,
            'lockedLessonIds' => $lockedLessonIds,
        ]);
    }

    private function decorateCourseProgress(Course $course, $completedLessonIds): Course
    {
        $allLessons = $course->chapters->flatMap(function ($chapter) {
            return $chapter->lessons;
        });

        $totalLessons = $allLessons->count();
        $doneLessons = $allLessons->whereIn('id', $completedLessonIds)->count();
        $progress = $totalLessons > 0 ? round(($doneLessons / $totalLessons) * 100, 1) : 0;
        $nextLesson = $allLessons->first(function ($lesson) use ($completedLessonIds) {
            return ! $completedLessonIds->contains($lesson->id);
        });

        $course->progress_percent = $progress;
        $course->total_lessons = $totalLessons;
        $course->done_lessons = $doneLessons;
        $course->next_lesson_title = optional($nextLesson)->title;
        $course->next_lesson_id = optional($nextLesson)->id;

        return $course;
    }
}
