<?php

namespace App\Http\Controllers;

use App\Models\CourseEnrollment;
use App\Models\Evaluation;
use App\Models\EvaluationAttempt;
use App\Models\EvaluationAttemptAnswer;
use App\Support\PlatformEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ApprenantEvaluationController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('evaluations')) {
            return view('dashboards.apprenant-evaluations', [
                'evaluations' => collect(),
                'attemptsByEvaluation' => collect(),
                'setupMissing' => true,
            ]);
        }

        $user = $request->user();
        $enrolledCourseIds = collect();
        if (Schema::hasTable('course_enrollments')) {
            $enrolledCourseIds = CourseEnrollment::approvedForLearner(optional($user)->id)->pluck('course_id');
        }

        $evaluations = Evaluation::with(['course', 'questions'])
            ->where('is_published', true)
            ->when($enrolledCourseIds->isNotEmpty(), function ($query) use ($enrolledCourseIds) {
                $query->whereIn('course_id', $enrolledCourseIds);
            }, function ($query) {
                $query->whereRaw('1 = 0');
            })
            ->latest()
            ->paginate(12);

        $attemptsByEvaluation = collect();
        if (Schema::hasTable('evaluation_attempts')) {
            $attemptsByEvaluation = EvaluationAttempt::where('eleve_user_id', optional($request->user())->id)
                ->latest('id')
                ->get()
                ->unique('evaluation_id')
                ->keyBy('evaluation_id');
        }

        $now = now();
        foreach ($attemptsByEvaluation as $attempt) {
            if ($attempt->status === 'in_progress' && $attempt->expires_at && $now->greaterThan($attempt->expires_at)) {
                $attempt->status = 'expired';
                $attempt->submitted_at = $attempt->submitted_at ?? $attempt->expires_at;
                $attempt->save();
            }
        }

        if (Schema::hasTable('evaluation_attempts')) {
            $attemptsByEvaluation = EvaluationAttempt::where('eleve_user_id', optional($request->user())->id)
                ->latest('id')
                ->get()
                ->unique('evaluation_id')
                ->keyBy('evaluation_id');
        }

        return view('dashboards.apprenant-evaluations', [
            'evaluations' => $evaluations,
            'attemptsByEvaluation' => $attemptsByEvaluation,
            'setupMissing' => false,
        ]);
    }

    public function start(Request $request, Evaluation $evaluation)
    {
        if (! Schema::hasTable('evaluation_attempts')) {
            return back()->withErrors(['evaluation' => 'Le module des tentatives n est pas initialise. Lancez les migrations.']);
        }

        $user = $request->user();

        if (! $evaluation->is_published) {
            abort(403);
        }

        if (Schema::hasTable('course_enrollments')) {
            $isEnrolled = CourseEnrollment::approvedForLearner(optional($user)->id)
                ->where('course_id', $evaluation->course_id)
                ->exists();

            if (! $isEnrolled) {
                return back()->withErrors(['evaluation' => 'Inscrivez-vous au cours avant de commencer cette evaluation.']);
            }
        }

        if ($evaluation->opens_at && now()->lt($evaluation->opens_at)) {
            return back()->withErrors(['evaluation' => 'Cette evaluation n est pas encore ouverte.']);
        }

        if ($evaluation->due_at && now()->gt($evaluation->due_at)) {
            return back()->withErrors(['evaluation' => 'Cette evaluation est fermee.']);
        }

        $existingSubmitted = EvaluationAttempt::where('evaluation_id', $evaluation->id)
            ->where('eleve_user_id', optional($user)->id)
            ->where('status', 'submitted')
            ->exists();

        if ($existingSubmitted) {
            return back()->withErrors(['evaluation' => 'Vous avez deja soumis cette evaluation.']);
        }

        $attempt = EvaluationAttempt::where('evaluation_id', $evaluation->id)
            ->where('eleve_user_id', optional($user)->id)
            ->where('status', 'in_progress')
            ->latest('started_at')
            ->first();

        if ($attempt && $attempt->expires_at && now()->greaterThan($attempt->expires_at)) {
            $attempt->status = 'expired';
            $attempt->submitted_at = $attempt->submitted_at ?? $attempt->expires_at;
            $attempt->save();
            $attempt = null;
        }

        if (! $attempt) {
            $expiresAt = $this->computeAttemptExpiresAt($evaluation);

            $attempt = EvaluationAttempt::create([
                'evaluation_id' => $evaluation->id,
                'eleve_user_id' => optional($user)->id,
                'answers' => [],
                'score' => 0,
                'max_score' => 0,
                'started_at' => now(),
                'expires_at' => $expiresAt,
                'status' => 'in_progress',
            ]);
        }

        return back()->with('success_eval_start', 'Evaluation demarree. Votre chrono est lance.');
    }

    public function submit(Request $request, Evaluation $evaluation)
    {
        if (! Schema::hasTable('evaluation_attempts')) {
            return back()->withErrors(['evaluation' => 'Le module des tentatives n est pas initialise. Lancez les migrations.']);
        }

        if (! $evaluation->is_published) {
            abort(403);
        }

        $user = $request->user();
        if (Schema::hasTable('course_enrollments')) {
            $isEnrolled = CourseEnrollment::approvedForLearner(optional($user)->id)
                ->where('course_id', $evaluation->course_id)
                ->exists();

            if (! $isEnrolled) {
                return back()->withErrors(['evaluation' => 'Inscrivez-vous au cours avant de soumettre cette evaluation.']);
            }
        }

        if ($evaluation->due_at && now()->greaterThan($evaluation->due_at)) {
            return back()->withErrors(['evaluation' => 'La date limite de cette evaluation est depassee.']);
        }

        $evaluation->load('questions');

        if ($evaluation->questions->isEmpty()) {
            return back()->withErrors(['evaluation' => 'Cette evaluation ne contient pas encore de questions.']);
        }

        $attempt = EvaluationAttempt::where('id', $request->input('attempt_id'))
            ->where('evaluation_id', $evaluation->id)
            ->where('eleve_user_id', optional($user)->id)
            ->where('status', 'in_progress')
            ->first();

        if (! $attempt) {
            return back()->withErrors(['evaluation' => 'Session de composition invalide. Cliquez d abord sur "Commencer".']);
        }

        if ($attempt->expires_at && now()->greaterThan($attempt->expires_at)) {
            $attempt->status = 'expired';
            $attempt->submitted_at = $attempt->submitted_at ?? $attempt->expires_at;
            $attempt->save();

            return back()->withErrors(['evaluation' => 'Temps ecoule. Le sujet est ferme automatiquement.']);
        }

        $answers = (array) $request->input('answers', []);
        $score = 0;
        $maxScore = 0;

        $questions = $evaluation->questions;
        if ($evaluation->randomize_questions) {
            $questions = $questions->shuffle();
        }

        foreach ($questions as $question) {
            $maxScore += (int) $question->points;
            $answer = isset($answers[$question->id]) ? trim((string) $answers[$question->id]) : '';

            if ($question->type === 'qcm' && $question->correct_choice !== null && $answer === (string) $question->correct_choice) {
                $score += (int) $question->points;
            }
        }

        $attempt->fill([
            'answers' => $answers,
            'score' => $score,
            'max_score' => $maxScore,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
        $attempt->save();

        if (Schema::hasTable('evaluation_attempt_answers')) {
            foreach ($questions as $question) {
                EvaluationAttemptAnswer::updateOrCreate(
                    [
                        'evaluation_attempt_id' => $attempt->id,
                        'evaluation_question_id' => $question->id,
                    ],
                    [
                    'evaluation_attempt_id' => $attempt->id,
                    'evaluation_question_id' => $question->id,
                    'answer_text' => isset($answers[$question->id]) ? (string) $answers[$question->id] : null,
                    'awarded_points' => $question->type === 'qcm' && $question->correct_choice !== null && ((string) ($answers[$question->id] ?? '') === (string) $question->correct_choice)
                        ? (float) $question->points
                        : null,
                    ]
                );
            }
        }

        PlatformEvents::log(optional($user)->id, 'evaluation.submitted', Evaluation::class, $evaluation->id, ['score' => $score, 'max' => $maxScore]);
        if ($evaluation->formateur_user_id) {
            PlatformEvents::notify((int) $evaluation->formateur_user_id, 'Nouvelle tentative', optional($user)->name.' a soumis '.$evaluation->title);
        }

        return back()->with('success_eval_submit', 'Evaluation soumise. Score automatique: '.$score.' / '.$maxScore);
    }

    private function computeAttemptExpiresAt(Evaluation $evaluation)
    {
        $expiresAt = null;

        if ($evaluation->duration_minutes) {
            $expiresAt = now()->copy()->addMinutes((int) $evaluation->duration_minutes);
        }

        if ($evaluation->due_at) {
            if (! $expiresAt || $evaluation->due_at->lt($expiresAt)) {
                $expiresAt = $evaluation->due_at->copy();
            }
        }

        return $expiresAt;
    }
}
