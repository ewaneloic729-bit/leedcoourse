<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>LEEDCOURSE</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: url('{{ asset("images/image.png") }}') no-repeat center center fixed;
            background-size: cover;
        }

        /* Overlay pour que le texte ressorte */
        .overlay {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .btn-primary {
            background-color: #16a34a; /* Vert principal */
            color: white;
        }

        .btn-primary:hover {
            background-color: #15803d;
        }

        .modal-bg {
            background-color: rgba(0,0,0,0.6);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Overlay principal -->
    <div class="overlay min-h-screen flex flex-col justify-center items-center text-center p-6">
        <h1 class="text-5xl md:text-6xl font-bold text-white mb-4">LEEDCOURSE</h1>
        <p class="text-white text-lg md:text-2xl mb-6">Apprenez en ligne vos compétences préférées</p>

        <!-- Boutons -->
        <div class="space-x-4">
    <a href="{{ route('login') }}">Se connecter</a>
    <a href="{{ route('register') }}">S'inscrire</a>
</div>

    </div>

    <!-- Liste des cours disponibles -->
    <div class="bg-gray-100 py-12">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-8">Nos Cours Disponibles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="font-bold text-xl mb-2">Informatique</h3>
                    <p>Apprenez le développement web, la programmation et bien plus.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="font-bold text-xl mb-2">Cybersécurité</h3>
                    <p>Protégez les systèmes et données avec nos cours avancés.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <h3 class="font-bold text-xl mb-2">Réseau</h3>
                    <p>Maîtrisez les réseaux et l'administration système.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale Connexion -->
    <div id="loginModal" class="fixed inset-0 hidden justify-center items-center modal-bg z-50">
        <div class="bg-white rounded-lg w-11/12 md:w-1/3 p-6 relative">
            <button onclick="closeModal('loginModal')" class="absolute top-2 right-3 text-gray-600 text-xl font-bold">&times;</button>
            <h2 class="text-2xl font-bold mb-4">Connexion</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded">
                <input type="password" name="password" placeholder="Mot de passe" required class="w-full mb-3 p-2 border rounded">
                <button type="submit" class="btn-primary w-full py-2 rounded">Se connecter</button>
            </form>
        </div>
    </div>

    <!-- Modale Inscription -->
    <div id="registerModal" class="fixed inset-0 hidden justify-center items-center modal-bg z-50">
        <div class="bg-white rounded-lg w-11/12 md:w-1/3 p-6 relative">
            <button onclick="closeModal('registerModal')" class="absolute top-2 right-3 text-gray-600 text-xl font-bold">&times;</button>
            <h2 class="text-2xl font-bold mb-4">Inscription</h2>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="text" name="name" placeholder="Nom" required class="w-full mb-3 p-2 border rounded">
                <input type="email" name="email" placeholder="Email" required class="w-full mb-3 p-2 border rounded">
                <input type="password" name="password" placeholder="Mot de passe" required class="w-full mb-3 p-2 border rounded">
                <input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required class="w-full mb-3 p-2 border rounded">
                <button type="submit" class="btn-primary w-full py-2 rounded">S'inscrire</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('flex');
            document.getElementById(id).classList.add('hidden');
        }
    </script>

</body>
</html>
