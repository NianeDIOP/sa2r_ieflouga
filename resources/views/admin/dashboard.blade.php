@extends('layouts.admin')

@section('title', 'Dashboard Admin - SA2R')

@section('content')
<div class="space-y-4" x-data="dashboardZones()">
    
    <!-- En-tête compact -->
    <div class="flex items-center justify-between pb-3 border-b border-gray-200">
        <div>
            <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i class="fas fa-map-marked-alt text-[#002147] text-lg"></i>
                Carte Interactive des Zones
            </h1>
            <p class="text-xs text-gray-600 mt-0.5">
                Année: <span class="font-semibold">{{ $anneeScolaireActive->annee }}</span> • 
                {{ $stats['total_zones'] }} zones • 
                {{ $stats['total_etablissements'] }} établissements
            </p>
        </div>
        
        <a href="{{ route('admin.suivi-rapports.index') }}" class="px-3 py-2 bg-[#002147] text-white text-xs font-semibold rounded-lg hover:bg-[#003366] transition">
            <i class="fas fa-list mr-1"></i>
            Voir tous les rapports
        </a>
    </div>

    <!-- Statistiques globales avec mini graphiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <!-- Carte 1: Taux global avec graphique circulaire -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg p-3 text-white shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-[10px] opacity-90 mb-0.5">Taux de soumission global</p>
                    <p class="text-2xl font-bold">
                        <span x-text="animatedGlobalRate"></span><span class="text-lg">%</span>
                    </p>
                    <p class="text-[10px] opacity-75 mt-0.5">{{ $stats['total_rapports_annee'] }} rapports</p>
                </div>
                <div class="relative w-12 h-12">
                    <svg class="w-12 h-12 transform -rotate-90">
                        <circle cx="24" cy="24" r="20" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="none"/>
                        <circle cx="24" cy="24" r="20" stroke="white" stroke-width="3" fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 20 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 20 * (1 - $stats['taux_soumission_global'] / 100) }}"
                                stroke-linecap="round"/>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-check-circle text-lg opacity-90"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte 2: Zones performantes -->
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-[10px] text-gray-600 mb-0.5">Zones performantes</p>
                    <p class="text-2xl font-bold text-emerald-600" x-text="zonesPerformantes"></p>
                    <p class="text-[10px] text-gray-500 mt-0.5">≥ 80% de soumission</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trophy text-emerald-600 text-base"></i>
                </div>
            </div>
        </div>

        <!-- Carte 3: Zones moyennes -->
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-[10px] text-gray-600 mb-0.5">Zones moyennes</p>
                    <p class="text-2xl font-bold text-amber-600" x-text="zonesMoyennes"></p>
                    <p class="text-[10px] text-gray-500 mt-0.5">50-79% de soumission</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-base"></i>
                </div>
            </div>
        </div>

        <!-- Carte 4: Zones critiques -->
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-[10px] text-gray-600 mb-0.5">Zones critiques</p>
                    <p class="text-2xl font-bold text-red-600" x-text="zonesCritiques"></p>
                    <p class="text-[10px] text-gray-500 mt-0.5">&lt; 50% de soumission</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-base"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de filtres et recherche -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex flex-col md:flex-row gap-3 items-center">
            <!-- Recherche -->
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Rechercher une zone..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
            </div>

            <!-- Filtres par taux -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-600 font-semibold">Filtrer:</span>
                <button 
                    @click="filterTaux = 'tous'"
                    :class="filterTaux === 'tous' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    class="px-3 py-2 text-xs font-semibold rounded-lg transition">
                    Tous
                </button>
                <button 
                    @click="filterTaux = 'haut'"
                    :class="filterTaux === 'haut' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'"
                    class="px-3 py-2 text-xs font-semibold rounded-lg transition">
                    <i class="fas fa-arrow-up text-[10px] mr-1"></i>
                    ≥80%
                </button>
                <button 
                    @click="filterTaux = 'moyen'"
                    :class="filterTaux === 'moyen' ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-700 hover:bg-amber-200'"
                    class="px-3 py-2 text-xs font-semibold rounded-lg transition">
                    <i class="fas fa-minus text-[10px] mr-1"></i>
                    50-79%
                </button>
                <button 
                    @click="filterTaux = 'bas'"
                    :class="filterTaux === 'bas' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200'"
                    class="px-3 py-2 text-xs font-semibold rounded-lg transition">
                    <i class="fas fa-arrow-down text-[10px] mr-1"></i>
                    &lt;50%
                </button>
            </div>

            <!-- Tri -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-600 font-semibold">Trier:</span>
                <select 
                    x-model="sortBy"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="nom">Nom (A-Z)</option>
                    <option value="pourcentage-desc">% Décroissant</option>
                    <option value="pourcentage-asc">% Croissant</option>
                    <option value="etablissements">Nb établissements</option>
                </select>
            </div>

            <!-- Compteur -->
            <div class="text-xs text-gray-600">
                <span class="font-bold" x-text="filteredZones.length"></span> / <span x-text="allZones.length"></span> zones
            </div>
        </div>
    </div>

    <!-- Liste organisée des zones en grille compacte -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <template x-for="zone in filteredZones" :key="zone.nom">
            <button 
                @click="openZoneModal(zone.nom)"
                class="flex flex-col items-center gap-1.5 p-2 transition-all duration-200 hover:bg-gray-50 rounded-lg group"
                :class="hoveredZone === zone.nom ? 'bg-gray-50' : ''"
                @mouseenter="hoveredZone = zone.nom"
                @mouseleave="hoveredZone = null"
            >
                <!-- Cercle avec remplissage progressif -->
                <div class="relative flex-shrink-0">
                    <!-- Conteneur du cercle avec overflow hidden -->
                    <div class="w-14 h-14 rounded-full overflow-hidden relative border-2 border-gray-200 shadow-md">
                        <!-- Fond rouge (0% - état initial) -->
                        <div class="absolute inset-0 bg-red-500"></div>
                        
                        <!-- Remplissage vert selon le pourcentage (de bas en haut) -->
                        <div class="absolute bottom-0 left-0 right-0 bg-emerald-500 transition-all duration-500"
                             :style="`height: ${zone.pourcentage}%`">
                        </div>
                        
                        <!-- Contenu au-dessus : pourcentage -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center z-10">
                            <span class="text-sm font-bold text-white drop-shadow" x-text="zone.pourcentage"></span>
                            <span class="text-[7px] text-white/90 drop-shadow">%</span>
                        </div>
                    </div>
                    
                    <!-- Indicateur d'état -->
                    <template x-if="zone.pourcentage >= 100">
                        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                    </template>
                    <template x-if="zone.pourcentage >= 80 && zone.pourcentage < 100">
                        <span class="absolute -top-1 -right-1 bg-emerald-100 rounded-full p-0.5">
                            <i class="fas fa-check text-emerald-600 text-[7px]"></i>
                        </span>
                    </template>
                    <template x-if="zone.pourcentage < 50">
                        <span class="absolute -top-1 -right-1 bg-red-100 rounded-full p-0.5">
                            <i class="fas fa-exclamation text-red-600 text-[7px]"></i>
                        </span>
                    </template>
                    
                    <!-- Tooltip au survol -->
                    <div x-show="hoveredZone === zone.nom" 
                         x-transition
                         class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 p-2.5 min-w-[160px] z-50 pointer-events-none">
                        <p class="text-[10px] font-bold text-gray-900 mb-1 text-center border-b border-gray-200 pb-1" x-text="zone.nom"></p>
                        <div class="space-y-0.5 text-[9px]">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-semibold text-gray-900" x-text="zone.total_etablissements"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-emerald-700">Validés:</span>
                                </div>
                                <span class="font-semibold text-emerald-700" x-text="zone.rapports_valides"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                    <span class="text-purple-700">Soumis:</span>
                                </div>
                                <span class="font-semibold text-purple-700" x-text="zone.rapports_soumis"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span class="text-amber-700">Brouillons:</span>
                                </div>
                                <span class="font-semibold text-amber-700" x-text="zone.rapports_brouillons"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    <span class="text-red-700">Rejetés:</span>
                                </div>
                                <span class="font-semibold text-red-700" x-text="zone.rapports_rejetes"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    <span class="text-gray-600">Sans rapport:</span>
                                </div>
                                <span class="font-semibold text-gray-700" x-text="zone.sans_rapport"></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informations de la zone (fond transparent) -->
                <div class="text-center w-full">
                    <h3 class="text-[11px] font-bold text-gray-700 mb-0.5 truncate group-hover:text-gray-900 leading-tight" x-text="zone.nom"></h3>
                    <div class="text-[9px] text-gray-500 leading-tight">
                        <div><span x-text="zone.total_etablissements"></span> étab.</div>
                        <div class="font-semibold"
                             :class="zone.pourcentage >= 80 ? 'text-emerald-600' : (zone.pourcentage >= 50 ? 'text-amber-600' : 'text-red-600')">
                            <span x-text="zone.rapports_soumis + zone.rapports_valides"></span>/<span x-text="zone.total_etablissements"></span>
                        </div>
                    </div>
                </div>
            </button>
            </template>
        </div>
    </div>

    <!-- Modale Niveau 1: Liste établissements de la zone -->
    <div x-show="showZoneModal" 
         x-cloak
         @keydown.escape.window="closeZoneModal()"
         class="fixed inset-0 z-50 overflow-hidden"
         style="display: none;">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" 
             @click="closeZoneModal()"></div>
        
        <!-- Modale -->
        <div class="fixed inset-y-0 right-0 max-w-2xl w-full bg-white shadow-xl transform transition-transform"
             :class="showZoneModal ? 'translate-x-0' : 'translate-x-full'">
            
            <!-- En-tête modale -->
            <div class="bg-[#002147] text-white px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-sm"></i>
                    <div>
                        <h3 class="text-sm font-bold" x-text="selectedZone"></h3>
                        <p class="text-xs opacity-90" x-text="etablissements.length + ' établissements'"></p>
                    </div>
                </div>
                <button @click="closeZoneModal()" class="text-white hover:bg-white/10 rounded p-1 transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Recherche -->
            <div class="p-3 border-b border-gray-200">
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Rechercher un établissement..."
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent">
            </div>

            <!-- Liste établissements -->
            <div class="overflow-y-auto" style="height: calc(100vh - 140px);">
                <div class="p-3 space-y-2">
                    <template x-for="etab in filteredEtablissements" :key="etab.id">
                        <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900" x-text="etab.nom"></p>
                                    <p class="text-xs text-gray-600 mt-0.5">
                                        <span class="font-mono" x-text="etab.code"></span> • 
                                        <span x-text="etab.commune"></span>
                                    </p>
                                    
                                    <!-- Statut rapport -->
                                    <div class="mt-2">
                                        <template x-if="!etab.rapport">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded">
                                                <i class="fas fa-circle text-[8px]"></i>
                                                Pas de rapport
                                            </span>
                                        </template>
                                        <template x-if="etab.rapport && etab.rapport.statut === 'brouillon'">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs rounded">
                                                <i class="fas fa-edit text-[10px]"></i>
                                                Brouillon
                                            </span>
                                        </template>
                                        <template x-if="etab.rapport && etab.rapport.statut === 'soumis'">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">
                                                <i class="fas fa-paper-plane text-[10px]"></i>
                                                Soumis
                                            </span>
                                        </template>
                                        <template x-if="etab.rapport && etab.rapport.statut === 'validé'">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded">
                                                <i class="fas fa-check-circle text-[10px]"></i>
                                                Validé
                                            </span>
                                        </template>
                                        <template x-if="etab.rapport && etab.rapport.statut === 'rejeté'">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded">
                                                <i class="fas fa-times-circle text-[10px]"></i>
                                                Rejeté
                                            </span>
                                        </template>
                                    </div>
                                </div>
                                
                                <div class="ml-3 space-y-1">
                                    <button @click="openEtablissementModal(etab)" 
                                            class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition">
                                        <i class="fas fa-eye mr-1"></i>
                                        Détails
                                    </button>
                                    <template x-if="etab.rapport">
                                        <a :href="'/admin/suivi-rapports/' + etab.rapport.id" 
                                           class="block px-3 py-1.5 bg-[#002147] text-white text-xs font-semibold rounded hover:bg-[#003366] transition text-center">
                                            <i class="fas fa-file-alt mr-1"></i>
                                            Rapport
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Modale Niveau 2: Détails établissement -->
    <div x-show="showEtablissementModal" 
         x-cloak
         @keydown.escape.window="closeEtablissementModal()"
         class="fixed inset-0 z-[60] overflow-hidden"
         style="display: none;">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity" 
             @click="closeEtablissementModal()"></div>
        
        <!-- Modale -->
        <div class="fixed inset-y-0 right-0 max-w-lg w-full bg-white shadow-2xl transform transition-transform"
             :class="showEtablissementModal ? 'translate-x-0' : 'translate-x-full'">
            
            <template x-if="selectedEtablissement">
                <div>
                    <!-- En-tête -->
                    <div class="bg-gradient-to-r from-[#002147] to-[#003366] text-white px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-school text-sm"></i>
                            <div>
                                <h3 class="text-sm font-bold" x-text="selectedEtablissement.nom"></h3>
                                <p class="text-xs opacity-90" x-text="'Code: ' + selectedEtablissement.code"></p>
                            </div>
                        </div>
                        <button @click="closeEtablissementModal()" class="text-white hover:bg-white/10 rounded p-1 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Contenu -->
                    <div class="overflow-y-auto p-4 space-y-3" style="height: calc(100vh - 60px);">
                        <!-- Infos générales -->
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2">
                            <h4 class="text-xs font-bold text-gray-700 uppercase">Informations Générales</h4>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <span class="text-gray-600">Commune:</span>
                                    <p class="font-semibold text-gray-900" x-text="selectedEtablissement.commune"></p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Zone:</span>
                                    <p class="font-semibold text-gray-900" x-text="selectedZone"></p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Arrondissement:</span>
                                    <p class="font-semibold text-gray-900" x-text="selectedEtablissement.arrondissement"></p>
                                </div>
                                <div>
                                    <span class="text-gray-600">Statut:</span>
                                    <p class="font-semibold text-gray-900" x-text="selectedEtablissement.statut"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Statut rapport -->
                        <div class="bg-white rounded-lg border-2 border-gray-200 p-3">
                            <h4 class="text-xs font-bold text-gray-700 uppercase mb-2">Rapport {{ $anneeScolaireActive->annee }}</h4>
                            <template x-if="!selectedEtablissement.rapport">
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox text-gray-300 text-3xl mb-2"></i>
                                    <p class="text-xs text-gray-600">Aucun rapport pour cette année</p>
                                </div>
                            </template>
                            <template x-if="selectedEtablissement.rapport">
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Statut:</span>
                                        <span :class="{
                                            'bg-amber-100 text-amber-700': selectedEtablissement.rapport.statut === 'brouillon',
                                            'bg-purple-100 text-purple-700': selectedEtablissement.rapport.statut === 'soumis',
                                            'bg-emerald-100 text-emerald-700': selectedEtablissement.rapport.statut === 'validé',
                                            'bg-red-100 text-red-700': selectedEtablissement.rapport.statut === 'rejeté'
                                        }" class="px-2 py-1 rounded font-semibold" x-text="selectedEtablissement.rapport.statut"></span>
                                    </div>
                                    <div x-show="selectedEtablissement.rapport.date_soumission" class="flex justify-between">
                                        <span class="text-gray-600">Date soumission:</span>
                                        <span class="font-semibold" x-text="selectedEtablissement.rapport.date_soumission"></span>
                                    </div>
                                    <div x-show="selectedEtablissement.rapport.date_validation" class="flex justify-between">
                                        <span class="text-gray-600">Date validation:</span>
                                        <span class="font-semibold" x-text="selectedEtablissement.rapport.date_validation"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Actions -->
                        <template x-if="selectedEtablissement.rapport">
                            <a :href="'/admin/suivi-rapports/' + selectedEtablissement.rapport.id" 
                               class="block w-full px-4 py-3 bg-[#002147] text-white text-sm font-semibold rounded-lg hover:bg-[#003366] transition text-center">
                                <i class="fas fa-file-alt mr-2"></i>
                                Voir le rapport complet
                            </a>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
