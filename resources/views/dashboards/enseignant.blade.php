<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard Enseignant
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Bonjour, {{ auth()->user()->name }}. Gérez vos cours et vos élèves.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-gradient-to-r from-blue-700 to-sky-600 rounded-2xl p-6 text-white shadow">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold">Votre espace enseignant</h3>
                        <p class="text-blue-100 mt-2">Créez, mettez à jour et suivez vos cours facilement.</p>
                    </div>
                    <a class="inline-flex items-center px-4 py-2 bg-white/10 border border-white/30 text-white text-sm font-semibold rounded-md hover:bg-white/20"
                       href="{{ url('/admin/courses/create') }}">
                        Créer un cours
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-blue-700 font-semibold mb-2">Mes cours</div>
                    <p class="text-gray-600 text-sm">Créez et mettez à jour vos cours en toute simplicité.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-blue-700 font-semibold mb-2">Suivi des élèves</div>
                    <p class="text-gray-600 text-sm">Visualisez la progression de vos apprenants.</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-blue-700 font-semibold mb-2">Ressources</div>
                    <p class="text-gray-600 text-sm">Partagez des supports et des exercices.</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a class="inline-flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-semibold rounded-md hover:bg-blue-800"
                   href="{{ url('/admin/courses/create') }}">
                    Créer un cours
                </a>
                <a class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-md hover:bg-blue-100"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Se déconnecter
                </a>
            </div>

            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</x-app-layout>
