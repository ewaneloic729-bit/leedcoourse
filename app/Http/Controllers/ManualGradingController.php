<?php

namespace App\Http\Controllers;

use App\Models\EvaluationAttempt;
use App\Models\EvaluationAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ManualGradingController extends Controller
{
    public function grade(Request $request, EvaluationAttempt $attempt)
    {
        if (! Schema::hasTable('evaluation_attempt_answers')) {
            return back()->withErrors(['grade' => 'Module de correction manuelle non initialise.']);
        }

        $user = $request->user();
        if (! $user || optional($attempt->evaluation)->formateur_user_id !== $user->id) {
            abort(403);
        }

        $grades = (array) $request->input('grades', []);
        $feedbacks = (array) $request->input('feedbacks', []);

        $total = 0;

        foreach ($grades as $questionId => $awarded) {
            $points = is_numeric($awarded) ? (float) $awarded : 0;
            $total += $points;

            EvaluationAttemptAnswer::updateOrCreate(
                [
                    'evaluation_attempt_id' => $attempt->id,
                    'evaluation_question_id' => $questionId,
                ],
                [
                    'awarded_points' => $points,
                    'teacher_feedback' => $feedbacks[$questionId] ?? null,
                ]
            );
        }

        $attempt->score = $total;
        $attempt->save();

        return back()->with('success_grade', 'Correction manuelle enregistree.');
    }
}
