<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\QuestionBankItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnseignantQuestionBankController extends Controller
{
    public function index(Request $request)
    {
        $items = collect();

        if (Schema::hasTable('question_bank_items')) {
            $items = QuestionBankItem::where('formateur_user_id', optional($request->user())->id)
                ->latest()
                ->paginate(20);
        }

        return view('dashboards.enseignant-question-bank', ['items' => $items]);
    }

    public function store(Request $request)
    {
        if (! Schema::hasTable('question_bank_items')) {
            return back()->withErrors(['question' => 'Banque de questions non initialisee.']);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:qcm,text'],
            'question' => ['required', 'string', 'max:2000'],
            'choices' => ['nullable', 'string'],
            'correct_choice' => ['nullable', 'string', 'max:255'],
            'default_points' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $choices = null;
        if ($validated['type'] === 'qcm') {
            $choices = collect(explode('|', (string) ($validated['choices'] ?? '')))
                ->map(function ($v) { return trim($v); })
                ->filter()
                ->values()
                ->all();
        }

        QuestionBankItem::create([
            'formateur_user_id' => optional($request->user())->id,
            'type' => $validated['type'],
            'question' => $validated['question'],
            'choices' => $choices,
            'correct_choice' => $validated['correct_choice'] ?? null,
            'default_points' => $validated['default_points'],
        ]);

        return back()->with('success_bank', 'Question ajoutee a la banque.');
    }

    public function importCsv(Request $request)
    {
        if (! Schema::hasTable('question_bank_items')) {
            return back()->withErrors(['csv' => 'Banque de questions non initialisee.']);
        }

        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $content = file($request->file('csv_file')->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($content as $line) {
            $cols = str_getcsv($line);
            if (count($cols) < 3) {
                continue;
            }

            $type = trim((string) $cols[0]) === 'text' ? 'text' : 'qcm';
            $question = trim((string) $cols[1]);
            $choices = $type === 'qcm'
                ? collect(explode('|', (string) $cols[2]))->map(function ($v) { return trim($v); })->filter()->values()->all()
                : null;
            $correct = $cols[3] ?? null;
            $points = isset($cols[4]) ? max(1, (int) $cols[4]) : 1;

            if ($question === '') {
                continue;
            }

            QuestionBankItem::create([
                'formateur_user_id' => optional($request->user())->id,
                'type' => $type,
                'question' => $question,
                'choices' => $choices,
                'correct_choice' => $correct,
                'default_points' => $points,
            ]);
        }

        return back()->with('success_bank', 'Import CSV termine.');
    }

    public function attachToEvaluation(Request $request, Evaluation $evaluation, QuestionBankItem $item)
    {
        $user = $request->user();
        if (! $user || $evaluation->formateur_user_id !== $user->id || $item->formateur_user_id !== $user->id) {
            abort(403);
        }

        EvaluationQuestion::create([
            'evaluation_id' => $evaluation->id,
            'type' => $item->type,
            'question' => $item->question,
            'choices' => $item->choices,
            'correct_choice' => $item->correct_choice,
            'points' => $item->default_points,
            'position' => (int) ($evaluation->questions()->max('position') ?? 0) + 1,
        ]);

        return back()->with('success_evaluation', 'Question ajoutee depuis la banque.');
    }

    public function duplicate(Request $request, EvaluationQuestion $question)
    {
        $evaluation = $question->evaluation;
        $user = $request->user();

        if (! $user || ! $evaluation || $evaluation->formateur_user_id !== $user->id) {
            abort(403);
        }

        EvaluationQuestion::create([
            'evaluation_id' => $evaluation->id,
            'type' => $question->type,
            'question' => $question->question,
            'choices' => $question->choices,
            'correct_choice' => $question->correct_choice,
            'points' => $question->points,
            'position' => (int) ($evaluation->questions()->max('position') ?? 0) + 1,
        ]);

        return back()->with('success_evaluation', 'Question dupliquee.');
    }
}
