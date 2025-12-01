@extends('layouts.etablissement')

@section('title', 'Mon Profil')

@section('content')
<div class="space-y-4">
    
    <!-- En-tête -->
    <div class="flex items-center gap-2 pb-3 border-b border-gray-200">
        <i class="fas fa-user text-gray-700 text-sm"></i>
        <h1 class="text-xl font-bold text-gray-900">Mon Profil</h1>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-3 py-2 rounded-lg flex items-center gap-2 text-sm">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm">
        <div class="flex items-start gap-2">
            <i class="fas fa-exclamation-circle mt-0.5"></i>
            <div class="flex-1">
                <p class="font-semibold mb-1">Erreurs :</p>
                <ul class="list-disc list-inside space-y-0.5 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
        
        <!-- Colonne Gauche - Informations -->
        <div class="lg:col-span-3 space-y-4">
            
            <!-- Informations Établissement -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b">
                    <div class="w-8 h-8 bg-[#002147] rounded-lg flex items-center justify-center">
                        <i class="fas fa-school text-white text-xs"></i>
                    </div>
                    <h2 class="text-base font-bold text-gray-900">Informations Établissement</h2>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Code</label>
                        <p class="text-sm font-bold text-[#002147] mt-0.5">{{ $etablissement->code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Nom</label>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $etablissement->etablissement ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Arrondissement</label>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $etablissement->arrondissement ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Commune</label>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $etablissement->commune ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Zone</label>
                        <p class="text-sm text-gray-700 mt-0.5">{{ $etablissement->zone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Statut</label>
                        <p class="text-sm text-gray-700 mt-0.5">
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs font-semibold">
                                {{ $etablissement->statut ?? 'N/A' }}
                            </span>
                        </p>
                    </div>
                </div>
                
                <div class="mt-3 pt-3 border-t bg-blue-50 rounded p-2">
                    <p class="text-xs text-blue-700 flex items-center gap-1.5">
                        <i class="fas fa-info-circle"></i>
                        <span>Ces informations sont en lecture seule.</span>
                    </p>
                </div>
            </div>

            <!-- Informations Directeur -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-tie text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-base font-bold text-gray-900">Informations Directeur</h2>
                        @if($anneeScolaireActive)
                        <p class="text-xs text-gray-500">Année {{ $anneeScolaireActive->annee }}</p>
                        @endif
                    </div>
                </div>
                
                @if($infoDirecteur)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div class="col-span-2">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Nom Complet</label>
                        <p class="text-sm font-semibold text-gray-900 mt-0.5">{{ $infoDirecteur->directeur_nom ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Contact 1</label>
                        <p class="text-sm text-gray-700 mt-0.5">
                            <i class="fas fa-phone text-emerald-600 mr-1 text-xs"></i>{{ $infoDirecteur->directeur_contact_1 ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Contact 2</label>
                        <p class="text-sm text-gray-700 mt-0.5">
                            <i class="fas fa-phone text-emerald-600 mr-1 text-xs"></i>{{ $infoDirecteur->directeur_contact_2 ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Email</label>
                        <p class="text-sm text-gray-700 mt-0.5">
                            <i class="fas fa-envelope text-emerald-600 mr-1 text-xs"></i>{{ $infoDirecteur->directeur_email ?? 'N/A' }}
                        </p>
                    </div>
                    @if($infoDirecteur->distance_siege)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Distance (km)</label>
                        <p class="text-sm text-gray-700 mt-0.5">
                            <i class="fas fa-map-marker-alt text-emerald-600 mr-1 text-xs"></i>{{ number_format($infoDirecteur->distance_siege, 1) }} km
                        </p>
                    </div>
                    @endif
                </div>
                @else
                <div class="bg-gray-50 rounded p-3 text-center">
                    <i class="fas fa-info-circle text-gray-400 text-xl mb-1"></i>
                    <p class="text-xs text-gray-600">Aucune information directeur. Remplissez le rapport de rentrée.</p>
                </div>
                @endif
            </div>

        </div>

        <!-- Colonne Droite - Sécurité -->
        <div class="space-y-4">
            
            <!-- Connexion -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-sign-in-alt text-gray-600 text-xs"></i>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900">Connexion</h2>
                </div>
                
                <div class="space-y-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 uppercase">Identifiant</label>
                        <div class="mt-1 flex items-center gap-1.5">
                            <p class="text-xs font-mono bg-gray-50 px-2 py-1.5 rounded border border-gray-200 flex-1">
                                {{ $user->code }}
                            </p>
                            <span class="px-1.5 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-0.5"></i>Non modifiable
                        </p>
                    </div>
                    
                    @if($user->last_login_at)
                    <div class="pt-2 border-t">
                        <label class="text-xs font-semibold text-gray-500 uppercase">Dernière connexion</label>
                        <p class="text-xs text-gray-600 mt-1">
                            <i class="fas fa-clock text-gray-400 mr-1"></i>
                            {{ $user->last_login_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Changer le mot de passe -->
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-emerald-600 text-xs"></i>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900">Mot de passe</h2>
                </div>
                
                <form action="{{ route('etablissement.profil.change-password') }}" method="POST" class="space-y-3">
                    @csrf
                    
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-gray-700 mb-1">
                            Mot de passe actuel
                        </label>
                        <input 
                            type="password" 
                            id="current_password"
                            name="current_password"
                            class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        >
                    </div>
                    
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 mb-1">
                            Nouveau mot de passe
                        </label>
                        <input 
                            type="password" 
                            id="password"
                            name="password"
                            class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        >
                        <p class="text-xs text-gray-500 mt-0.5">Min. 8 caractères</p>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 mb-1">
                            Confirmer
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                            required
                        >
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2 px-3 rounded-lg transition-colors text-xs"
                    >
                        <i class="fas fa-check mr-1"></i>Modifier
                    </button>
                </form>
                
                <div class="mt-3 pt-3 border-t bg-amber-50 rounded p-2">
                    <p class="text-xs text-amber-700 flex items-center gap-1.5">
                        <i class="fas fa-shield-alt"></i>
                        <span>Défaut : <strong>sa2r2025</strong></span>
                    </p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
