<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics | LEEDCOURSE</title>
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
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Analytics formateur</h1>
                    <p class="mt-2 text-sm text-slate-200">Vue globale de la performance pédagogique et de la progression.</p>
                </div>
                <a href="{{ route('dashboard.enseignant') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour dashboard</a>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <article class="panel rounded-2xl p-5 shadow-lg text-slate-800"><p class="text-xs text-slate-500">Cours</p><p class="text-3xl font-extrabold mt-2">{{ $coursesCount }}</p></article>
            <article class="panel rounded-2xl p-5 shadow-lg text-slate-800"><p class="text-xs text-slate-500">Tentatives</p><p class="text-3xl font-extrabold mt-2">{{ $attemptsCount }}</p></article>
            <article class="panel rounded-2xl p-5 shadow-lg text-slate-800"><p class="text-xs text-slate-500">Score moyen</p><p class="text-3xl font-extrabold mt-2">{{ $avgScore ?? 0 }}</p></article>
            <article class="panel rounded-2xl p-5 shadow-lg text-slate-800"><p class="text-xs text-slate-500">Complétion leçons</p><p class="text-3xl font-extrabold mt-2">{{ $lessonCompletionRate ?? 0 }}%</p></article>
        </section>

        <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
            <h2 class="text-lg font-bold">Focus pédagogique</h2>
            <p class="mt-2 text-sm text-slate-600">La section "questions à surveiller" sera enrichie automatiquement à mesure que les corrections manuelles et les tentatives augmentent.</p>
        </section>
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
