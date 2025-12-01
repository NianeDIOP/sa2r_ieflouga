@extends('layouts.etablissement')

@section('title', 'Dashboard - SA2R')

@section('content')
<div class="space-y-4">
    
    <!-- Header avec sélecteur d'année -->
    <div class="flex items-center justify-between flex-wrap gap-3 pb-3 border-b border-gray-200">
        <div>
            <h1 class="text-xl font-bold text-gray-900 mb-0.5">{{ $etablissement->nom }}</h1>
            <p class="text-xs text-gray-600">
                <i class="fas fa-map-marker-alt mr-1"></i>
                {{ $etablissement->commune }} • {{ $etablissement->arrondissement }}
            </p>
        </div>
        
        <form method="GET" action="{{ route('etablissement.dashboard') }}" class="flex items-center gap-2">
            <select name="annee" id="annee" 
                    class="px-2 py-1.5 bg-gray-50 border border-gray-300 rounded text-xs font-semibold focus:ring-2 focus:ring-emerald-500"
                    onchange="this.form.submit()">
                @foreach($anneesDisponibles as $annee)
                    <option value="{{ $annee->annee }}" {{ $anneeSelectionnee == $annee->annee ? 'selected' : '' }}>
                        {{ $annee->annee }} {{ $annee->is_active ? '(Active)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    
    <!-- Statut Rapport -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-lg flex items-center justify-center
                    @if($stats['statut_rapport'] === 'validé') bg-green-100
                    @elseif($stats['statut_rapport'] === 'soumis') bg-purple-100
                    @elseif($stats['statut_rapport'] === 'brouillon') bg-orange-100
                    @elseif($stats['statut_rapport'] === 'rejeté') bg-red-100
                    @else bg-gray-100
                    @endif">
                    <i class="fas fa-file-alt text-2xl
                        @if($stats['statut_rapport'] === 'validé') text-green-600
                        @elseif($stats['statut_rapport'] === 'soumis') text-purple-600
                        @elseif($stats['statut_rapport'] === 'brouillon') text-orange-600
                        @elseif($stats['statut_rapport'] === 'rejeté') text-red-600
                        @else text-gray-400
                        @endif"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 font-semibold">STATUT RAPPORT {{ $anneeSelectionnee }}</p>
                    <p class="text-lg font-bold
                        @if($stats['statut_rapport'] === 'validé') text-green-600
                        @elseif($stats['statut_rapport'] === 'soumis') text-purple-600
                        @elseif($stats['statut_rapport'] === 'brouillon') text-orange-600
                        @elseif($stats['statut_rapport'] === 'rejeté') text-red-600
                        @else text-gray-400
                        @endif">
                        @if($stats['statut_rapport'] === 'aucun') Non créé
                        @elseif($stats['statut_rapport'] === 'brouillon') En cours
                        @elseif($stats['statut_rapport'] === 'soumis') En attente de validation
                        @elseif($stats['statut_rapport'] === 'validé') ✓ Validé
                        @else Rejeté
                        @endif
                    </p>
                    @if($anneeSelectionnee == $anneeScolaireActive?->annee && $anneeScolaireActive->description)
                    <p class="text-xs text-gray-600 mt-1 italic">
                        <i class="fas fa-comment-dots mr-1"></i>{{ $anneeScolaireActive->description }}
                    </p>
                    @endif
                </div>
            </div>
            @if($rapport)
            <a href="{{ route('etablissement.rapport-rentree.historique.show', $rapport->id) }}" 
               class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">
                <i class="fas fa-eye mr-1"></i>Voir
            </a>
            @endif
        </div>
    </div>

    <!-- Grille Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
        <!-- Effectifs -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-[#002147] rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-sm">Effectifs Scolaires</h3>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total élèves</span>
                    <span class="font-bold text-[#002147] text-lg">{{ number_format($stats['total_eleves']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Garçons</span>
                    <span class="font-semibold text-gray-700">{{ number_format($stats['total_garcons']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Filles</span>
                    <span class="font-semibold text-gray-700">{{ number_format($stats['total_filles']) }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Taux féminisation</span>
                        <span class="font-bold text-[#002147]">{{ number_format($stats['taux_feminisation'], 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Examens -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-sm">Examens & Résultats</h3>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Taux CFEE</span>
                    <span class="font-bold text-emerald-600 text-lg">{{ number_format($stats['taux_reussite_cfee'], 1) }}%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Admission 6ème</span>
                    <span class="font-bold text-emerald-600">{{ number_format($stats['taux_admission_6eme'], 1) }}%</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    @if($stats['taux_reussite_cfee'] >= 80)
                        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-trophy mr-1"></i>Excellent
                        </span>
                    @elseif($stats['taux_reussite_cfee'] >= 70)
                        <span class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-thumbs-up mr-1"></i>Bon
                        </span>
                    @else
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-chart-line mr-1"></i>À améliorer
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Personnel -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-[#002147] rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-sm">Personnel Enseignant</h3>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total enseignants</span>
                    <span class="font-bold text-[#002147] text-lg">{{ $stats['total_enseignants'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Classes</span>
                    <span class="font-semibold text-gray-700">{{ $stats['total_classes'] }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Ratio élèves/ens.</span>
                        <span class="font-bold text-[#002147]">{{ number_format($stats['ratio_eleves_enseignant'], 1) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Infrastructures -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-building text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-sm">Infrastructures</h3>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Salles en dur</span>
                    <span class="font-bold text-emerald-600 text-lg">{{ $stats['salles_dur'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Toilettes</span>
                    <span class="font-semibold text-gray-700">{{ $stats['toilettes_total'] }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Bon état</span>
                        <span class="font-bold text-emerald-600">{{ number_format($stats['infrastructures_bon_etat'], 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matériel -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-[#002147] rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-sm">Matériel Pédagogique</h3>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Manuels élèves</span>
                    <span class="font-bold text-[#002147] text-lg">{{ number_format($stats['manuels_total']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Dictionnaires</span>
                    <span class="font-semibold text-gray-700">{{ $stats['dictionnaires_total'] }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Ordinateurs</span>
                        <span class="font-bold text-[#002147]">{{ $stats['ordinateurs_total'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finances -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-coins text-white text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-900 text-sm">Finances & Communauté</h3>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Budget total</span>
                    <span class="font-bold text-emerald-600 text-lg">{{ number_format($stats['budget_total'], 0, ',', ' ') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Membres CGE</span>
                    <span class="font-semibold text-gray-700">{{ $stats['cge_membres'] }}</span>
                </div>
                <div class="pt-2 border-t border-gray-100">
                    @if($stats['cge_membres'] >= 15)
                        <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>Actif
                        </span>
                    @else
                        <span class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-info-circle mr-1"></i>À renforcer
                        </span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Actions Rapides -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h2 class="text-sm font-bold text-gray-900 mb-3">Actions Rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            
            <a href="{{ route('etablissement.rapport-rentree.index') }}" 
               class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition group">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition">
                    <i class="fas fa-edit text-gray-600 group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Rapport</h3>
                    <p class="text-xs text-gray-500">Remplir/Modifier</p>
                </div>
            </a>

            <a href="{{ route('etablissement.rapport-rentree.historique.index') }}" 
               class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition group">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition">
                    <i class="fas fa-history text-gray-600 group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Historique</h3>
                    <p class="text-xs text-gray-500">Consulter</p>
                </div>
            </a>

            @if($rapport)
            <a href="{{ route('etablissement.rapport-rentree.historique.excel', $rapport->id) }}" 
               class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition group">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition">
                    <i class="fas fa-file-excel text-gray-600 group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Excel</h3>
                    <p class="text-xs text-gray-500">Télécharger</p>
                </div>
            </a>
            @endif

            <a href="#" 
               class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg hover:border-emerald-500 hover:bg-emerald-50 transition group">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition">
                    <i class="fas fa-user text-gray-600 group-hover:text-white"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 text-sm">Profil</h3>
                    <p class="text-xs text-gray-500">Mon compte</p>
                </div>
            </a>

        </div>
    </div>

    <!-- Messages -->
    @if($stats['statut_rapport'] === 'validé')
    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center gap-2 text-green-800">
            <i class="fas fa-check-circle text-lg"></i>
            <p class="text-sm">
                <strong>Rapport validé !</strong> Votre rapport {{ $anneeSelectionnee }} a été approuvé par l'IEF.
            </p>
        </div>
    </div>
    @elseif($stats['statut_rapport'] === 'aucun')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center gap-2 text-blue-800">
            <i class="fas fa-info-circle text-lg"></i>
            <p class="text-sm">
                <strong>Bienvenue !</strong> Commencez par remplir votre rapport de rentrée {{ $anneeSelectionnee }}.
            </p>
        </div>
    </div>
    @endif

</div>
@endsection
