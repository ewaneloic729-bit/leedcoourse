<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lesson->title }} | LEEDCOURSE</title>
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
        .media-frame {
            width: 100%;
            aspect-ratio: 16 / 9;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            overflow: hidden;
            background: #0f172a;
        }
        .lesson-brand {
            position: relative;
            width: 4.8rem;
            height: 4.8rem;
            display: none;
            align-items: center;
            justify-content: center;
            flex: none;
        }
        .lesson-brand::before,
        .lesson-brand::after {
            content: "";
            position: absolute;
            border-radius: 999px;
        }
        .lesson-brand::before {
            inset: 0;
            border: 1px solid rgba(255,255,255,0.24);
        }
        .lesson-brand::after {
            inset: 0.35rem;
            border: 2px dashed rgba(74, 222, 128, 0.78);
            animation: lesson-spin 10s linear infinite;
        }
        .lesson-brand img {
            width: 3.1rem;
            height: 3.1rem;
            border-radius: 1rem;
            object-fit: cover;
            box-shadow: 0 16px 28px rgba(15, 23, 42, 0.28);
            animation: lesson-bob 2.8s ease-in-out infinite;
        }
        @keyframes lesson-spin {
            to { transform: rotate(360deg); }
        }
        @keyframes lesson-bob {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-4px) rotate(5deg); }
        }
        @media (min-width: 768px) {
            .lesson-brand {
                display: inline-flex;
            }
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-4">
                    <div class="lesson-brand" aria-hidden="true">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="">
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                        <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">{{ $lesson->title }}</h1>
                        <p class="mt-2 text-sm text-slate-200">{{ $course->title }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('apprenant.courses.index') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Mes cours</a>
                    <a href="{{ route('apprenant.courses.show', $course) }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Espace cours</a>
                    <a href="{{ route('dashboard.eleve') }}" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold">Dashboard</a>
                </div>
            </div>
        </header>

        @if($errors->any())
            <div class="panel rounded-xl p-4 text-red-700 border border-red-200">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if(session('success_progress'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_progress') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <section class="lg:col-span-2 panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-xl font-bold">Contenu de la leçon</h2>
                    @if($completedLessonIds->contains($lesson->id))
                        <span class="text-xs px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">Terminée</span>
                    @endif
                </div>

                @php
                    $lessonType = $lesson->lesson_type ?? 'text';
                    $videoUrl = trim((string) ($lesson->video_url ?? ''));
                    $embedUrl = null;
                    $videoPlayerSrc = null;
                    if ($videoUrl !== '') {
                        if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                            $embedUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                        } elseif (str_contains($videoUrl, 'youtu.be/')) {
                            $embedUrl = str_replace('youtu.be/', 'www.youtube.com/embed/', $videoUrl);
                        } elseif (str_contains($videoUrl, 'vimeo.com/')) {
                            $embedUrl = preg_replace('#https?://(www\.)?vimeo\.com/#', 'https://player.vimeo.com/video/', $videoUrl);
                        } elseif (filter_var($videoUrl, FILTER_VALIDATE_URL)) {
                            $videoPlayerSrc = $videoUrl;
                        } else {
                            $videoPlayerSrc = \Illuminate\Support\Facades\Storage::disk('public')->url($videoUrl);
                        }
                    }
                @endphp

                @if($lessonType === 'video' && $videoUrl !== '')
                    <div class="mt-4 media-frame">
                        @if($embedUrl)
                            <iframe src="{{ $embedUrl }}" class="w-full h-full" allowfullscreen></iframe>
                        @else
                            <video src="{{ $videoPlayerSrc ?? $videoUrl }}" class="w-full h-full" controls preload="metadata"></video>
                        @endif
                    </div>
                @elseif($lessonType === 'pdf' && !empty($lesson->pdf_path))
                    <div class="mt-4 media-frame bg-white">
                        <iframe src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($lesson->pdf_path) }}" class="w-full h-full"></iframe>
                    </div>
                    <div class="mt-3">
                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($lesson->pdf_path) }}" target="_blank" class="px-3 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold">
                            Ouvrir le PDF dans un nouvel onglet
                        </a>
                    </div>
                @endif

                <article class="mt-4 prose prose-slate max-w-none">
                    @if(trim((string) $lesson->content) !== '')
                        {!! nl2br(e($lesson->content)) !!}
                    @else
                        <p>Le contenu de cette leçon sera bientôt disponible.</p>
                    @endif
                </article>

                <div class="mt-6 flex flex-wrap gap-2">
                    @if(! $completedLessonIds->contains($lesson->id))
                        <form method="POST" action="{{ route('apprenant.lessons.complete', $lesson) }}" data-learning-launch data-launch-title="Validation de la progression" data-launch-message="Enregistrement de cette lecon dans votre suivi d apprentissage.">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                                Marquer comme terminée
                            </button>
                        </form>
                    @endif

                    @if($previousLesson)
                        <a href="{{ route('apprenant.lessons.show', $previousLesson) }}" class="px-4 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-700 text-sm font-semibold" data-learning-launch data-launch-title="Chargement de la lecon precedente" data-launch-message="Retour au contenu precedent avec mise a jour du contexte de progression.">
                            Leçon précédente
                        </a>
                    @endif

                    @if($nextLesson)
                        <a href="{{ route('apprenant.lessons.show', $nextLesson) }}" class="px-4 py-2 rounded-lg bg-sky-600 text-white text-sm font-semibold hover:bg-sky-700" data-learning-launch data-launch-title="Chargement de la lecon suivante" data-launch-message="Preparation de la suite du parcours et du suivi de progression.">
                            Leçon suivante
                        </a>
                    @endif
                </div>
            </section>

            <aside class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
                <h3 class="text-lg font-bold">Plan du cours</h3>
                <div class="mt-4 space-y-3">
                    @foreach($chapters as $chapter)
                        <div>
                            <p class="text-sm font-semibold text-slate-700">{{ $chapter->title }}</p>
                            <ul class="mt-2 space-y-1">
                                @foreach($chapter->lessons as $chapterLesson)
                                    @php
                                        $isLocked = ($lockedLessonIds ?? collect())->contains($chapterLesson->id) && ! $completedLessonIds->contains($chapterLesson->id);
                                    @endphp
                                    <li>
                                        @if($isLocked)
                                            <span class="text-sm text-slate-400">{{ $chapterLesson->title }} (verrouillee)</span>
                                        @else
                                            <a href="{{ route('apprenant.lessons.show', $chapterLesson) }}"
                                                class="text-sm {{ (int) $chapterLesson->id === (int) $lesson->id ? 'text-emerald-700 font-semibold' : 'text-slate-600 hover:text-slate-800' }}">
                                                {{ $chapterLesson->title }}
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
    @include('partials.learning-launch-overlay')
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
