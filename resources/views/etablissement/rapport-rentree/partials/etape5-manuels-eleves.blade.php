<!-- Manuels des Élèves -->
<div id="manuels-eleves" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-book-open text-gray-500 mr-2 text-xs"></i>
        Manuels des Élèves
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire des manuels scolaires par niveau et par matière.
    </p>

    <form id="manuels-eleves-form" data-save-url="{{ route('etablissement.rapport-rentree.save-manuels-eleves', $rapport) }}">
        @csrf
        
        @php
            $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
            $matieres = [
                'lc_francais' => 'Français (L.C.)',
                'mathematiques' => 'Mathématiques', 
                'edd' => 'É.D.D.',
                'dm' => 'D.M.',
                'manuel_classe' => 'Manuel classe',
                'livret_maison' => 'Livret maison',
                'livret_devoir_gradue' => 'Livret devoir gradué',
                'planche_alphabetique' => 'Planche alphabétique',
                'manuel_arabe' => 'Manuel arabe',
                'manuel_religion' => 'Manuel religion',
                'autre_religion' => 'Autre religion',
                'autres_manuels' => 'Autres manuels'
            ];
            
            // Préparer les données comme pour les effectifs
            $manuelsData = collect($niveaux)->mapWithKeys(function($niveau) use ($rapport) {
                $manuel = $rapport->manuelsEleves->where('niveau', $niveau)->first();
                return [$niveau => $manuel ?? (object)[]];
            });
        @endphp

        <!-- Tableau des manuels par niveau -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-3 py-2 text-left text-xs font-semibold text-gray-700 border-b border-gray-300">
                            <i class="fas fa-layer-group mr-1"></i>
                            Matières
                        </th>
                        @foreach($niveaux as $niveau)
                            <th class="px-3 py-2 text-center text-xs font-semibold text-emerald-700 border-b border-gray-300 bg-emerald-50">
                                {{ $niveau }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($matieres as $matiere_key => $matiere_label)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 text-xs font-medium text-gray-700 border-b border-gray-200 bg-gray-50">
                                {{ $matiere_label }}
                            </td>
                            @foreach($niveaux as $niveau)
                                @php
                                    $data = $manuelsData[$niveau];
                                @endphp
                                <td class="px-2 py-2 text-center border-b border-gray-200">
                                    <input type="number" 
                                           id="{{ strtolower($niveau) }}_{{ $matiere_key }}" 
                                           name="manuels[{{ $niveau }}][{{ $matiere_key }}]"
                                           min="0"
                                           value="{{ $data->{$matiere_key} ?? 0 }}"
                                           data-section="manuels-eleves"
                                           data-niveau="{{ $niveau }}"
                                           data-matiere="{{ $matiere_key }}"
                                           onchange="autoSave('manuels-eleves'); calculateManuelsElevesTotals();"
                                           oninput="calculateManuelsElevesTotals();"
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
                                     id="total-{{ strtolower($niveau) }}">0</div>
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Statistiques globales -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mt-6">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-gray-500 mr-2 text-xs"></i>
                Statistiques Manuels des Élèves
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-manuels-eleves">0</div>
                    <div class="text-xs text-gray-600">Total manuels</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="moyenne-par-niveau">0</div>
                    <div class="text-xs text-gray-600">Moyenne/niveau</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="matieres-couvertes">0</div>
                    <div class="text-xs text-gray-600">Matières couvertes</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="niveaux-complets">0</div>
                    <div class="text-xs text-gray-600">Niveaux complets</div>
                </div>
            </div>
            
            <!-- Répartition par matière -->
            <div class="mt-4">
                <h5 class="text-xs font-semibold text-emerald-700 mb-2">Répartition par matière principale</h5>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-francais">0</div>
                        <div class="text-xs text-gray-600">Français</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-maths">0</div>
                        <div class="text-xs text-gray-600">Maths</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-edd">0</div>
                        <div class="text-xs text-gray-600">É.D.D.</div>
                    </div>
                    <div class="bg-white rounded p-2 border border-emerald-200 text-center">
                        <div class="text-sm font-semibold text-emerald-600" id="total-arabe">0</div>
                        <div class="text-xs text-gray-600">Arabe</div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Fonction de calcul des totaux
function calculateManuelsElevesTotals() {
    console.log('=== DÉBUT CALCUL TOTAUX MANUELS ÉLÈVES ==='); // Debug
    
    const niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    const matieres = ['lc_francais', 'mathematiques', 'edd', 'dm', 'manuel_classe', 'livret_maison', 
                      'livret_devoir_gradue', 'planche_alphabetique', 'manuel_arabe', 'manuel_religion', 
                      'autre_religion', 'autres_manuels'];
    
    let totalGeneral = 0;
    let totalParMatiere = {
        'lc_francais': 0,
        'mathematiques': 0,
        'edd': 0,
        'manuel_arabe': 0
    };
    let matieresAvecDonnees = new Set();
    let niveauxComplets = 0;

    // Calculer totaux par niveau
    niveaux.forEach(niveau => {
        let totalNiveau = 0;
        let hasDataForNiveau = false;
        
        console.log(`--- Niveau ${niveau} ---`);
        matieres.forEach(matiere => {
            const inputId = niveau.toLowerCase() + '_' + matiere;
            const input = document.getElementById(inputId);
            if (input) {
                const value = parseInt(input.value) || 0;
                console.log(`  ${matiere}: ${input.value} -> ${value}`); // Debug
                totalNiveau += value;
                totalGeneral += value;
                
                // Compter pour les totaux par matière principale
                if (totalParMatiere.hasOwnProperty(matiere)) {
                    totalParMatiere[matiere] += value;
                }
                
                if (value > 0) {
                    hasDataForNiveau = true;
                    matieresAvecDonnees.add(matiere);
                }
            } else {
                console.log(`  INPUT INTROUVABLE: ${inputId}`); // Debug
            }
        });
        
        // Afficher total par niveau
        const totalElement = document.getElementById('total-' + niveau.toLowerCase());
        if (totalElement) {
            totalElement.textContent = totalNiveau;
            console.log(`Total ${niveau}: ${totalNiveau}`); // Debug
        } else {
            console.log(`ELEMENT TOTAL INTROUVABLE: total-${niveau.toLowerCase()}`); // Debug
        }
        
        // Compter niveau comme complet s'il a des données
        if (hasDataForNiveau) {
            niveauxComplets++;
        }
    });

    // Mettre à jour les statistiques globales
    const elements = {
        'total-manuels-eleves': totalGeneral,
        'moyenne-par-niveau': Math.round(totalGeneral / niveaux.length),
        'matieres-couvertes': matieresAvecDonnees.size,
        'niveaux-complets': niveauxComplets,
        'total-francais': totalParMatiere.lc_francais,
        'total-maths': totalParMatiere.mathematiques,
        'total-edd': totalParMatiere.edd,
        'total-arabe': totalParMatiere.manuel_arabe
    };

    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value;
            console.log(`${id}: ${value}`); // Debug
        } else {
            console.log(`Element ${id} not found`); // Debug
        }
    });
    
    console.log('Totaux calculés!'); // Debug
}

