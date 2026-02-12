<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Superadmin
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Bienvenue, {{ auth()->user()->name }}. Vous avez tous les droits sur la plateforme.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-gradient-to-r from-indigo-700 to-purple-600 rounded-2xl p-6 text-white shadow">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold">Console Superadmin</h3>
                        <p class="text-indigo-100 mt-2">Gerez les acces, les utilisateurs et la plateforme.</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 bg-white/10 border border-white/30 text-sm font-semibold rounded-full">
                        Acces total
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-indigo-700 font-semibold mb-2">Gestion des utilisateurs</h3>
                    <p class="text-gray-600 text-sm">Creer, modifier et desactiver des comptes.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-indigo-700 font-semibold mb-2">Roles & permissions</h3>
                    <p class="text-gray-600 text-sm">Configurer les acces et les responsabilites.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-indigo-700 font-semibold mb-2">Supervision</h3>
                    <p class="text-gray-600 text-sm">Suivre les activites et la sante du systeme.</p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a class="inline-flex items-center px-4 py-2 bg-indigo-700 text-white text-sm font-semibold rounded-md hover:bg-indigo-800" href="{{ url('/') }}">
                    Retour accueil
                </a>
                <a class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-md hover:bg-indigo-100" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Se deconnecter
                </a>
            </div>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</x-app-layout>
