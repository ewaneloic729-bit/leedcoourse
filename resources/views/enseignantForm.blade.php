<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Créer un Enseignant</title>
    <style>
        body { background-color: #f8fafc; padding: 40px 0; }
        .form-card { 
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-card">
                <h1 class="mb-4 text-slate-900 h3">Créer un Enseignant</h1>
                
                <form action="{{ route('enseignants.store') }}" method="POST" class="row g-3">
                    @csrf
                    
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Ex: Dupont" required>
                    </div>

                    <div class="col-md-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Ex: Marie" required>
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label">Adresse Email Professionnelle</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="marie.dupont@ecole.fr" required>
                    </div>

                    <div class="col-md-8">
                        <label for="subject" class="form-label">Matière</label>
                        <select id="subject" name="subject" class="form-select">
                            <option selected>Choisir une discipline...</option>
                            <option value="maths">Mathématiques</option>
                            <option value="physique">Physique-Chimie</option>
                            <option value="francais">Français</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Statut</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="status" name="active" checked>
                            <label class="form-check-label" for="status">
                                Enseignant actif
                            </label>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            Enregistrer l'enseignant
                        </button>
                        <a href="{{ route('enseignants.index') }}" class="btn btn-light border px-4 py-2 ms-2">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>