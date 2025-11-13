<!-- Manuels du Maître -->
<div id="manuels-maitre" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-user-tie text-gray-500 mr-2 text-xs"></i>
        Manuels du Maître
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire des guides pédagogiques et outils du maître par niveau.
    </p>

    <form id="manuels-maitre-form" data-save-url="{{ route('etablissement.rapport-rentree.save-manuels-maitre', $rapport) }}">
        @csrf
        
        @php
            $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
            $guidesMaitre = [
                'guide_lc_francais' => 'Guide L.C. Français',
                'guide_mathematiques' => 'Guide Mathématiques',
                'guide_edd' => 'Guide É.D.D.',
                'guide_dm' => 'Guide D.M.',
                'guide_pedagogique' => 'Guide Pédagogique',
                'guide_arabe_religieux' => 'Guide Arabe/Religieux',
                'guide_langue_nationale' => 'Guide Langue Nationale',
                'cahier_recits' => 'Cahier de Récits',
                'autres_manuels_maitre' => 'Autres Manuels'
            ];
        @endphp

        <!-- Tableau des guides du maître par niveau -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-b border-gray-300">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Guides et Outils
                        </th>
                        @foreach($niveaux as $niveau)
                            <th class="px-3 py-2 text-center text-xs font-semibold text-emerald-700 border-b border-gray-300 bg-emerald-50">
                                {{ $niveau }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($guidesMaitre as $guide_key => $guide_label)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-xs font-medium text-gray-700 border-b border-gray-200 bg-gray-50">
                                {{ $guide_label }}
                            </td>
                            @foreach($niveaux as $niveau)
                                @php
                                    $manuelMaitre = $rapport->manuelsMaitre?->where('niveau', $niveau)->first();
                                @endphp
                                <td class="px-2 py-2 text-center border-b border-gray-200">
                                    <input type="number" 
                                           id="{{ strtolower($niveau) }}_{{ $guide_key }}" 
                                           name="manuels_maitre[{{ $niveau }}][{{ $guide_key }}]"
                                           min="0"
                                           value="{{ $manuelMaitre?->{$guide_key} ?? 0 }}"
                                           data-section="manuels-maitre"
                                           data-niveau="{{ $niveau }}"
                                           data-guide="{{ $guide_key }}"
                                           onchange="autoSave('manuels-maitre'); calculateManuelsMaitreTotals();"
                                           oninput="calculateManuelsMaitreTotals();"
                                           class="w-full px-2 py-1 text-sm text-center border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                           placeholder="0">
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-emerald-50 border-t-2 border-emerald-300">
                        <td class="px-3 py-2 text-xs font-bold text-emerald-700">
                            <i class="fas fa-calculator mr-1"></i>
                            TOTAL PAR NIVEAU
                        </td>
                        @foreach($niveaux as $niveau)
                            <td class="px-2 py-2 text-center">
                                <div class="text-sm font-bold text-emerald-700 bg-white rounded px-2 py-1" 
                                     id="total-maitre-{{ strtolower($niveau) }}">0</div>
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Statistiques globales -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mt-6">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chart-line text-gray-500 mr-2 text-xs"></i>
                Statistiques Manuels du Maître
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-manuels-maitre">0</div>
                    <div class="text-xs text-gray-600">Total guides</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="moyenne-guides-niveau">0</div>
                    <div class="text-xs text-gray-600">Moyenne/niveau</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="guides-disponibles">0</div>
                    <div class="text-xs text-gray-600">Types de guides</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="couverture-niveaux">0%</div>
                    <div class="text-xs text-gray-600">Couverture niveaux</div>
                </div>
            </div>

            <!-- Répartition par type de guide -->
            <div class="mt-4">
                <h5 class="text-xs font-semibold text-emerald-700 mb-2">Répartition par matière</h5>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-guide-francais">0</div>
                        <div class="text-xs text-gray-600">Français</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-guide-maths">0</div>
                        <div class="text-xs text-gray-600">Maths</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-guide-edd">0</div>
                        <div class="text-xs text-gray-600">É.D.D.</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-guide-arabe">0</div>
                        <div class="text-xs text-gray-600">Arabe</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-guide-pedagogique">0</div>
                        <div class="text-xs text-gray-600">Pédagogiques</div>
                    </div>
                </div>
            </div>
            
            <!-- Taux de disponibilité par niveau -->
            <div class="mt-4">
                <h5 class="text-xs font-semibold text-emerald-700 mb-2">Taux de disponibilité par niveau</h5>
                <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
                    @foreach($niveaux as $niveau)
                        <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                            <div class="text-sm font-semibold text-emerald-600" id="taux-{{ strtolower($niveau) }}">0%</div>
                            <div class="text-xs text-gray-600">{{ $niveau }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Fonction de calcul des totaux Manuels Maître
function calculateManuelsMaitreTotals() {
    console.log('=== DÉBUT CALCUL TOTAUX MANUELS MAÎTRE ===');
    
    const niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    const guides = ['guide_lc_francais', 'guide_mathematiques', 'guide_edd', 'guide_dm', 
                   'guide_pedagogique', 'guide_arabe_religieux', 'guide_langue_nationale', 
                   'cahier_recits', 'autres_manuels_maitre'];
    
    let totalGeneral = 0;
    let totalParGuide = {
        'guide_lc_francais': 0,
        'guide_mathematiques': 0,
        'guide_edd': 0,
        'guide_arabe_religieux': 0,
        'guide_pedagogique': 0
    };
    let guidesAvecDonnees = new Set();
    let niveauxComplets = 0;

    // Calculer totaux par niveau
    niveaux.forEach(niveau => {
        let totalNiveau = 0;
        let hasDataForNiveau = false;
        
        guides.forEach(guide => {
            const inputId = niveau.toLowerCase() + '_' + guide;
            const input = document.getElementById(inputId);
            if (input) {
                const value = parseInt(input.value) || 0;
                totalNiveau += value;
                totalGeneral += value;
                
                if (totalParGuide.hasOwnProperty(guide)) {
                    totalParGuide[guide] += value;
                }
                
                if (value > 0) {
                    hasDataForNiveau = true;
                    guidesAvecDonnees.add(guide);
                }
            }
        });
        
        // Afficher total par niveau
        const totalElement = document.getElementById('total-maitre-' + niveau.toLowerCase());
        if (totalElement) {
            totalElement.textContent = totalNiveau;
        }
        
        // Calculer taux de disponibilité (basé sur 9 guides possibles)
        const taux = totalNiveau > 0 ? Math.round((totalNiveau / 9) * 100) : 0;
        const tauxElement = document.getElementById('taux-' + niveau.toLowerCase());
        if (tauxElement) {
            tauxElement.textContent = taux + '%';
        }
        
        if (hasDataForNiveau) {
            niveauxComplets++;
        }
    });

    // Calculer couverture générale
    const couverture = niveauxComplets > 0 ? Math.round((niveauxComplets / 6) * 100) : 0;

    // Mettre à jour les statistiques globales
    const elements = {
        'total-manuels-maitre': totalGeneral,
        'moyenne-guides-niveau': Math.round(totalGeneral / niveaux.length),
        'guides-disponibles': guidesAvecDonnees.size,
        'couverture-niveaux': couverture + '%',
        'total-guide-francais': totalParGuide.guide_lc_francais,
        'total-guide-maths': totalParGuide.guide_mathematiques,
        'total-guide-edd': totalParGuide.guide_edd,
        'total-guide-arabe': totalParGuide.guide_arabe_religieux,
        'total-guide-pedagogique': totalParGuide.guide_pedagogique
    };

    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
        }
    });
    
    console.log('✅ Totaux Manuels Maître calculés!');
}

// Rendre la fonction globale
window.calculateManuelsMaitreTotals = calculateManuelsMaitreTotals;

// Fonction d'initialisation
window.initManuelsMaitre = function() {
    console.log('🔵 Initialisation Manuels Maître...');
    
    setTimeout(function() {
        calculateManuelsMaitreTotals();
        
        const inputs = document.querySelectorAll('[data-section="manuels-maitre"]');
        console.log(`✅ ${inputs.length} inputs trouvés pour manuels-maitre`);
        
        inputs.forEach(input => {
            input.removeEventListener('change', calculateManuelsMaitreTotals);
            input.removeEventListener('input', calculateManuelsMaitreTotals);
            
            input.addEventListener('change', calculateManuelsMaitreTotals);
            input.addEventListener('input', calculateManuelsMaitreTotals);
        });
    }, 150);
};
</script>