<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Étudiant | LEEDCOURSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: #1f7a4f;
            color: #fff;
            padding: 30px 20px;
            position: relative;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
        }

        /* LOGOUT */
        .logout-btn {
            background: #c0392b;
            border: none;
            width: 100%;
            padding: 12px;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background: #a93226;
        }

        .user-info {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 14px;
        }

        /* CONTENT */
        .content {
            flex: 1;
            padding: 40px;
        }

        .welcome {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        }

        .welcome h1 {
            color: #1f7a4f;
            margin: 0;
        }

        /* STATS */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        }

        .card h3 {
            margin: 0;
            font-size: 16px;
            color: #666;
        }

        .card p {
            font-size: 32px;
            margin: 10px 0 0;
            color: #1f7a4f;
            font-weight: 700;
        }

        /* PROGRESS */
        .progress-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: conic-gradient(#1f7a4f 70%, #e0e0e0 0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
        }

        .progress-circle span {
            font-size: 22px;
            font-weight: 700;
            color: #1f7a4f;
            background: #fff;
            border-radius: 50%;
            padding: 20px;
        }

        /* ANNOUNCES */
        .announcements {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        }

        .announcements ul {
            padding-left: 20px;
        }

        .announcements li {
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>LEEDCOURSE</h2>

        <a href="#">📚 Mes cours</a>
        <a href="#">📝 Devoirs</a>
        <a href="#">📊 Notes</a>
        <a href="#">📢 Annonces</a>
        <a href="#">⚙️ Paramètres</a>

        <!-- BOUTON DÉCONNEXION -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                🚪 Déconnexion
            </button>
        </form>

        <div class="user-info">
            Connecté en tant que :<br>
            <strong>{{ Auth::user()->name }}</strong>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="welcome">
            <h1>Bienvenue, {{ Auth::user()->name }} 👋</h1>
            <p>Voici votre tableau de bord étudiant sur LEEDCOURSE.</p>
        </div>

        <div class="stats">
            <div class="card">
                <h3>Cours suivis</h3>
                <p>5</p>
            </div>

            <div class="card">
                <h3>Devoirs à rendre</h3>
                <p>2</p>
            </div>

            <div class="card">
                <h3>Progression globale</h3>
                <div class="progress-circle">
                    <span>70%</span>
                </div>
            </div>

            <div class="card">
                <h3>Moyenne générale</h3>
                <p>14.5</p>
            </div>
        </div>

        <div class="announcements">
            <h3>📢 Annonces importantes</h3>
            <ul>
                <li>Nouveau cours disponible en cybersécurité</li>
                <li>Date limite devoir Réseaux : 25 juin</li>
                <li>Maintenance de la plateforme dimanche</li>
            </ul>
        </div>

    </div>
</div>

</body>
</html>
