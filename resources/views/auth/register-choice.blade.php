<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | LEEDCOURSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

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
            text-align: center;
        }

        .register-box img {
            display: block;
            margin: 0 auto 20px;
            height: 55px;
        }

        .register-box h2 {
            margin-bottom: 10px;
            color: #2e7d32;
        }

        .register-box p {
            color: #555;
            margin-bottom: 30px;
        }

        .role-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .role-link {
            display: block;
            padding: 14px;
            background: #f5f5f5;
            color: #2e7d32;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .role-link:hover {
            background: #e8f5e9;
            border-color: #2e7d32;
        }

        .links {
            margin-top: 18px;
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
            Rejoignez LEEDCOURSE et commencez votre parcours d'apprentissage
        </h1>
    </div>

    <div class="form-side">
        <div class="register-box">
            <img src="{{ asset('images/logo.png') }}" alt="LEEDCOURSE">
            <h2>Créer un compte</h2>
            <p>Choisissez le type de compte pour continuer.</p>

            <div class="role-actions">
                <a class="role-link" href="{{ route('register.eleve') }}">Je suis un élève</a>
                <a class="role-link" href="{{ route('register.enseignant') }}">Je suis un enseignant</a>
            </div>

            <div class="links">
                <p>
                    Déjà inscrit ?
                    <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
