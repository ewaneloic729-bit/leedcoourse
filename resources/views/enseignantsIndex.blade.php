<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Enseignants</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </head>
<body>

<div class="dashboard-container">
    <header class="header">
        <div>
            <h1>Enseignants</h1>
            <p class="subtitle">Gérez la liste de tous les enseignants</p>
        </div>
        <button class="btn-add">
            <a href="{{ route('enseignants.create') }}" class="text-white">
                <span>+</span> Ajouter un enseignant
            </a>
        </button>
    </header>

    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher par nom, matière ou email...">
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Matière</th>
                        <th>Étudiants</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="teacherTableBody">
                    </tbody>
            </table>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-label">Total Enseignants</span>
            <p class="stat-value" id="totalTeachers">0</p>
        </div>
        <div class="stat-card">
            <span class="stat-label">Enseignants Actifs</span>
            <p class="stat-value text-green" id="activeTeachers">0</p>
        </div>
        <div class="stat-card">
            <span class="stat-label">Total Étudiants</span>
            <p class="stat-value text-blue" id="totalStudents">0</p>
        </div>
    </div>
</div>

<script src="{{ asset('js/script.js') }}"></script>
</body>
</html>