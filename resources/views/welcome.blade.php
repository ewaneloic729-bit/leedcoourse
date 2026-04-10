<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LEEDCOURSE</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --brand-green: #16a34a;
            --brand-green-deep: #166534;
            --brand-ink: #e5efe8;
            --card-bg: rgba(8, 15, 23, 0.62);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, rgba(2, 6, 23, 0.72), rgba(5, 46, 22, 0.55)),
                        url('{{ asset("images/image.png") }}') no-repeat center center fixed;
            background-size: cover;
            color: #f8fafc;
        }

        .top-nav {
            backdrop-filter: blur(10px);
            background: linear-gradient(90deg, rgba(2, 6, 23, 0.86), rgba(6, 24, 18, 0.72));
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: 0 10px 24px rgba(2, 6, 23, 0.3);
        }

        .brand-mark {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.07em;
            font-weight: 800;
            background: linear-gradient(90deg, #ffffff 15%, #86efac 40%, #22c55e 80%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 8px 30px rgba(34, 197, 94, 0.28);
        }

        .brand-logo {
            width: clamp(2.4rem, 5vw, 3.3rem);
            height: clamp(2.4rem, 5vw, 3.3rem);
            border-radius: clamp(0.45rem, 1.2vw, 0.75rem);
            object-fit: contain;
            background: rgba(2, 6, 23, 0.32);
            padding: 0.22rem;
        }

        .nav-shell {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            min-height: 62px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(148, 163, 184, 0.2);
            box-shadow: inset 0 0 0 1px rgba(34, 197, 94, 0.08);
        }

        .mobile-drawer {
            display: none;
        }

        .nav-link {
            position: relative;
            font-size: 0.7rem;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            font-weight: 700;
            color: rgba(226, 232, 240, 0.9);
            transition: color 0.2s ease;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -0.45rem;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, rgba(34, 197, 94, 0.1), rgba(34, 197, 94, 0.9));
            opacity: 0;
            transform: scaleX(0.6);
            transform-origin: left;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #bbf7d0;
        }

        .nav-link:hover::after,
        .nav-link:focus::after {
            opacity: 1;
            transform: scaleX(1);
        }

        .hero-wrap {
            min-height: calc(100vh - 72px);
        }

        .hero-panel {
            background: radial-gradient(120% 140% at 10% 0%, rgba(22, 163, 74, 0.2), rgba(15, 23, 42, 0.78));
            border: 1px solid rgba(148, 163, 184, 0.24);
            box-shadow: 0 22px 40px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(6px);
        }

        .hero-title {
            display: inline-block;
            background: linear-gradient(120deg, #f0fdf4 10%, #a7f3d0 42%, #34d399 72%, #5eead4 95%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 14px 34px rgba(16, 185, 129, 0.32);
        }

        .hero-dynamic {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            white-space: nowrap;
        }

        .hero-dynamic::after {
            content: "";
            width: 0.12rem;
            height: 1.1em;
            background: #86efac;
            display: inline-block;
            animation: caretBlink 1.1s steps(1, end) infinite;
            box-shadow: 0 0 12px rgba(134, 239, 172, 0.6);
        }

        .dynamic-word {
            display: inline-block;
            min-width: 8ch;
            transition: opacity 0.35s ease, transform 0.35s ease;
            color: #d1fae5;
            -webkit-text-fill-color: #d1fae5;
            text-shadow: 0 10px 24px rgba(94, 234, 212, 0.45);
        }

        .dynamic-word.is-switching {
            opacity: 0;
            transform: translateY(-6px);
        }

        .hero-kicker {
            color: var(--brand-ink);
            border: 1px solid rgba(134, 239, 172, 0.45);
            background: rgba(22, 101, 52, 0.28);
        }

        .auth-actions {
            background: rgba(2, 6, 23, 0.48);
            border: 1px solid rgba(148, 163, 184, 0.2);
            backdrop-filter: blur(8px);
        }

        .auth-btn,
        .section-btn {
            --btn-bg-start: #22c55e;
            --btn-bg-end: #15803d;
            --btn-ink: #f8fafc;
            --btn-shadow: rgba(22, 163, 74, 0.38);
            position: relative;
            isolation: isolate;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.66rem 1.02rem;
            border-radius: 0.78rem;
            border: 1px solid rgba(255, 255, 255, 0.22);
            font-weight: 800;
            letter-spacing: 0.01em;
            text-decoration: none;
            color: var(--btn-ink);
            background: linear-gradient(135deg, var(--btn-bg-start), var(--btn-bg-end));
            box-shadow: 0 10px 24px -8px var(--btn-shadow), inset 0 1px 0 rgba(255, 255, 255, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
            overflow: hidden;
            white-space: nowrap;
        }

        .auth-btn::before,
        .section-btn::before {
            content: "";
            position: absolute;
            top: -120%;
            left: -55%;
            width: 42%;
            height: 320%;
            transform: rotate(20deg);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transition: left 0.4s ease;
            pointer-events: none;
            z-index: -1;
        }

        .auth-btn:hover,
        .section-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px -10px var(--btn-shadow), inset 0 1px 0 rgba(255, 255, 255, 0.35);
            filter: saturate(1.1) brightness(1.04);
        }

        .auth-btn:hover::before,
        .section-btn:hover::before {
            left: 125%;
        }

        .auth-btn:active,
        .section-btn:active {
            transform: translateY(0);
        }

        .auth-btn:focus-visible,
        .section-btn:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(167, 243, 208, 0.35), 0 12px 26px -10px var(--btn-shadow);
        }

        .auth-btn-login {
            --btn-bg-start: #f8fafc;
            --btn-bg-end: #cbd5e1;
            --btn-ink: #0f172a;
            --btn-shadow: rgba(148, 163, 184, 0.45);
            border-color: rgba(148, 163, 184, 0.5);
        }

        .auth-btn-register {
            --btn-bg-start: #22c55e;
            --btn-bg-end: #166534;
            --btn-ink: #f8fafc;
            --btn-shadow: rgba(22, 163, 74, 0.42);
        }

        .auth-btn-ghost {
            --btn-bg-start: #1e293b;
            --btn-bg-end: #0f172a;
            --btn-ink: #e2e8f0;
            --btn-shadow: rgba(15, 23, 42, 0.52);
            border-color: rgba(148, 163, 184, 0.45);
        }

        .courses-section {
            position: relative;
            background: linear-gradient(180deg, rgba(148, 163, 184, 0.98), rgba(100, 116, 139, 0.95));
            color: #0f172a;
        }

        .courses-section::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.74), rgba(22, 101, 52, 0.68)),
                        url('{{ asset("images/image.png") }}') no-repeat center center;
            background-size: cover;
            z-index: 0;
            filter: saturate(1.05) contrast(1.02);
        }

        .courses-content {
            position: relative;
            z-index: 1;
        }

        .courses-heading {
            color: #f8fafc;
            text-shadow: 0 10px 30px rgba(2, 6, 23, 0.35);
        }

        .courses-subtitle {
            color: #dbeafe;
        }

        .courses-grid {
            animation: coursesDrift 12s ease-in-out infinite;
        }

        .course-card {
            border: 1px solid rgba(15, 23, 42, 0.08);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
            animation: cardFloat 6s ease-in-out infinite;
        }

        .course-card:hover {
            transform: translateY(-4px);
            border-color: rgba(22, 163, 74, 0.35);
            box-shadow: 0 20px 36px rgba(15, 23, 42, 0.2);
        }

        .course-card:nth-child(2) {
            animation-delay: 0.8s;
        }

        .course-card:nth-child(3) {
            animation-delay: 1.4s;
        }

        .course-card:nth-child(4) {
            animation-delay: 1.9s;
        }

        .course-logo {
            width: 3rem;
            height: 3rem;
            border-radius: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.14), rgba(22, 101, 52, 0.2));
            color: #166534;
            margin-bottom: 1rem;
        }
        .course-cover {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 0.8rem;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.08);
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #0f172a, #14532d);
            position: relative;
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
            color: #dcfce7;
            font-weight: 800;
            letter-spacing: 0.1em;
        }

        .section-btn {
            --btn-bg-start: #0ea5e9;
            --btn-bg-end: #2563eb;
            --btn-shadow: rgba(37, 99, 235, 0.45);
        }

        .modal-bg {
            background-color: rgba(2, 6, 23, 0.72);
            backdrop-filter: blur(6px);
        }

        @keyframes coursesDrift {
            0% { transform: translateX(0); }
            50% { transform: translateX(-14px); }
            100% { transform: translateX(0); }
        }

        @keyframes cardFloat {
            0% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
            100% { transform: translateY(0); }
        }

        @keyframes caretBlink {
            0%, 40% { opacity: 1; }
            41%, 100% { opacity: 0; }
        }

        @media (max-width: 768px) {
            .courses-grid {
                animation-duration: 10s;
            }

            .mobile-drawer {
                display: block;
            }

            .hero-wrap {
                min-height: auto;
                padding-top: 2rem;
            }

            .hero-dynamic {
                white-space: normal;
            }

            .dynamic-word {
                min-width: 0;
            }
        }
    </style>
