<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\QuestionBankItem;
use App\Support\PlatformEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnseignantEvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $courses = Course::query()
            ->when(Schema::hasColumn('courses', 'formateur_user_id') && $user, function ($query) use ($user) {
                $query->where('formateur_user_id', $user->id);
            })
            ->latest()
            ->get();

        if (! Schema::hasTable('evaluations')) {
            return view('dashboards.enseignant-evaluations', [
                'courses' => $courses,
                'evaluations' => collect(),
                'setupMissing' => true,
            ]);
        }

        $evaluations = Evaluation::with(['course', 'questions'])
            ->when($user, function ($query) use ($user) {
                $query->where('formateur_user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        return view('dashboards.enseignant-evaluations', [
            'courses' => $courses,
            'evaluations' => $evaluations,
            'questionBankItems' => Schema::hasTable('question_bank_items')
                ? QuestionBankItem::where('formateur_user_id', optional($user)->id)->latest()->take(30)->get()
                : collect(),
            'setupMissing' => false,
        ]);
    }

    public function store(Request $request)
    {
        if (! Schema::hasTable('evaluations')) {
            return back()->withErrors([
                'title' => 'Le module des evaluations n est pas encore initialise. Lancez les migrations.',
            ]);
        }

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'type' => ['required', 'in:quiz,devoir,examen'],
            'total_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'opens_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
            'is_published' => ['nullable', 'boolean'],
            'randomize_questions' => ['nullable', 'boolean'],
            'pass_score' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
        ]);

        if (! empty($validated['opens_at']) && ! empty($validated['due_at']) && strtotime((string) $validated['due_at']) <= strtotime((string) $validated['opens_at'])) {
            return back()->withErrors(['due_at' => 'La fermeture doit etre apres l ouverture.'])->withInput();
        }

        $course = Course::findOrFail($validated['course_id']);
        $user = $request->user();

        if (Schema::hasColumn('courses', 'formateur_user_id') && $user && $course->formateur_user_id && $course->formateur_user_id !== $user->id) {
            abort(403);
        }

        Evaluation::create([
            'course_id' => $course->id,
            'formateur_user_id' => optional($user)->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'total_points' => (int) $validated['total_points'],
            'opens_at' => $validated['opens_at'] ?? null,
            'due_at' => $validated['due_at'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'randomize_questions' => $request->boolean('randomize_questions'),
            'pass_score' => $validated['pass_score'] ?? 10,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
        ]);

        PlatformEvents::log(optional($user)->id, 'evaluation.created', Evaluation::class, null, ['course_id' => $course->id, 'title' => $validated['title']]);

        return back()->with('success_evaluation', 'Evaluation creee avec succes.');
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $user = $request->user();
        if (! $user || $evaluation->formateur_user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'type' => ['required', 'in:quiz,devoir,examen'],
            'total_points' => ['required', 'integer', 'min:1', 'max:1000'],
            'opens_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date'],
            'randomize_questions' => ['nullable', 'boolean'],
            'pass_score' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
        ]);

        if (! empty($validated['opens_at']) && ! empty($validated['due_at']) && strtotime((string) $validated['due_at']) <= strtotime((string) $validated['opens_at'])) {
            return back()->withErrors(['due_at' => 'La fermeture doit etre apres l ouverture.'])->withInput();
        }

        $evaluation->update(array_merge($validated, [
            'randomize_questions' => $request->boolean('randomize_questions'),
            'pass_score' => $validated['pass_score'] ?? $evaluation->pass_score,
            'duration_minutes' => $validated['duration_minutes'] ?? null,
        ]));

        return back()->with('success_evaluation', 'Evaluation mise a jour.');
    }

    public function destroy(Request $request, Evaluation $evaluation)
    {
        $user = $request->user();
        if (! $user || $evaluation->formateur_user_id !== $user->id) {
            abort(403);
        }

        $evaluation->delete();

        PlatformEvents::log(optional($user)->id, 'evaluation.deleted', Evaluation::class, $evaluation->id);

        return back()->with('success_evaluation', 'Evaluation supprimee.');
    }

    public function publish(Request $request, Evaluation $evaluation)
    {
        $user = $request->user();
        if (! $user || $evaluation->formateur_user_id !== $user->id) {
            abort(403);
        }

        $evaluation->is_published = ! $evaluation->is_published;
        $evaluation->save();

        PlatformEvents::log(optional($user)->id, 'evaluation.publish_toggled', Evaluation::class, $evaluation->id, ['published' => $evaluation->is_published]);

        return back()->with('success_evaluation', $evaluation->is_published ? 'Evaluation publiee.' : 'Evaluation retiree de la publication.');
    }

    public function storeQuestion(Request $request, Evaluation $evaluation)
    {
        if (! Schema::hasTable('evaluation_questions')) {
            return back()->withErrors([
                'question' => 'Le module des questions n est pas encore initialise. Lancez les migrations.',
            ]);
        }

        $user = $request->user();
        if (! $user || $evaluation->formateur_user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', 'in:qcm,text'],
            'question' => ['required', 'string', 'max:2000'],
            'choice_a' => ['nullable', 'string', 'max:255'],
            'choice_b' => ['nullable', 'string', 'max:255'],
            'choice_c' => ['nullable', 'string', 'max:255'],
            'choice_d' => ['nullable', 'string', 'max:255'],
            'correct_choice' => ['nullable', 'string', 'max:255'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        $choices = null;
        if ($validated['type'] === 'qcm') {
            $choices = collect([
                $validated['choice_a'] ?? null,
                $validated['choice_b'] ?? null,
                $validated['choice_c'] ?? null,
                $validated['choice_d'] ?? null,
            ])->filter(function ($value) {
                return trim((string) $value) !== '';
            })->values()->all();
        }

        EvaluationQuestion::create([
            'evaluation_id' => $evaluation->id,
            'type' => $validated['type'],
            'question' => $validated['question'],
            'choices' => $choices,
            'correct_choice' => $validated['type'] === 'qcm' ? ($validated['correct_choice'] ?? null) : null,
            'points' => (int) $validated['points'],
            'position' => $validated['position'] ?? 1,
        ]);

        PlatformEvents::log(optional($user)->id, 'evaluation.question_added', Evaluation::class, $evaluation->id);

        return back()->with('success_evaluation', 'Question ajoutee a l evaluation.');
    }
}
