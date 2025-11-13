<!-- Matériel Pédagogique -->
<div id="pedagogique" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-chalkboard text-gray-500 mr-2 text-xs"></i>
        Matériel Pédagogique
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire du matériel pédagogique avec état de conservation et statistiques générales.
    </p>

    <form id="pedagogique-form" data-save-url="{{ route('etablissement.rapport-rentree.save-materiel', $rapport) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Globe -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Globe</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="globe_total" 
                               name="globe_total"
                               min="0"
                               value="{{ $rapport->materielDidactique?->globe_total }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="globe_bon_etat" 
                               name="globe_bon_etat"
                               min="0"
                               value="{{ $rapport->materielDidactique?->globe_bon_etat }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Cartes murales -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Cartes Murales</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="cartes_murales_total" 
                               name="cartes_murales_total"
                               min="0"
                               value="{{ $rapport->materielDidactique?->cartes_murales_total }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="cartes_murales_bon_etat" 
                               name="cartes_murales_bon_etat"
                               min="0"
                               value="{{ $rapport->materielDidactique?->cartes_murales_bon_etat }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Planches illustrées -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Planches Illustrées</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="planches_illustrees_total" 
                               name="planches_illustrees_total"
                               min="0"
                               value="{{ $rapport->materielDidactique?->planches_illustrees_total }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="planches_illustrees_bon_etat" 
                               name="planches_illustrees_bon_etat"
                               min="0"
                               value="{{ $rapport->materielDidactique?->planches_illustrees_bon_etat }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Kit matériel scientifique -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Kit Matériel Scientifique</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="kit_materiel_scientifique_total" 
                               name="kit_materiel_scientifique_total"
                               min="0"
                               value="{{ $rapport->materielDidactique?->kit_materiel_scientifique_total }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="kit_materiel_scientifique_bon_etat" 
                               name="kit_materiel_scientifique_bon_etat"
                               min="0"
                               value="{{ $rapport->materielDidactique?->kit_materiel_scientifique_bon_etat }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Autres matériel -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Autres Matériel</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="autres_materiel_total" 
                               name="autres_materiel_total"
                               min="0"
                               value="{{ $rapport->materielDidactique?->autres_materiel_total }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="autres_materiel_bon_etat" 
                               name="autres_materiel_bon_etat"
                               min="0"
                               value="{{ $rapport->materielDidactique?->autres_materiel_bon_etat }}"
                               data-section="materiel"
                               onchange="autoSave('materiel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques Globales -->
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chart-pie text-emerald-500 mr-2"></i>
                Statistiques Générales du Matériel
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-3 bg-white rounded border">
                    <div class="text-lg font-bold text-blue-600" id="total-materiel-general">0</div>
                    <div class="text-xs text-gray-600">Total Général</div>
                </div>
                
                <div class="text-center p-3 bg-white rounded border">
                    <div class="text-lg font-bold text-green-600" id="total-materiel-bon-etat">0</div>
                    <div class="text-xs text-gray-600">En Bon État</div>
                </div>
                
                <div class="text-center p-3 bg-white rounded border">
                    <div class="text-lg font-bold text-emerald-600" id="pourcentage-bon-etat">0%</div>
                    <div class="text-xs text-gray-600">Taux de Conservation</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function calculateMaterielTotals() {
    // Listes de tous les éléments de matériel
    const materielItems = [
        'dico_francais', 'dico_arabe', 'dico_autre',
        'regle_plate', 'equerre', 'compas', 'rapporteur',
        'decametre', 'chaine_arpenteur', 'boussole', 'thermometre', 'kit_capacite', 'balance',
        'globe', 'cartes_murales', 'planches_illustrees', 'kit_materiel_scientifique', 'autres_materiel'
    ];
    
    let totalGeneral = 0;
    let totalBonEtat = 0;
    
    // Calculer les totaux
    materielItems.forEach(item => {
        const total = parseInt(document.getElementById(item + '_total')?.value || 0);
        const bonEtat = parseInt(document.getElementById(item + '_bon_etat')?.value || 0);
        
        totalGeneral += total;
        totalBonEtat += bonEtat;
    });
    
    // Calculer le pourcentage de conservation
    const pourcentageConservation = totalGeneral > 0 ? ((totalBonEtat / totalGeneral) * 100).toFixed(1) : 0;
    
    // Mettre à jour l'affichage
    const totalGeneralElement = document.getElementById('total-materiel-general');
    const totalBonEtatElement = document.getElementById('total-materiel-bon-etat');
    const pourcentageBonEtatElement = document.getElementById('pourcentage-bon-etat');
    
    if (totalGeneralElement) totalGeneralElement.textContent = totalGeneral;
    if (totalBonEtatElement) totalBonEtatElement.textContent = totalBonEtat;
    if (pourcentageBonEtatElement) pourcentageBonEtatElement.textContent = pourcentageConservation + '%';
}

// Initialiser les calculs au chargement
document.addEventListener('DOMContentLoaded', function() {
    calculateMaterielTotals();
});
</script>
