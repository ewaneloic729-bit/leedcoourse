<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes cours | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        :root {
            --ink: #0f172a;
            --mint: #10b981;
            --teal: #14b8a6;
            --paper: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background:
                radial-gradient(42rem 42rem at 6% 0%, rgba(20, 184, 166, 0.2), transparent 58%),
                radial-gradient(36rem 36rem at 92% 2%, rgba(16, 185, 129, 0.16), transparent 55%),
                linear-gradient(140deg, #020617 0%, #0f172a 50%, #065f46 100%);
            color: #e2e8f0;
            min-height: 100vh;
        }

        .glass {
            background: rgba(15, 23, 42, 0.54);
            border: 1px solid rgba(148, 163, 184, 0.28);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 44px rgba(2, 6, 23, 0.32);
        }

        .panel {
            background: rgba(248, 250, 252, 0.97);
            border: 1px solid rgba(15, 23, 42, 0.07);
            color: var(--ink);
            box-shadow: 0 18px 34px rgba(2, 6, 23, 0.16);
        }

        .kpi-band {
            position: relative;
            overflow: hidden;
        }

        .kpi-band::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--mint), var(--teal), #38bdf8);
        }

        .progress-track {
            width: 100%;
            height: 10px;
            border-radius: 999px;
            background: #dbeafe;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #10b981 0%, #14b8a6 50%, #0ea5e9 100%);
            transition: width 0.7s ease;
        }

        .course-card {
            border-radius: 1rem;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.98), rgba(241, 245, 249, 0.96));
        }

        .course-card-header {
            background: linear-gradient(130deg, rgba(16, 185, 129, 0.15), rgba(14, 165, 233, 0.12));
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            padding: 0.3rem 0.62rem;
        }

        .status-done { background: #dcfce7; color: #15803d; }
        .status-running { background: #dbeafe; color: #1d4ed8; }
        .status-idle { background: #f1f5f9; color: #475569; }

        .brand-orbit {
            position: relative;
            width: 4.5rem;
            height: 4.5rem;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .brand-orbit::before,
        .brand-orbit::after {
            content: "";
            position: absolute;
            border-radius: 999px;
        }

        .brand-orbit::before {
            inset: 0;
            border: 1px solid rgba(255, 255, 255, 0.28);
        }

        .brand-orbit::after {
            inset: 0.35rem;
            border: 2px dashed rgba(110, 231, 183, 0.72);
            animation: orbit-spin 12s linear infinite;
        }

        .brand-orbit img {
            width: 3rem;
            height: 3rem;
            border-radius: 0.9rem;
            object-fit: cover;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.3);
            animation: orbit-float 3s ease-in-out infinite;
        }

        @keyframes orbit-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes orbit-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-3px) rotate(5deg); }
        }

        @media (min-width: 768px) {
            .brand-orbit {
                display: inline-flex;
            }
        }
    </style>
