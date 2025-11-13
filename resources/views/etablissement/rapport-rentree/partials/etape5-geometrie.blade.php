<!-- Instruments de Géométrie -->
<div id="geometrie" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-ruler-combined text-gray-500 mr-2 text-xs"></i>
        Instruments de Géométrie
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire des instruments de géométrie avec leur état de conservation.
    </p>

    <form id="geometrie-form" data-save-url="{{ route('etablissement.rapport-rentree.save-geometrie', $rapport) }}">
        @csrf
        
        <!-- Instruments de base -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Règles plates -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Règles plates</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="regle_plate_total"
                               min="0"
                               value="{{ $rapport->geometrie?->regle_plate_total ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="regle_plate_bon_etat"
                               min="0"
                               value="{{ $rapport->geometrie?->regle_plate_bon_etat ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>

            <!-- Équerres -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Équerres</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="equerre_total"
                               min="0"
                               value="{{ $rapport->geometrie?->equerre_total ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="equerre_bon_etat"
                               min="0"
                               value="{{ $rapport->geometrie?->equerre_bon_etat ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>

            <!-- Rapporteurs -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Rapporteurs</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="rapporteur_total"
                               min="0"
                               value="{{ $rapport->geometrie?->rapporteur_total ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="rapporteur_bon_etat"
                               min="0"
                               value="{{ $rapport->geometrie?->rapporteur_bon_etat ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>

            <!-- Compas -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Compas</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="compas_total"
                               min="0"
                               value="{{ $rapport->geometrie?->compas_total ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="compas_bon_etat"
                               min="0"
                               value="{{ $rapport->geometrie?->compas_bon_etat ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie'); calculateGeometrieTotals()"
                               oninput="calculateGeometrieTotals()"
                               class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>
        </div>

        <!-- Besoins et Budget -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-clipboard-list text-gray-500 mr-2 text-xs"></i>
                Besoins et Budget
            </h4>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Besoins en matériel de géométrie</label>
                    <textarea name="besoins_geometrie"
                              rows="2"
                              data-section="geometrie"
                              onchange="autoSave('geometrie')"
                              class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                              placeholder="Décrire les besoins...">{{ $rapport->geometrie?->besoins_geometrie ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Budget estimé (FCFA)</label>
                        <input type="number" 
                               name="budget_estime_geometrie"
                               min="0"
                               value="{{ $rapport->geometrie?->budget_estime_geometrie ?? '' }}"
                               data-section="geometrie"
                               onchange="autoSave('geometrie')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                               placeholder="Montant">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Observations</label>
                        <textarea name="observations_geometrie"
                                  rows="1"
                                  data-section="geometrie"
                                  onchange="autoSave('geometrie')"
                                  class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                  placeholder="Observations...">{{ $rapport->geometrie?->observations_geometrie ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-gray-500 mr-2 text-xs"></i>
                Statistiques Géométrie
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-instruments-geometrie">
                        @php
                            $total = ($rapport->geometrie?->regle_plate_total ?? 0) + 
                                    ($rapport->geometrie?->equerre_total ?? 0) + 
                                    ($rapport->geometrie?->rapporteur_total ?? 0) + 
                                    ($rapport->geometrie?->compas_total ?? 0);
                            echo $total;
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">Total instruments</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-bon-etat-geometrie">
                        @php
                            $totalBonEtat = ($rapport->geometrie?->regle_plate_bon_etat ?? 0) + 
                                           ($rapport->geometrie?->equerre_bon_etat ?? 0) + 
                                           ($rapport->geometrie?->rapporteur_bon_etat ?? 0) + 
                                           ($rapport->geometrie?->compas_bon_etat ?? 0);
                            echo $totalBonEtat;
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">En bon état</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="taux-bon-etat-geometrie">
                        @php
                            $total = ($rapport->geometrie?->regle_plate_total ?? 0) + 
                                    ($rapport->geometrie?->equerre_total ?? 0) + 
                                    ($rapport->geometrie?->rapporteur_total ?? 0) + 
                                    ($rapport->geometrie?->compas_total ?? 0);
                            $totalBonEtat = ($rapport->geometrie?->regle_plate_bon_etat ?? 0) + 
                                           ($rapport->geometrie?->equerre_bon_etat ?? 0) + 
                                           ($rapport->geometrie?->rapporteur_bon_etat ?? 0) + 
                                           ($rapport->geometrie?->compas_bon_etat ?? 0);
                            $taux = $total > 0 ? round(($totalBonEtat / $total) * 100) : 0;
                            echo $taux . '%';
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">Taux bon état</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="types-geometrie">
                        @php
                            $types = 0;
                            if (($rapport->geometrie?->regle_plate_total ?? 0) > 0) $types++;
                            if (($rapport->geometrie?->equerre_total ?? 0) > 0) $types++;
                            if (($rapport->geometrie?->rapporteur_total ?? 0) > 0) $types++;
                            if (($rapport->geometrie?->compas_total ?? 0) > 0) $types++;
                            echo $types;
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">Types disponibles</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
window.initGeometrie = function() {
    console.log('📐 Initialisation Géométrie - DÉSACTIVÉE pour test');
    // setTimeout(() => {
    //     calculateGeometrieTotals();
    // }, 200);
};

function calculateGeometrieTotals() {
    console.log('=== CALCUL TOTAUX GÉOMÉTRIE (appelé manuellement) ===');
    
    const regleTotal = parseInt(document.querySelector('[name="regle_plate_total"]')?.value) || 0;
    const regleBonEtat = parseInt(document.querySelector('[name="regle_plate_bon_etat"]')?.value) || 0;
    const equerreTotal = parseInt(document.querySelector('[name="equerre_total"]')?.value) || 0;
    const equerreBonEtat = parseInt(document.querySelector('[name="equerre_bon_etat"]')?.value) || 0;
    const rapporteurTotal = parseInt(document.querySelector('[name="rapporteur_total"]')?.value) || 0;
    const rapporteurBonEtat = parseInt(document.querySelector('[name="rapporteur_bon_etat"]')?.value) || 0;
    const compasTotal = parseInt(document.querySelector('[name="compas_total"]')?.value) || 0;
    const compasBonEtat = parseInt(document.querySelector('[name="compas_bon_etat"]')?.value) || 0;
    
    console.log('Valeurs récupérées:', {
        regleTotal, regleBonEtat,
        equerreTotal, equerreBonEtat,
        rapporteurTotal, rapporteurBonEtat,
        compasTotal, compasBonEtat
    });
    
    const totalGeneral = regleTotal + equerreTotal + rapporteurTotal + compasTotal;
    const totalBonEtat = regleBonEtat + equerreBonEtat + rapporteurBonEtat + compasBonEtat;
    const tauxBonEtat = totalGeneral > 0 ? Math.round((totalBonEtat / totalGeneral) * 100) : 0;
    
    // Compter les types disponibles
    let types = 0;
    if (regleTotal > 0) types++;
    if (equerreTotal > 0) types++;
    if (rapporteurTotal > 0) types++;
    if (compasTotal > 0) types++;
    
    console.log('Résultats calculés:', {totalGeneral, totalBonEtat, tauxBonEtat, types});
    
    // Mettre à jour l'affichage
    const elemTotal = document.getElementById('total-instruments-geometrie');
    const elemBonEtat = document.getElementById('total-bon-etat-geometrie');
    const elemTaux = document.getElementById('taux-bon-etat-geometrie');
    const elemTypes = document.getElementById('types-geometrie');
    
    console.log('Éléments DOM trouvés:', {
        elemTotal: !!elemTotal,
        elemBonEtat: !!elemBonEtat,
        elemTaux: !!elemTaux,
        elemTypes: !!elemTypes
    });
    
    if (elemTotal) elemTotal.textContent = totalGeneral;
    if (elemBonEtat) elemBonEtat.textContent = totalBonEtat;
    if (elemTaux) elemTaux.textContent = tauxBonEtat + '%';
    if (elemTypes) elemTypes.textContent = types;
    
    console.log('✅ Totaux géométrie calculés et affichés!');
}

// ⚠️ DÉSACTIVÉ pour test - ne pas calculer au chargement
// console.log('🔧 Script Géométrie chargé, calcul immédiat...');
// calculateGeometrieTotals();
</script>
