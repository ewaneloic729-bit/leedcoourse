<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des cours | LEEDCOURSE</title>
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
        .course-cover {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 14px;
            overflow: hidden;
            background: linear-gradient(135deg, #0f172a, #065f46);
            border: 1px solid rgba(15, 23, 42, 0.1);
        }
        .course-cover::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(2, 6, 23, 0.08), rgba(2, 6, 23, 0.5));
        }
        .course-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .course-cover-fallback {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            color: #d1fae5;
            font-weight: 800;
            letter-spacing: 0.08em;
            z-index: 1;
        }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Catalogue des cours</h1>
                    <p class="mt-2 text-sm text-slate-200">Consultez toutes les formations disponibles et trouvez le bon parcours.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Accueil</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold">Mon espace</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-white text-slate-900 text-sm font-semibold">Se connecter</a>
                    @endauth
                </div>
            </div>
        </header>

        @if(session('success_enroll'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_enroll') }}</div>
        @endif

        <section class="panel rounded-2xl p-5 shadow-lg text-slate-800">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Recherche</label>
                    <input type="text" name="q" value="{{ $query }}" placeholder="Titre, categorie, niveau..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Categorie</label>
                    <select name="category" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Toutes</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Niveau</label>
                    <select name="level" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <option value="">Tous</option>
                        @foreach($levels as $level)
                            <option value="{{ $level }}" @selected($selectedLevel === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex flex-wrap gap-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Filtrer</button>
                    <a href="{{ route('catalog.index') }}" class="px-4 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-700 text-sm font-semibold">Reinitialiser</a>
                </div>
            </form>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($courses as $course)
                <article class="panel rounded-2xl p-5 shadow-lg text-slate-800">
                    <div class="course-cover mb-3">
                        @if(!empty($course->image))
                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->image) }}" alt="{{ $course->title }}">
                        @else
                            <div class="course-cover-fallback">{{ strtoupper(\Illuminate\Support\Str::limit($course->category ?? 'COURSE', 14, '')) }}</div>
                        @endif
                    </div>
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $course->category }}{{ $course->level ? ' - '.$course->level : '' }}</p>
                    <h2 class="text-lg font-bold mt-2">{{ $course->title }}</h2>
                    <p class="text-sm text-slate-600 mt-2">{{ \Illuminate\Support\Str::limit($course->description, 130) }}</p>
                    <p class="text-xs text-slate-500 mt-3">Formateur: {{ optional($course->formateur)->name ?? 'LEEDCOURSE' }}</p>
                    @if($course->is_promo_only ?? false)
                        <p class="text-xs text-amber-700 mt-2">Cours vitrine: presentation uniquement (inscription indisponible).</p>
                    @endif

                    <div class="mt-4 flex gap-2">
                        @auth
                            @if(auth()->user()->isEleve())
                                @if($course->is_promo_only ?? false)
                                    <span class="text-xs px-3 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-600">Bientot disponible</span>
                                @else
                                    @php
                                        $enrollment = ($enrollmentStatusByCourse ?? collect())->get($course->id);
                                        $enrollmentStatus = $enrollment->status ?? null;
                                    @endphp
                                    @if($enrollmentStatus === \App\Models\CourseEnrollment::STATUS_APPROVED)
                                        <span class="text-xs px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 font-semibold">Inscription acceptee</span>
                                    @elseif($enrollmentStatus === \App\Models\CourseEnrollment::STATUS_PENDING)
                                        <span class="text-xs px-3 py-2 rounded-lg bg-amber-100 text-amber-700 font-semibold">
                                            En attente (max {{ optional($enrollment->response_deadline_at)->format('d/m') ?? '-' }})
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('apprenant.enrollments.store') }}">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" class="text-xs px-3 py-2 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                                                {{ $enrollmentStatus === \App\Models\CourseEnrollment::STATUS_REJECTED ? 'Redemander' : "S'inscrire" }}
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @else
                                <a href="{{ route('dashboard') }}" class="text-xs px-3 py-2 rounded-lg bg-slate-100 border border-slate-300">Voir dans mon espace</a>
                            @endif
                        @else
                            <button type="button" onclick="openGuestCourseModal(@js($course->title))" class="text-xs px-3 py-2 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700">Commencer ce cours</button>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="panel rounded-2xl p-5 text-slate-700 md:col-span-2 xl:col-span-3">Aucun cours ne correspond a votre recherche.</div>
            @endforelse
        </section>

        @if(method_exists($courses, 'links'))
            <div class="panel rounded-2xl p-3 text-slate-800">{{ $courses->links() }}</div>
        @endif
    </div>

    <div id="guestCourseModal" class="fixed inset-0 hidden items-center justify-center bg-slate-950/70 z-50 p-4">
        <div class="bg-white text-slate-900 w-full max-w-md rounded-2xl p-6 shadow-2xl">
            <h3 class="text-xl font-extrabold">Acces a la formation</h3>
            <p class="mt-3 text-slate-600">Vous avez choisi <span id="selectedCourseName" class="font-bold text-slate-900"></span>. Creez un compte pour suivre ce cours.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold">Creer un compte</a>
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-slate-100 border border-slate-300 text-slate-700 text-sm font-semibold">Se connecter</a>
                <button type="button" onclick="closeGuestCourseModal()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-sm font-semibold">Plus tard</button>
            </div>
        </div>
    </div>

    <script>
        function openGuestCourseModal(courseName) {
            const nameEl = document.getElementById('selectedCourseName');
            const modal = document.getElementById('guestCourseModal');
            nameEl.textContent = courseName;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeGuestCourseModal() {
            const modal = document.getElementById('guestCourseModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
