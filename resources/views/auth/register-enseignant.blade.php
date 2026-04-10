<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Inscription Enseignant | LEEDCOURSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .image-side {
            flex: 1;
            background: linear-gradient(
                rgba(0, 0, 0, 0.5),
                rgba(0, 0, 0, 0.5)
            ),
            url('{{ asset("images/register-bg.jpg") }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px;
        }

        .image-side h1 {
            font-size: 40px;
            max-width: 500px;
        }

        .form-side {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .register-box {
            width: 100%;
            max-width: 420px;
        }

        .register-box img {
            display: block;
            margin: 0 auto 20px;
            height: 55px;
        }

        .register-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #2e7d32;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            width: 100%;
            padding: 12px;
            background: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background: #1b5e20;
        }

        .links {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .links a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-side {
                height: 35vh;
                text-align: center;
            }

            .image-side h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>


<div class="container">
    <div class="image-side">
        <h1>
            Créez votre compte enseignant et partagez votre expertise
        </h1>
    </div>

    <div class="form-side">
        <div class="register-box pro-form-card fade-in" style="padding: 1.3rem; border-radius: 1rem;">
            <a href="{{ url('/') }}" class="brand-lockup" style="justify-content:center; width:100%; margin-bottom: 0.9rem;">
                <span class="brand-logo-badge">
                    <img class="brand-logo-img" src="{{ asset('images/logo.jpeg') }}" alt="LEEDCOURSE">
                </span>
                <span class="brand-name" style="color:#0f172a;">LEEDCOURSE</span>
            </a>
            <h2>Inscription Enseignant</h2>

            @if ($errors->any())
                <div style="margin-bottom:12px;border:1px solid #fecaca;background:#fef2f2;color:#b91c1c;padding:10px;border-radius:8px;font-size:13px;">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.enseignant.store') }}">
                @csrf

                <input type="text" name="nom" value="{{ old('nom') }}" placeholder="Nom" required>
                <input type="text" name="prenom" value="{{ old('prenom') }}" placeholder="Prénom" required>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Adresse email" required>
                <input type="text" name="whatsapp_phone" value="{{ old('whatsapp_phone') }}" placeholder="Numero WhatsApp (ex: 2376XXXXXXXX)" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>

                <input type="text" name="specialite" value="{{ old('specialite') }}" placeholder="Spécialité / Matière enseignée" required>
                <input type="text" name="diplome" value="{{ old('diplome') }}" placeholder="Diplôme principal" required>
                <input type="number" name="annees_experience" value="{{ old('annees_experience') }}" placeholder="Années d'expérience" min="0" required>

                <button type="submit" class="pro-submit">Créer mon compte</button>
            </form>

            <div class="links">
                <p>
                    Vous êtes élève ?
                    <a href="{{ route('register.eleve') }}">Créer un compte élève</a>
                </p>
                <p>
                    Déjà inscrit ?
                    <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>

@include('partials.password-toggle')

    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
