@extends('layouts.admin')

@section('title', 'Contrôle & Validation des Données')

@section('content')
<div x-data="controleQualite()" class="p-4">
    {{-- Header compact --}}
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-shield-check text-emerald-600 text-sm"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-gray-800">Contrôle & Validation</h1>
                <p class="text-[10px] text-gray-600">{{ $rapportsAvecScores->count() }} rapports validés</p>
            </div>
        </div>

        {{-- Sélecteur année scolaire --}}
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-700 font-medium">Année:</label>
            <select onchange="window.location.href='?annee_scolaire=' + this.value" 
                    class="text-xs border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                @foreach($anneesScolaires as $annee)
                    <option value="{{ $annee }}" {{ $annee === $anneeScolaire ? 'selected' : '' }}>
                        {{ $annee }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Statistiques globales --}}
    <div class="grid grid-cols-1 md:grid-cols-6 gap-2 mb-3">
        {{-- Score moyen --}}
        <div class="bg-white border-2 border-blue-200 rounded-lg p-2.5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-blue-700 font-semibold uppercase tracking-wide">Score Moyen</p>
                    <p class="text-xl font-bold text-blue-600 mt-0.5">{{ $statistiques['score_moyen'] }}<span class="text-xs">/100</span></p>
                </div>
                <div class="bg-blue-100 rounded-full p-1.5">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Excellent --}}
        <div class="bg-white border-2 border-emerald-200 rounded-lg p-2.5 shadow-sm">
            <p class="text-[9px] text-emerald-700 font-semibold uppercase tracking-wide">⭐ Excellent</p>
            <p class="text-xl font-bold text-emerald-600 mt-0.5">{{ $statistiques['repartition']['excellent'] }}</p>
            <p class="text-[9px] text-emerald-600">≥ 90/100</p>
        </div>

        {{-- Très bien --}}
        <div class="bg-white border-2 border-green-200 rounded-lg p-2.5 shadow-sm">
            <p class="text-[9px] text-green-700 font-semibold uppercase tracking-wide">✅ Très bien</p>
            <p class="text-xl font-bold text-green-600 mt-0.5">{{ $statistiques['repartition']['tres_bien'] }}</p>
            <p class="text-[9px] text-green-600">80-89/100</p>
        </div>

        {{-- Bon --}}
        <div class="bg-white border-2 border-blue-200 rounded-lg p-2.5 shadow-sm">
            <p class="text-[9px] text-blue-700 font-semibold uppercase tracking-wide">👍 Bon</p>
            <p class="text-xl font-bold text-blue-600 mt-0.5">{{ $statistiques['repartition']['bon'] }}</p>
            <p class="text-[9px] text-blue-600">70-79/100</p>
        </div>

        {{-- Acceptable --}}
        <div class="bg-white border-2 border-amber-200 rounded-lg p-2.5 shadow-sm">
            <p class="text-[9px] text-amber-700 font-semibold uppercase tracking-wide">⚠️ Acceptable</p>
            <p class="text-xl font-bold text-amber-600 mt-0.5">{{ $statistiques['repartition']['acceptable'] }}</p>
            <p class="text-[9px] text-amber-600">60-69/100</p>
        </div>

        {{-- Critique --}}
        <div class="bg-white border-2 border-red-200 rounded-lg p-2.5 shadow-sm">
            <p class="text-[9px] text-red-700 font-semibold uppercase tracking-wide">❌ Critique</p>
            <p class="text-xl font-bold text-red-600 mt-0.5">{{ $statistiques['repartition']['a_ameliorer'] + $statistiques['repartition']['insuffisant'] }}</p>
            <p class="text-[9px] text-red-600">&lt; 60/100</p>
        </div>
    </div>

    {{-- Onglets --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        {{-- Navigation onglets --}}
        <div class="border-b border-gray-200">
            <nav class="flex gap-1 p-1" aria-label="Tabs">
                <button @click="activeTab = 'validation'" 
                        :class="activeTab === 'validation' ? 'bg-emerald-50 text-emerald-700 border-emerald-500' : 'text-gray-600 hover:text-gray-800 border-transparent hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-medium rounded-lg border-2 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Validation</span>
                </button>
                <button @click="activeTab = 'scores'" 
                        :class="activeTab === 'scores' ? 'bg-emerald-50 text-emerald-700 border-emerald-500' : 'text-gray-600 hover:text-gray-800 border-transparent hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-medium rounded-lg border-2 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Scores</span>
                </button>
                <button @click="activeTab = 'inspection'" 
                        :class="activeTab === 'inspection' ? 'bg-emerald-50 text-emerald-700 border-emerald-500' : 'text-gray-600 hover:text-gray-800 border-transparent hover:bg-gray-50'"
                        class="px-3 py-2 text-xs font-medium rounded-lg border-2 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Inspection</span>
                </button>
            </nav>
        </div>

        {{-- Contenu onglets --}}
        <div class="p-3">
            {{-- Onglet 1: Validation Automatique --}}
            <div x-show="activeTab === 'validation'" x-transition>
                <div class="mb-3 grid grid-cols-1 md:grid-cols-12 gap-2">
                    <input type="text" x-model="searchQuery" placeholder="Rechercher un établissement..." 
                           class="md:col-span-6 text-xs border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 py-2 px-3">
                    
                    <select x-model="filterQualite" class="md:col-span-4 text-xs border-gray-300 rounded-lg py-2 px-3">
                        <option value="tous">Tous les rapports</option>
                        <option value="excellent">⭐ Excellent (≥90)</option>
                        <option value="bon">👍 Bon (≥70)</option>
                        <option value="critique">❌ Critique (&lt;60)</option>
                    </select>

                    <span class="md:col-span-2 text-xs text-gray-600 font-medium px-3 py-2 bg-gray-100 rounded flex items-center justify-center" x-text="filteredRapports.length + ' rapport(s)'"></span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Établissement</th>
                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Score</th>
                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Complétude</th>
                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Cohérence</th>
                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Précision</th>
                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Anomalies</th>
                                <th class="px-3 py-2 text-center text-[10px] font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="item in filteredRapports" :key="item.rapport_id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 py-2">
                                        <div>
                                            <p class="text-xs font-medium text-gray-900" x-text="item.etablissement"></p>
                                            <p class="text-[10px] text-gray-500" x-text="item.zone"></p>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            <span x-text="item.badge_icon" class="text-sm"></span>
                                            <span class="text-xs font-bold" x-text="item.score_total"></span>
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full inline-block mt-1"
                                              :class="`bg-${item.badge_color}-100 text-${item.badge_color}-700`"
                                              x-text="item.badge_label"></span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="text-xs font-semibold" x-text="item.completude_score + '/40'"></div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="bg-blue-500 h-1.5 rounded-full" :style="`width: ${item.completude_pct}%`"></div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="text-xs font-semibold" x-text="item.coherence_score + '/30'"></div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="bg-green-500 h-1.5 rounded-full" :style="`width: ${item.coherence_pct}%`"></div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="text-xs font-semibold" x-text="item.precision_score + '/30'"></div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                            <div class="bg-purple-500 h-1.5 rounded-full" :style="`width: ${item.precision_pct}%`"></div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium"
                                              :class="item.anomalies_count > 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                                              x-text="item.anomalies_count > 0 ? item.anomalies_count + ' détectée(s)' : 'Aucune'">
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <a :href="'/admin/controle-qualite/' + item.rapport_id" 
                                           class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Voir détails →
                                        </a>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Onglet 2: Scores de Qualité --}}
            <div x-show="activeTab === 'scores'" x-transition>
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Classement par score de qualité</h3>
                    <p class="text-xs text-gray-600">Les établissements sont classés du meilleur au moins bon score.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <template x-for="(item, index) in filteredRapports.slice(0, 9)" :key="item.rapport_id">
                        <div class="border rounded-lg p-3 hover:shadow-md transition-shadow"
                             :class="index < 3 ? 'bg-gradient-to-br from-yellow-50 to-amber-50 border-amber-200' : 'bg-white'">
                            {{-- Médaille pour le top 3 --}}
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <div class="flex items-center gap-1 mb-1">
                                        <span class="text-lg" x-show="index === 0">🥇</span>
                                        <span class="text-lg" x-show="index === 1">🥈</span>
                                        <span class="text-lg" x-show="index === 2">🥉</span>
                                        <span class="text-xs font-bold text-gray-500" x-show="index >= 3" x-text="'#' + (index + 1)"></span>
                                    </div>
                                    <p class="text-xs font-semibold text-gray-900" x-text="item.etablissement"></p>
                                    <p class="text-[10px] text-gray-500" x-text="item.zone"></p>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold" :class="`text-${item.badge_color}-600`" x-text="item.score_total"></p>
                                    <p class="text-[10px] text-gray-500">/100</p>
                                </div>
                            </div>

                            {{-- Breakdown des scores --}}
                            <div class="space-y-1 mt-3">
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-gray-600">Complétude</span>
                                    <span class="font-semibold" x-text="item.completude_score + '/40'"></span>
                                </div>
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-gray-600">Cohérence</span>
                                    <span class="font-semibold" x-text="item.coherence_score + '/30'"></span>
                                </div>
                                <div class="flex items-center justify-between text-[10px]">
                                    <span class="text-gray-600">Précision</span>
                                    <span class="font-semibold" x-text="item.precision_score + '/30'"></span>
                                </div>
                            </div>

                            {{-- Badge --}}
                            <div class="mt-3 pt-2 border-t border-gray-200">
                                <span class="text-xs px-2 py-1 rounded-full inline-flex items-center gap-1"
                                      :class="`bg-${item.badge_color}-100 text-${item.badge_color}-700`">
                                    <span x-text="item.badge_icon"></span>
                                    <span x-text="item.badge_label"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Onglet 3: Inspection Technique --}}
            <div x-show="activeTab === 'inspection'" x-transition>
                <div class="mb-3">
                    <h3 class="text-sm font-semibold text-gray-800 mb-2">Analyse technique détaillée</h3>
                    <p class="text-xs text-gray-600 mb-3">Vue d'ensemble des problèmes détectés par catégorie.</p>
                    
                    {{-- Filtres pour l'inspection --}}
                    <div class="flex flex-wrap gap-2 mb-3">
                        <button @click="filterInspection = 'tous'" 
                                :class="filterInspection === 'tous' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-colors">
                            Tous
                        </button>
                        <button @click="filterInspection = 'error'" 
                                :class="filterInspection === 'error' ? 'bg-red-600 text-white' : 'bg-red-50 text-red-700 hover:bg-red-100'"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                            <span>⚠️</span>
                            <span>Erreurs critiques</span>
                            <span class="bg-white/30 px-1.5 rounded" x-text="countAnomaliesByGravite('error')"></span>
                        </button>
                        <button @click="filterInspection = 'warning'" 
                                :class="filterInspection === 'warning' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                            <span>⚡</span>
                            <span>Avertissements</span>
                            <span class="bg-white/30 px-1.5 rounded" x-text="countAnomaliesByGravite('warning')"></span>
                        </button>
                        <button @click="filterInspection = 'info'" 
                                :class="filterInspection === 'info' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100'"
                                class="px-3 py-1 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                            <span>ℹ️</span>
                            <span>Informations</span>
                            <span class="bg-white/30 px-1.5 rounded" x-text="countAnomaliesByGravite('info')"></span>
                        </button>
                    </div>
                </div>

                {{-- Résumé des anomalies (cartes cliquables) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-3">
                    <button @click="filterInspection = 'error'" 
                            :class="filterInspection === 'error' ? 'ring-2 ring-red-500' : ''"
                            class="bg-red-50 border border-red-200 rounded-lg p-2.5 hover:shadow-md transition-all text-left">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">⚠️</span>
                            <div>
                                <p class="text-[10px] text-red-700 font-medium">Erreurs critiques</p>
                                <p class="text-lg font-bold text-red-800" x-text="countAnomaliesByGravite('error')"></p>
                            </div>
                        </div>
                    </button>

                    <button @click="filterInspection = 'warning'" 
                            :class="filterInspection === 'warning' ? 'ring-2 ring-amber-500' : ''"
                            class="bg-amber-50 border border-amber-200 rounded-lg p-2.5 hover:shadow-md transition-all text-left">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">⚡</span>
                            <div>
                                <p class="text-[10px] text-amber-700 font-medium">Avertissements</p>
                                <p class="text-lg font-bold text-amber-800" x-text="countAnomaliesByGravite('warning')"></p>
                            </div>
                        </div>
                    </button>

                    <button @click="filterInspection = 'info'" 
                            :class="filterInspection === 'info' ? 'ring-2 ring-blue-500' : ''"
                            class="bg-blue-50 border border-blue-200 rounded-lg p-2.5 hover:shadow-md transition-all text-left">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">ℹ️</span>
                            <div>
                                <p class="text-[10px] text-blue-700 font-medium">Informations</p>
                                <p class="text-lg font-bold text-blue-800" x-text="countAnomaliesByGravite('info')"></p>
                            </div>
                        </div>
                    </button>

                    <button @click="filterInspection = 'tous'" 
                            :class="filterInspection === 'tous' ? 'ring-2 ring-gray-500' : ''"
                            class="bg-gray-50 border border-gray-200 rounded-lg p-2.5 hover:shadow-md transition-all text-left">
                        <div class="flex items-center gap-2">
                            <span class="text-xl">📋</span>
                            <div>
                                <p class="text-[10px] text-gray-700 font-medium">Total anomalies</p>
                                <p class="text-lg font-bold text-gray-800" x-text="countTotalAnomalies()"></p>
                            </div>
                        </div>
                    </button>
                </div>

                {{-- Liste des anomalies par établissement --}}
                <div class="space-y-2">
                    <template x-for="item in filteredInspectionRapports()" :key="item.rapport_id">
                        <div class="border border-gray-200 rounded-lg p-2.5 bg-white hover:shadow-sm transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <p class="text-xs font-semibold text-gray-900" x-text="item.etablissement"></p>
                                    <p class="text-[10px] text-gray-500" x-text="item.zone"></p>
                                </div>
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-medium"
                                      x-text="item.anomalies_count + ' anomalie(s)'">
                                </span>
                            </div>

                            <div class="space-y-1 mt-2">
                                <template x-for="anomalie in item.anomalies" :key="anomalie.type + anomalie.message">
                                    <div class="flex items-start gap-2 text-[10px] p-2 rounded"
                                         :class="{
                                             'bg-red-50 text-red-700': anomalie.gravite === 'error',
                                             'bg-amber-50 text-amber-700': anomalie.gravite === 'warning',
                                             'bg-blue-50 text-blue-700': anomalie.gravite === 'info'
                                         }">
                                        <span x-text="anomalie.gravite === 'error' ? '❌' : (anomalie.gravite === 'warning' ? '⚠️' : 'ℹ️')"></span>
                                        <span x-text="anomalie.message"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div x-show="rapportsWithAnomalies().length === 0" class="text-center py-8">
                        <p class="text-sm text-gray-500">✅ Aucune anomalie détectée dans les rapports validés</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function controleQualite() {
    return {
        activeTab: 'validation',
        searchQuery: '',
        filterQualite: 'tous',
        filterInspection: 'tous',
        rapportsData: @json($rapportsData),

        get filteredRapports() {
            let rapports = [...this.rapportsData];

            // Filtre par recherche
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                rapports = rapports.filter(r => 
                    r.etablissement.toLowerCase().includes(query) ||
                    r.zone.toLowerCase().includes(query)
                );
            }

            // Filtre par qualité
            if (this.filterQualite === 'excellent') {
                rapports = rapports.filter(r => r.score_total >= 90);
            } else if (this.filterQualite === 'bon') {
                rapports = rapports.filter(r => r.score_total >= 70);
            } else if (this.filterQualite === 'critique') {
                rapports = rapports.filter(r => r.score_total < 60);
            }

            return rapports;
        },

        rapportsWithAnomalies() {
            return this.rapportsData.filter(r => r.anomalies_count > 0);
        },

        filteredInspectionRapports() {
            let rapports = this.rapportsWithAnomalies();
            
            // Filtre par type d'anomalie
            if (this.filterInspection !== 'tous') {
                rapports = rapports.map(r => {
                    const filteredAnomalies = r.anomalies.filter(a => a.gravite === this.filterInspection);
                    return {
                        ...r,
                        anomalies: filteredAnomalies,
                        anomalies_count: filteredAnomalies.length
                    };
                }).filter(r => r.anomalies_count > 0);
            }
            
            return rapports;
        },

        countAnomaliesByGravite(gravite) {
            return this.rapportsData.reduce((total, r) => {
                return total + r.anomalies.filter(a => a.gravite === gravite).length;
            }, 0);
        },

        countTotalAnomalies() {
            return this.rapportsData.reduce((total, r) => total + r.anomalies_count, 0);
        }
    }
}
</script>
@endsection
