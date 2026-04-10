<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des apprenants | LEEDCOURSE</title>
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

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            backdrop-filter: blur(8px);
        }

        .panel {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(15, 23, 42, 0.08);
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <header class="glass rounded-2xl p-5 sm:p-6 mb-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Suivi des apprenants</h1>
                    <p class="mt-2 text-slate-200 text-sm">Vue globale des soumissions, corrections et progression des apprenants.</p>
                </div>
                <a href="{{ route('dashboard.enseignant') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">
                    Retour dashboard
                </a>
            </div>
        </header>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <form method="GET" action="{{ route('enseignant.apprenants.index') }}" class="flex flex-col sm:flex-row gap-2 sm:items-end mb-5">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Recherche apprenant</label>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Nom ou email" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800">
                    Rechercher
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b border-slate-200">
                            <th class="py-2 pr-3">Apprenant</th>
                            <th class="py-2 pr-3">Email</th>
                            <th class="py-2 pr-3">Soumissions</th>
                            <th class="py-2 pr-3">En attente</th>
                            <th class="py-2 pr-3">En correction</th>
                            <th class="py-2 pr-3">Corrigées</th>
                            <th class="py-2 pr-3">Moyenne</th>
                            <th class="py-2">Dernière activité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr class="border-b border-slate-100">
                                <td class="py-3 pr-3 font-semibold">{{ $row['name'] }}</td>
                                <td class="py-3 pr-3 text-slate-600">{{ $row['email'] }}</td>
                                <td class="py-3 pr-3">{{ $row['total'] }}</td>
                                <td class="py-3 pr-3 text-amber-700">{{ $row['pending'] }}</td>
                                <td class="py-3 pr-3 text-blue-700">{{ $row['in_review'] }}</td>
                                <td class="py-3 pr-3 text-emerald-700">{{ $row['corrected'] }}</td>
                                <td class="py-3 pr-3">{{ $row['average'] !== null ? $row['average'].' / 20' : '-' }}</td>
                                <td class="py-3 text-slate-500">{{ $row['last_submission'] ? $row['last_submission']->format('d/m/Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-slate-500">Aucun apprenant à afficher.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
