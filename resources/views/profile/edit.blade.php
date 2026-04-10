<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil | LEEDCOURSE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/platform-pro.css') }}">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, rgba(2, 6, 23, 0.92), rgba(22, 101, 52, 0.74)), url('{{ asset("images/image.png") }}') center/cover no-repeat fixed;
        }
        .glass { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); backdrop-filter: blur(8px); }
        .panel { background: rgba(255,255,255,0.95); border: 1px solid rgba(15,23,42,0.08); }
    </style>
</head>
<body class="min-h-screen text-slate-100">

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
        <header class="glass rounded-2xl p-5 sm:p-6 shadow-2xl">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs uppercase tracking-[0.24em] text-emerald-200 font-semibold">LEEDCOURSE</p>
                    <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">Mon profil</h1>
                    <p class="mt-2 text-sm text-slate-200">Mettez a jour vos informations personnelles et votre mot de passe.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white/10 border border-white/30 hover:bg-white/20 text-white text-sm font-semibold">Retour dashboard</a>
            </div>
        </header>

        @if(session('success_profile'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_profile') }}</div>
        @endif
        @if(session('success_password'))
            <div class="panel rounded-xl p-4 text-green-700 border border-green-200">{{ session('success_password') }}</div>
        @endif

        @if($errors->any())
            <div class="panel rounded-xl p-4 text-red-700 border border-red-200">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h2 class="text-lg font-bold">Informations du compte</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                @if(\Illuminate\Support\Facades\Schema::hasColumn('users', 'whatsapp_phone'))
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Numero WhatsApp</label>
                        <input type="text" name="whatsapp_phone" value="{{ old('whatsapp_phone', $user->whatsapp_phone) }}" placeholder="Ex: 2376XXXXXXXX (indicatif inclus)" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                        <p class="text-xs text-slate-500 mt-1">Utilise pour recuperation du mot de passe via WhatsApp.</p>
                    </div>
                @endif

                @if($user->isEleve())
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Classe</label>
                        <input type="text" name="classe" value="{{ old('classe', optional($eleve)->classe) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Date de naissance</label>
                        <input type="date" name="date_naissance" value="{{ old('date_naissance', optional(optional($eleve)->date_naissance)->format('Y-m-d')) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                @endif

                @if($user->isEnseignant())
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nom</label>
                        <input type="text" name="nom" value="{{ old('nom', optional($enseignant)->nom) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Prenom</label>
                        <input type="text" name="prenom" value="{{ old('prenom', optional($enseignant)->prenom) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Specialite</label>
                        <input type="text" name="specialite" value="{{ old('specialite', optional($enseignant)->specialite) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Diplome</label>
                        <input type="text" name="diplome" value="{{ old('diplome', optional($enseignant)->diplome) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Annees d'experience</label>
                        <input type="number" min="0" max="80" name="annees_experience" value="{{ old('annees_experience', optional($enseignant)->annees_experience) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                @endif

                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Enregistrer les informations</button>
                </div>
            </form>
        </section>

        <section class="panel rounded-2xl p-5 sm:p-6 shadow-lg text-slate-800">
            <h2 class="text-lg font-bold">Changer le mot de passe</h2>
            <form method="POST" action="{{ route('profile.password.update') }}" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Mot de passe actuel</label>
                    <input type="password" name="current_password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div></div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nouveau mot de passe</label>
                    <input type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Confirmation</label>
                    <input type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" required>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-lg bg-slate-800 text-white text-sm font-semibold hover:bg-slate-900">Mettre a jour le mot de passe</button>
                </div>
            </form>
        </section>
    </div>
    @include('partials.password-toggle')
    <script src="{{ asset('js/button-sounds.js') }}" defer></script>
    @include('partials.language-switcher')
</body>
</html>
