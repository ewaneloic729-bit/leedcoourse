<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | LEEDCOURSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font -->
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

        /* IMAGE SECTION */
        .image-side {
            flex: 1;
            background: linear-gradient(
                rgba(0, 0, 0, 0.5),
                rgba(0, 0, 0, 0.5)
            ),
            url('{{ asset("images/login-bg.jpg") }}') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px;
        }

        .image-side h1 {
            font-size: 42px;
            max-width: 500px;
        }

        /* FORM SECTION */
        .form-side {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
        }

        .login-box img {
            display: block;
            margin: 0 auto 20px;
            height: 50px;
        }

        .login-box h2 {
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
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
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

        /* MOBILE */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .image-side {
                height: 40vh;
                text-align: center;
            }

            .image-side h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

<div class="container">

    <!-- IMAGE -->
    <div class="image-side">
        <h1>
            Connectez-vous et développez vos compétences avec LEEDCOURSE
        </h1>
    </div>

    <!-- FORM -->
    <div class="form-side">
        <div class="login-box">

            <img src="{{ asset('images/logo.png') }}" alt="LEEDCOURSE">

            <h2>Connexion</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <input type="email" name="email" placeholder="Adresse email" required autofocus>

                <input type="password" name="password" placeholder="Mot de passe" required>

                <button type="submit">Se connecter</button>
            </form>

            <div class="links">
                <p>
                    Pas encore de compte ?
                    <a href="{{ route('register') }}">Créer un compte</a>
                </p>
            </div>

        </div>
    </div>

</div>

</body>
</html>
