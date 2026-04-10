<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banque de questions | LEEDCOURSE</title>
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
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Banque de questions</h1>
                    <p class="mt-2 text-sm text-slate-200">Créez, importez et réutilisez vos questions pour toutes vos évaluations.</p>
                </div>
                <a href="{{ route('dashboard.enseignant') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour dashboard</a>
            </div>
        </header>

        @if(session('success_bank'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_bank') }}</div>
        @endif
        @if($errors->any())
            <div class="panel rounded-xl p-4 text-red-700 border border-red-200">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <article class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                <h2 class="font-bold mb-3">Ajouter une question</h2>
                <form method="POST" action="{{ route('enseignant.question-bank.store') }}" class="space-y-2">
                    @csrf
                    <select name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="qcm">QCM</option>
                        <option value="text">Texte</option>
                    </select>
                    <input name="question" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Question" required>
                    <input name="choices" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Choix QCM séparés par |">
                    <input name="correct_choice" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" placeholder="Bonne réponse">
                    <input type="number" name="default_points" min="1" value="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Ajouter</button>
                </form>
            </article>

            <article class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                <h2 class="font-bold mb-3">Importer CSV</h2>
                <form method="POST" action="{{ route('enseignant.question-bank.import-csv') }}" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <input type="file" name="csv_file" accept=".csv,.txt" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                    <p class="text-xs text-slate-500">Format: type,question,choices_pipe,correct_choice,points</p>
                    <button class="px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-700">Importer</button>
                </form>
            </article>
        </section>

        <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
            <h2 class="font-bold mb-3">Questions enregistrées</h2>
            <ul class="space-y-2">
                @forelse($items as $item)
                    <li class="border rounded-lg p-3 bg-white">
                        <div class="text-xs text-slate-500">{{ strtoupper($item->type) }} • {{ $item->default_points }} pt(s)</div>
                        <div class="font-semibold text-sm mt-1">{{ $item->question }}</div>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Aucune question dans la banque.</li>
                @endforelse
            </ul>
        </section>

        @if(method_exists($items,'links'))
            <div class="panel rounded-xl p-3">{{ $items->links() }}</div>
        @endif
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
