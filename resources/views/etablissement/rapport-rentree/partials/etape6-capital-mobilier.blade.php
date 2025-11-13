<!-- Capital Mobilier -->
<div id="capital-mobilier" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <form id="capital-mobilier-form" data-save-url="{{ route('etablissement.rapport-rentree.save-capital-mobilier', $rapport) }}">
        @csrf
        
        <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
            <i class="fas fa-chair text-gray-500 mr-2 text-xs"></i>
            Capital Mobilier
        </h3>
        
        <p class="text-xs text-gray-500 mb-4 italic">
            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
            Inventaire du mobilier scolaire et état de conservation.
        </p>

        <!-- Mobilier Élèves -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-users text-gray-500 mr-2 text-xs"></i>
                Mobilier Élèves
            </h4>
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <div class="space-y-3">
                    <!-- Tables-bancs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tables-bancs (total)</label>
                            <input type="number" 
                                   name="tables_bancs_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->tables_bancs_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tables-bancs (bon état)</label>
                            <input type="number" 
                                   name="tables_bancs_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->tables_bancs_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <!-- Chaises élèves -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Chaises élèves (total)</label>
                            <input type="number" 
                                   name="chaises_eleves_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->chaises_eleves_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Chaises élèves (bon état)</label>
                            <input type="number" 
                                   name="chaises_eleves_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->chaises_eleves_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <!-- Tables individuelles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tables individuelles (total)</label>
                            <input type="number" 
                                   name="tables_individuelles_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->tables_individuelles_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tables individuelles (bon état)</label>
                            <input type="number" 
                                   name="tables_individuelles_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->tables_individuelles_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobilier Enseignants -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chalkboard-teacher text-gray-500 mr-2 text-xs"></i>
                Mobilier Enseignants
            </h4>
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <div class="space-y-3">
                    <!-- Bureaux maître -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bureaux maître (total)</label>
                            <input type="number" 
                                   name="bureaux_maitre_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->bureaux_maitre_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bureaux maître (bon état)</label>
                            <input type="number" 
                                   name="bureaux_maitre_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->bureaux_maitre_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <!-- Chaises maître -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Chaises maître (total)</label>
                            <input type="number" 
                                   name="chaises_maitre_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->chaises_maitre_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Chaises maître (bon état)</label>
                            <input type="number" 
                                   name="chaises_maitre_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->chaises_maitre_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <!-- Tableaux -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tableaux (total)</label>
                            <input type="number" 
                                   name="tableaux_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->tableaux_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Tableaux (bon état)</label>
                            <input type="number" 
                                   name="tableaux_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->tableaux_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <!-- Armoires -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Armoires (total)</label>
                            <input type="number" 
                                   name="armoires_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->armoires_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Armoires (bon état)</label>
                            <input type="number" 
                                   name="armoires_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->armoires_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobilier Administratif -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-briefcase text-gray-500 mr-2 text-xs"></i>
                Mobilier Administratif
            </h4>
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <div class="space-y-3">
                    <!-- Bureaux administratifs -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bureaux administratifs (total)</label>
                            <input type="number" 
                                   name="bureaux_admin_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->bureaux_admin_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bureaux administratifs (bon état)</label>
                            <input type="number" 
                                   name="bureaux_admin_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->bureaux_admin_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                    
                    <!-- Chaises administratives -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Chaises administratives (total)</label>
                            <input type="number" 
                                   name="chaises_admin_total" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->chaises_admin_total ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Chaises administratives (bon état)</label>
                            <input type="number" 
                                   name="chaises_admin_bon_etat" 
                                   min="0"
                                   value="{{ $rapport->capitalMobilier->chaises_admin_bon_etat ?? '' }}"
                                   data-section="capital-mobilier"
                                   onchange="autoSave('capital-mobilier')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        @php
            $totalMobilier = ($rapport->capitalMobilier->tables_bancs_total ?? 0) + 
                           ($rapport->capitalMobilier->chaises_eleves_total ?? 0) + 
                           ($rapport->capitalMobilier->tables_individuelles_total ?? 0) + 
                           ($rapport->capitalMobilier->bureaux_maitre_total ?? 0) + 
                           ($rapport->capitalMobilier->chaises_maitre_total ?? 0) + 
                           ($rapport->capitalMobilier->tableaux_total ?? 0) + 
                           ($rapport->capitalMobilier->armoires_total ?? 0) + 
                           ($rapport->capitalMobilier->bureaux_admin_total ?? 0) + 
                           ($rapport->capitalMobilier->chaises_admin_total ?? 0);
            
            $totalBonEtat = ($rapport->capitalMobilier->tables_bancs_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->chaises_eleves_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->tables_individuelles_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->bureaux_maitre_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->chaises_maitre_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->tableaux_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->armoires_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->bureaux_admin_bon_etat ?? 0) + 
                          ($rapport->capitalMobilier->chaises_admin_bon_etat ?? 0);
            
            $tauxBonEtat = $totalMobilier > 0 ? round(($totalBonEtat / $totalMobilier) * 100, 1) : 0;
            
            $typesPresents = 0;
            if (($rapport->capitalMobilier->tables_bancs_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->chaises_eleves_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->tables_individuelles_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->bureaux_maitre_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->chaises_maitre_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->tableaux_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->armoires_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->bureaux_admin_total ?? 0) > 0) $typesPresents++;
            if (($rapport->capitalMobilier->chaises_admin_total ?? 0) > 0) $typesPresents++;
        @endphp

        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 rounded-lg p-4 border border-emerald-200">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-emerald-600 mr-2"></i>
                Synthèse du Capital Mobilier
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Total mobilier</div>
                    <div class="text-lg font-bold text-gray-700">{{ number_format($totalMobilier) }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">En bon état</div>
                    <div class="text-lg font-bold text-emerald-600">{{ number_format($totalBonEtat) }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Taux bon état</div>
                    <div class="text-lg font-bold {{ $tauxBonEtat >= 70 ? 'text-emerald-600' : ($tauxBonEtat >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $tauxBonEtat }}%
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Types disponibles</div>
                    <div class="text-lg font-bold text-blue-600">{{ $typesPresents }}/9</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
window.initCapitalMobilier = function() {
    console.log('✅ Init Capital Mobilier');
};
</script>
