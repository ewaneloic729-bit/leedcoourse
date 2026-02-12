<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un cours | LEEDCOURSE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }
        input, textarea, button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
        }
        button {
            background: #2e7d32;
            color: white;
            border: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Créer un nouveau cours</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="/admin/courses" enctype="multipart/form-data">
        @csrf

        <input type="text" name="title" placeholder="Titre du cours" required>

        <textarea name="description" placeholder="Description du cours" required></textarea>

        <input type="text" name="category" placeholder="Catégorie (Informatique, Réseau...)" required>

        <input type="text" name="level" placeholder="Niveau (Débutant, Avancé...)">

        <input type="file" name="image">

        <button type="submit">Créer le cours</button>
    </form>
</div>

</body>
</html>
