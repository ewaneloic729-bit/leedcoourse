<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublie | LEEDCOURSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        :root {
            --brand: #16a34a;
            --brand-dark: #166534;
            --ink: #0f172a;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(120deg, rgba(2, 6, 23, 0.78), rgba(20, 83, 45, 0.62)),
                        url('{{ asset("images/image.png") }}') center/cover no-repeat;
            color: #e2e8f0;
            display: grid;
            place-items: center;
            padding: 20px;
        }

        .auth-shell {
            width: 100%;
            max-width: 980px;
            min-height: 620px;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.28);
            box-shadow: 0 24px 60px rgba(2, 6, 23, 0.5);
            backdrop-filter: blur(8px);
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: rgba(2, 6, 23, 0.45);
        }

        .panel-left {
            padding: 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(160deg, rgba(15, 23, 42, 0.45), rgba(3, 7, 18, 0.15));
        }

        .brand {
            color: #ffffff;
            letter-spacing: 0.16em;
            font-size: 1rem;
            font-weight: 800;
            text-decoration: none;
        }

        .hero-title {
            margin: 18px 0 14px;
            font-size: clamp(1.6rem, 2.4vw, 2.4rem);
            line-height: 1.14;
            color: #f8fafc;
        }

        .hero-text {
            margin: 0;
            color: #cbd5e1;
            max-width: 420px;
            line-height: 1.6;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 14px;
            border: 1px solid rgba(134, 239, 172, 0.45);
            background: rgba(22, 101, 52, 0.26);
            font-weight: 600;
            font-size: 0.8rem;
            color: #dcfce7;
            width: fit-content;
        }

        .panel-right {
            background: #ffffff;
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
        }

        .card {
            width: 100%;
            max-width: 390px;
        }

        .title {
            margin: 0;
            font-size: 1.65rem;
            color: #0f172a;
        }

        .subtitle {
            margin-top: 8px;
            margin-bottom: 18px;
            color: var(--muted);
            line-height: 1.55;
            font-size: 0.93rem;
        }

        .success-box {
            margin-bottom: 14px;
            border-radius: 10px;
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        .error-box {
            margin-bottom: 14px;
            border-radius: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        .field {
            margin-bottom: 12px;
        }

        .label {
            display: block;
            font-size: 0.84rem;
            margin-bottom: 6px;
            color: #334155;
            font-weight: 600;
        }

        .input {
            width: 100%;
            border: 1px solid #d0d7e2;
            border-radius: 11px;
            padding: 12px 13px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.18);
        }

        .submit {
            width: 100%;
            margin-top: 4px;
            border: none;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.96rem;
            font-weight: 700;
            color: #ffffff;
            cursor: pointer;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            transition: transform 0.2s ease, filter 0.2s ease;
        }

        .submit:hover {
            transform: translateY(-1px);
            filter: brightness(1.04);
        }

        .channel-switch {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }

        .channel-btn {
            appearance: none;
            border: 1px solid #d0d7e2;
            background: #f8fafc;
            color: #334155;
            border-radius: 11px;
            padding: 10px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
        }

        .channel-btn.active {
            border-color: #16a34a;
            background: #f0fdf4;
            color: #166534;
        }

        .links {
            margin-top: 14px;
            font-size: 0.88rem;
        }

        .links a {
            color: #15803d;
            text-decoration: none;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .panel-left {
                padding: 24px;
                min-height: 190px;
            }

            .panel-right {
                padding: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="auth-shell">
        <section class="panel-left">
            <div>
                <a class="brand-lockup" href="{{ url('/') }}">
                    <span class="brand-logo-badge">
                        <img class="brand-logo-img" src="{{ asset('images/logo.jpeg') }}" alt="LEEDCOURSE">
                    </span>
                    <span class="brand-name">LEEDCOURSE</span>
                </a>
                <h1 class="hero-title">Recuperez l'acces a votre compte en quelques secondes.</h1>
                <p class="hero-text">Entrez votre email ou votre numero WhatsApp, puis validez avec un code temporaire.</p>
            </div>
            <span class="pill">Procedure simple et securisee</span>
        </section>

        <section class="panel-right">
            <div class="card pro-form-card fade-in">
                @php
                    $selectedChannel = old('channel', 'email');
                @endphp
                <h2 class="title">Mot de passe oublie</h2>
                <p class="subtitle">Choisissez le canal de recuperation puis recevez un code temporaire de verification.</p>

                @if (session('status'))
                    <div class="success-box">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="error-box">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <input type="hidden" name="channel" id="channelInput" value="{{ $selectedChannel }}">

                    <div class="channel-switch">
                        <button type="button" class="channel-btn {{ $selectedChannel === 'email' ? 'active' : '' }}" data-channel="email">Email</button>
                        <button type="button" class="channel-btn {{ $selectedChannel === 'whatsapp' ? 'active' : '' }}" data-channel="whatsapp">WhatsApp</button>
                    </div>

                    <div class="field" id="emailField">
                        <label class="label" for="email">Email du compte</label>
                        <input
                            id="email"
                            class="input"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="exemple@email.com"
                        >
                    </div>

                    <div class="field" id="whatsappField">
                        <label class="label" for="whatsapp_phone">Numero WhatsApp du compte</label>
                        <input
                            id="whatsapp_phone"
                            class="input"
                            type="text"
                            name="whatsapp_phone"
                            value="{{ old('whatsapp_phone') }}"
                            placeholder="Ex: 2376XXXXXXXX"
                        >
                    </div>

                    <button type="submit" class="submit pro-submit">Recevoir le code temporaire</button>
                </form>

                <div class="links">
                    <a href="{{ route('login') }}">Retour a la connexion</a>
                </div>
            </div>
        </section>
    </div>
    <script>
        const channelInput = document.getElementById('channelInput');
        const channelButtons = document.querySelectorAll('.channel-btn');
        const emailField = document.getElementById('emailField');
        const whatsappField = document.getElementById('whatsappField');
        const emailInput = document.getElementById('email');
        const whatsappInput = document.getElementById('whatsapp_phone');

        function applyChannel(channel) {
            channelInput.value = channel;
            channelButtons.forEach((btn) => btn.classList.toggle('active', btn.dataset.channel === channel));

            if (channel === 'whatsapp') {
                whatsappField.style.display = 'block';
                emailField.style.display = 'none';
                whatsappInput.required = true;
                emailInput.required = false;
            } else {
                whatsappField.style.display = 'none';
                emailField.style.display = 'block';
                whatsappInput.required = false;
                emailInput.required = true;
            }
        }

        channelButtons.forEach((btn) => {
            btn.addEventListener('click', () => applyChannel(btn.dataset.channel));
        });

        applyChannel(channelInput.value || 'email');
    </script>
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
