<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Apprenant | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background:
                linear-gradient(135deg, rgba(2, 6, 23, 0.9), rgba(22, 101, 52, 0.72)),
                url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(42rem 42rem at 8% 0%, rgba(45, 212, 191, 0.12), transparent 60%),
                radial-gradient(34rem 34rem at 96% 6%, rgba(52, 211, 153, 0.11), transparent 58%);
            pointer-events: none;
            z-index: 0;
        }

        .dashboard-shell {
            position: relative;
            z-index: 1;
        }

        .hero-brand {
            position: relative;
            width: 5rem;
            height: 5rem;
            display: none;
            align-items: center;
            justify-content: center;
            flex: none;
        }

        .hero-brand::before,
        .hero-brand::after {
            content: "";
            position: absolute;
            border-radius: 999px;
        }

        .hero-brand::before {
            inset: 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-brand::after {
            inset: 0.4rem;
            border: 2px dashed rgba(94, 234, 212, 0.72);
            animation: eleve-brand-spin 11s linear infinite;
        }

        .hero-brand img {
            width: 3.3rem;
            height: 3.3rem;
            border-radius: 1rem;
            object-fit: cover;
            box-shadow: 0 14px 32px rgba(2, 6, 23, 0.34);
            animation: eleve-brand-float 3s ease-in-out infinite;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
            box-shadow: 0 22px 44px rgba(2, 6, 23, 0.34);
        }

        .dashboard-header {
            position: sticky;
            top: 0.8rem;
            z-index: 20;
            background: linear-gradient(135deg, rgba(2, 6, 23, 0.92), rgba(15, 23, 42, 0.88)) !important;
            border-color: rgba(148, 163, 184, 0.35);
            backdrop-filter: blur(12px) saturate(120%);
        }

        .dashboard-header .inline-flex,
        .dashboard-header button {
            border-radius: 0.78rem;
            padding: 0.58rem 0.88rem;
            font-size: 0.78rem;
            letter-spacing: 0.01em;
            font-weight: 700;
            transition: transform 0.16s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .dashboard-header .inline-flex:hover,
        .dashboard-header button:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(2, 6, 23, 0.3);
        }

        .panel {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 14px 34px rgba(2, 6, 23, 0.14);
        }

        .kpi-card {
            position: relative;
            overflow: hidden;
        }

        .kpi-card::before {
            content: "";
            position: absolute;
            inset: 0 auto auto 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #16a34a, #22c55e, #14b8a6);
            opacity: 0.9;
        }

        .workspace-grid {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: 1fr;
        }

        .sidebar-panel {
            height: fit-content;
            background: linear-gradient(180deg, rgba(2, 6, 23, 0.9), rgba(15, 23, 42, 0.86)) !important;
            border-color: rgba(148, 163, 184, 0.35);
            backdrop-filter: blur(12px) saturate(120%);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 0.7rem;
            padding: 0.62rem 0.78rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #e2e8f0;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: rgba(2, 6, 23, 0.34);
            text-decoration: none;
        }

        .sidebar-link:hover {
            background: rgba(15, 23, 42, 0.62);
        }

        @media (min-width: 1280px) {
            .workspace-grid {
                grid-template-columns: 300px minmax(0, 1fr);
                align-items: start;
            }

            .sidebar-panel {
                position: sticky;
                top: 0.8rem;
            }
        }

        .confetti-layer {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 60;
        }

        .confetti-piece {
            position: absolute;
            width: 10px;
            height: 16px;
            opacity: 0.9;
            animation: fall 2600ms linear forwards;
        }

        .progress-donut {
            --value: 0;
            width: 180px;
            height: 180px;
            border-radius: 999px;
            background: conic-gradient(#16a34a calc(var(--value) * 1%), #e2e8f0 0);
            display: grid;
            place-items: center;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.06);
        }

        .progress-donut::before {
            content: "";
            width: 136px;
            height: 136px;
            border-radius: 999px;
            background: #ffffff;
            box-shadow: 0 1px 0 rgba(15, 23, 42, 0.03);
        }

        .progress-donut-label {
            position: absolute;
            text-align: center;
        }

        .progress-track {
            height: 10px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
            width: 0;
            transition: width 0.9s ease;
        }

        .metric-row {
            border-radius: 12px;
            padding: 10px;
            transition: background-color 0.2s ease, transform 0.14s ease;
        }

        .metric-row:hover {
            background: #f8fafc;
            transform: translateY(-1px);
        }

        .quick-launch {
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .quick-launch::after {
            content: "";
            position: absolute;
            inset: auto -35% -65% auto;
            width: 9rem;
            height: 9rem;
            border-radius: 999px;
            background: radial-gradient(circle, rgba(52, 211, 153, 0.24), transparent 68%);
            pointer-events: none;
        }

        @keyframes fall {
            from { transform: translateY(-10vh) rotate(0deg); }
            to { transform: translateY(110vh) rotate(540deg); opacity: 0; }
        }

        @keyframes eleve-brand-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes eleve-brand-float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-4px) rotate(4deg); }
        }

        @media (min-width: 768px) {
            .hero-brand {
                display: inline-flex;
            }
        }

        @media (max-width: 767px) {
            body {
                background-attachment: scroll;
            }

            .dashboard-header {
                position: static;
            }

            .dashboard-header .inline-flex,
            .dashboard-header button {
                width: 100%;
                justify-content: center;
            }

            .progress-donut {
                width: 148px;
                height: 148px;
            }

            .progress-donut::before {
                width: 110px;
                height: 110px;
            }
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div id="confettiLayer" class="confetti-layer hidden" aria-hidden="true"></div>
    @php
        $sidebarEval = number_format((float) $evaluationAttempts->avg('score'), 1);
        $sidebarPending = $mySubmissions->where('status', 'pending')->count();
    @endphp
    <div class="dashboard-shell max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="workspace-grid">
            <aside class="sidebar-panel glass rounded-2xl p-4 sm:p-5 space-y-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.22em] text-emerald-200 font-semibold">Pilotage</p>
                    <h2 class="mt-2 text-lg font-extrabold">Espace Apprenant</h2>
                    <p class="mt-1 text-xs text-slate-200">Tableau compact avec vos chiffres clés.</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Cours</p>
                        <p class="text-xl font-extrabold">{{ $enrolledCourses->count() }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Progression</p>
                        <p class="text-xl font-extrabold">{{ $progressPercent ?? 0 }}%</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">A corriger</p>
                        <p class="text-xl font-extrabold">{{ $sidebarPending }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Moyenne</p>
                        <p class="text-xl font-extrabold">{{ $sidebarEval }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('apprenant.courses.index') }}" class="sidebar-link" data-learning-launch data-launch-title="Ouverture de vos cours" data-launch-message="Chargement de vos parcours, de la progression et des prochaines lecons.">Mes cours <span>→</span></a>
                    <a href="{{ route('apprenant.evaluations.index') }}" class="sidebar-link" data-learning-launch data-launch-title="Ouverture des evaluations" data-launch-message="Preparation de vos tentatives et de vos resultats.">Evaluations <span>→</span></a>
                    <a href="{{ route('messages.index') }}" class="sidebar-link" data-learning-launch data-launch-title="Ouverture de la messagerie" data-launch-message="Chargement de vos conversations privees et des utilisateurs disponibles.">Messagerie <span>{{ auth()->user()->unreadConversationMessagesCount() }}</span></a>
                    <a href="{{ route('notifications.index') }}" class="sidebar-link" data-learning-launch data-launch-title="Ouverture des notifications" data-launch-message="Chargement des annonces et messages importants.">Notifications <span>{{ $unreadInAppCount ?? 0 }}</span></a>
                    <a href="{{ route('profile.edit') }}" class="sidebar-link" data-learning-launch data-launch-title="Chargement du profil" data-launch-message="Preparation de vos informations personnelles et parametres.">Mon profil <span>→</span></a>
                </div>
            </aside>

            <div class="space-y-6">
        <header class="dashboard-header glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-brand" aria-hidden="true">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="">
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                        <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Espace Apprenant</h1>
                        <p class="mt-2 text-slate-200 text-sm sm:text-base">Bonjour {{ Auth::user()->name }}, continuez votre progression et vos évaluations.</p>
                        <p class="mt-1 text-xs text-emerald-100/90">Matricule: <span class="font-semibold">{{ Auth::user()->matricule ?? 'Non attribue' }}</span></p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('apprenant.courses.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold" data-learning-launch data-launch-title="Ouverture de vos cours" data-launch-message="Chargement de votre environnement de suivi et des contenus disponibles.">
                        Mes cours
                    </a>
                    <a href="{{ route('apprenant.evaluations.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold" data-learning-launch data-launch-title="Ouverture des evaluations" data-launch-message="Preparation de vos tests, tentatives et resultats.">
                        Mes évaluations
                    </a>
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold" data-learning-launch data-launch-title="Chargement des notifications" data-launch-message="Recuperation des annonces et alertes de votre espace.">
                        Notifications ({{ $unreadInAppCount ?? 0 }})
                    </a>
                    <a href="{{ route('messages.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold" data-learning-launch data-launch-title="Ouverture de la messagerie" data-launch-message="Chargement de vos discussions privees et des contacts disponibles.">
                        Messages ({{ auth()->user()->unreadConversationMessagesCount() }})
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold" data-learning-launch data-launch-title="Chargement du profil" data-launch-message="Preparation de vos informations personnelles et de vos preferences.">
                        Mon profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900/55 border border-white/20 hover:bg-slate-900/75 text-white text-sm font-semibold">
                            Se deconnecter
                        </button>
                    </form>
                </div>
            </div>
        </header>

        @if(session('success_devoir'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_devoir') }}</div>
        @endif

        @if(session('success_enroll'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_enroll') }}</div>
        @endif

        @if(session('success_progress'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_progress') }}</div>
        @endif

        @if(session('success_comment'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_comment') }}</div>
        @endif

        @if($acceptanceCelebration)
            <div class="panel rounded-xl p-4 border border-emerald-300 bg-gradient-to-r from-lime-50 to-emerald-50 text-emerald-800" data-celebration="1">
                <p class="font-semibold">Felicitations, votre inscription a ete acceptee.</p>
                <p class="text-sm mt-1">{{ $acceptanceCelebration->message }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="panel rounded-xl p-4 text-red-700 border border-red-200">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6">
            <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                <p class="text-sm text-slate-500">Cours inscrits</p>
                <p class="text-3xl font-extrabold mt-2">{{ $enrolledCourses->count() }}</p>
            </article>

            <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                <p class="text-sm text-slate-500">Progression globale</p>
                <p class="text-3xl font-extrabold mt-2">{{ $progressPercent ?? 0 }}%</p>
            </article>

            <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                <p class="text-sm text-slate-500">Devoirs en attente</p>
                <p class="text-3xl font-extrabold mt-2">{{ $mySubmissions->where('status', 'pending')->count() }}</p>
            </article>

            <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                <p class="text-sm text-slate-500">Moyenne évaluations</p>
                <p class="text-3xl font-extrabold mt-2">{{ number_format((float) $evaluationAttempts->avg('score'), 1) }}</p>
            </article>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <a href="{{ route('apprenant.courses.index') }}" class="quick-launch panel rounded-2xl p-5 shadow-lg text-slate-800" data-learning-launch data-launch-title="Demarrage du suivi des cours" data-launch-message="Ouverture rapide de vos parcours et synchronisation de la progression.">
                <p class="text-xs uppercase tracking-[0.22em] text-emerald-700 font-semibold">Acces rapide</p>
                <h2 class="mt-3 text-lg font-extrabold">Relancer un cours</h2>
                <p class="mt-2 text-sm text-slate-600">Reprenez un module en un clic avec l etat de progression visible des l ouverture.</p>
            </a>
            <a href="{{ route('apprenant.evaluations.index') }}" class="quick-launch panel rounded-2xl p-5 shadow-lg text-slate-800" data-learning-launch data-launch-title="Preparation des evaluations" data-launch-message="Chargement de vos examens disponibles et de votre historique.">
                <p class="text-xs uppercase tracking-[0.22em] text-sky-700 font-semibold">Evaluation</p>
                <h2 class="mt-3 text-lg font-extrabold">Passer aux tests</h2>
                <p class="mt-2 text-sm text-slate-600">Accedez plus vite aux evaluations publiees et aux resultats deja obtenus.</p>
            </a>
            <a href="{{ route('notifications.index') }}" class="quick-launch panel rounded-2xl p-5 shadow-lg text-slate-800" data-learning-launch data-launch-title="Lecture des annonces" data-launch-message="Chargement des notifications et des messages utiles a votre progression.">
                <p class="text-xs uppercase tracking-[0.22em] text-amber-700 font-semibold">Informations</p>
                <h2 class="mt-3 text-lg font-extrabold">Voir les alertes</h2>
                <p class="mt-2 text-sm text-slate-600">Consultez les nouvelles annonces, corrections et rappels importants.</p>
            </a>
        </section>

        @php
            $completion = max(0, min(100, (float) ($progressPercent ?? 0)));
            $totalLessons = $enrolledCourses->flatMap(function ($course) {
                return $course->chapters->flatMap(function ($chapter) {
                    return $chapter->lessons;
                });
            })->count();
            $doneLessons = $completedLessonIds->count();
            $submissionTotal = $mySubmissions->count();
            $submissionDone = $mySubmissions->where('status', 'corrected')->count();
            $submissionRate = $submissionTotal > 0 ? round(($submissionDone / $submissionTotal) * 100, 1) : 0;
            $evalAvgScore = (float) $evaluationAttempts->avg('score');
            $evalAvgMax = (float) $evaluationAttempts->avg('max_score');
            $evalRate = $evalAvgMax > 0 ? round(min(100, ($evalAvgScore / $evalAvgMax) * 100), 1) : 0;
        @endphp

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold">Diagramme de progression</h2>
                    <p class="text-sm text-slate-600 mt-1">Suivi visuel de votre avancement global sur la plateforme.</p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                    Mis a jour en temps reel
                </span>
            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <article class="rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex justify-center">
                        <div class="relative progress-donut" data-progress-donut data-target="{{ $completion }}" style="--value: 0;">
                            <div class="progress-donut-label">
                                <p class="text-3xl font-extrabold text-slate-800"><span data-progress-value>0.0</span>%</p>
                                <p class="text-xs text-slate-500 mt-1">Progression globale</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-slate-600 text-center mt-4">
                        {{ $doneLessons }} lecon(s) terminee(s) sur {{ $totalLessons }}.
                    </p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 space-y-4">
                    <div class="metric-row" title="Progression des lecons terminees">
                        <div class="flex items-center justify-between text-sm">
                            <p class="font-semibold text-slate-700">Lecons completees</p>
                            <p class="text-slate-500">{{ $doneLessons }}/{{ $totalLessons }}</p>
                        </div>
                        <div class="progress-track mt-2">
                            <div class="progress-fill" data-progress-fill data-target="{{ $completion }}"></div>
                        </div>
                    </div>

                    <div class="metric-row" title="Part des devoirs deja corriges">
                        <div class="flex items-center justify-between text-sm">
                            <p class="font-semibold text-slate-700">Devoirs corriges</p>
                            <p class="text-slate-500">{{ $submissionRate }}%</p>
                        </div>
                        <div class="progress-track mt-2">
                            <div class="progress-fill" data-progress-fill data-target="{{ $submissionRate }}"></div>
                        </div>
                    </div>

                    <div class="metric-row" title="Performance moyenne sur les evaluations">
                        <div class="flex items-center justify-between text-sm">
                            <p class="font-semibold text-slate-700">Performance evaluations</p>
                            <p class="text-slate-500">{{ $evalRate }}%</p>
                        </div>
                        <div class="progress-track mt-2">
                            <div class="progress-fill" data-progress-fill data-target="{{ $evalRate }}"></div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h2 class="text-xl font-bold">Cours disponibles</h2>
                <span class="text-sm text-slate-500">Inscrivez-vous pour accéder au contenu</span>
            </div>

            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse($availableCourses->take(8) as $course)
                    <div class="border border-slate-200 rounded-xl p-4 bg-white">
                        @php
                            $enrollment = ($enrollmentStatusByCourse ?? collect())->get($course->id);
                            $enrollmentStatus = $enrollment->status ?? null;
                        @endphp
                        <div class="w-full aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-100 mb-3">
                            @if(!empty($course->image))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->image) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full grid place-items-center text-xs font-bold text-slate-500 tracking-wide">{{ strtoupper(\Illuminate\Support\Str::limit($course->category ?? 'COURSE', 14, '')) }}</div>
                            @endif
                        </div>
                        <p class="font-semibold">{{ $course->title }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $course->category }} {{ $course->level ? '- '.$course->level : '' }}</p>
                        @if($course->is_promo_only ?? false)
                            <p class="text-xs text-amber-700 mt-2">Cours vitrine: non inscriptible pour le moment.</p>
                        @endif
                        @if(!($course->is_promo_only ?? false))
                            @if($enrollmentStatus === \App\Models\CourseEnrollment::STATUS_APPROVED)
                                <p class="mt-3 text-xs text-emerald-700 font-semibold">Inscription acceptee</p>
                            @elseif($enrollmentStatus === \App\Models\CourseEnrollment::STATUS_PENDING)
                                <p class="mt-3 text-xs text-amber-700 font-semibold">
                                    Demande en attente. Reponse max le {{ optional($enrollment->response_deadline_at)->format('d/m/Y H:i') ?? '-' }}.
                                </p>
                            @else
                                <form method="POST" action="{{ route('apprenant.enrollments.store') }}" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <button type="submit" class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                                        {{ $enrollmentStatus === \App\Models\CourseEnrollment::STATUS_REJECTED ? 'Redemander l acces' : "S'inscrire" }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Aucun cours disponible.</p>
                @endforelse
            </div>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h2 class="text-xl font-bold">Mes demandes d inscription</h2>
            <p class="text-sm text-slate-600 mt-1">Chaque demande recoit une reponse (acceptee ou refusee) sous 3 jours maximum.</p>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse(($enrollmentRequests ?? collect()) as $requestItem)
                    <li class="border border-slate-200 rounded-lg p-3 bg-white">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-semibold">{{ optional($requestItem->course)->title ?? 'Cours' }}</p>
                            @php
                                $statusStyles = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-[11px] {{ $statusStyles[$requestItem->status ?? ''] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $requestItem->status ?? 'approved' }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 mt-2">
                            Demandee le {{ optional($requestItem->requested_at ?? $requestItem->created_at)->format('d/m/Y H:i') ?? '-' }}
                            @if(($requestItem->status ?? null) === \App\Models\CourseEnrollment::STATUS_PENDING)
                                | Date limite: {{ optional($requestItem->response_deadline_at)->format('d/m/Y H:i') ?? '-' }}
                            @endif
                            @if(($requestItem->status ?? null) !== \App\Models\CourseEnrollment::STATUS_PENDING && $requestItem->decision_at)
                                | Traitee le {{ optional($requestItem->decision_at)->format('d/m/Y H:i') }}
                            @endif
                        </div>
                        @if($requestItem->response_note)
                            <p class="mt-2 text-xs text-slate-700">{{ $requestItem->response_note }}</p>
                        @endif
                    </li>
                @empty
                    <li class="text-slate-500">Aucune demande pour le moment.</li>
                @endforelse
            </ul>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h2 class="text-xl font-bold">Progression des leçons</h2>
            <div class="mt-4 space-y-3">
                @forelse($enrolledCourses as $course)
                    <details class="border border-slate-200 rounded-xl p-3 bg-white">
                        <summary class="cursor-pointer font-semibold">{{ $course->title }}</summary>
                        <div class="mt-3 space-y-2">
                            @foreach($course->chapters as $chapter)
                                <div class="text-sm font-semibold text-slate-700">{{ $chapter->title }}</div>
                                @foreach($chapter->lessons as $lesson)
                                    <div class="flex items-center justify-between gap-2 border rounded-lg px-3 py-2">
                                        <a href="{{ route('apprenant.lessons.show', $lesson) }}" class="text-sm text-slate-700 hover:text-emerald-700" data-learning-launch data-launch-title="Ouverture de la lecon" data-launch-message="Preparation du contenu et synchronisation du suivi de progression.">
                                            {{ $lesson->title }}
                                        </a>
                                        @if($completedLessonIds->contains($lesson->id))
                                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Terminee</span>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('apprenant.lessons.show', $lesson) }}" class="text-xs px-3 py-1 rounded-lg bg-slate-100 border border-slate-300 text-slate-700" data-learning-launch data-launch-title="Ouverture de la lecon" data-launch-message="Preparation du contenu et synchronisation du suivi de progression.">Ouvrir</a>
                                                <form method="POST" action="{{ route('apprenant.lessons.complete', $lesson) }}" data-learning-launch data-launch-title="Validation de la progression" data-launch-message="Enregistrement de la lecon comme terminee dans votre espace.">
                                                    @csrf
                                                    <button type="submit" class="text-xs px-3 py-1 rounded-lg bg-sky-600 text-white">Marquer terminee</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </details>
                @empty
                    <p class="text-sm text-slate-500">Inscrivez-vous d'abord a un cours.</p>
                @endforelse
            </div>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <article class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <h3 class="text-lg font-bold">Envoyer un devoir PDF</h3>
                <form method="POST" action="{{ route('devoirs.store') }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cours</label>
                        <select id="course_id" name="course_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selectionner un cours</option>
                            @foreach($enrolledCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }} - {{ $course->category }}</option>
                            @endforeach
                        </select>
                        @if($enrolledCourses->isEmpty())
                            <p class="text-xs text-amber-600 mt-1">Inscrivez-vous a un cours avant d envoyer un devoir.</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Fichier PDF</label>
                        <input id="devoir_pdf" type="file" name="devoir_pdf" accept="application/pdf" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                    </div>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                        Envoyer au formateur
                    </button>
                </form>
            </article>

            <article class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <h3 class="text-lg font-bold">Mes soumissions</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    @forelse($mySubmissions->take(8) as $submission)
                        <li class="border border-slate-200 rounded-lg p-3 bg-white">
                            <div class="font-semibold">{{ optional($submission->course)->title ?? 'Cours' }}</div>
                            <div class="text-xs text-slate-500 mt-1">Statut: {{ ucfirst($submission->status) }} @if($submission->score !== null)• Note: {{ $submission->score }}/20 @endif</div>
                            <div class="mt-2 flex gap-2">
                                <a href="{{ route('devoirs.files.original', $submission) }}" class="text-xs px-2 py-1 rounded bg-slate-100 border border-slate-300">PDF soumis</a>
                                @if($submission->corrected_pdf_path)
                                    <a href="{{ route('devoirs.files.corrected', $submission) }}" class="text-xs px-2 py-1 rounded bg-emerald-100 text-emerald-700">PDF corrige</a>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="text-slate-500">Aucune soumission envoyee pour le moment.</li>
                    @endforelse
                </ul>
            </article>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h3 class="text-lg font-bold">Annonces de cours</h3>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse(($announcements ?? collect()) as $annonce)
                    <li class="border border-slate-200 rounded-lg p-3 bg-white">
                        <div class="font-semibold">{{ $annonce->title }}</div>
                        <div class="text-slate-600 mt-1">{{ \Illuminate\Support\Str::limit($annonce->message, 160) }}</div>
                    </li>
                @empty
                    <li class="text-slate-500">Aucune annonce pour le moment.</li>
                @endforelse
            </ul>
        </section>

        <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <article class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <h3 class="text-lg font-bold">Laisser un commentaire sur un cours</h3>
                <p class="text-sm text-slate-600 mt-1">Votre message est d abord valide par le formateur avant publication.</p>
                <form method="POST" action="{{ route('apprenant.comments.store') }}" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cours</label>
                        <select name="course_id" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Selectionner un cours</option>
                            @foreach($enrolledCourses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Note (optionnel)</label>
                        <select name="rating" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="">Sans note</option>
                            <option value="5">5 / 5</option>
                            <option value="4">4 / 5</option>
                            <option value="3">3 / 5</option>
                            <option value="2">2 / 5</option>
                            <option value="1">1 / 5</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Commentaire</label>
                        <textarea name="comment" rows="4" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Partagez votre experience sur le cours, le contenu et la pedagogie..."></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                        Envoyer le commentaire
                    </button>
                </form>
            </article>

            <article class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <h3 class="text-lg font-bold">Mes commentaires</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    @forelse(($myCourseComments ?? collect()) as $comment)
                        <li class="border border-slate-200 rounded-lg p-3 bg-white">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-semibold">{{ optional($comment->course)->title ?? 'Cours' }}</p>
                                @php
                                    $commentStatus = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'approved' => 'bg-emerald-100 text-emerald-700',
                                        'hidden' => 'bg-slate-200 text-slate-700',
                                    ];
                                @endphp
                                <span class="text-[11px] px-2 py-1 rounded-full {{ $commentStatus[$comment->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $comment->status }}
                                </span>
                            </div>
                            @if($comment->rating)
                                <div class="text-xs text-amber-600 mt-1">Note: {{ $comment->rating }}/5</div>
                            @endif
                            <div class="text-xs text-slate-600 mt-2">{{ $comment->comment }}</div>
                            @if($comment->formateur_reply)
                                <div class="mt-2 p-2 rounded-lg bg-emerald-50 border border-emerald-100 text-xs text-emerald-800">
                                    <span class="font-semibold">Reponse formateur:</span> {{ $comment->formateur_reply }}
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="text-slate-500">Aucun commentaire envoye pour le moment.</li>
                    @endforelse
                </ul>
            </article>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h3 class="text-lg font-bold">Avis de la communaute</h3>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse(($approvedCourseComments ?? collect())->take(6) as $comment)
                    <li class="border border-slate-200 rounded-lg p-3 bg-white">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <p class="font-semibold">{{ optional($comment->course)->title ?? 'Cours' }}</p>
                            <p class="text-xs text-slate-500">{{ optional($comment->eleve)->name ?? 'Apprenant' }}</p>
                        </div>
                        @if($comment->rating)
                            <div class="text-xs text-amber-600 mt-1">Note: {{ $comment->rating }}/5</div>
                        @endif
                        <p class="text-slate-700 mt-2">{{ $comment->comment }}</p>
                    </li>
                @empty
                    <li class="text-slate-500">Aucun avis publie pour le moment.</li>
                @endforelse
            </ul>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h3 class="text-lg font-bold">Activites recentes</h3>
            <p class="text-sm text-slate-600 mt-1">Historique personnel pour suivre votre progression sur la plateforme.</p>
            <ul class="mt-4 space-y-2 text-sm">
                @forelse(($recentActivities ?? collect()) as $activity)
                    @php
                        $labels = [
                            'lesson.completed' => 'Lecon terminee',
                            'chapter.completed' => 'Chapitre termine',
                            'course.completed' => 'Cours termine',
                            'course.enrollment.requested' => 'Demande d inscription envoyee',
                            'course.comment.created' => 'Commentaire envoye',
                        ];
                    @endphp
                    <li class="border border-slate-200 rounded-lg p-3 bg-white">
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-semibold">{{ $labels[$activity->action] ?? $activity->action }}</p>
                            <span class="text-xs text-slate-500">{{ optional($activity->created_at)->format('d/m/Y H:i') }}</span>
                        </div>
                    </li>
                @empty
                    <li class="text-slate-500">Aucune activite recente.</li>
                @endforelse
            </ul>
        </section>
            </div>
        </div>
    </div>

    @include('partials.learning-launch-overlay')
    <script>
        (function () {
            const hasCelebration = document.querySelector('[data-celebration="1"]');
            if (!hasCelebration) return;

            const layer = document.getElementById('confettiLayer');
            if (!layer) return;
            layer.classList.remove('hidden');

            const colors = ['#16a34a', '#22c55e', '#f59e0b', '#eab308', '#10b981', '#84cc16'];
            const total = 100;
            for (let i = 0; i < total; i++) {
                const piece = document.createElement('span');
                piece.className = 'confetti-piece';
                piece.style.left = Math.random() * 100 + 'vw';
                piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                piece.style.animationDelay = (Math.random() * 0.8) + 's';
                layer.appendChild(piece);
            }

            setTimeout(() => {
                layer.classList.add('hidden');
                layer.innerHTML = '';
            }, 4000);
        })();

        (function () {
            const donut = document.querySelector('[data-progress-donut]');
            const progressText = document.querySelector('[data-progress-value]');
            const fills = document.querySelectorAll('[data-progress-fill]');
            if (!donut) return;

            const target = Math.max(0, Math.min(100, parseFloat(donut.dataset.target || '0')));
            let current = 0;
            const step = Math.max(0.8, target / 45);

            const tick = () => {
                current = Math.min(target, current + step);
                donut.style.setProperty('--value', current.toFixed(1));
                if (progressText) progressText.textContent = current.toFixed(1);
                if (current < target) {
                    requestAnimationFrame(tick);
                }
            };

            requestAnimationFrame(tick);
            fills.forEach((fill, index) => {
                const v = Math.max(0, Math.min(100, parseFloat(fill.dataset.target || '0')));
                setTimeout(() => {
                    fill.style.width = v + '%';
                }, 220 + (index * 130));
            });
        })();
    </script>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
