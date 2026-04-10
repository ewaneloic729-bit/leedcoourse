<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} | Espace Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background:
                radial-gradient(32rem 32rem at 8% 0%, rgba(16, 185, 129, 0.18), transparent 60%),
                radial-gradient(28rem 28rem at 90% 0%, rgba(14, 165, 233, 0.15), transparent 58%),
                linear-gradient(145deg, #020617, #0f172a 60%, #064e3b);
            min-height: 100vh;
            color: #e2e8f0;
        }
        .glass { background: rgba(15, 23, 42, 0.56); border: 1px solid rgba(148, 163, 184, 0.28); backdrop-filter: blur(12px); }
        .panel { background: rgba(248, 250, 252, 0.97); border: 1px solid rgba(15, 23, 42, 0.08); color: #0f172a; }
        .progress-track { height: 10px; border-radius: 999px; background: #dbeafe; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #10b981, #0ea5e9); }
        .hero-brand {
            position: relative;
            width: 5rem;
            height: 5rem;
            flex: none;
            display: none;
            align-items: center;
            justify-content: center;
        }
        .hero-brand::before,
        .hero-brand::after {
            content: "";
            position: absolute;
            border-radius: 999px;
        }
        .hero-brand::before {
            inset: 0;
            border: 1px solid rgba(255, 255, 255, 0.24);
        }
        .hero-brand::after {
            inset: 0.4rem;
            border: 2px dashed rgba(94, 234, 212, 0.72);
            animation: course-orbit 10s linear infinite;
        }
        .hero-brand img {
            width: 3.35rem;
            height: 3.35rem;
            border-radius: 1rem;
            object-fit: cover;
            animation: course-hover 3s ease-in-out infinite;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.28);
        }
        @keyframes course-orbit {
            to { transform: rotate(360deg); }
        }
        @keyframes course-hover {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-4px) rotate(4deg); }
        }
        @media (min-width: 768px) {
            .hero-brand { display: inline-flex; }
        }
    </style>
</head>
<body>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="flex items-start gap-4">
                    <div class="hero-brand" aria-hidden="true">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="">
                    </div>
                    <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-200">Espace Cours</p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">{{ $course->title }}</h1>
                    <p class="text-sm text-slate-200 mt-2">{{ $course->description }}</p>
                    <p class="text-xs text-slate-300 mt-2">Formateur: {{ optional($course->formateur)->name ?? 'LEEDCOURSE' }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('apprenant.courses.index') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 text-white text-sm font-semibold">Mes cours</a>
                    @if($course->next_lesson_id)
                        <a href="{{ route('apprenant.lessons.show', $course->next_lesson_id) }}" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold" data-learning-launch data-launch-title="Demarrage du suivi du cours" data-launch-message="Chargement de votre prochaine lecon et preparation de l espace de travail.">Continuer</a>
                    @endif
                </div>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <article class="panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Progression</p>
                <p class="text-3xl font-extrabold mt-2">{{ $course->progress_percent }}%</p>
            </article>
            <article class="panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Lecons</p>
                <p class="text-3xl font-extrabold mt-2">{{ $course->done_lessons }}<span class="text-base text-slate-500">/{{ $course->total_lessons }}</span></p>
            </article>
            <article class="panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Evaluations</p>
                <p class="text-3xl font-extrabold mt-2">{{ $publishedEvaluationCount }}</p>
            </article>
            <article class="panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-wide text-slate-500">Annonces</p>
                <p class="text-3xl font-extrabold mt-2">{{ $announcements->count() }}</p>
            </article>
        </section>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <section class="xl:col-span-2 panel rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-extrabold">Plan pedagogique</h2>
                    <a href="{{ route('apprenant.evaluations.index') }}" class="text-xs px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">Voir les evaluations</a>
                </div>

                <div class="mt-4 space-y-4">
                    @forelse($chapters as $chapter)
                        <article class="rounded-xl border border-slate-200 bg-white p-4">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <h3 class="font-bold text-slate-900">{{ $chapter->position }}. {{ $chapter->title }}</h3>
                                    <p class="text-xs text-slate-500 mt-1">{{ $chapter->done_lessons }}/{{ $chapter->total_lessons }} lecons terminees</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $chapter->progress_percent >= 100 ? 'bg-emerald-100 text-emerald-700' : 'bg-sky-100 text-sky-700' }}">{{ $chapter->progress_percent }}%</span>
                            </div>
                            <div class="progress-track mt-3"><div class="progress-fill" style="width: {{ $chapter->progress_percent }}%"></div></div>

                            <ul class="mt-4 space-y-2">
                                @foreach($chapter->lessons as $lesson)
                                    @php
                                        $isDone = $completedLessonIds->contains($lesson->id);
                                        $isLocked = $lockedLessonIds->contains($lesson->id) && !$isDone;
                                    @endphp
                                    <li class="border rounded-lg px-3 py-2 flex items-center justify-between gap-2 {{ $isLocked ? 'bg-slate-50 border-slate-200' : 'bg-white border-slate-200' }}">
                                        <div>
                                            <p class="text-sm font-semibold {{ $isLocked ? 'text-slate-500' : 'text-slate-800' }}">{{ $lesson->title }}</p>
                                            <p class="text-[11px] text-slate-500 mt-1">Type: {{ $lesson->lesson_type ?? 'text' }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($isDone)
                                                <span class="text-[11px] px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Terminee</span>
                                            @elseif($isLocked)
                                                <span class="text-[11px] px-2 py-1 rounded-full bg-slate-100 text-slate-600">Verrouillee</span>
                                            @else
                                                <span class="text-[11px] px-2 py-1 rounded-full bg-sky-100 text-sky-700">Disponible</span>
                                            @endif

                                            @if(!$isLocked)
                                                <a href="{{ route('apprenant.lessons.show', $lesson) }}" class="text-xs px-3 py-1 rounded-lg bg-slate-900 text-white" data-learning-launch data-launch-title="Ouverture de la lecon" data-launch-message="Mise en place du contenu et du suivi de progression.">Ouvrir</a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">Aucun chapitre publie pour ce cours.</p>
                    @endforelse
                </div>
            </section>

            <aside class="space-y-6">
                <section class="panel rounded-2xl p-5">
                    <h2 class="text-lg font-extrabold">Annonces recentes</h2>
                    <div class="mt-3 space-y-3">
                        @forelse($announcements as $announcement)
                            <article class="rounded-lg border border-slate-200 p-3 bg-white">
                                <p class="text-sm font-bold text-slate-800">{{ $announcement->title }}</p>
                                <p class="text-xs text-slate-600 mt-1">{{ \Illuminate\Support\Str::limit($announcement->message, 120) }}</p>
                            </article>
                        @empty
                            <p class="text-sm text-slate-500">Aucune annonce.</p>
                        @endforelse
                    </div>
                </section>

                <section class="panel rounded-2xl p-5">
                    <h2 class="text-lg font-extrabold">Avis apprenants</h2>
                    <div class="mt-3 space-y-3">
                        @forelse($approvedComments as $comment)
                            <article class="rounded-lg border border-slate-200 p-3 bg-white">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-slate-800">{{ optional($comment->eleve)->name ?? 'Apprenant' }}</p>
                                    @if($comment->rating)
                                        <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">{{ $comment->rating }}/5</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-600 mt-2">{{ \Illuminate\Support\Str::limit($comment->comment, 140) }}</p>
                            </article>
                        @empty
                            <p class="text-sm text-slate-500">Aucun avis valide pour le moment.</p>
                        @endforelse
                    </div>
                </section>
            </aside>
        </div>
    </div>
    @include('partials.learning-launch-overlay')
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
