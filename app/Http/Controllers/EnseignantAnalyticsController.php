<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\EvaluationAttempt;
use App\Models\EvaluationQuestion;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnseignantAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $userId = optional($request->user())->id;

        $courses = Course::where('formateur_user_id', $userId)->get();
        $courseIds = $courses->pluck('id');

        $lessonCompletionRate = null;
        if (Schema::hasTable('lesson_progress') && Schema::hasTable('course_lessons')) {
            $lessonIds = CourseLesson::whereHas('chapter', function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds);
            })->pluck('id');

            $totalCompletions = LessonProgress::whereIn('course_lesson_id', $lessonIds)->where('is_completed', true)->count();
            $totalRows = LessonProgress::whereIn('course_lesson_id', $lessonIds)->count();
            $lessonCompletionRate = $totalRows > 0 ? round(($totalCompletions / $totalRows) * 100, 2) : null;
        }

        $attempts = collect();
        $avgScore = null;
        if (Schema::hasTable('evaluation_attempts')) {
            $attempts = EvaluationAttempt::whereHas('evaluation', function ($q) use ($userId) {
                $q->where('formateur_user_id', $userId);
            })->get();
            $avgScore = round((float) $attempts->avg('score'), 2);
        }

        $questionFailure = collect();
        if (Schema::hasTable('evaluation_attempt_answers') && Schema::hasTable('evaluation_questions')) {
            $questionFailure = EvaluationQuestion::whereHas('evaluation', function ($q) use ($userId) {
                $q->where('formateur_user_id', $userId);
            })->withCount(['evaluation'])
            ->take(10)
            ->get();
        }

        return view('dashboards.enseignant-analytics', [
            'coursesCount' => $courses->count(),
            'attemptsCount' => $attempts->count(),
            'avgScore' => $avgScore,
            'lessonCompletionRate' => $lessonCompletionRate,
            'questionFailure' => $questionFailure,
        ]);
    }
}
