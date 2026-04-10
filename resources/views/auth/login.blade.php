<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Connexion | LEEDCOURSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">

    <style>
        :root {
            --forest-950: #082f1b;
            --forest-900: #14532d;
            --forest-800: #166534;
            --forest-500: #22c55e;
            --forest-100: #dcfce7;
            --teal-400: #2dd4bf;
            --amber-300: #fcd34d;
            --sky-300: #7dd3fc;
            --slate-950: #020617;
            --slate-900: #0f172a;
            --slate-700: #334155;
            --slate-500: #64748b;
            --slate-300: #cbd5e1;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --white: #ffffff;
            --danger-bg: #fff1f2;
            --danger-border: #fecdd3;
            --danger-text: #be123c;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--white);
            background:
                radial-gradient(circle at 8% 12%, rgba(125, 211, 252, 0.26), transparent 26%),
                radial-gradient(circle at 88% 16%, rgba(252, 211, 77, 0.18), transparent 22%),
                radial-gradient(circle at bottom left, rgba(34, 197, 94, 0.28), transparent 34%),
                radial-gradient(circle at bottom right, rgba(45, 212, 191, 0.2), transparent 28%),
                linear-gradient(135deg, rgba(2, 6, 23, 0.92), rgba(8, 47, 27, 0.8)),
                url('{{ asset('images/image.png') }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow-x: hidden;
        }

        .auth-shell {
            width: 100%;
            max-width: 1200px;
            min-height: min(720px, calc(100vh - 48px));
            display: grid;
            grid-template-columns: minmax(0, 1.08fr) minmax(320px, 0.92fr);
            border-radius: 30px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.15);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.04));
            box-shadow: 0 30px 80px rgba(2, 6, 23, 0.46);
            backdrop-filter: blur(14px);
        }

        .hero-panel {
            position: relative;
            padding: 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background:
                radial-gradient(circle at top right, rgba(125, 211, 252, 0.16), transparent 22%),
                radial-gradient(circle at bottom left, rgba(252, 211, 77, 0.12), transparent 24%),
                linear-gradient(180deg, rgba(15, 23, 42, 0.28), rgba(2, 6, 23, 0.62)),
                linear-gradient(140deg, rgba(22, 163, 74, 0.16), transparent 44%);
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            pointer-events: none;
        }

        .hero-content,
        .hero-footer {
            position: relative;
            z-index: 1;
        }

        .brand-lockup {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--white);
        }

        .brand-mark {
            width: 78px;
            height: 78px;
            border-radius: 24px;
            padding: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(125, 211, 252, 0.18), rgba(34, 197, 94, 0.18));
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 18px 36px rgba(2, 6, 23, 0.3);
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 18px;
        }

        .brand-copy span {
            display: block;
        }

        .brand-kicker {
            font-size: 0.76rem;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: rgba(220, 252, 231, 0.82);
        }

        .brand-name {
            margin-top: 4px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .eyebrow {
            margin-top: 46px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(12, 74, 36, 0.58), rgba(8, 47, 73, 0.5));
            border: 1px solid rgba(125, 211, 252, 0.28);
            font-size: 0.82rem;
            font-weight: 700;
            color: #ecfdf5;
            width: fit-content;
            box-shadow: 0 16px 26px rgba(2, 6, 23, 0.16);
        }

        .hero-title {
            margin: 22px 0 14px;
            max-width: 560px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.2rem, 3.8vw, 4.3rem);
            line-height: 1.02;
            letter-spacing: -0.04em;
        }

        .hero-text {
            margin: 0;
            max-width: 540px;
            color: rgba(226, 232, 240, 0.88);
            font-size: 1rem;
            line-height: 1.75;
        }

        .feature-grid {
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .feature-card {
            padding: 18px;
            border-radius: 20px;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .feature-step {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: rgba(220, 252, 231, 0.16);
            color: #bbf7d0;
            font-weight: 800;
            font-size: 0.85rem;
        }

        .feature-card:nth-child(1) .feature-step {
            background: rgba(125, 211, 252, 0.18);
            color: var(--sky-300);
        }

        .feature-card:nth-child(2) .feature-step {
            background: rgba(252, 211, 77, 0.18);
            color: var(--amber-300);
        }

        .feature-card:nth-child(3) .feature-step {
            background: rgba(45, 212, 191, 0.18);
            color: var(--teal-400);
        }

        .feature-card strong {
            display: block;
            margin-top: 14px;
            font-size: 1rem;
        }

        .feature-card p {
            margin: 8px 0 0;
            color: rgba(226, 232, 240, 0.82);
            font-size: 0.9rem;
            line-height: 1.65;
        }

        .hero-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-top: 28px;
        }

        .hero-footer-copy {
            max-width: 360px;
            color: rgba(226, 232, 240, 0.82);
            font-size: 0.9rem;
            line-height: 1.7;
        }

        .hero-metric {
            padding: 16px 18px;
            min-width: 180px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(125, 211, 252, 0.18), rgba(34, 197, 94, 0.12), rgba(252, 211, 77, 0.1));
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 18px 30px rgba(2, 6, 23, 0.16);
        }

        .hero-metric strong {
            display: block;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            line-height: 1;
        }

        .hero-metric span {
            display: block;
            margin-top: 8px;
            color: rgba(226, 232, 240, 0.8);
            font-size: 0.86rem;
        }

        .form-panel {
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(244, 251, 247, 0.96));
            color: var(--slate-900);
            padding: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 452px;
            padding: 14px;
            border-radius: 28px;
            background:
                radial-gradient(circle at top right, rgba(125, 211, 252, 0.16), transparent 26%),
                linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.96));
            border: 1px solid rgba(209, 250, 229, 0.95);
            box-shadow: 0 26px 50px rgba(15, 23, 42, 0.12);
        }

        .form-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--forest-100), #e0f2fe);
            color: var(--forest-900);
            font-size: 0.8rem;
            font-weight: 800;
        }

        .form-title {
            margin: 18px 0 10px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2rem;
            line-height: 1.08;
            letter-spacing: -0.04em;
        }

        .form-subtitle {
            margin: 0 0 24px;
            color: var(--slate-500);
            line-height: 1.75;
            font-size: 0.95rem;
        }

        .status-box,
        .error-box {
            margin-bottom: 16px;
            border-radius: 18px;
            padding: 14px 16px;
            font-size: 0.92rem;
            line-height: 1.65;
        }

        .status-box {
            background: #ecfdf5;
            border: 1px solid #86efac;
            color: var(--forest-900);
        }

        .error-box {
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }

        .role-switch {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .role-btn {
            appearance: none;
            border: 1px solid var(--slate-200);
            border-radius: 18px;
            background: var(--white);
            padding: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            font: inherit;
            font-weight: 800;
            color: var(--slate-700);
            cursor: pointer;
            transition: 0.2s ease;
        }

        .role-btn:hover {
            transform: translateY(-1px);
            border-color: #86efac;
            box-shadow: 0 12px 26px rgba(34, 197, 94, 0.09);
        }

        .role-btn.active {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7, #e0f2fe);
            border-color: #86efac;
            color: var(--forest-900);
            box-shadow: 0 16px 28px rgba(34, 197, 94, 0.14);
        }

        .role-icon {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--slate-100);
            color: var(--slate-700);
            flex-shrink: 0;
        }

        .role-btn.active .role-icon {
            background: rgba(34, 197, 94, 0.14);
            color: var(--forest-900);
        }

        .hint {
            display: block;
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 16px;
            border: 1px solid #d1fae5;
            background: linear-gradient(135deg, #f8fffb, #eff6ff);
            color: var(--slate-700);
            font-size: 0.88rem;
        }

        .field {
            margin-bottom: 14px;
        }

        .field-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
        }

        .label {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--slate-700);
        }

        .helper {
            font-size: 0.8rem;
            color: var(--slate-500);
        }

        .input {
            width: 100%;
            border: 1px solid #d6dee8;
            border-radius: 16px;
            padding: 14px 15px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.92));
            color: var(--slate-900);
            font: inherit;
            font-size: 0.96rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .input::placeholder {
            color: #94a3b8;
        }

        .input:focus {
            outline: none;
            border-color: var(--forest-500);
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.14);
            transform: translateY(-1px);
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 2px;
            margin-bottom: 8px;
        }

        .checkbox {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--slate-700);
            font-size: 0.9rem;
            font-weight: 700;
        }

        .checkbox input {
            width: 18px;
            height: 18px;
            accent-color: var(--forest-800);
        }

        .form-row a,
        .auth-links a {
            color: var(--forest-800);
            text-decoration: none;
            font-weight: 800;
        }

        .submit {
            width: 100%;
            margin-top: 10px;
            border: none;
            border-radius: 18px;
            padding: 15px 18px;
            background: linear-gradient(135deg, #22c55e, #14b8a6 55%, #0f766e);
            color: var(--white);
            font: inherit;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 18px 28px rgba(20, 184, 166, 0.24);
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 34px rgba(22, 163, 74, 0.26);
            filter: brightness(1.02);
        }

        .auth-links {
            margin-top: 18px;
            display: flex;
            justify-content: center;
            gap: 8px;
            color: var(--slate-500);
            font-size: 0.92rem;
        }

        @media (max-width: 1100px) {
            .auth-shell {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .hero-panel {
                min-height: 420px;
            }

            .form-panel {
                padding: 26px 22px 30px;
            }
        }

        @media (max-width: 720px) {
            body {
                padding: 14px;
                align-items: stretch;
            }

            .auth-shell {
                border-radius: 24px;
            }

            .hero-panel,
            .form-panel {
                padding: 24px 18px;
            }

            .hero-panel::after {
                inset: 16px;
                border-radius: 18px;
            }

            .feature-grid,
            .role-switch {
                grid-template-columns: 1fr;
            }

            .hero-footer,
            .form-row {
                flex-direction: column;
                align-items: stretch;
            }

            .field-top,
            .auth-links {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-title {
                font-size: 1.7rem;
            }

            .hero-metric {
                min-width: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @php
        $selectedProfile = old('profile', 'eleve');
        if (!in_array($selectedProfile, ['eleve', 'enseignant'], true)) {
            $selectedProfile = 'eleve';
        }
    @endphp

    <div class="auth-shell">
        <section class="hero-panel">
            <div class="hero-content">
                <a class="brand-lockup" href="{{ url('/') }}">
                    <span class="brand-mark">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="LEEDCOURSE">
                    </span>
                    <span class="brand-copy">
                        <span class="brand-kicker">Plateforme d'apprentissage</span>
                        <span class="brand-name">LEEDCOURSE</span>
                    </span>
                </a>

                <span class="eyebrow">Connexion securisee eleve / enseignant</span>

                <h1 class="hero-title">Retrouvez votre espace de travail sans friction.</h1>
                <p class="hero-text">
                    Connectez-vous avec le bon profil pour acceder a vos cours, evaluations, contenus et suivis dans un espace clair, rapide et adapte a votre role.
                </p>

                <div class="feature-grid">
                    <article class="feature-card">
                        <span class="feature-step">01</span>
                        <strong>Choix de profil intelligent</strong>
                        <p>Le formulaire s'aligne avec votre type de compte pour reduire les erreurs de connexion.</p>
                    </article>
                    <article class="feature-card">
                        <span class="feature-step">02</span>
                        <strong>Acces immediat au tableau de bord</strong>
                        <p>Une fois authentifie, vous etes redirige vers l'espace correspondant a votre activite.</p>
                    </article>
                    <article class="feature-card">
                        <span class="feature-step">03</span>
                        <strong>Experience plus rassurante</strong>
                        <p>Champs lisibles, actions visibles et rappel du role pour une connexion plus fluide.</p>
                    </article>
                    <article class="feature-card">
                        <span class="feature-step">04</span>
                        <strong>Concu pour mobile et desktop</strong>
                        <p>La mise en page reste nette et confortable, meme sur petit ecran.</p>
                    </article>
                </div>
            </div>

            <div class="hero-footer">
                <div class="hero-footer-copy">
                    LEEDCOURSE centralise le suivi pedagogique, les contenus et les interactions pour garder chaque utilisateur dans le bon rythme.
                </div>
                <div class="hero-metric">
                    <strong>24/7</strong>
                    <span>Acces disponible a votre espace d'apprentissage</span>
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="auth-card pro-form-card fade-in">
                <span class="form-kicker">Bienvenue</span>
                <h2 class="form-title">Connexion a votre compte</h2>
                <p class="form-subtitle">
                    Selectionnez votre profil, renseignez vos acces et reprenez votre progression la ou vous l'avez laissee.
                </p>

                @if (session('status'))
                    <div class="status-box">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" data-learning-launch data-launch-title="Connexion a votre espace" data-launch-message="Verification de vos acces et preparation de votre tableau de bord.">
                    @csrf

                    <input type="hidden" name="profile" id="profileInput" value="{{ $selectedProfile }}">

                    <div class="role-switch">
                        <button type="button" class="role-btn {{ $selectedProfile === 'eleve' ? 'active' : '' }}" data-role="eleve">
                            <span class="role-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path d="M4 20c0-3.9 3.6-6 8-6"></path>
                                    <path d="M16 17h6"></path>
                                    <path d="M19 14v6"></path>
                                </svg>
                            </span>
                            Apprenant
                        </button>
                        <button type="button" class="role-btn {{ $selectedProfile === 'enseignant' ? 'active' : '' }}" data-role="enseignant">
                            <span class="role-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="8" r="4"></circle>
                                    <path d="M6 20c0-3.3 2.7-6 6-6s6 2.7 6 6"></path>
                                </svg>
                            </span>
                            Enseignant
                        </button>
                    </div>

                    <span class="hint" id="roleHint"></span>

                    <div class="field">
                        <div class="field-top">
                            <label for="email" class="label">Adresse email</label>
                            <span class="helper">Utilisez l'email lie a votre compte</span>
                        </div>
                        <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" placeholder="exemple@leedcourse.com" autocomplete="email" required autofocus>
                    </div>

                    <div class="field">
                        <div class="field-top">
                            <label for="password" class="label">Mot de passe</label>
                            <span class="helper">Minimum recommande: mot de passe fort</span>
                        </div>
                        <input id="password" class="input" type="password" name="password" placeholder="Entrez votre mot de passe" autocomplete="current-password" required>
                    </div>

                    <div class="form-row">
                        <label class="checkbox" for="remember">
                            <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Garder ma session ouverte
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Mot de passe oublie ?</a>
                        @endif
                    </div>

                    <button class="submit pro-submit" type="submit">Se connecter</button>

                    <div class="auth-links">
                        <span>Pas encore de compte ?</span>
                        <a href="{{ route('register') }}">Creer un compte</a>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @include('partials.learning-launch-overlay')
    @include('partials.password-toggle')

    <script>
        const loginRoleButtons = document.querySelectorAll('.role-btn');
        const loginProfileInput = document.getElementById('profileInput');
        const loginRoleHint = document.getElementById('roleHint');

        const loginRoleConfig = {
            eleve: "Profil apprenant selectionne: vous serez connecte a l'espace de suivi de cours et d'evaluations.",
            enseignant: "Profil enseignant selectionne: vous serez connecte a l'espace de gestion pedagogique."
        };

        function applyLoginRole(role) {
            loginProfileInput.value = role;
            loginRoleHint.textContent = loginRoleConfig[role] || loginRoleConfig.eleve;

            loginRoleButtons.forEach((button) => {
                button.classList.toggle('active', button.dataset.role === role);
            });
        }

        loginRoleButtons.forEach((button) => {
            button.addEventListener('click', () => applyLoginRole(button.dataset.role));
        });

        applyLoginRole(loginProfileInput.value || 'eleve');
    </script>

    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
