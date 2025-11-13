<!-- Équipements Informatiques -->
<div id="equipements-informatiques" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <form id="equipements-informatiques-form" data-save-url="{{ route('etablissement.rapport-rentree.save-equipements-informatiques', $rapport) }}">
        @csrf
        
        <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
            <i class="fas fa-laptop text-gray-500 mr-2 text-xs"></i>
            Équipements Informatiques
        </h3>
        
        <p class="text-xs text-gray-500 mb-4 italic">
            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
            Pour chaque type d'équipement, indiquez le nombre total et le nombre en bon état de fonctionnement.
        </p>

        <!-- ORDINATEURS -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-desktop text-gray-500 mr-2 text-xs"></i>
                Ordinateurs
            </h4>
            
            <!-- Ordinateurs Fixes -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Ordinateurs fixes (de bureau)</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="ordinateurs_fixes_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->ordinateurs_fixes_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="ordinateurs_fixes_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->ordinateurs_fixes_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Ordinateurs Portables -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Ordinateurs portables (laptops)</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="ordinateurs_portables_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->ordinateurs_portables_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="ordinateurs_portables_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->ordinateurs_portables_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Tablettes -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Tablettes numériques</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="tablettes_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->tablettes_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="tablettes_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->tablettes_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- IMPRIMANTES -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-print text-gray-500 mr-2 text-xs"></i>
                Imprimantes et Photocopieurs
            </h4>
            
            <!-- Imprimantes Laser -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Imprimantes laser</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="imprimantes_laser_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->imprimantes_laser_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="imprimantes_laser_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->imprimantes_laser_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Imprimantes Jet d'encre -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Imprimantes jet d'encre</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="imprimantes_jet_encre_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->imprimantes_jet_encre_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="imprimantes_jet_encre_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->imprimantes_jet_encre_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Imprimantes Multifonction -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Imprimantes multifonction (impression + scanner)</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="imprimantes_multifonction_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->imprimantes_multifonction_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="imprimantes_multifonction_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->imprimantes_multifonction_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Photocopieuses -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Photocopieuses</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="photocopieuses_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->photocopieuses_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="photocopieuses_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->photocopieuses_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- ÉQUIPEMENT AUDIOVISUEL -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-video text-gray-500 mr-2 text-xs"></i>
                Équipement Audiovisuel
            </h4>
            
            <!-- Vidéoprojecteurs -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Vidéoprojecteurs</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="videoprojecteurs_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->videoprojecteurs_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="videoprojecteurs_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->videoprojecteurs_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>

            <!-- Autres Équipements -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 mb-3">
                <label class="block text-xs font-medium text-gray-700 mb-2">Autres équipements (TV, tableaux interactifs, etc.)</label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="autres_equipements_total" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->autres_equipements_total ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="autres_equipements_bon_etat" 
                               min="0"
                               value="{{ $rapport->equipementInformatique?->autres_equipements_bon_etat ?? '' }}"
                               data-section="equipements-informatiques"
                               onchange="autoSave('equipements-informatiques'); calculateEquipementsInformatiquesTotals()"
                               oninput="calculateEquipementsInformatiquesTotals()"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                               placeholder="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        @php
            $totalEquipements = 0;
            $totalBonEtat = 0;
            $typesDisponibles = 0;
            
            if ($rapport->equipementInformatique) {
                $equipements = [
                    'ordinateurs_fixes',
                    'ordinateurs_portables',
                    'tablettes',
                    'imprimantes_laser',
                    'imprimantes_jet_encre',
                    'imprimantes_multifonction',
                    'photocopieuses',
                    'videoprojecteurs',
                    'autres_equipements'
                ];
                
                foreach ($equipements as $equip) {
                    $total = $rapport->equipementInformatique->{$equip . '_total'} ?? 0;
                    $bonEtat = $rapport->equipementInformatique->{$equip . '_bon_etat'} ?? 0;
                    
                    $totalEquipements += $total;
                    $totalBonEtat += $bonEtat;
                    
                    if ($total > 0) {
                        $typesDisponibles++;
                    }
                }
            }
            
            $tauxBonEtat = $totalEquipements > 0 ? round(($totalBonEtat / $totalEquipements) * 100, 1) : 0;
        @endphp

        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 rounded-lg p-4 border border-emerald-200">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-emerald-600 mr-2"></i>
                Synthèse des Équipements Informatiques
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Total équipements</div>
                    <div id="total-equipements-informatiques" class="text-lg font-bold text-gray-700">{{ number_format($totalEquipements) }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">En bon état</div>
                    <div id="total-bon-etat-informatiques" class="text-lg font-bold text-emerald-600">{{ number_format($totalBonEtat) }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Taux bon état</div>
                    <div id="taux-bon-etat-informatiques" class="text-lg font-bold {{ $tauxBonEtat >= 70 ? 'text-emerald-600' : ($tauxBonEtat >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ $tauxBonEtat }}%
                    </div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Types disponibles</div>
                    <div id="types-disponibles-informatiques" class="text-lg font-bold text-blue-600">{{ $typesDisponibles }}/9</div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
// Initialisation de la section Équipements Informatiques [v2.0-fixed]
window.initEquipementsInformatiques = function() {
    console.log('✅ Init Équipements Informatiques v2.0');
    // Attendre que le DOM soit complètement chargé
    setTimeout(() => {
        calculateEquipementsInformatiquesTotals();
    }, 100);
};

// Calcul des totaux (temps réel)
function calculateEquipementsInformatiquesTotals() {
    // Vérifier si TOUS les éléments statistiques existent
    const totalElement = document.getElementById('total-equipements-informatiques');
    const bonEtatElement = document.getElementById('total-bon-etat-informatiques');
    const tauxElement = document.getElementById('taux-bon-etat-informatiques');
    const typesElement = document.getElementById('types-disponibles-informatiques');
    
    if (!totalElement || !bonEtatElement || !tauxElement || !typesElement) {
        console.log('⚠️ Éléments statistiques pas encore dans le DOM, annulation calcul');
        return;
    }
    
    const equipements = [
        'ordinateurs_fixes',
        'ordinateurs_portables',
        'tablettes',
        'imprimantes_laser',
        'imprimantes_jet_encre',
        'imprimantes_multifonction',
        'photocopieuses',
        'videoprojecteurs',
        'autres_equipements'
    ];
    
    let totalEquipements = 0;
    let totalBonEtat = 0;
    let typesDisponibles = 0;
    
    equipements.forEach(equip => {
        const totalInput = document.querySelector(`input[name="${equip}_total"]`);
        const bonEtatInput = document.querySelector(`input[name="${equip}_bon_etat"]`);
        
        const total = parseInt(totalInput?.value || 0);
        const bonEtat = parseInt(bonEtatInput?.value || 0);
        
        totalEquipements += total;
        totalBonEtat += bonEtat;
        
        if (total > 0) {
            typesDisponibles++;
        }
    });
    
    const tauxBonEtat = totalEquipements > 0 ? Math.round((totalBonEtat / totalEquipements) * 100) : 0;
    
    // Mise à jour des affichages (déjà vérifié en début de fonction)
    totalElement.textContent = totalEquipements;
    bonEtatElement.textContent = totalBonEtat;
    tauxElement.textContent = tauxBonEtat + '%';
    typesElement.textContent = typesDisponibles;
}
</script>
