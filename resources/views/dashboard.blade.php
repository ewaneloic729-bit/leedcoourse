<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Vous etes connecte. Choisissez une action pour continuer.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800">Bienvenue sur votre espace</h3>
                <p class="text-sm text-gray-600 mt-2">Votre role determine les actions disponibles dans votre tableau de bord.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-blue-700 font-semibold mb-2">Profil</h4>
                    <p class="text-sm text-gray-600">Mettez a jour vos informations et votre securite.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-blue-700 font-semibold mb-2">Activite recente</h4>
                    <p class="text-sm text-gray-600">Consultez vos dernieres actions sur la plateforme.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