// Rendre la fonction globale
window.calculateManuelsElevesTotals = calculateManuelsElevesTotals;

// Fonction d'initialisation (appelée depuis index.blade.php lors du switchToEtape)
window.initManuelsEleves = function() {
    console.log('🔵 Initialisation Manuels Élèves...');
    
    // Vérifier si la section est visible
    const section = document.getElementById('manuels-eleves');
    if (!section) {
        console.warn('⚠️ Section manuels-eleves introuvable');
        return;
    }
    
    // Attendre un peu que tous les éléments soient rendus
    setTimeout(function() {
        console.log('🔵 Calcul des totaux initiaux...');
        
        // Calculer les totaux initiaux
        calculateManuelsElevesTotals();
        
        // Attacher les événements sur tous les inputs
        const inputs = document.querySelectorAll('[data-section="manuels-eleves"]');
        console.log(`✅ ${inputs.length} inputs trouvés pour manuels-eleves`);
        
        inputs.forEach(input => {
            // Éviter les doublons d'événements
            input.removeEventListener('change', calculateManuelsElevesTotals);
            input.removeEventListener('input', calculateManuelsElevesTotals);
            
            input.addEventListener('change', calculateManuelsElevesTotals);
            input.addEventListener('input', calculateManuelsElevesTotals);
        });
    }, 150);
};
</script>