</head>
<body>
    @php
        $notStartedCourses = $courses->filter(fn($course) => (float) $course->progress_percent <= 0)->count();
        $inProgressCourses = $courses->filter(fn($course) => (float) $course->progress_percent > 0 && (float) $course->progress_percent < 100)->count();
        $completedCoursesLocal = $courses->filter(fn($course) => (float) $course->progress_percent >= 100)->count();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Simulation de suivi des cours</h1>
                    <p class="mt-2 text-sm text-slate-200">Visualisez vos parcours, reprenez vos lecons et mesurez votre rythme d apprentissage.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="brand-orbit" aria-hidden="true">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="">
                    </div>
                    <a href="{{ route('dashboard.eleve') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Dashboard</a>
                    <a href="{{ route('apprenant.evaluations.index') }}" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold">Evaluations</a>
                </div>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <article class="panel kpi-band rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Cours inscrits</p>
                <p class="text-3xl font-extrabold mt-2">{{ $totalCourses }}</p>
                <p class="text-xs text-slate-500 mt-2">Parcours actifs sur votre espace</p>
            </article>
            <article class="panel kpi-band rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Lecons terminees</p>
                <p class="text-3xl font-extrabold mt-2">{{ $doneLessons }}<span class="text-base font-semibold text-slate-500">/{{ $totalLessons }}</span></p>
                <p class="text-xs text-slate-500 mt-2">Base de la progression globale</p>
            </article>
            <article class="panel kpi-band rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Progression moyenne</p>
                <p class="text-3xl font-extrabold mt-2">{{ $avgProgress }}%</p>
                <p class="text-xs text-slate-500 mt-2">Moyenne sur tous les cours inscrits</p>
            </article>
            <article class="panel kpi-band rounded-2xl p-5">
                <p class="text-xs uppercase tracking-wide text-slate-500">Cours completes</p>
                <p class="text-3xl font-extrabold mt-2">{{ $completedCourses }}</p>
                <p class="text-xs text-slate-500 mt-2">Objectifs atteints a 100%</p>
            </article>
        </section>

        <section class="panel rounded-2xl p-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 bg-white p-4">
                    <h2 class="font-bold text-slate-800">Etat des parcours</h2>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Non commences</p>
                            <p class="text-2xl font-extrabold text-slate-700 mt-1">{{ $notStartedCourses }}</p>
                        </div>
                        <div class="rounded-lg border border-blue-100 bg-blue-50 p-3">
                            <p class="text-xs text-blue-600">En cours</p>
                            <p class="text-2xl font-extrabold text-blue-700 mt-1">{{ $inProgressCourses }}</p>
                        </div>
                        <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-3">
                            <p class="text-xs text-emerald-600">Termines</p>
                            <p class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $completedCoursesLocal }}</p>
                        </div>
                    </div>
                </div>

                <form method="GET" class="rounded-xl border border-slate-200 bg-white p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Recherche</label>
                        <input type="text" name="q" value="{{ $query }}" placeholder="Titre, description, categorie" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Categorie</label>
                        <select name="category" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Toutes</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3 flex gap-2">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Filtrer</button>
                        <a href="{{ route('apprenant.courses.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-700 text-sm font-semibold">Reinitialiser</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($courses as $course)
                @php
                    $courseProgress = (float) $course->progress_percent;
                    $statusLabel = $courseProgress >= 100 ? 'Termine' : ($courseProgress > 0 ? 'En cours' : 'Non commence');
                    $statusClass = $courseProgress >= 100 ? 'status-done' : ($courseProgress > 0 ? 'status-running' : 'status-idle');
                    $chapterCount = $course->chapters->count();
                @endphp
                <article class="course-card">
                    <div class="course-card-header p-4">
                        <div class="w-full aspect-video rounded-xl overflow-hidden border border-slate-200 bg-slate-100 mb-3">
                            @if(!empty($course->image))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full grid place-items-center text-xs font-bold text-slate-500 tracking-wide">{{ strtoupper(\Illuminate\Support\Str::limit($course->category ?? 'COURSE', 14, '')) }}</div>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs uppercase tracking-wide text-slate-500">{{ $course->category }}{{ $course->level ? ' - '.$course->level : '' }}</p>
                            <span class="pill {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <h2 class="text-lg font-extrabold mt-2 text-slate-900">{{ $course->title }}</h2>
                        <p class="text-sm text-slate-600 mt-2">{{ \Illuminate\Support\Str::limit($course->description, 128) }}</p>
                    </div>

                    <div class="p-4 space-y-3">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
                                <p class="text-[11px] text-slate-500">Chapitres</p>
                                <p class="text-base font-extrabold text-slate-800">{{ $chapterCount }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
                                <p class="text-[11px] text-slate-500">Lecons</p>
                                <p class="text-base font-extrabold text-slate-800">{{ $course->total_lessons }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 border border-slate-200 p-2">
                                <p class="text-[11px] text-slate-500">Validees</p>
                                <p class="text-base font-extrabold text-slate-800">{{ $course->done_lessons }}</p>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span>Progression</span>
                                <span>{{ $course->progress_percent }}%</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $course->progress_percent }}%"></div>
                            </div>
                        </div>

                        @if($course->next_lesson_title)
                            <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-3">
                                <p class="text-[11px] uppercase tracking-wide text-emerald-700 font-semibold">Prochaine action</p>
                                <p class="text-sm text-emerald-900 mt-1"><span class="font-semibold">Lecon suivante:</span> {{ $course->next_lesson_title }}</p>
                            </div>
                        @else
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <p class="text-sm text-slate-600">Parcours complete. Vous pouvez revoir les lecons ou passer aux evaluations.</p>
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('apprenant.courses.show', $course) }}" class="text-xs px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold" data-learning-launch data-launch-title="Ouverture de l espace cours" data-launch-message="Chargement du tableau de bord pedagogique et de votre progression.">Espace du cours</a>
                            @if($course->next_lesson_id)
                                <a href="{{ route('apprenant.lessons.show', $course->next_lesson_id) }}" class="text-xs px-3 py-2 rounded-lg bg-slate-900 text-white font-semibold hover:bg-slate-800" data-learning-launch data-launch-title="Demarrage du suivi du cours" data-launch-message="Preparation de votre prochaine lecon et synchronisation du suivi.">Reprendre le cours</a>
                            @else
                                <a href="{{ route('dashboard.eleve') }}" class="text-xs px-3 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-700">Vue globale</a>
                            @endif
                            <a href="{{ route('apprenant.evaluations.index') }}" class="text-xs px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">Voir les evaluations</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="panel rounded-2xl p-5 text-slate-700 md:col-span-2 xl:col-span-3">
                    Aucun cours inscrit correspondant aux filtres.
                </div>
            @endforelse
        </section>
    </div>
    @include('partials.learning-launch-overlay')
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
