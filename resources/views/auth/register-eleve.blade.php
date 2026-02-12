<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Élève | LEEDCOURSE</title>
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
            Créez votre compte élève et accédez aux cours LEEDCOURSE
        </h1>
    </div>

    <div class="form-side">
        <div class="register-box">
            <img src="{{ asset('images/logo.png') }}" alt="LEEDCOURSE">
            <h2>Inscription Élève</h2>

            <form method="POST" action="{{ route('register.eleve.store') }}">
                @csrf

                <input type="text" name="name" placeholder="Nom complet" required>
                <input type="email" name="email" placeholder="Adresse email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>

                <input type="text" name="classe" placeholder="Classe (ex: Terminale S)" required>
                <input type="date" name="date_naissance" placeholder="Date de naissance" required>

                <button type="submit">Créer mon compte</button>
            </form>

            <div class="links">
                <p>
                    Vous êtes enseignant ?
                    <a href="{{ route('register.enseignant') }}">Créer un compte enseignant</a>
                </p>
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
