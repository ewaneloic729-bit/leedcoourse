<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contenus | LEEDCOURSE</title>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-28 sm:py-8 sm:pb-32 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Gestion des contenus</h1>
                    <p class="mt-2 text-sm text-slate-200">Organisez chapitres et lecons de vos formations.</p>
                </div>
                <a href="{{ route('dashboard.enseignant') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour dashboard</a>
            </div>
        </header>

        @if(session('success_content'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_content') }}</div>
        @endif

        @if($errors->any())
            <div class="panel rounded-xl p-4 text-red-700 border border-red-200">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @if($setupMissing)
            <div class="panel rounded-xl p-4 text-amber-700 border border-amber-200">Module de contenu non initialise. Lancez les migrations.</div>
        @endif

        <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
            <form method="GET" class="flex gap-2 items-end">
                <div class="flex-1">
                    <label class="text-xs font-semibold text-slate-600">Cours</label>
                    <select name="course_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" onchange="this.form.submit()">
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected($selectedCourse && $selectedCourse->id === $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </section>

        @if($selectedCourse)
            <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                <h2 class="font-bold mb-3">Miniature du cours</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                    <div class="rounded-xl overflow-hidden border border-slate-200 bg-slate-100 aspect-video">
                        @if(!empty($selectedCourse->image))
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($selectedCourse->image) }}" alt="{{ $selectedCourse->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-500 text-sm">Aucune miniature pour ce cours.</div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('enseignant.content.course-thumbnail', $selectedCourse) }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <label class="text-xs font-semibold text-slate-600">Televerser une image (JPG/PNG/WEBP, max 4MB)</label>
                        <input type="file" name="course_image" accept="image/png,image/jpeg,image/webp" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                            Mettre a jour la miniature
                        </button>
                    </form>
                </div>
            </section>

            <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                <h2 class="font-bold mb-3">Workflow de publication</h2>
                <form method="POST" action="{{ route('enseignant.content.course-status', $selectedCourse) }}" class="flex flex-wrap items-end gap-2">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="text-xs font-semibold text-slate-600">Statut</label>
                        <select name="publication_status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="draft" @selected(($selectedCourse->publication_status ?? 'draft') === 'draft')>Brouillon</option>
                            <option value="review" @selected(($selectedCourse->publication_status ?? 'draft') === 'review')>En relecture</option>
                            <option value="published" @selected(($selectedCourse->publication_status ?? 'draft') === 'published')>Publié</option>
                        </select>
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold">Mettre à jour</button>
                </form>
            </section>

            <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                <h2 class="font-bold mb-3">Co-formateurs</h2>
                <form method="POST" action="{{ route('enseignant.coformateurs.store', $selectedCourse) }}" class="flex flex-wrap items-end gap-2">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-slate-600">Ajouter un formateur</label>
                        <select name="formateur_user_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            @foreach($formateurCandidates as $candidate)
                                @if($candidate->id !== $selectedCourse->formateur_user_id)
                                    <option value="{{ $candidate->id }}">{{ $candidate->name }} ({{ $candidate->email }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold">Ajouter</button>
                </form>
                <div class="mt-3 space-y-2">
                    @forelse($selectedCourse->coFormateurs as $cof)
                        <div class="flex items-center justify-between border rounded-lg p-2 bg-white">
                            <span class="text-sm">{{ $cof->name }} ({{ $cof->email }})</span>
                            <form method="POST" action="{{ route('enseignant.coformateurs.destroy', [$selectedCourse, $cof]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 rounded bg-red-100 text-red-700 text-xs">Retirer</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucun co-formateur.</p>
                    @endforelse
                </div>
            </section>

            <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <article class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                    <h2 class="font-bold mb-3">Ajouter un chapitre</h2>
                    <form method="POST" action="{{ route('enseignant.content.chapters.store') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                        <input name="title" placeholder="Titre du chapitre" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        <input name="position" type="number" min="1" value="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <label class="text-sm inline-flex gap-2 items-center"><input type="checkbox" name="is_published" value="1"> Publier</label>
                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Ajouter chapitre</button>
                    </form>
                </article>

                <article class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                    <h2 class="font-bold mb-3">Ajouter une leçon</h2>
                    <form method="POST" action="{{ route('enseignant.content.lessons.store') }}" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <select name="course_chapter_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                            <option value="">Selectionner un chapitre</option>
                            @foreach($chapters as $chapter)
                                <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                            @endforeach
                        </select>
                        <input name="title" placeholder="Titre de la leçon" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                        <select name="lesson_type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <option value="text">Lecon texte</option>
                            <option value="video">Lecon video</option>
                            <option value="pdf">Lecon PDF</option>
                        </select>
                        <input name="video_url" type="text" placeholder="Lien video (YouTube/Vimeo) si type video" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <input name="video_resource" type="file" accept="video/mp4,video/webm,video/ogg" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                        <input name="pdf_resource" type="file" accept="application/pdf" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                        <textarea name="content" rows="4" placeholder="Contenu" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                        <input name="position" type="number" min="1" value="1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <label class="text-sm inline-flex gap-2 items-center"><input type="checkbox" name="is_published" value="1"> Publier</label>
                        <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Ajouter leçon</button>
                    </form>
                </article>
            </section>

            <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                <h2 class="font-bold mb-3">Structure du cours</h2>
                <div class="space-y-3">
                    @forelse($chapters as $chapter)
                        <div class="border rounded-lg p-3 bg-white">
                            <div class="font-semibold">{{ $chapter->position }}. {{ $chapter->title }} {!! $chapter->is_published ? '<span class="text-xs text-emerald-600">(publie)</span>' : '<span class="text-xs text-slate-500">(brouillon)</span>' !!}</div>
                            <form method="POST" action="{{ route('enseignant.content.chapters.update', $chapter) }}" class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" value="{{ $chapter->title }}" class="rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                <input type="number" name="position" min="1" value="{{ $chapter->position }}" class="rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                <label class="text-xs inline-flex items-center gap-1"><input type="checkbox" name="is_published" value="1" @checked($chapter->is_published)> Publier</label>
                                <button class="px-3 py-1 rounded bg-slate-800 text-white text-xs w-fit">Maj chapitre</button>
                            </form>
                            <form method="POST" action="{{ route('enseignant.content.chapters.destroy', $chapter) }}" onsubmit="return confirm('Supprimer ce chapitre et ses leçons ?');" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 rounded bg-red-100 text-red-700 text-xs">Supprimer chapitre</button>
                            </form>
                            <ul class="mt-2 pl-5 list-disc text-sm text-slate-600">
                                @forelse($chapter->lessons as $lesson)
                                    <li>
                                        <div>
                                            {{ $lesson->position }}. {{ $lesson->title }}
                                            <span class="text-xs text-slate-500">[{{ $lesson->lesson_type ?? 'text' }}]</span>
                                            {{ $lesson->is_published ? '(publiee)' : '(brouillon)' }}
                                        </div>
                                        <form method="POST" action="{{ route('enseignant.content.lessons.update', $lesson) }}" enctype="multipart/form-data" class="mt-1 grid grid-cols-1 md:grid-cols-3 gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="title" value="{{ $lesson->title }}" class="rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                            <input type="number" name="position" min="1" value="{{ $lesson->position }}" class="rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                            <label class="text-xs inline-flex items-center gap-1"><input type="checkbox" name="is_published" value="1" @checked($lesson->is_published)> Publier</label>
                                            <select name="lesson_type" class="md:col-span-3 rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                                <option value="text" @selected(($lesson->lesson_type ?? 'text') === 'text')>Lecon texte</option>
                                                <option value="video" @selected(($lesson->lesson_type ?? 'text') === 'video')>Lecon video</option>
                                                <option value="pdf" @selected(($lesson->lesson_type ?? 'text') === 'pdf')>Lecon PDF</option>
                                            </select>
                                            <input type="text" name="video_url" value="{{ $lesson->video_url }}" placeholder="Lien video (YouTube/Vimeo)" class="md:col-span-3 rounded-lg border border-slate-300 px-2 py-1 text-xs">
                                            <input type="file" name="video_resource" accept="video/mp4,video/webm,video/ogg" class="md:col-span-3 rounded-lg border border-slate-300 px-2 py-1 text-xs bg-white">
                                            @if($lesson->video_url)
                                                <label class="md:col-span-3 text-xs inline-flex items-center gap-1">
                                                    <input type="checkbox" name="remove_video" value="1"> Retirer la video actuelle
                                                </label>
                                            @endif
                                            <input type="file" name="pdf_resource" accept="application/pdf" class="md:col-span-3 rounded-lg border border-slate-300 px-2 py-1 text-xs bg-white">
                                            @if($lesson->pdf_path)
                                                <label class="md:col-span-3 text-xs inline-flex items-center gap-1">
                                                    <input type="checkbox" name="remove_pdf" value="1"> Retirer le PDF actuel
                                                </label>
                                            @endif
                                            <textarea name="content" rows="2" class="md:col-span-3 rounded-lg border border-slate-300 px-2 py-1 text-xs">{{ $lesson->content }}</textarea>
                                            <button class="px-2 py-1 rounded bg-slate-800 text-white text-xs w-fit">Maj leçon</button>
                                        </form>
                                        <form method="POST" action="{{ route('enseignant.content.lessons.destroy', $lesson) }}" onsubmit="return confirm('Supprimer cette leçon ?');" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-2 py-1 rounded bg-red-100 text-red-700 text-xs">Supprimer leçon</button>
                                        </form>
                                    </li>
                                @empty
                                    <li>Aucune leçon</li>
                                @endforelse
                            </ul>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucun chapitre pour ce cours.</p>
                    @endforelse
                </div>
            </section>
        @endif
    </div>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
