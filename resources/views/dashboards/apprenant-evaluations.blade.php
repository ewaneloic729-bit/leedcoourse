<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Evaluations | LEEDCOURSE</title>
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

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Mes évaluations</h1>
                    <p class="mt-2 text-sm text-slate-200">Passez vos quiz, devoirs et examens publiés.</p>
                </div>
                <a href="{{ route('dashboard.eleve') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour dashboard</a>
            </div>
        </header>

        @if(session('success_eval_submit'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_eval_submit') }}</div>
        @endif

        @if(session('success_eval_start'))
            <div class="panel rounded-xl p-4 text-blue-700 border border-blue-200">{{ session('success_eval_start') }}</div>
        @endif

        @if($setupMissing)
            <div class="panel rounded-xl p-4 text-amber-700 border border-amber-200">Module des evaluations non initialise.</div>
        @endif

        <div class="space-y-4">
            @foreach($evaluations as $evaluation)
                @php
                    $attempt = $attemptsByEvaluation->get($evaluation->id);
                    $now = now();
                    $notOpened = $evaluation->opens_at && $now->lt($evaluation->opens_at);
                    $closed = $evaluation->due_at && $now->gt($evaluation->due_at);
                    $inProgress = $attempt && $attempt->status === 'in_progress';
                    $submitted = $attempt && $attempt->status === 'submitted';
                    $expired = $attempt && $attempt->status === 'expired';
                    $remainingSeconds = ($inProgress && $attempt->expires_at) ? max(0, $attempt->expires_at->diffInSeconds($now, false) * -1) : null;
                @endphp
                <details class="panel rounded-2xl p-4 shadow-lg text-slate-800">
                    <summary class="cursor-pointer flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-semibold">{{ $evaluation->title }}</p>
                            <p class="text-xs text-slate-500">
                                {{ optional($evaluation->course)->title }} • {{ strtoupper($evaluation->type) }} • seuil {{ $evaluation->pass_score ?? 10 }}/{{ $evaluation->total_points }}
                                {{ $evaluation->duration_minutes ? '• '.$evaluation->duration_minutes.' min' : '' }}
                                {{ $evaluation->randomize_questions ? '• questions mélangées' : '' }}
                            </p>
                        </div>
                        <div class="text-xs text-slate-500 text-right">
                            <div>{{ $evaluation->opens_at ? 'Ouvre: '.$evaluation->opens_at->format('d/m/Y H:i') : 'Ouverte maintenant' }}</div>
                            <div>{{ $evaluation->due_at ? 'Ferme: '.$evaluation->due_at->format('d/m/Y H:i') : 'Sans fermeture' }}</div>
                        </div>
                    </summary>

                    @if($submitted)
                        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 p-3 text-sm">
                            Evaluation deja soumise. Dernier score: {{ $attempt->score }} / {{ $attempt->max_score }}
                        </div>
                    @elseif($expired || $closed)
                        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 text-red-800 p-3 text-sm">
                            Sujet ferme automatiquement. Vous ne pouvez plus soumettre.
                        </div>
                    @elseif($notOpened)
                        <div class="mt-4 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 p-3 text-sm">
                            Cette evaluation ouvrira a {{ $evaluation->opens_at->format('d/m/Y H:i') }}.
                        </div>
                    @elseif(! $inProgress)
                        <form method="POST" action="{{ route('apprenant.evaluations.start', $evaluation) }}" class="mt-4">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-semibold hover:bg-sky-700">
                                Commencer l evaluation
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('apprenant.evaluations.submit', $evaluation) }}" class="mt-4 space-y-3 js-eval-form">
                            @csrf
                            <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">

                            @if($remainingSeconds !== null)
                                <div class="rounded-lg border border-sky-200 bg-sky-50 text-sky-800 p-3 text-sm">
                                    Temps restant:
                                    <span class="font-bold js-countdown" data-seconds="{{ $remainingSeconds }}">
                                        {{ gmdate('H:i:s', $remainingSeconds) }}
                                    </span>
                                </div>
                            @endif

                            @forelse($evaluation->questions as $question)
                                <div class="border rounded-lg p-3 bg-white">
                                    <p class="font-semibold text-sm">{{ $question->position }}. {{ $question->question }} ({{ $question->points }} pt)</p>
                                    @if($question->type === 'qcm')
                                        @foreach((array) $question->choices as $choice)
                                            <label class="block text-sm mt-2"><input type="radio" name="answers[{{ $question->id }}]" value="{{ $choice }}"> {{ $choice }}</label>
                                        @endforeach
                                    @else
                                        <textarea name="answers[{{ $question->id }}]" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm mt-2" placeholder="Votre réponse"></textarea>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Aucune question configuree pour cette evaluation.</p>
                            @endforelse

                            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Soumettre</button>
                        </form>
                    @endif
                </details>
            @endforeach
        </div>

        <div class="panel rounded-xl p-3">{{ $evaluations->links() }}</div>
    </div>

    <script>
        document.querySelectorAll('.js-countdown').forEach((el) => {
            let seconds = parseInt(el.dataset.seconds || '0', 10);
            const form = el.closest('.js-eval-form');

            const render = () => {
                const h = String(Math.floor(seconds / 3600)).padStart(2, '0');
                const m = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
                const s = String(seconds % 60).padStart(2, '0');
                el.textContent = `${h}:${m}:${s}`;
            };

            render();

            const timer = setInterval(() => {
                seconds -= 1;
                if (seconds <= 0) {
                    clearInterval(timer);
                    el.textContent = '00:00:00';
                    if (form) {
                        form.querySelectorAll('input, textarea, button').forEach((field) => {
                            field.disabled = true;
                        });
                    }
                    return;
                }
                render();
            }, 1000);
        });
    </script>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
