<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Code de verification | LEEDCOURSE</title>
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
        * { box-sizing: border-box; }
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
        .card {
            width: 100%;
            max-width: 460px;
            border-radius: 18px;
            background: #fff;
            color: var(--ink);
            padding: 26px;
            box-shadow: 0 24px 50px rgba(2, 6, 23, 0.45);
        }
        .title {
            margin: 0;
            font-size: 1.5rem;
        }
        .subtitle {
            margin-top: 8px;
            margin-bottom: 16px;
            color: var(--muted);
            font-size: 0.92rem;
        }
        .success-box, .error-box {
            margin-bottom: 14px;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
        }
        .success-box {
            background: #f0fdf4;
            border: 1px solid #86efac;
            color: #166534;
        }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
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
        }
        .submit {
            width: 100%;
            margin-top: 4px;
            border: none;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.96rem;
            font-weight: 700;
            color: #fff;
            cursor: pointer;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
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
        .whatsapp-box {
            margin-bottom: 14px;
            border-radius: 12px;
            padding: 12px 14px;
            background: #ecfdf5;
            border: 1px solid #86efac;
            color: #166534;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .whatsapp-link {
            display: inline-block;
            margin-top: 10px;
            border-radius: 10px;
            padding: 10px 14px;
            background: linear-gradient(135deg, #22c55e, #15803d);
            color: #fff;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="card pro-form-card fade-in">
        <a href="{{ url('/') }}" class="brand-lockup" style="margin-bottom: 0.9rem;">
            <span class="brand-logo-badge">
                <img class="brand-logo-img" src="{{ asset('images/logo.jpeg') }}" alt="LEEDCOURSE">
            </span>
            <span class="brand-name" style="color:#0f172a;">LEEDCOURSE</span>
        </a>
        <h2 class="title">Verifier le code</h2>
        <p class="subtitle">Entrez le code temporaire recu par {{ $channel === 'whatsapp' ? 'WhatsApp' : 'email' }}, puis choisissez un nouveau mot de passe.</p>
        @if(!empty($sentTo))
            <p class="subtitle" style="margin-top:-8px;">Destination masquee: <strong>{{ $sentTo }}</strong></p>
        @endif

        @if ($channel === 'whatsapp' && !empty($whatsAppUrl))
            <div class="whatsapp-box">
                Ouvrez WhatsApp pour retrouver le message pre-rempli contenant votre code de verification.
                <div>
                    <a href="{{ $whatsAppUrl }}" class="whatsapp-link" target="_blank" rel="noopener noreferrer">Ouvrir WhatsApp</a>
                </div>
            </div>
        @endif

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

        <form method="POST" action="{{ route('password.code.reset') }}">
            @csrf

            <div class="field">
                <label class="label" for="code">Code temporaire (6 chiffres)</label>
                <input id="code" class="input" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" value="{{ old('code') }}" required>
            </div>

            <div class="field">
                <label class="label" for="password">Nouveau mot de passe</label>
                <input id="password" class="input" type="password" name="password" required>
            </div>

            <div class="field">
                <label class="label" for="password_confirmation">Confirmer le mot de passe</label>
                <input id="password_confirmation" class="input" type="password" name="password_confirmation" required>
            </div>

            <button type="submit" class="submit pro-submit">Reinitialiser le mot de passe</button>
        </form>

        <div class="links">
            <a href="{{ route('password.request') }}">Demander un nouveau code</a>
        </div>
    </div>

    @include('partials.password-toggle')
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