function dashboardZones() {
    return {
        // État des zones
        allZones: @json($zonesData),
        hoveredZone: null,
        searchQuery: '',
        filterTaux: 'tous',
        sortBy: 'nom',
        
        // Animation du taux global
        animatedGlobalRate: 0,
        
        // Modales
        showZoneModal: false,
        showEtablissementModal: false,
        selectedZone: '',
        etablissements: [],
        selectedEtablissement: null,
        
        init() {
            // Animation du compteur au chargement
            this.animateCounter();
        },
        
        animateCounter() {
            const target = {{ $stats['taux_soumission_global'] }};
            const duration = 1500;
            const steps = 60;
            const increment = target / steps;
            let current = 0;
            
            const interval = setInterval(() => {
                current += increment;
                if (current >= target) {
                    this.animatedGlobalRate = target;
                    clearInterval(interval);
                } else {
                    this.animatedGlobalRate = Math.round(current);
                }
            }, duration / steps);
        },
        
        // Statistiques calculées
        get zonesPerformantes() {
            return this.allZones.filter(z => z.pourcentage >= 80).length;
        },
        
        get zonesMoyennes() {
            return this.allZones.filter(z => z.pourcentage >= 50 && z.pourcentage < 80).length;
        },
        
        get zonesCritiques() {
            return this.allZones.filter(z => z.pourcentage < 50).length;
        },
        
        // Filtrage et tri
        get filteredZones() {
            let zones = [...this.allZones];
            
            // Filtre par recherche
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                zones = zones.filter(z => z.nom.toLowerCase().includes(query));
            }
            
            // Filtre par taux
            if (this.filterTaux === 'haut') {
                zones = zones.filter(z => z.pourcentage >= 80);
            } else if (this.filterTaux === 'moyen') {
                zones = zones.filter(z => z.pourcentage >= 50 && z.pourcentage < 80);
            } else if (this.filterTaux === 'bas') {
                zones = zones.filter(z => z.pourcentage < 50);
            }
            
            // Tri
            if (this.sortBy === 'nom') {
                zones.sort((a, b) => a.nom.localeCompare(b.nom));
            } else if (this.sortBy === 'pourcentage-desc') {
                zones.sort((a, b) => b.pourcentage - a.pourcentage);
            } else if (this.sortBy === 'pourcentage-asc') {
                zones.sort((a, b) => a.pourcentage - b.pourcentage);
            } else if (this.sortBy === 'etablissements') {
                zones.sort((a, b) => b.total_etablissements - a.total_etablissements);
            }
            
            return zones;
        },
        
        get filteredEtablissements() {
            if (!this.searchQuery) return this.etablissements;
            
            const query = this.searchQuery.toLowerCase();
            return this.etablissements.filter(etab => 
                etab.nom.toLowerCase().includes(query) ||
                etab.code.toLowerCase().includes(query) ||
                etab.commune.toLowerCase().includes(query)
            );
        },
        
        async openZoneModal(zone) {
            this.selectedZone = zone;
            this.showZoneModal = true;
            this.searchQuery = '';
            
            // Charger les établissements de la zone
            try {
                const response = await fetch(`/admin/dashboard/zone-etablissements?zone=${encodeURIComponent(zone)}&annee_scolaire={{ $anneeScolaireActive->annee }}`);
                const data = await response.json();
                this.etablissements = data.etablissements;
            } catch (error) {
                console.error('Erreur:', error);
            }
        },
        
        closeZoneModal() {
            this.showZoneModal = false;
            this.selectedZone = '';
            this.etablissements = [];
        },
        
        openEtablissementModal(etab) {
            this.selectedEtablissement = etab;
            this.showEtablissementModal = true;
        },
        
        closeEtablissementModal() {
            this.showEtablissementModal = false;
            this.selectedEtablissement = null;
        }
    }
}
</script>
@endpush
@endsection
