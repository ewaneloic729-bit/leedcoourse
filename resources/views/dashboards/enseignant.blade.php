<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Formateur | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background:
                linear-gradient(135deg, rgba(2, 6, 23, 0.92), rgba(22, 101, 52, 0.74)),
                url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(40rem 40rem at 0% 4%, rgba(16, 185, 129, 0.14), transparent 60%),
                radial-gradient(28rem 28rem at 96% 8%, rgba(34, 197, 94, 0.12), transparent 58%);
            pointer-events: none;
            z-index: 0;
        }

        .dashboard-shell {
            position: relative;
            z-index: 1;
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
            box-shadow: 0 22px 46px rgba(2, 6, 23, 0.35);
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

        .progress-donut {
            --value: 0;
            width: 170px;
            height: 170px;
            border-radius: 999px;
            background: conic-gradient(#16a34a calc(var(--value) * 1%), #e2e8f0 0);
            display: grid;
            place-items: center;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.06);
        }

        .progress-donut::before {
            content: "";
            width: 128px;
            height: 128px;
            border-radius: 999px;
            background: #ffffff;
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
                width: 140px;
                height: 140px;
            }

            .progress-donut::before {
                width: 104px;
                height: 104px;
            }
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    @php
        $pendingEnrollCount = ($pendingEnrollmentRequests ?? collect())->count();
        $avgEval = ($stats['avg_eval_score'] ?? 0) . ' / ' . (($stats['avg_eval_max'] ?? 0) > 0 ? $stats['avg_eval_max'] : 0);
    @endphp
    <div class="dashboard-shell max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="workspace-grid">
            <aside class="sidebar-panel glass rounded-2xl p-4 sm:p-5 space-y-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.22em] text-emerald-200 font-semibold">Pilotage</p>
                    <h2 class="mt-2 text-lg font-extrabold">Espace Formateur</h2>
                    <p class="mt-1 text-xs text-slate-200">Vision rapide des indicateurs pédagogiques.</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Cours</p>
                        <p class="text-xl font-extrabold">{{ $stats['total_courses'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Apprenants</p>
                        <p class="text-xl font-extrabold">{{ $stats['active_learners'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Demandes</p>
                        <p class="text-xl font-extrabold">{{ $pendingEnrollCount }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Moy. eval</p>
                        <p class="text-sm font-extrabold">{{ $avgEval }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('enseignant.courses.create') }}" class="sidebar-link">Creer un cours <span>→</span></a>
                    <a href="{{ route('enseignant.analytics.index') }}" class="sidebar-link">Analytics <span>→</span></a>
                    <a href="{{ route('enseignant.question-bank.index') }}" class="sidebar-link">Banque QCM <span>→</span></a>
                    <a href="{{ route('messages.index') }}" class="sidebar-link">Messagerie <span>{{ auth()->user()->unreadConversationMessagesCount() }}</span></a>
                    <a href="{{ route('notifications.index') }}" class="sidebar-link">Notifications <span>{{ $unreadInAppCount ?? 0 }}</span></a>
                </div>
            </aside>

            <div class="space-y-6">
        <header class="dashboard-header glass rounded-2xl p-4 sm:p-6 mb-6 shadow-2xl">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Espace Formateur</h1>
                    <p class="mt-2 text-slate-200 text-sm sm:text-base">Bonjour {{ auth()->user()->name }}, pilotez vos formations sans interruption.</p>
                    <p class="mt-1 text-xs text-emerald-100/90">Matricule: <span class="font-semibold">{{ auth()->user()->matricule ?? 'Non attribue' }}</span></p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('enseignant.courses.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold">
                        Creer un cours
                    </a>
                    <a href="{{ route('enseignant.analytics.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Analytics
                    </a>
                    <a href="{{ route('enseignant.question-bank.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Banque questions
                    </a>
                    <a href="{{ route('notifications.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Notifications ({{ $unreadInAppCount ?? 0 }})
                    </a>
                    <a href="{{ route('messages.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Messages ({{ auth()->user()->unreadConversationMessagesCount() }})
                    </a>
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Audit
                    </a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Mon profil
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                        Vue globale
                    </a>
                    <a href="{{ route('logout') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900/55 border border-white/20 hover:bg-slate-900/75 text-white text-sm font-semibold"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Se deconnecter
                    </a>
                </div>
            </div>
        </header>

        <main class="space-y-6">
            @if(session('success_correction'))
                <div class="panel rounded-xl p-4 text-green-700 border border-green-200">
                    {{ session('success_correction') }}
                </div>
            @endif

            @if(session('success_comment_moderation'))
                <div class="panel rounded-xl p-4 text-green-700 border border-green-200">
                    {{ session('success_comment_moderation') }}
                </div>
            @endif

            @if(session('success_enroll'))
                <div class="panel rounded-xl p-4 text-green-700 border border-green-200">
                    {{ session('success_enroll') }}
                </div>
            @endif

            @if($errors->any())
                <div class="panel rounded-xl p-4 text-red-700 border border-red-200">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <section class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                    <p class="text-sm text-slate-500">Cours assignes</p>
                    <p class="text-3xl font-extrabold mt-2">{{ $stats['total_courses'] }}</p>
                    <p class="text-sm mt-2 text-slate-600">Disponibles dans votre espace formateur</p>
                </article>

                <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                    <p class="text-sm text-slate-500">Apprenants actifs</p>
                    <p class="text-3xl font-extrabold mt-2">{{ $stats['active_learners'] }}</p>
                    <p class="text-sm mt-2 text-slate-600">Ayant remis un devoir ou une evaluation</p>
                </article>

                <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                    <p class="text-sm text-slate-500">Evaluations (moyenne)</p>
                    <p class="text-3xl font-extrabold mt-2">{{ $stats['avg_eval_score'] }} / {{ $stats['avg_eval_max'] > 0 ? $stats['avg_eval_max'] : 0 }}</p>
                    <p class="text-sm mt-2 text-slate-600">Basé sur les tentatives reçues</p>
                </article>
            </section>

            @php
                $evalRate = ($stats['avg_eval_max'] ?? 0) > 0
                    ? round(min(100, (($stats['avg_eval_score'] ?? 0) / $stats['avg_eval_max']) * 100), 1)
                    : 0;
                $pendingEnrollCount = ($pendingEnrollmentRequests ?? collect())->count();
                $enrollmentResponseScore = max(0, 100 - ($pendingEnrollCount * 8));
                $reviewBacklogScore = max(0, 100 - (($stats['pending_reviews'] ?? 0) * 7));
                $globalPilotScore = round(max(0, min(100, ($evalRate + $enrollmentResponseScore + $reviewBacklogScore) / 3)), 1);
            @endphp

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold">Diagramme de pilotage formateur</h2>
                        <p class="text-sm text-slate-600 mt-1">Vue synthese de votre performance pedagogique et operationnelle.</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                        Indicateurs dynamiques
                    </span>
                </div>

                <div class="mt-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <article class="rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="flex justify-center">
                            <div class="relative progress-donut" data-progress-donut data-target="{{ $globalPilotScore }}" style="--value: 0;">
                                <div class="progress-donut-label">
                                    <p class="text-3xl font-extrabold text-slate-800"><span data-progress-value>0.0</span>%</p>
                                    <p class="text-xs text-slate-500 mt-1">Score global</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 text-center mt-4">
                            Base: evaluation, gestion des demandes, charge de correction.
                        </p>
                    </article>

                    <article class="rounded-2xl border border-slate-200 bg-white p-5 space-y-4">
                        <div class="metric-row" title="Performance moyenne des evaluations">
                            <div class="flex items-center justify-between text-sm">
                                <p class="font-semibold text-slate-700">Performance evaluations</p>
                                <p class="text-slate-500">{{ $evalRate }}%</p>
                            </div>
                            <div class="progress-track mt-2">
                                <div class="progress-fill" data-progress-fill data-target="{{ $evalRate }}"></div>
                            </div>
                        </div>

                        <div class="metric-row" title="Reactivite sur les demandes d inscription">
                            <div class="flex items-center justify-between text-sm">
                                <p class="font-semibold text-slate-700">Reponse inscriptions</p>
                                <p class="text-slate-500">{{ $enrollmentResponseScore }}%</p>
                            </div>
                            <div class="progress-track mt-2">
                                <div class="progress-fill" data-progress-fill data-target="{{ $enrollmentResponseScore }}"></div>
                            </div>
                        </div>

                        <div class="metric-row" title="Charge de correction actuelle">
                            <div class="flex items-center justify-between text-sm">
                                <p class="font-semibold text-slate-700">Charge de correction</p>
                                <p class="text-slate-500">{{ $reviewBacklogScore }}%</p>
                            </div>
                            <div class="progress-track mt-2">
                                <div class="progress-fill" data-progress-fill data-target="{{ $reviewBacklogScore }}"></div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            @if(($notifications ?? collect())->isNotEmpty())
                <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                    <h3 class="text-lg font-bold">Notifications</h3>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        @foreach($notifications as $notification)
                            <li>• {{ $notification }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="text-xl font-bold">Demandes d inscription en attente</h3>
                    <span class="text-sm text-slate-500">{{ ($pendingEnrollmentRequests ?? collect())->count() }} a traiter</span>
                </div>
                <p class="text-sm text-slate-600 mt-1">Chaque demande doit etre acceptee ou refusee sous 3 jours maximum.</p>

                <div class="mt-4 space-y-3">
                    @forelse(($pendingEnrollmentRequests ?? collect()) as $enrollment)
                        <div class="rounded-xl border border-slate-200 p-4 bg-white">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div>
                                    <p class="font-semibold text-sm">{{ optional($enrollment->course)->title ?? 'Cours' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ optional($enrollment->eleve)->name ?? 'Apprenant' }} ({{ optional($enrollment->eleve)->email ?? 'email inconnu' }})</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Repondre avant {{ optional($enrollment->response_deadline_at)->format('d/m/Y H:i') ?? '-' }}
                                </span>
                            </div>

                            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-600">
                                Validation geree par l administration. Le formateur ne valide plus l acces au cours.
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucune demande en attente.</p>
                    @endforelse
                </div>
            </section>

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold">Changer de cours sans deconnexion</h2>
                        <p class="text-sm text-slate-600 mt-1">Selectionnez votre cours actif pour basculer instantanement dans votre espace de gestion.</p>
                    </div>

                    <form method="GET" action="{{ route('dashboard.enseignant') }}" class="w-full lg:w-auto">
                        <label for="course" class="block text-sm font-semibold text-slate-700 mb-2">Cours actif</label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <select id="course" name="course" class="w-full lg:w-80 rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500" onchange="this.form.submit()">
                                @forelse($courses as $course)
                                    <option value="{{ $course->id }}" @selected($activeCourse && $activeCourse->id === $course->id)>
                                        {{ $course->title }} - {{ $course->category }}
                                    </option>
                                @empty
                                    <option value="">Aucun cours disponible</option>
                                @endforelse
                            </select>
                            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900">
                                Ouvrir
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <article class="xl:col-span-2 panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-600 font-bold">Cours actif</p>
                            <h3 class="text-2xl font-extrabold mt-2">{{ $activeCourse->title ?? 'Aucun cours selectionne' }}</h3>
                            <p class="text-sm text-slate-600 mt-2">Categorie: {{ $activeCourse->category ?? 'Non definie' }} | Niveau: {{ $activeCourse->level ?? 'Standard' }}</p>
                        </div>
                        @if($activeCourse)
                            <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">En direct</span>
                        @endif
                    </div>

                    <p class="mt-4 text-slate-700 leading-relaxed">
                        {{ $activeCourse->description ?? 'Ajoutez un cours pour commencer a organiser votre contenu pedagogique et suivre votre progression.' }}
                    </p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs text-slate-500">Cours geres</p>
                            <p class="text-xl font-bold mt-1">{{ $stats['total_courses'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs text-slate-500">Apprenants actifs</p>
                            <p class="text-xl font-bold mt-1">{{ $stats['active_learners'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-xs text-slate-500">Devoirs en attente</p>
                            <p class="text-xl font-bold mt-1">{{ $stats['pending_reviews'] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('enseignant.content.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Ajouter un module
                        </a>
                        <a href="{{ route('enseignant.evaluations.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200">
                            Gerer les evaluations
                        </a>
                        <a href="{{ route('enseignant.apprenants.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200">
                            Suivi des apprenants
                        </a>
                        <a href="{{ route('enseignant.gradebook.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-100 text-slate-700 text-sm font-semibold border border-slate-200 hover:bg-slate-200">
                            Carnet de notes
                        </a>
                    </div>
                </article>

                <aside class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h3 class="text-lg font-bold">Vos cours recents</h3>
                    <div class="mt-4 space-y-3">
                        @forelse($courses->take(5) as $course)
                            <div class="rounded-xl border border-slate-200 p-3 {{ $activeCourse && $activeCourse->id === $course->id ? 'bg-emerald-50 border-emerald-200' : 'bg-white' }}">
                                <p class="font-semibold text-sm">{{ $course->title }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $course->category }} {{ $course->level ? '• '.$course->level : '' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Aucun cours pour le moment.</p>
                        @endforelse
                    </div>
                </aside>
            </section>

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <h3 class="text-xl font-bold">Annonces de cours</h3>
                @if(session('success_announcement'))
                    <div class="mt-3 p-3 rounded-lg bg-green-100 text-green-700 text-sm">{{ session('success_announcement') }}</div>
                @endif
                <form method="POST" action="{{ route('enseignant.announcements.store') }}" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Cours</label>
                        <select name="course_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            <option value="">Selectionner</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Titre</label>
                        <input type="text" name="title" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Message</label>
                        <textarea name="message" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Publier l'annonce
                        </button>
                    </div>
                </form>
                <div class="mt-4 space-y-2">
                    @forelse(($recentAnnouncements ?? collect()) as $annonce)
                        <div class="rounded-lg border border-slate-200 p-3 bg-white">
                            <form method="POST" action="{{ route('enseignant.announcements.update', $annonce) }}" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" value="{{ $annonce->title }}" class="w-full rounded border border-slate-300 px-2 py-1 text-sm">
                                <textarea name="message" rows="2" class="w-full rounded border border-slate-300 px-2 py-1 text-sm">{{ $annonce->message }}</textarea>
                                <label class="text-xs inline-flex items-center gap-1"><input type="checkbox" name="is_published" value="1" @checked($annonce->is_published)> Publier</label>
                                <button class="px-2 py-1 rounded bg-slate-800 text-white text-xs">Mettre à jour</button>
                            </form>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <form method="POST" action="{{ route('enseignant.announcements.destroy', $annonce) }}" onsubmit="return confirm('Supprimer cette annonce ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">Supprimer</button>
                                </form>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">{{ $annonce->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucune annonce recente.</p>
                    @endforelse
                </div>
            </section>

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="text-xl font-bold">Commentaires des apprenants</h3>
                    <span class="text-sm text-slate-500">{{ ($pendingCourseComments ?? collect())->count() }} en attente</span>
                </div>
                <p class="text-sm text-slate-600 mt-1">Validez, masquez ou repondez aux retours des apprenants.</p>

                <div class="mt-5 space-y-3">
                    @forelse(($recentCourseComments ?? collect()) as $comment)
                        @php
                            $cStatusStyles = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'hidden' => 'bg-slate-200 text-slate-700',
                            ];
                        @endphp
                        <details class="rounded-xl border border-slate-200 bg-white p-4">
                            <summary class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 cursor-pointer">
                                <div>
                                    <p class="font-semibold text-sm">{{ optional($comment->course)->title ?? 'Cours' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ optional($comment->eleve)->name ?? 'Apprenant' }} ({{ optional($comment->eleve)->email ?? 'email inconnu' }})</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $cStatusStyles[$comment->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $comment->status }}
                                    </span>
                                    @if($comment->rating)
                                        <span class="text-xs px-2 py-1 rounded-full bg-amber-100 text-amber-700">{{ $comment->rating }}/5</span>
                                    @endif
                                    <span class="text-xs text-slate-500">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </summary>

                            <div class="mt-4 pt-4 border-t border-slate-200 space-y-3">
                                <p class="text-sm text-slate-700">{{ $comment->comment }}</p>

                                <form method="POST" action="{{ route('enseignant.comments.moderate', $comment) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Statut</label>
                                        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="pending" @selected($comment->status === 'pending')>Pending</option>
                                            <option value="approved" @selected($comment->status === 'approved')>Approved</option>
                                            <option value="hidden" @selected($comment->status === 'hidden')>Hidden</option>
                                        </select>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Reponse du formateur</label>
                                        <textarea name="formateur_reply" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $comment->formateur_reply }}</textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                                            Enregistrer la moderation
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </details>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-sm text-slate-500">Aucun commentaire recu pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="text-xl font-bold">Devoirs PDF recus des apprenants</h3>
                    <span class="text-sm text-slate-500">{{ $devoirSubmissions->count() }} soumission(s)</span>
                </div>
                <p class="text-sm text-slate-600 mt-1">Telechargez les devoirs, corrigez et renvoyez un PDF corrige directement.</p>

                <div class="mt-5 space-y-3">
                    @forelse($devoirSubmissions as $submission)
                        @php
                            $statusStyles = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'in_review' => 'bg-blue-100 text-blue-700',
                                'corrected' => 'bg-emerald-100 text-emerald-700',
                            ];
                            $statusLabels = [
                                'pending' => 'En attente',
                                'in_review' => 'En correction',
                                'corrected' => 'Corrige',
                            ];
                        @endphp
                        <details class="rounded-xl border border-slate-200 bg-white p-4">
                            <summary class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 cursor-pointer">
                                <div>
                                    <p class="font-semibold text-sm">{{ optional($submission->course)->title ?? 'Cours supprime' }}</p>
                                    <p class="text-xs text-slate-500 mt-1">{{ $submission->student_name }} ({{ $submission->student_email }})</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$submission->status] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ $statusLabels[$submission->status] ?? $submission->status }}
                                    </span>
                                    <span class="text-xs text-slate-500">{{ $submission->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </summary>

                            <div class="mt-4 pt-4 border-t border-slate-200 space-y-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('devoirs.files.original', $submission) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-slate-900 text-white text-xs font-semibold">
                                        Ouvrir le PDF soumis
                                    </a>
                                    @if($submission->corrected_pdf_path)
                                        <a href="{{ route('devoirs.files.corrected', $submission) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold">
                                            Ouvrir le PDF corrige
                                        </a>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('devoirs.update', $submission) }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Statut</label>
                                        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                            <option value="pending" @selected($submission->status === 'pending')>En attente</option>
                                            <option value="in_review" @selected($submission->status === 'in_review')>En correction</option>
                                            <option value="corrected" @selected($submission->status === 'corrected')>Corrige</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Note /20</label>
                                        <input type="number" step="0.25" min="0" max="20" name="score" value="{{ $submission->score }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Feedback</label>
                                        <textarea name="correction_note" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ $submission->correction_note }}</textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">PDF corrige (optionnel)</label>
                                        <input type="file" name="corrected_pdf" accept="application/pdf" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                                    </div>
                                    <div class="md:col-span-2">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                                            Enregistrer la correction
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </details>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-sm text-slate-500">Aucun devoir recu pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
            </div>
        </div>
    </div>

    <script>
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
                if (current < target) requestAnimationFrame(tick);
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
