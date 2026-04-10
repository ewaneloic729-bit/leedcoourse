<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carnet de notes | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(2, 6, 23, 0.9), rgba(22, 101, 52, 0.72)), url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
        }
        .glass { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); backdrop-filter: blur(8px); }
        .panel { background: rgba(255,255,255,0.95); border: 1px solid rgba(15,23,42,0.08); }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Carnet de notes</h1>
                    <p class="mt-2 text-sm text-slate-200">Suivi des notes des évaluations et export CSV.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('enseignant.gradebook.export.csv') }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Exporter CSV</a>
                    <a href="{{ route('dashboard.enseignant') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour</a>
                </div>
            </div>
        </header>

        @if($setupMissing)
            <div class="panel rounded-xl p-4 text-amber-700 border border-amber-200">Module des notes non initialise.</div>
        @endif

        <section class="panel rounded-2xl p-4 border shadow-sm overflow-x-auto text-slate-800">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-500 border-b">
                        <th class="py-2 pr-3">Apprenant</th>
                        <th class="py-2 pr-3">Cours</th>
                        <th class="py-2 pr-3">Evaluation</th>
                        <th class="py-2 pr-3">Score</th>
                        <th class="py-2 pr-3">Max</th>
                        <th class="py-2">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr class="border-b border-slate-100">
                            <td class="py-3 pr-3">{{ optional($row->eleve)->name }}<div class="text-xs text-slate-500">{{ optional($row->eleve)->email }}</div></td>
                            <td class="py-3 pr-3">{{ optional(optional($row->evaluation)->course)->title }}</td>
                            <td class="py-3 pr-3">{{ optional($row->evaluation)->title }}</td>
                            <td class="py-3 pr-3 font-semibold">{{ $row->score }}</td>
                            <td class="py-3 pr-3">{{ $row->max_score }}</td>
                            <td class="py-3">{{ optional($row->submitted_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-4 text-slate-500">Aucune note disponible.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="panel rounded-2xl p-4 border shadow-sm text-slate-800">
            <h2 class="font-bold mb-3">Correction manuelle des questions ouvertes</h2>
            <div class="space-y-4">
                @foreach($rows as $row)
                    @php
                        $textQuestions = optional($row->evaluation)->questions ? optional($row->evaluation)->questions->where('type', 'text') : collect();
                    @endphp
                    @if($textQuestions->isNotEmpty())
                        <details class="border rounded-lg p-3 bg-white">
                            <summary class="cursor-pointer text-sm font-semibold">
                                {{ optional($row->eleve)->name }} - {{ optional($row->evaluation)->title }}
                            </summary>
                            <form method="POST" action="{{ route('enseignant.evaluations.attempts.manual-grade', $row) }}" class="mt-3 space-y-3">
                                @csrf
                                @foreach($textQuestions as $question)
                                    @php
                                        $existing = $row->answerDetails->firstWhere('evaluation_question_id', $question->id);
                                        $submitted = is_array($row->answers) ? ($row->answers[$question->id] ?? '') : '';
                                    @endphp
                                    <div class="border rounded-lg p-3">
                                        <p class="text-sm font-semibold">{{ $question->question }}</p>
                                        <p class="text-xs text-slate-500 mt-1">Réponse apprenant: {{ $submitted }}</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2">
                                            <input type="number" step="0.25" min="0" max="{{ $question->points }}" name="grades[{{ $question->id }}]" value="{{ $existing ? $existing->awarded_points : '' }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Points accordés">
                                            <input type="text" name="feedbacks[{{ $question->id }}]" value="{{ $existing ? $existing->teacher_feedback : '' }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Feedback">
                                        </div>
                                    </div>
                                @endforeach
                                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                                    Enregistrer la correction manuelle
                                </button>
                            </form>
                        </details>
                    @endif
                @endforeach
            </div>
        </section>

        @if(method_exists($rows, 'links'))
            <div class="panel rounded-xl p-3">{{ $rows->links() }}</div>
        @endif
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
