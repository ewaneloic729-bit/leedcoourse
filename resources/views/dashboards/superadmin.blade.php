<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | LEEDCOURSE</title>
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
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(40rem 40rem at 6% 0%, rgba(16, 185, 129, 0.15), transparent 62%),
                radial-gradient(30rem 30rem at 95% 10%, rgba(20, 184, 166, 0.12), transparent 58%);
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
            z-index: 24;
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

        .admin-tab {
            position: relative;
            overflow: hidden;
            transition: transform 0.15s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        }

        .admin-tab:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(2, 6, 23, 0.28);
        }

        .workspace-grid {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: 1fr;
        }

        .sidebar-panel {
            position: sticky;
            top: 0.8rem;
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
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    @php
        $pendingEnrollmentCount = ($pendingEnrollmentRequests ?? collect())->count();
    @endphp
    <div class="dashboard-shell max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="workspace-grid">
            <aside class="sidebar-panel glass rounded-2xl p-4 sm:p-5 space-y-4">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.22em] text-emerald-200 font-semibold">Contrôle</p>
                    <h2 class="mt-2 text-lg font-extrabold">Espace Admin</h2>
                    <p class="mt-1 text-xs text-slate-200">Monitoring et accès rapide à chaque module.</p>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Utilisateurs</p>
                        <p class="text-xl font-extrabold">{{ $stats['users'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">En attente</p>
                        <p class="text-xl font-extrabold">{{ $stats['pending_validations'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Cours</p>
                        <p class="text-xl font-extrabold">{{ $stats['courses'] }}</p>
                    </div>
                    <div class="rounded-xl border border-white/15 bg-white/10 p-3">
                        <p class="text-[11px] text-slate-200">Demandes</p>
                        <p class="text-xl font-extrabold">{{ $pendingEnrollmentCount }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'overview']) }}" class="sidebar-link">Vue globale <span>→</span></a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'users']) }}" class="sidebar-link">Utilisateurs <span>→</span></a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'enrollments']) }}" class="sidebar-link">Inscriptions <span>→</span></a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'courses']) }}" class="sidebar-link">Cours <span>→</span></a>
                    <a href="{{ route('messages.index') }}" class="sidebar-link">Messagerie <span>{{ auth()->user()->unreadConversationMessagesCount() }}</span></a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'audit']) }}" class="sidebar-link">Audit <span>→</span></a>
                </div>
            </aside>

            <div class="space-y-6">
        <header class="dashboard-header glass rounded-2xl p-4 sm:p-6 mb-6 shadow-2xl">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Espace Admin</h1>
                    <p class="mt-2 text-slate-200 text-sm sm:text-base">Bonjour {{ auth()->user()->name }}, pilotez la plateforme comme les autres espaces metier.</p>
                    <p class="mt-1 text-xs text-emerald-100/90">Matricule: <span class="font-semibold">{{ auth()->user()->matricule ?? 'Non attribue' }}</span></p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'overview']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'overview' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Vue globale</a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'users']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'users' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Utilisateurs</a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'enrollments']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'enrollments' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Inscriptions</a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'courses']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'courses' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Cours</a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'communications']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'communications' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Communiques</a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'settings']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'settings' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Parametres</a>
                    <a href="{{ route('dashboard.superadmin', ['tab' => 'audit']) }}" class="admin-tab inline-flex items-center px-4 py-2 rounded-lg {{ $selectedTab === 'audit' ? 'bg-emerald-500' : 'bg-white/10 border border-white/30 hover:bg-white/20' }} text-white text-sm font-semibold">Audit</a>
                    <a href="{{ route('messages.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Messages ({{ auth()->user()->unreadConversationMessagesCount() }})</a>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Mon profil</a>
                    <a href="{{ route('logout') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900/55 border border-white/20 hover:bg-slate-900/75 text-white text-sm font-semibold"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Se deconnecter</a>
                </div>
            </div>
        </header>

        <main class="space-y-6">
            @if(session('success_superadmin'))
                <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_superadmin') }}</div>
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
                    <p class="text-sm text-slate-500">Utilisateurs</p>
                    <p class="text-3xl font-extrabold mt-2">{{ $stats['users'] }}</p>
                </article>
                <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                    <p class="text-sm text-slate-500">Comptes en attente</p>
                    <p class="text-3xl font-extrabold mt-2">{{ $stats['pending_validations'] }}</p>
                </article>
                <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                    <p class="text-sm text-slate-500">Cours</p>
                    <p class="text-3xl font-extrabold mt-2">{{ $stats['courses'] }}</p>
                </article>
                <article class="panel kpi-card rounded-2xl p-5 shadow-lg text-slate-800">
                    <p class="text-sm text-slate-500">Demandes d'inscription</p>
                    <p class="text-3xl font-extrabold mt-2">{{ ($pendingEnrollmentRequests ?? collect())->count() }}</p>
                </article>
            </section>

            @if($selectedTab === 'overview')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Vue globale administration</h2>
                    <p class="text-sm text-slate-600 mt-1">Choisissez un module de travail ci-dessous.</p>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                        <a href="{{ route('dashboard.superadmin', ['tab' => 'users']) }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                            <p class="font-semibold">Gestion des utilisateurs</p>
                            <p class="text-xs text-slate-500 mt-1">Creer, activer, editer roles et supprimer.</p>
                        </a>
                        <a href="{{ route('dashboard.superadmin', ['tab' => 'enrollments']) }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                            <p class="font-semibold">Validation inscriptions</p>
                            <p class="text-xs text-slate-500 mt-1">Accepter ou refuser les demandes d'acces.</p>
                        </a>
                        <a href="{{ route('dashboard.superadmin', ['tab' => 'courses']) }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                            <p class="font-semibold">Supervision des cours</p>
                            <p class="text-xs text-slate-500 mt-1">Publier, modifier et supprimer les cours.</p>
                        </a>
                        <a href="{{ route('dashboard.superadmin', ['tab' => 'communications']) }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                            <p class="font-semibold">Communiques</p>
                            <p class="text-xs text-slate-500 mt-1">Informer tous les utilisateurs.</p>
                        </a>
                        <a href="{{ route('dashboard.superadmin', ['tab' => 'settings']) }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                            <p class="font-semibold">Parametres plateforme</p>
                            <p class="text-xs text-slate-500 mt-1">Inscriptions et maintenance.</p>
                        </a>
                        <a href="{{ route('dashboard.superadmin', ['tab' => 'audit']) }}" class="rounded-xl border border-slate-200 bg-white p-4 hover:bg-slate-50">
                            <p class="font-semibold">Journal d'audit</p>
                            <p class="text-xs text-slate-500 mt-1">Historique des actions admin.</p>
                        </a>
                    </div>
                </section>
            @endif

            @if($selectedTab === 'users')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Gestion des utilisateurs</h2>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <details class="rounded-xl border border-slate-200 bg-white p-3">
                            <summary class="cursor-pointer inline-flex items-center px-3 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold">
                                Liste des apprenants
                            </summary>
                            <div class="mt-3 overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-2 py-2 text-left">Nom</th>
                                            <th class="px-2 py-2 text-left">Email</th>
                                            <th class="px-2 py-2 text-left">Statut</th>
                                            <th class="px-2 py-2 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @forelse(($eleveUsersQuick ?? collect()) as $quickUser)
                                            <tr>
                                                <td class="px-2 py-2">{{ $quickUser->name }}</td>
                                                <td class="px-2 py-2">{{ $quickUser->email }}</td>
                                                <td class="px-2 py-2">{{ $quickUser->is_active ? 'Actif' : 'Inactif' }}</td>
                                                <td class="px-2 py-2 text-right">
                                                    @if((int) auth()->id() !== (int) $quickUser->id)
                                                        <form method="POST" action="{{ route('superadmin.users.destroy', $quickUser) }}" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-red-600 text-white hover:bg-red-700" title="Supprimer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h.293l.94 12.22A2 2 0 0 0 8.227 21h7.546a2 2 0 0 0 1.994-1.78L18.707 7H19a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9Zm2 2h2v0h-2ZM9.25 9a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7A.75.75 0 0 1 9.25 9Zm5.5 0a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7a.75.75 0 0 1 .75-.75Z"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-2 py-4 text-center text-slate-500">Aucun apprenant.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </details>

                        <details class="rounded-xl border border-slate-200 bg-white p-3">
                            <summary class="cursor-pointer inline-flex items-center px-3 py-2 rounded-lg bg-sky-600 text-white text-sm font-semibold">
                                Liste des formateurs
                            </summary>
                            <div class="mt-3 overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-2 py-2 text-left">Nom</th>
                                            <th class="px-2 py-2 text-left">Email</th>
                                            <th class="px-2 py-2 text-left">Statut</th>
                                            <th class="px-2 py-2 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200">
                                        @forelse(($enseignantUsersQuick ?? collect()) as $quickUser)
                                            <tr>
                                                <td class="px-2 py-2">{{ $quickUser->name }}</td>
                                                <td class="px-2 py-2">{{ $quickUser->email }}</td>
                                                <td class="px-2 py-2">{{ $quickUser->is_active ? 'Actif' : 'Inactif' }}</td>
                                                <td class="px-2 py-2 text-right">
                                                    @if((int) auth()->id() !== (int) $quickUser->id)
                                                        <form method="POST" action="{{ route('superadmin.users.destroy', $quickUser) }}" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-red-600 text-white hover:bg-red-700" title="Supprimer">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                                    <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h.293l.94 12.22A2 2 0 0 0 8.227 21h7.546a2 2 0 0 0 1.994-1.78L18.707 7H19a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9Zm2 2h2v0h-2ZM9.25 9a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7A.75.75 0 0 1 9.25 9Zm5.5 0a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7a.75.75 0 0 1 .75-.75Z"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="px-2 py-4 text-center text-slate-500">Aucun formateur.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    </div>

                    <div class="mt-4 grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <article class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="font-semibold">Creer un compte</h3>
                            <form method="POST" action="{{ route('superadmin.users.store') }}" class="mt-3 space-y-3">
                                @csrf
                                <input type="text" name="name" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Nom complet" required>
                                <input type="email" name="email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Email" required>
                                <select name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                                    <option value="eleve">Apprenant</option>
                                    <option value="enseignant">Formateur</option>
                                    <option value="superadmin">Admin</option>
                                </select>
                                <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Mot de passe" required>
                                <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Confirmer" required>
                                <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="is_active" value="1" checked>Compte actif</label>
                                <label class="flex items-center gap-2 text-xs"><input type="checkbox" name="email_verified" value="1" checked>Email verifie</label>
                                <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Creer</button>
                            </form>
                        </article>

                        <article class="rounded-xl border border-slate-200 bg-white p-4">
                            <h3 class="font-semibold">Filtres</h3>
                            <form method="GET" class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <input type="hidden" name="tab" value="users">
                                <input type="text" name="q" value="{{ $query }}" placeholder="Nom, email, matricule" class="rounded-lg border border-slate-300 px-3 py-2 text-sm sm:col-span-2">
                                <select name="role" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Tous les roles</option>
                                    <option value="eleve" @selected($selectedRole === 'eleve')>Apprenant</option>
                                    <option value="enseignant" @selected($selectedRole === 'enseignant')>Formateur</option>
                                    <option value="superadmin" @selected($selectedRole === 'superadmin')>Admin</option>
                                </select>
                                <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Tous les statuts</option>
                                    <option value="pending" @selected($selectedStatus === 'pending')>En attente</option>
                                    <option value="active" @selected($selectedStatus === 'active')>Actif</option>
                                    <option value="inactive" @selected($selectedStatus === 'inactive')>Inactif</option>
                                </select>
                                <button type="submit" class="sm:col-span-2 px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900">Filtrer</button>
                            </form>
                        </article>
                    </div>

                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200 bg-white">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-3 py-2 text-left">Nom</th>
                                    <th class="px-3 py-2 text-left">Email</th>
                                    <th class="px-3 py-2 text-left">Role</th>
                                    <th class="px-3 py-2 text-left">Statut</th>
                                    <th class="px-3 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-3 py-3">{{ $user->name }}</td>
                                        <td class="px-3 py-3">{{ $user->email }}</td>
                                        <td class="px-3 py-3">{{ $user->role }}</td>
                                        <td class="px-3 py-3">{{ $user->is_active ? 'Actif' : 'Inactif' }}</td>
                                        <td class="px-3 py-3">
                                            @if((int) auth()->id() !== (int) $user->id)
                                                <div class="flex justify-end gap-2">
                                                    <form method="POST" action="{{ route('superadmin.users.update', $user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="is_active" value="{{ $user->is_active ? 0 : 1 }}">
                                                        <button type="submit" class="px-2 py-1 rounded bg-amber-500 text-white text-xs">{{ $user->is_active ? 'Desactiver' : 'Activer' }}</button>
                                                    </form>

                                                    <details>
                                                        <summary class="cursor-pointer px-2 py-1 rounded border text-xs">Editer</summary>
                                                        <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="mt-2 w-72 space-y-2 rounded-lg border bg-white p-3 shadow">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded border px-2 py-1 text-xs" required>
                                                            <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded border px-2 py-1 text-xs" required>
                                                            <select name="role" class="w-full rounded border px-2 py-1 text-xs">
                                                                <option value="eleve" @selected($user->role === 'eleve')>Apprenant</option>
                                                                <option value="enseignant" @selected($user->role === 'enseignant')>Formateur</option>
                                                                <option value="superadmin" @selected($user->role === 'superadmin')>Admin</option>
                                                            </select>
                                                            <select name="is_active" class="w-full rounded border px-2 py-1 text-xs">
                                                                <option value="1" @selected($user->is_active)>Actif</option>
                                                                <option value="0" @selected(! $user->is_active)>Inactif</option>
                                                            </select>
                                                            <button type="submit" class="w-full px-2 py-1 rounded bg-sky-600 text-white text-xs">Sauvegarder</button>
                                                        </form>
                                                    </details>

                                                    <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-red-600 text-white hover:bg-red-700" title="Supprimer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor">
                                                                <path d="M9 3a1 1 0 0 0-1 1v1H5a1 1 0 1 0 0 2h.293l.94 12.22A2 2 0 0 0 8.227 21h7.546a2 2 0 0 0 1.994-1.78L18.707 7H19a1 1 0 1 0 0-2h-3V4a1 1 0 0 0-1-1H9Zm2 2h2v0h-2ZM9.25 9a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7A.75.75 0 0 1 9.25 9Zm5.5 0a.75.75 0 0 1 .75.75v7a.75.75 0 0 1-1.5 0v-7a.75.75 0 0 1 .75-.75Z"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400">Compte courant</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-3 py-6 text-center text-slate-500">Aucun utilisateur trouve.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $users->links() }}</div>
                </section>
            @endif

            @if($selectedTab === 'enrollments')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Validation des inscriptions aux cours</h2>
                    <div class="mt-4 space-y-3">
                        @forelse(($pendingEnrollmentRequests ?? collect()) as $enrollment)
                            <div class="rounded-xl border border-slate-200 p-4 bg-white">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="font-semibold text-sm">{{ optional($enrollment->course)->title ?? 'Cours' }}</p>
                                        <p class="text-xs text-slate-500 mt-1">{{ optional($enrollment->eleve)->name ?? 'Apprenant' }} ({{ optional($enrollment->eleve)->email ?? 'email inconnu' }})</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Repondre avant {{ optional($enrollment->response_deadline_at)->format('d/m/Y H:i') ?? '-' }}</span>
                                </div>

                                <form method="POST" action="{{ route('superadmin.enrollments.decide', $enrollment) }}" class="mt-3 grid grid-cols-1 lg:grid-cols-3 gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="response_reason" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                                        <option value="">Motif de refus</option>
                                        <option value="level_mismatch">Niveau insuffisant</option>
                                        <option value="prerequisites_missing">Prerequis manquants</option>
                                        <option value="incomplete_profile">Profil incomplet</option>
                                        <option value="no_seats">Plus de places</option>
                                        <option value="other">Autre</option>
                                    </select>
                                    <textarea name="response_note" rows="2" class="lg:col-span-2 rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Precision (si refus)"></textarea>
                                    <div class="lg:col-span-3 flex gap-2">
                                        <button type="submit" name="decision" value="approve" class="px-3 py-2 rounded-lg bg-emerald-600 text-white text-xs font-semibold">Accepter</button>
                                        <button type="submit" name="decision" value="reject" class="px-3 py-2 rounded-lg bg-red-600 text-white text-xs font-semibold">Refuser</button>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Aucune demande en attente.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            @if($selectedTab === 'courses')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Gestion des cours disponibles</h2>
                    <div class="mt-4 space-y-3">
                        @forelse($availableCourses as $course)
                            <details class="rounded-xl border border-slate-200 p-4 bg-white">
                                <summary class="cursor-pointer flex flex-wrap items-center justify-between gap-2">
                                    <span class="font-semibold text-sm">{{ $course->title }}</span>
                                    <span class="text-xs text-slate-500">{{ $course->category }} {{ $course->level ? '• '.$course->level : '' }}</span>
                                </summary>

                                <div class="mt-3 grid grid-cols-1 lg:grid-cols-2 gap-3">
                                    <form method="POST" action="{{ route('superadmin.courses.update', $course) }}" class="space-y-2 rounded-lg border border-slate-200 p-3">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="title" value="{{ $course->title }}" class="w-full rounded border border-slate-300 px-3 py-2 text-sm" required>
                                        <input type="text" name="category" value="{{ $course->category }}" class="w-full rounded border border-slate-300 px-3 py-2 text-sm" required>
                                        <input type="text" name="level" value="{{ $course->level }}" class="w-full rounded border border-slate-300 px-3 py-2 text-sm">
                                        <textarea name="description" rows="2" class="w-full rounded border border-slate-300 px-3 py-2 text-sm" required>{{ $course->description }}</textarea>
                                        <label class="text-xs inline-flex items-center gap-2"><input type="checkbox" name="is_available" value="1" @checked($course->is_available ?? true)>Disponible</label>
                                        <label class="text-xs inline-flex items-center gap-2"><input type="checkbox" name="is_promo_only" value="1" @checked($course->is_promo_only ?? false)>Vitrine</label>
                                        <select name="publication_status" class="w-full rounded border border-slate-300 px-3 py-2 text-xs">
                                            <option value="draft" @selected(($course->publication_status ?? null) === 'draft')>Brouillon</option>
                                            <option value="published" @selected(($course->publication_status ?? 'published') === 'published')>Publie</option>
                                        </select>
                                        <button type="submit" class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold">Mettre a jour</button>
                                    </form>

                                    <form method="POST" action="{{ route('superadmin.courses.destroy', $course) }}" class="rounded-lg border border-red-200 bg-red-50 p-3" onsubmit="return confirm('Supprimer ce cours ?');">
                                        @csrf
                                        @method('DELETE')
                                        <p class="text-sm font-semibold text-red-700">Suppression</p>
                                        <p class="text-xs text-red-600 mt-1">Suppression bloquee si le cours a des dependances.</p>
                                        <button type="submit" class="mt-3 px-3 py-2 rounded-lg bg-red-600 text-white text-xs font-semibold">Supprimer</button>
                                    </form>
                                </div>
                            </details>
                        @empty
                            <p class="text-sm text-slate-500">Aucun cours disponible.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            @if($selectedTab === 'communications')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Communiques globaux</h2>
                    <form method="POST" action="{{ route('superadmin.communications.store') }}" class="mt-4 space-y-3 rounded-xl border border-slate-200 bg-white p-4">
                        @csrf
                        <input type="text" name="title" placeholder="Titre" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        <textarea name="message" rows="3" placeholder="Message" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required></textarea>
                        <label class="text-xs inline-flex items-center gap-2"><input type="checkbox" name="is_published" value="1" checked>Publier immediatement</label>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold">Publier</button>
                    </form>

                    <div class="mt-4 space-y-2">
                        @forelse($communications as $communication)
                            <div class="rounded-lg border border-slate-200 p-3 bg-white">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-semibold text-sm">{{ $communication->title }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full {{ $communication->is_published ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">{{ $communication->is_published ? 'Publie' : 'Brouillon' }}</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1">{{ \Illuminate\Support\Str::limit($communication->message, 220) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Aucun communique.</p>
                        @endforelse
                    </div>
                </section>
            @endif

            @if($selectedTab === 'settings')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Parametres plateforme</h2>
                    <form method="POST" action="{{ route('superadmin.settings.update') }}" class="mt-4 space-y-3 rounded-xl border border-slate-200 bg-white p-4">
                        @csrf
                        @method('PATCH')
                        <label class="text-sm inline-flex items-center gap-2"><input type="checkbox" name="registrations_open" value="1" @checked($registrationsOpen)>Inscriptions ouvertes</label>
                        <label class="text-sm inline-flex items-center gap-2"><input type="checkbox" name="maintenance_mode" value="1" @checked($maintenanceMode)>Mode maintenance informatif</label>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold">Enregistrer</button>
                    </form>
                </section>
            @endif

            @if($selectedTab === 'audit')
                <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                    <h2 class="text-xl font-bold">Journal d'audit admin</h2>
                    <div class="mt-4 space-y-2">
                        @forelse($auditLogs as $log)
                            <div class="rounded-lg border border-slate-200 p-3 bg-white">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
                                    <p class="text-sm font-semibold">{{ $log->action }}</p>
                                    <p class="text-xs text-slate-500">{{ optional($log->created_at)->format('d/m/Y H:i:s') }}</p>
                                </div>
                                <p class="text-xs text-slate-600 mt-1">Admin: {{ optional($log->user)->name ?? 'inconnu' }} @if($log->entity_type) | Cible: {{ $log->entity_type }} #{{ $log->entity_id }} @endif</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Aucune action admin enregistree.</p>
                        @endforelse
                    </div>
                </section>
            @endif
        </main>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