</head>
<body class="min-h-screen">
    <header class="top-nav sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2.5 nav-shell">
            <a href="{{ url('/') }}" class="brand-mark text-base sm:text-lg lg:text-xl flex items-center gap-2">
                <img src="{{ asset('images/logo.jpeg') }}" alt="Logo LEEDCOURSE" class="brand-logo">
                <span class="hidden sm:inline">LEEDCOURSE</span>
            </a>
            <nav class="hidden sm:flex nav-links">
                <a href="{{ url('/') }}" class="nav-link">Accueil</a>
                <a href="#apropos" class="nav-link">A propos</a>
            </nav>
            <div class="hidden sm:flex items-center gap-1.5">
                <a href="{{ route('catalog.index') }}" class="auth-btn auth-btn-ghost">Catalogue</a>
                <a href="{{ route('login') }}" class="auth-btn auth-btn-login">Se connecter</a>
                <a href="{{ route('register') }}" class="auth-btn auth-btn-register">S'inscrire</a>
            </div>
            <button type="button" id="mobileMenuToggle" class="mobile-drawer auth-btn auth-btn-ghost sm:hidden text-sm" aria-expanded="false" aria-controls="mobileMenuPanel">
                Menu
            </button>
        </div>
        <div id="mobileMenuPanel" class="sm:hidden px-4 pb-4 hidden">
            <div class="auth-actions flex flex-col gap-2 p-3 rounded-2xl">
                <a href="{{ url('/') }}" class="auth-btn auth-btn-ghost">Accueil</a>
                <a href="#apropos" class="auth-btn auth-btn-ghost">A propos</a>
                <a href="{{ route('catalog.index') }}" class="auth-btn auth-btn-ghost">Catalogue</a>
                <a href="{{ route('login') }}" class="auth-btn auth-btn-login">Se connecter</a>
                <a href="{{ route('register') }}" class="auth-btn auth-btn-register">S'inscrire</a>
            </div>
        </div>
    </header>
    <section class="hero-wrap flex items-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-7xl mx-auto w-full">
            <div class="hero-panel max-w-3xl rounded-2xl p-8 sm:p-12">
                <span class="hero-kicker inline-block text-xs sm:text-sm uppercase tracking-wider font-bold px-3 py-1 rounded-full mb-6">Plateforme e-learning moderne</span>
                <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">
                    Apprenez avec des cours
                    <span class="hero-dynamic">
                        <span id="dynamicWord" class="dynamic-word">concrets</span>
                    </span>
                    et orientes metier.
                </h1>
                <p class="mt-5 text-base sm:text-lg text-slate-200 max-w-2xl">
                    Developpez vos competences en informatique, cybersecurite et reseau avec une experience
                    <span class="font-semibold text-emerald-200">simple</span>,
                    <span class="font-semibold text-emerald-300">immersive</span> et
                    <span class="font-semibold text-emerald-100">efficace</span>.
                </p>
                <div class="auth-actions mt-8 inline-flex flex-col sm:flex-row gap-3 p-3 rounded-xl">
                    <a href="{{ route('login') }}" class="auth-btn auth-btn-login text-center">Se connecter</a>
                    @if($registrationsOpen ?? true)
                        <a href="{{ route('register') }}" class="auth-btn auth-btn-register text-center">S'inscrire</a>
                    @else
                        <span class="auth-btn auth-btn-register text-center opacity-70 cursor-not-allowed">Inscriptions fermees</span>
                    @endif
                </div>
                @if(!($registrationsOpen ?? true))
                    <p class="mt-3 text-sm text-amber-200">Les inscriptions sont temporairement fermees par l'administration.</p>
                @endif
                <a href="{{ route('catalog.index') }}" class="section-btn mt-4">
                    Voir les formations disponibles
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v8.586l3.293-3.293a1 1 0 111.414 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 111.414-1.414L9 12.586V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    <section id="apropos" class="px-4 sm:px-6 lg:px-8 pb-10">
        <div class="max-w-5xl mx-auto rounded-2xl border border-emerald-300/30 bg-emerald-950/35 backdrop-blur-sm p-6">
            <p class="text-xs uppercase tracking-[0.2em] text-emerald-200">A propos</p>
            <h3 class="text-2xl font-bold mt-2 text-white">LEEDCOURSE, votre systeme d'apprentissage intelligent.</h3>
            <p class="mt-2 text-emerald-100">
                LEEDCOURSE centralise cours, formateurs et suivi des progres pour offrir une experience fluide et motivee.
                Vous accedez a des parcours concrets, des ressources actualisees et un accompagnement clair, le tout dans
                un espace simple a utiliser, que vous soyez debutant ou en reconversion.
            </p>
        </div>
    </section>

    @if(!empty($platformCommunication))
        <section class="px-4 sm:px-6 lg:px-8 pb-10">
            <div class="max-w-5xl mx-auto rounded-2xl border border-emerald-300/40 bg-emerald-950/45 backdrop-blur-sm p-5">
                <p class="text-xs uppercase tracking-[0.2em] text-emerald-200">Communique Officiel</p>
                <h3 class="text-2xl font-bold mt-2 text-white">{{ $platformCommunication->title }}</h3>
                <p class="mt-2 text-emerald-100">{{ $platformCommunication->message }}</p>
            </div>
        </section>
    @endif

    <section id="formations" class="courses-section py-14 sm:py-16">
        <div class="courses-content max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="courses-heading text-3xl sm:text-4xl font-extrabold">Nos Cours Disponibles</h2>
            <p class="courses-subtitle mt-3">Des parcours penses pour passer de la theorie a la pratique.</p>
            <div class="courses-grid mt-10 grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                @forelse(($availableCourses ?? collect()) as $course)
                    @auth
                    <a href="{{ route('dashboard') }}" class="course-card bg-white p-7 rounded-xl text-left block">
                    @else
                    <button type="button" onclick="openGuestCourseModal(@js($course->title))" class="course-card bg-white p-7 rounded-xl text-left block w-full">
                    @endauth
                        <div class="course-cover">
                            @if(!empty($course->image))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->image) }}" alt="{{ $course->title }}">
                            @else
                                <span class="course-cover-fallback">{{ strtoupper(\Illuminate\Support\Str::limit($course->category ?? 'COURSE', 14, '')) }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-slate-900">{{ $course->title }}</h3>
                        <p class="text-slate-600">{{ \Illuminate\Support\Str::limit($course->description, 120) }}</p>
                        <p class="mt-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            {{ $course->category }}{{ $course->level ? ' - '.$course->level : '' }}
                        </p>
                        <p class="mt-2 text-xs text-slate-500">Formateur: {{ optional($course->formateur)->name ?? 'LEEDCOURSE' }}</p>
                        <p class="mt-4 text-sm font-bold text-green-700">Acceder au parcours</p>
                    @auth
                    </a>
                    @else
                    </button>
                    @endauth
                @empty
                    <div class="course-card bg-white p-7 rounded-xl text-left md:col-span-3">
                        <h3 class="font-bold text-xl mb-3 text-slate-900">Aucun cours disponible pour le moment</h3>
                        <p class="text-slate-600">Les formateurs publieront bientot de nouvelles formations.</p>
                    </div>
                @endforelse
            </div>
            <div class="sm:hidden mt-8">
                <a href="{{ route('catalog.index') }}" class="auth-btn auth-btn-register">Ouvrir le catalogue</a>
            </div>
        </div>
    </section>

    <!-- Version mobile des boutons auth dans l'en-tete -->
    <div class="sm:hidden fixed bottom-4 left-1/2 -translate-x-1/2 z-50 w-[calc(100%-1.5rem)] max-w-md">
        <div class="auth-actions flex items-center gap-2 p-2 rounded-xl shadow-xl">
            <a href="{{ route('login') }}" class="auth-btn auth-btn-login text-sm flex-1">Connexion</a>
            <a href="{{ route('register') }}" class="auth-btn auth-btn-register text-sm flex-1">Inscription</a>
        </div>
    </div>

    <div id="guestCourseModal" class="fixed inset-0 hidden items-center justify-center modal-bg z-50 p-4">
        <div class="bg-white text-slate-900 w-full max-w-md rounded-2xl p-6 shadow-2xl">
            <h3 class="text-xl font-extrabold">Acces a la formation</h3>
            <p class="mt-3 text-slate-600">
                Vous avez choisi le cours
                <span id="selectedCourseName" class="font-bold text-slate-900"></span>.
                Creez un compte pour ouvrir ce parcours et commencer la formation.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="auth-btn auth-btn-register">Creer un compte</a>
                <a href="{{ route('login') }}" class="auth-btn auth-btn-login">Se connecter</a>
                <button type="button" onclick="closeGuestCourseModal()" class="auth-btn auth-btn-ghost">Plus tard</button>
            </div>
        </div>
    </div>

    <script>
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuPanel = document.getElementById('mobileMenuPanel');
        const dynamicWord = document.getElementById('dynamicWord');
        const wordOptions = [
            'concrets',
            'clairs',
            'guides',
            'pratiques',
            'stimulates'
        ];
        let wordIndex = 0;

        function cycleWord() {
            wordIndex = (wordIndex + 1) % wordOptions.length;
            dynamicWord.classList.add('is-switching');
            setTimeout(() => {
                dynamicWord.textContent = wordOptions[wordIndex];
                dynamicWord.classList.remove('is-switching');
            }, 260);
        }

        setInterval(cycleWord, 2800);

        if (mobileMenuToggle && mobileMenuPanel) {
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenuPanel.classList.toggle('hidden');
                mobileMenuToggle.setAttribute('aria-expanded', String(!mobileMenuPanel.classList.contains('hidden')));
            });
        }

        function openGuestCourseModal(courseName) {
            const courseNameElement = document.getElementById('selectedCourseName');
            const modal = document.getElementById('guestCourseModal');
            courseNameElement.textContent = courseName;
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
