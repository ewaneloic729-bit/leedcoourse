<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Inscription | LEEDCOURSE</title>
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
            --forest-200: #bbf7d0;
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
                radial-gradient(circle at 8% 12%, rgba(125, 211, 252, 0.26), transparent 24%),
                radial-gradient(circle at 88% 14%, rgba(252, 211, 77, 0.18), transparent 20%),
                radial-gradient(circle at top left, rgba(34, 197, 94, 0.28), transparent 34%),
                radial-gradient(circle at bottom right, rgba(16, 185, 129, 0.18), transparent 30%),
                linear-gradient(140deg, rgba(2, 6, 23, 0.94), rgba(8, 47, 27, 0.8)),
                url('{{ asset('images/image.png') }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-shell {
            width: 100%;
            max-width: 1260px;
            min-height: 760px;
            display: grid;
            grid-template-columns: minmax(0, 1.04fr) minmax(460px, 0.96fr);
            border-radius: 32px;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.16);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.04));
            box-shadow: 0 34px 90px rgba(2, 6, 23, 0.5);
            backdrop-filter: blur(16px);
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
                linear-gradient(180deg, rgba(15, 23, 42, 0.3), rgba(2, 6, 23, 0.64)),
                linear-gradient(140deg, rgba(34, 197, 94, 0.16), transparent 46%);
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 24px;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.08);
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
            width: 80px;
            height: 80px;
            border-radius: 24px;
            padding: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(125, 211, 252, 0.18), rgba(34, 197, 94, 0.18));
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 20px 40px rgba(2, 6, 23, 0.28);
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 18px;
        }

        .brand-kicker {
            display: block;
            font-size: 0.76rem;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: rgba(220, 252, 231, 0.84);
        }

        .brand-name {
            display: block;
            margin-top: 4px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.34rem;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .eyebrow {
            margin-top: 44px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            padding: 9px 14px;
            border-radius: 999px;
            border: 1px solid rgba(125, 211, 252, 0.28);
            background: linear-gradient(135deg, rgba(12, 74, 36, 0.58), rgba(8, 47, 73, 0.5));
            color: #ecfdf5;
            font-size: 0.82rem;
            font-weight: 800;
            box-shadow: 0 16px 26px rgba(2, 6, 23, 0.16);
        }

        .hero-title {
            margin: 22px 0 16px;
            max-width: 540px;
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.3rem, 4vw, 4.4rem);
            line-height: 1.02;
            letter-spacing: -0.05em;
        }

        .hero-text {
            margin: 0;
            max-width: 520px;
            color: rgba(226, 232, 240, 0.86);
            font-size: 1rem;
            line-height: 1.75;
        }

        .hero-points {
            margin-top: 30px;
            display: grid;
            gap: 12px;
        }

        .hero-point {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 14px;
            align-items: start;
            padding: 16px 18px;
            border-radius: 20px;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .hero-point-badge {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            background: rgba(220, 252, 231, 0.16);
            color: var(--forest-200);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.9rem;
        }

        .hero-point:nth-child(1) .hero-point-badge {
            background: rgba(125, 211, 252, 0.18);
            color: var(--sky-300);
        }

        .hero-point:nth-child(2) .hero-point-badge {
            background: rgba(252, 211, 77, 0.18);
            color: var(--amber-300);
        }

        .hero-point:nth-child(3) .hero-point-badge {
            background: rgba(45, 212, 191, 0.18);
            color: var(--teal-400);
        }

        .hero-point strong {
            display: block;
            font-size: 0.98rem;
        }

        .hero-point p {
            margin: 6px 0 0;
            color: rgba(226, 232, 240, 0.8);
            font-size: 0.9rem;
            line-height: 1.65;
        }

        .hero-footer {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: end;
            padding-top: 28px;
        }

        .hero-footer-copy {
            color: rgba(226, 232, 240, 0.8);
            font-size: 0.9rem;
            line-height: 1.7;
            max-width: 360px;
        }

        .hero-metric {
            padding: 18px 20px;
            border-radius: 22px;
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
            color: rgba(226, 232, 240, 0.82);
            font-size: 0.86rem;
        }

        .form-panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.985), rgba(244, 251, 247, 0.96));
            color: var(--slate-900);
            padding: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 520px;
            padding: 10px;
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
            width: fit-content;
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
            font-size: 0.95rem;
            line-height: 1.75;
        }

        .error-box {
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: 18px;
            background: var(--danger-bg);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
            font-size: 0.92rem;
            line-height: 1.65;
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
            padding: 13px 14px;
            border-radius: 16px;
            background: linear-gradient(135deg, #f8fffb, #eff6ff);
            border: 1px solid #dcfce7;
            color: var(--slate-700);
            font-size: 0.88rem;
        }

        .fields-group {
            display: none;
        }

        .fields-group.active {
            display: block;
            animation: fadeIn 0.18s ease;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field {
            margin-bottom: 14px;
        }

        .field.field-full {
            grid-column: 1 / -1;
        }

        .label {
            display: block;
            margin-bottom: 8px;
            color: var(--slate-700);
            font-size: 0.88rem;
            font-weight: 800;
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

        .password-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .support-copy {
            margin-top: 2px;
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: 18px;
            background: linear-gradient(135deg, #f8fafc, #f0fdf4, #eff6ff);
            border: 1px solid var(--slate-200);
            color: var(--slate-700);
            font-size: 0.88rem;
            line-height: 1.65;
        }

        .submit {
            width: 100%;
            margin-top: 8px;
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

        .auth-links a {
            color: var(--forest-800);
            text-decoration: none;
            font-weight: 800;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1120px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .hero-panel {
                min-height: 460px;
            }

            .form-panel {
                padding: 26px 22px 30px;
            }
        }

        @media (max-width: 760px) {
            body {
                padding: 14px;
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

            .hero-footer,
            .form-grid,
            .password-grid,
            .role-switch {
                grid-template-columns: 1fr;
            }

            .hero-footer {
                display: grid;
            }

            .form-title {
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    @php
        $defaultProfile = $selectedProfile ?? old('profile', 'eleve');
        if (!in_array($defaultProfile, ['eleve', 'enseignant'], true)) {
            $defaultProfile = 'eleve';
        }
    @endphp

    <div class="auth-shell">
        <section class="hero-panel">
            <div class="hero-content">
                <a class="brand-lockup" href="{{ url('/') }}">
                    <span class="brand-mark">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="LEEDCOURSE">
                    </span>
                    <span>
                        <span class="brand-kicker">Plateforme d'apprentissage</span>
                        <span class="brand-name">LEEDCOURSE</span>
                    </span>
                </a>

                <span class="eyebrow">Inscription accompagnee et adaptee au profil</span>

                <h1 class="hero-title">Creez votre compte et entrez dans l'experience LEEDCOURSE.</h1>
                <p class="hero-text">
                    Le formulaire change selon votre role pour ne montrer que les informations utiles. Vous avancez plus vite, avec une interface plus claire et plus rassurante.
                </p>

                <div class="hero-points">
                    <article class="hero-point">
                        <span class="hero-point-badge">01</span>
                        <div>
                            <strong>Un seul ecran, deux parcours</strong>
                            <p>Choisissez apprenant ou enseignant et le formulaire affiche uniquement les champs pertinents.</p>
                        </div>
                    </article>
                    <article class="hero-point">
                        <span class="hero-point-badge">02</span>
                        <div>
                            <strong>Informations mieux organisees</strong>
                            <p>Les champs sont groupes de maniere plus lisible pour limiter les hesitations pendant l'inscription.</p>
                        </div>
                    </article>
                    <article class="hero-point">
                        <span class="hero-point-badge">03</span>
                        <div>
                            <strong>Experience plus propre sur mobile</strong>
                            <p>Le design reste fluide sur telephone et conserve une bonne hierarchie visuelle sur grand ecran.</p>
                        </div>
                    </article>
                </div>
            </div>

            <div class="hero-footer">
                <div class="hero-footer-copy">
                    Une fois le compte cree, l'utilisateur entre directement dans son parcours avec les droits, vues et contenus correspondant a son role.
                </div>
                <div class="hero-metric">
                    <strong>2 profils</strong>
                    <span>Un formulaire intelligent pour chaque type d'utilisateur</span>
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="auth-card pro-form-card fade-in">
                <span class="form-kicker">Inscription</span>
                <h2 class="form-title">Creer votre compte</h2>
                <p class="form-subtitle">
                    Selectionnez votre profil puis completez le formulaire adapte. Les champs obligatoires changent automatiquement selon votre role.
                </p>

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" id="registerForm" action="{{ $defaultProfile === 'enseignant' ? route('register.enseignant.store') : route('register.eleve.store') }}">
                    @csrf
                    <input type="hidden" name="profile" id="profileInput" value="{{ $defaultProfile }}">

                    <div class="role-switch">
                        <button type="button" class="role-btn {{ $defaultProfile === 'eleve' ? 'active' : '' }}" data-role="eleve">
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
                        <button type="button" class="role-btn {{ $defaultProfile === 'enseignant' ? 'active' : '' }}" data-role="enseignant">
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
                    <div class="support-copy" id="supportCopy"></div>

                    <div class="fields-group {{ $defaultProfile === 'eleve' ? 'active' : '' }}" data-group="eleve">
                        <div class="form-grid">
                            <div class="field field-full">
                                <label class="label" for="eleve_name">Nom complet</label>
                                <input id="eleve_name" class="input" type="text" name="name" value="{{ old('name') }}" placeholder="Votre nom complet" autocomplete="name" data-profile-field="eleve">
                            </div>

                            <div class="field field-full">
                                <label class="label" for="eleve_email">Adresse email</label>
                                <input id="eleve_email" class="input" type="email" name="email" value="{{ old('email') }}" placeholder="exemple@leedcourse.com" autocomplete="email" data-profile-field="eleve">
                            </div>

                            <div class="field field-full">
                                <label class="label" for="eleve_whatsapp_phone">Numero WhatsApp</label>
                                <input id="eleve_whatsapp_phone" class="input" type="text" name="whatsapp_phone" value="{{ old('whatsapp_phone') }}" placeholder="Ex: 2376XXXXXXXX" autocomplete="tel" data-profile-field="eleve">
                            </div>

                            <div class="field">
                                <label class="label" for="classe">Classe</label>
                                <input id="classe" class="input" type="text" name="classe" value="{{ old('classe') }}" placeholder="Ex: Terminale D" data-profile-field="eleve">
                            </div>

                            <div class="field">
                                <label class="label" for="date_naissance">Date de naissance</label>
                                <input id="date_naissance" class="input" type="date" name="date_naissance" value="{{ old('date_naissance') }}" data-profile-field="eleve">
                            </div>
                        </div>

                        <div class="password-grid">
                            <div class="field">
                                <label class="label" for="eleve_password">Mot de passe</label>
                                <input id="eleve_password" class="input" type="password" name="password" placeholder="Choisissez un mot de passe" autocomplete="new-password" data-profile-field="eleve">
                            </div>

                            <div class="field">
                                <label class="label" for="eleve_password_confirmation">Confirmation</label>
                                <input id="eleve_password_confirmation" class="input" type="password" name="password_confirmation" placeholder="Confirmez le mot de passe" autocomplete="new-password" data-profile-field="eleve">
                            </div>
                        </div>
                    </div>

                    <div class="fields-group {{ $defaultProfile === 'enseignant' ? 'active' : '' }}" data-group="enseignant">
                        <div class="form-grid">
                            <div class="field">
                                <label class="label" for="prenom">Prenom</label>
                                <input id="prenom" class="input" type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Votre prenom" autocomplete="given-name" data-profile-field="enseignant">
                            </div>

                            <div class="field">
                                <label class="label" for="nom">Nom</label>
                                <input id="nom" class="input" type="text" name="nom" value="{{ old('nom') }}" placeholder="Votre nom" autocomplete="family-name" data-profile-field="enseignant">
                            </div>

                            <div class="field field-full">
                                <label class="label" for="enseignant_email">Adresse email</label>
                                <input id="enseignant_email" class="input" type="email" name="email" value="{{ old('email') }}" placeholder="exemple@leedcourse.com" autocomplete="email" data-profile-field="enseignant">
                            </div>

                            <div class="field field-full">
                                <label class="label" for="enseignant_whatsapp_phone">Numero WhatsApp</label>
                                <input id="enseignant_whatsapp_phone" class="input" type="text" name="whatsapp_phone" value="{{ old('whatsapp_phone') }}" placeholder="Ex: 2376XXXXXXXX" autocomplete="tel" data-profile-field="enseignant">
                            </div>

                            <div class="field">
                                <label class="label" for="specialite">Specialite</label>
                                <input id="specialite" class="input" type="text" name="specialite" value="{{ old('specialite') }}" placeholder="Matiere enseignee" data-profile-field="enseignant">
                            </div>

                            <div class="field">
                                <label class="label" for="diplome">Diplome principal</label>
                                <input id="diplome" class="input" type="text" name="diplome" value="{{ old('diplome') }}" placeholder="Ex: Master, Licence..." data-profile-field="enseignant">
                            </div>

                            <div class="field field-full">
                                <label class="label" for="annees_experience">Annees d'experience</label>
                                <input id="annees_experience" class="input" type="number" min="0" name="annees_experience" value="{{ old('annees_experience') }}" placeholder="Entrez votre experience" data-profile-field="enseignant">
                            </div>
                        </div>

                        <div class="password-grid">
                            <div class="field">
                                <label class="label" for="enseignant_password">Mot de passe</label>
                                <input id="enseignant_password" class="input" type="password" name="password" placeholder="Choisissez un mot de passe" autocomplete="new-password" data-profile-field="enseignant">
                            </div>

                            <div class="field">
                                <label class="label" for="enseignant_password_confirmation">Confirmation</label>
                                <input id="enseignant_password_confirmation" class="input" type="password" name="password_confirmation" placeholder="Confirmez le mot de passe" autocomplete="new-password" data-profile-field="enseignant">
                            </div>
                        </div>
                    </div>

                    <button class="submit pro-submit" type="submit">Creer mon compte</button>

                    <div class="auth-links">
                        <span>Vous avez deja un compte ?</span>
                        <a href="{{ route('login') }}">Se connecter</a>
                    </div>
                </form>
            </div>
        </section>
    </div>

    @include('partials.password-toggle')

    <script>
        const roleButtons = document.querySelectorAll('.role-btn');
        const profileInput = document.getElementById('profileInput');
        const roleHint = document.getElementById('roleHint');
        const supportCopy = document.getElementById('supportCopy');
        const registerForm = document.getElementById('registerForm');
        const groups = document.querySelectorAll('.fields-group');
        const profileFields = document.querySelectorAll('[data-profile-field]');

        const roleConfig = {
            eleve: {
                hint: "Profil apprenant: completez vos informations personnelles et scolaires pour acceder a vos parcours.",
                support: "Le compte apprenant demande le nom complet, le numero WhatsApp, la classe et la date de naissance pour mieux personnaliser le suivi.",
                action: '{{ route('register.eleve.store') }}'
            },
            enseignant: {
                hint: "Profil enseignant: renseignez vos informations academiques et professionnelles pour ouvrir votre espace pedagogique.",
                support: "Le compte enseignant met l'accent sur la specialite, le diplome et l'experience afin de preparer un espace de travail adapte.",
                action: '{{ route('register.enseignant.store') }}'
            }
        };

        function applyRole(role) {
            const config = roleConfig[role] || roleConfig.eleve;

            profileInput.value = role;
            registerForm.action = config.action;
            roleHint.textContent = config.hint;
            supportCopy.textContent = config.support;

            roleButtons.forEach((button) => {
                button.classList.toggle('active', button.dataset.role === role);
            });

            groups.forEach((group) => {
                group.classList.toggle('active', group.dataset.group === role);
            });

            profileFields.forEach((field) => {
                const isActive = field.dataset.profileField === role;
                field.required = isActive;
                field.disabled = !isActive;
            });
        }

        roleButtons.forEach((button) => {
            button.addEventListener('click', () => applyRole(button.dataset.role));
        });

        applyRole(profileInput.value || 'eleve');
    </script>

    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
