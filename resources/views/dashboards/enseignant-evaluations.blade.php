<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluations | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background:
                linear-gradient(135deg, rgba(2, 6, 23, 0.9), rgba(22, 101, 52, 0.7)),
                url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
        }

        .panel {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <header class="glass rounded-2xl p-5 sm:p-6 mb-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Gestion des Evaluations</h1>
                    <p class="mt-2 text-slate-200 text-sm">Creez, publiez et mettez a jour vos quiz, devoirs et examens.</p>
                </div>
                <a href="{{ route('dashboard.enseignant') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                    Retour dashboard
                </a>
            </div>
        </header>

        @if(session('success_evaluation'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200 mb-6">
                {{ session('success_evaluation') }}
            </div>
        @endif

        @if($errors->any())
            <div class="panel rounded-xl p-4 text-red-700 border border-red-200 mb-6">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if($setupMissing)
            <div class="panel rounded-xl p-4 text-amber-700 border border-amber-200 mb-6">
                Le module des evaluations n'est pas initialise. Lancez <code>php artisan migrate</code> puis rechargez la page.
            </div>
        @endif

        <main class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800 xl:col-span-1">
                <h2 class="text-lg font-bold mb-2">Nouvelle evaluation</h2>
                <p class="text-sm text-slate-600 mb-4">Configurez une evaluation et publiez-la pour vos apprenants.</p>

                <form method="POST" action="{{ route('enseignant.evaluations.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cours</label>
                        <select name="course_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            <option value="">Selectionner</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }} - {{ $course->category }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Titre</label>
                        <input type="text" name="title" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Type</label>
                        <select name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            <option value="devoir">Devoir</option>
                            <option value="quiz">Quiz</option>
                            <option value="examen">Examen</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Points total</label>
                        <input type="number" name="total_points" min="1" max="1000" value="20" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Ouverture</label>
                        <input type="datetime-local" name="opens_at" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Date limite</label>
                        <input type="datetime-local" name="due_at" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Score de réussite</label>
                        <input type="number" name="pass_score" min="0" max="1000" value="10" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Durée (minutes)</label>
                        <input type="number" name="duration_minutes" min="1" max="600" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                    </div>

                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="is_published" value="1">
                        Publier immediatement
                    </label>
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" name="randomize_questions" value="1">
                        Mélanger les questions
                    </label>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                        Creer l'evaluation
                    </button>
                </form>
            </section>

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800 xl:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <h2 class="text-lg font-bold">Evaluations creees</h2>
                    <span class="text-sm text-slate-500">{{ $evaluations->count() }} element(s)</span>
                </div>

                <div class="space-y-4">
                    @forelse($evaluations as $evaluation)
                        <details class="rounded-xl border border-slate-200 bg-white p-4">
                            <summary class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 cursor-pointer">
                                <div>
                                    <p class="font-semibold text-sm">{{ $evaluation->title }}</p>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ optional($evaluation->course)->title ?? 'Cours supprime' }} • {{ strtoupper($evaluation->type) }} • /{{ $evaluation->total_points }} pts
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $evaluation->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $evaluation->is_published ? 'Publiee' : 'Brouillon' }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        {{ $evaluation->opens_at ? 'Ouvre: '.$evaluation->opens_at->format('d/m/Y H:i').' • ' : '' }}
                                        {{ $evaluation->due_at ? 'Ferme: '.$evaluation->due_at->format('d/m/Y H:i') : 'Sans date limite' }}
                                    </span>
                                </div>
                            </summary>

                            <div class="mt-4 pt-4 border-t border-slate-200 space-y-3">
                                <form id="inline-update-{{ $evaluation->id }}" method="POST" action="{{ route('enseignant.evaluations.update', $evaluation) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Titre</label>
                                        <input type="text" name="title" value="{{ $evaluation->title }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Type</label>
                                        <select name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                            <option value="devoir" @selected($evaluation->type === 'devoir')>Devoir</option>
                                            <option value="quiz" @selected($evaluation->type === 'quiz')>Quiz</option>
                                            <option value="examen" @selected($evaluation->type === 'examen')>Examen</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Points</label>
                                        <input type="number" name="total_points" min="1" max="1000" value="{{ $evaluation->total_points }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Ouverture</label>
                                        <input type="datetime-local" name="opens_at" value="{{ $evaluation->opens_at ? $evaluation->opens_at->format('Y-m-d\\TH:i') : '' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Date limite</label>
                                        <input type="datetime-local" name="due_at" value="{{ $evaluation->due_at ? $evaluation->due_at->format('Y-m-d\\TH:i') : '' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Score de réussite</label>
                                        <input type="number" name="pass_score" min="0" max="1000" value="{{ $evaluation->pass_score ?? 10 }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Durée (minutes)</label>
                                        <input type="number" name="duration_minutes" min="1" max="600" value="{{ $evaluation->duration_minutes }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Description</label>
                                        <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $evaluation->description }}</textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                            <input type="checkbox" name="randomize_questions" value="1" @checked($evaluation->randomize_questions)>
                                            Mélanger les questions
                                        </label>
                                    </div>
                                    <div class="md:col-span-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                                            Mettre a jour
                                        </button>
                                    </div>
                                </form>

                                <div class="flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('enseignant.evaluations.publish', $evaluation) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg {{ $evaluation->is_published ? 'bg-amber-500 hover:bg-amber-600' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white text-sm font-semibold">
                                            {{ $evaluation->is_published ? 'Retirer de la publication' : 'Publier' }}
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('enseignant.evaluations.destroy', $evaluation) }}" onsubmit="return confirm('Supprimer cette evaluation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>

                                <div class="pt-3 border-t border-slate-100">
                                    <h4 class="text-sm font-bold mb-2">Questions de l'evaluation</h4>
                                    <ul class="space-y-1 text-sm text-slate-600 mb-3">
                                        @forelse($evaluation->questions as $question)
                                            <li class="flex items-center justify-between gap-2">
                                                <span>{{ $question->position }}. {{ $question->question }} ({{ strtoupper($question->type) }} - {{ $question->points }} pt)</span>
                                                <form method="POST" action="{{ route('enseignant.evaluation-questions.duplicate', $question) }}">
                                                    @csrf
                                                    <button type="submit" class="text-xs px-2 py-1 rounded bg-slate-100 border border-slate-300">Dupliquer</button>
                                                </form>
                                            </li>
                                        @empty
                                            <li>Aucune question configuree.</li>
                                        @endforelse
                                    </ul>

                                    <form method="POST" action="{{ route('enseignant.evaluations.questions.store', $evaluation) }}" class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Type</label>
                                            <select name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                                <option value="qcm">QCM</option>
                                                <option value="text">Question ouverte</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Points</label>
                                            <input type="number" name="points" min="1" max="100" value="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Question</label>
                                            <input type="text" name="question" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                        </div>
                                        <div><input type="text" name="choice_a" placeholder="Choix A" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                                        <div><input type="text" name="choice_b" placeholder="Choix B" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                                        <div><input type="text" name="choice_c" placeholder="Choix C" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                                        <div><input type="text" name="choice_d" placeholder="Choix D" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></div>
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Bonne reponse (pour QCM)</label>
                                            <input type="text" name="correct_choice" placeholder="Ex: Choix A" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Position</label>
                                            <input type="number" name="position" min="1" value="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                                                Ajouter la question
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-3">
                                        <h5 class="text-xs font-bold text-slate-600 mb-2">Ajouter depuis la banque</h5>
                                        @if(($questionBankItems ?? collect())->isNotEmpty())
                                            <div class="space-y-1">
                                                @foreach(($questionBankItems ?? collect())->take(5) as $item)
                                                    <form method="POST" action="{{ route('enseignant.question-bank.attach', [$evaluation, $item]) }}" class="flex items-center justify-between gap-2">
                                                        @csrf
                                                        <span class="text-xs text-slate-600">{{ \Illuminate\Support\Str::limit($item->question, 60) }}</span>
                                                        <button class="text-xs px-2 py-1 rounded bg-emerald-100 text-emerald-700">Ajouter</button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        @else
                                            <a href="{{ route('enseignant.question-bank.index') }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-slate-100 border border-slate-300 text-xs font-semibold">
                                                Ouvrir la banque de questions
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </details>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-sm text-slate-500">Aucune evaluation creee pour le moment.</p>
                        </div>
                    @endforelse
                </div>
                @if(method_exists($evaluations, 'links'))
                    <div class="mt-4">{{ $evaluations->links() }}</div>
                @endif
            </section>
        </main>
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
