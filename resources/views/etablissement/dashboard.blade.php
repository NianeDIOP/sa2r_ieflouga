@extends('layouts.etablissement')

@section('title', 'Dashboard Établissement - SA2R')

@section('content')
<div class="space-y-4">
    
    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
        
        <!-- Carte 1 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-0.5">RAPPORTS</p>
                    <p class="text-2xl font-bold text-gray-900">0</p>
                    <p class="text-xs text-gray-600 mt-0.5">Cette année</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Carte 2 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-0.5">ÉLÈVES</p>
                    <p class="text-2xl font-bold text-gray-900">-</p>
                    <p class="text-xs text-gray-600 mt-0.5">Effectif total</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Carte 3 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-0.5">ENSEIGNANTS</p>
                    <p class="text-2xl font-bold text-gray-900">-</p>
                    <p class="text-xs text-gray-600 mt-0.5">Personnel</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Carte 4 -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-0.5">ANNÉE ACTIVE</p>
                    <p class="text-xl font-bold text-gray-900">{{ $annee_active }}</p>
                    <p class="text-xs text-gray-600 mt-0.5">En cours</p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded flex items-center justify-center">
                    <i class="fas fa-calendar-check text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Actions rapides -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h2 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
            <i class="fas fa-bolt text-yellow-500"></i>
            Actions Rapides
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            
            <a href="#" class="flex items-center gap-3 p-3 border border-gray-200 rounded hover:border-blue-500 hover:bg-blue-50 transition-all group">
                <div class="w-10 h-10 bg-blue-100 rounded flex items-center justify-center group-hover:bg-blue-500 transition-colors flex-shrink-0">
                    <i class="fas fa-file-alt text-blue-600 text-lg group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Nouveau Rapport</h3>
                    <p class="text-xs text-gray-600">Créer un rapport mensuel</p>
                </div>
            </a>

            <a href="#" class="flex items-center gap-3 p-3 border border-gray-200 rounded hover:border-green-500 hover:bg-green-50 transition-all group">
                <div class="w-10 h-10 bg-green-100 rounded flex items-center justify-center group-hover:bg-green-500 transition-colors flex-shrink-0">
                    <i class="fas fa-history text-green-600 text-lg group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Voir l'Historique</h3>
                    <p class="text-xs text-gray-600">Consulter les rapports</p>
                </div>
            </a>

            <a href="#" class="flex items-center gap-3 p-3 border border-gray-200 rounded hover:border-purple-500 hover:bg-purple-50 transition-all group">
                <div class="w-10 h-10 bg-purple-100 rounded flex items-center justify-center group-hover:bg-purple-500 transition-colors flex-shrink-0">
                    <i class="fas fa-user text-purple-600 text-lg group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Mon Profil</h3>
                    <p class="text-xs text-gray-600">Gérer mon compte</p>
                </div>
            </a>

        </div>
    </div>

    <!-- Message d'accueil -->
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-white"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 mb-1 text-sm">Bienvenue sur votre espace établissement</h3>
                <p class="text-xs text-gray-700 leading-relaxed">
                    Utilisez cet espace pour soumettre vos rapports mensuels, consulter l'historique de vos soumissions 
                    et gérer les informations de votre établissement. Pour toute question, n'hésitez pas à contacter l'IEF de Louga.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
