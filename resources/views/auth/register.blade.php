
                   








<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription | LEEDCOURSE</title>
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

        /* FORM SECTION */
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

        /* ROLE BUTTONS */
        .role-selector {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .role-btn {
            flex: 1;
            padding: 12px;
            background: #f5f5f5;
            color: #666;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .role-btn:hover {
            background: #e8f5e9;
            border-color: #2e7d32;
        }

        .role-btn.active {
            background: #2e7d32;
            color: white;
            border-color: #2e7d32;
        }

        input, select {
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

        /* HIDDEN FIELDS */
        .form-group {
            display: none;
        }

        .form-group.active {
            display: block;
        }

        /* MOBILE */
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

    <!-- IMAGE -->
    <div class="image-side">
        <h1>
            Rejoignez LEEDCOURSE et commencez votre parcours d'apprentissage
        </h1>
    </div>

    <!-- FORM -->
    <div class="form-side">
        <div class="register-box">

            <img src="{{ asset('images/logo.png') }}" alt="LEEDCOURSE">

            <h2>Créer un compte</h2>

            <!-- ROLE SELECTOR -->
            <div class="role-selector">
                <button type="button" class="role-btn active" onclick="switchRole('eleve')">
                    Élève
                </button>
                <button type="button" class="role-btn" onclick="switchRole('enseignant')">
                    Enseignant
                </button>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Hidden field for role -->
                <input type="hidden" name="role" id="role" value="eleve">

                <!-- COMMON FIELDS -->
                <input type="text" name="name" placeholder="Nom complet" required>
                <input type="email" name="email" placeholder="Adresse email" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required>

                <!-- ELEVE FIELDS -->
                <div id="eleve-fields" class="form-group active">
                    <input type="text" name="classe" placeholder="Classe (ex: Terminale S)">
                    <input type="date" name="date_naissance" placeholder="Date de naissance">
                </div>

                <!-- ENSEIGNANT FIELDS -->
                <div id="enseignant-fields" class="form-group">
                    <input type="text" name="specialite" placeholder="Spécialité / Matière enseignée">
                    <input type="number" name="annees_experience" placeholder="Années d'expérience" min="0">
                </div>

                <button type="submit">Créer mon compte</button>
            </form>

            <div class="links">
                <p>
                    Déjà inscrit ?
                    <a href="{{ route('login') }}">Se connecter</a>
                </p>
            </div>

        </div>
    </div>

</div>

<script>
    function switchRole(role) {
        // Update hidden role field
        document.getElementById('role').value = role;

        // Update button styles
        const buttons = document.querySelectorAll('.role-btn');
        buttons.forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        // Show/hide appropriate fields
        const eleveFields = document.getElementById('eleve-fields');
        const enseignantFields = document.getElementById('enseignant-fields');

        if (role === 'eleve') {
            eleveFields.classList.add('active');
            enseignantFields.classList.remove('active');
            
            // Make eleve fields required
            eleveFields.querySelectorAll('input').forEach(input => {
                input.setAttribute('required', 'required');
            });
            // Remove required from enseignant fields
            enseignantFields.querySelectorAll('input').forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            eleveFields.classList.remove('active');
            enseignantFields.classList.add('active');
            
            // Make enseignant fields required
            enseignantFields.querySelectorAll('input').forEach(input => {
                input.setAttribute('required', 'required');
            });
            // Remove required from eleve fields
            eleveFields.querySelectorAll('input').forEach(input => {
                input.removeAttribute('required');
            });
        }
    }
</script>

</body>
</html>