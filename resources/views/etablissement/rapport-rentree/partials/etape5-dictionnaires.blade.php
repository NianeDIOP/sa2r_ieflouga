<!-- Dictionnaires -->
<div id="dictionnaires" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-book text-gray-500 mr-2 text-xs"></i>
        Dictionnaires
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire des dictionnaires disponibles avec leur état de conservation.
    </p>

    <form id="dictionnaires-form" data-save-url="{{ route('etablissement.rapport-rentree.save-dictionnaires', $rapport) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Dictionnaires Français -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-book-open text-blue-500 mr-1.5"></i>
                    Dictionnaires Français
                </h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="dico_francais_total"
                               min="0"
                               value="{{ $rapport->dictionnaires?->dico_francais_total ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires'); calculateDictionnairesTotals()"
                               oninput="calculateDictionnairesTotals()"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="dico_francais_bon_etat"
                               min="0"
                               value="{{ $rapport->dictionnaires?->dico_francais_bon_etat ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires'); calculateDictionnairesTotals()"
                               oninput="calculateDictionnairesTotals()"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>

            <!-- Dictionnaires Arabe -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-book-open text-green-500 mr-1.5"></i>
                    Dictionnaires Arabe
                </h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="dico_arabe_total"
                               min="0"
                               value="{{ $rapport->dictionnaires?->dico_arabe_total ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires'); calculateDictionnairesTotals()"
                               oninput="calculateDictionnairesTotals()"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="dico_arabe_bon_etat"
                               min="0"
                               value="{{ $rapport->dictionnaires?->dico_arabe_bon_etat ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires'); calculateDictionnairesTotals()"
                               oninput="calculateDictionnairesTotals()"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>

            <!-- Autres Dictionnaires -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2 flex items-center">
                    <i class="fas fa-book-open text-purple-500 mr-1.5"></i>
                    Autres Dictionnaires
                </h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="dico_autre_total"
                               min="0"
                               value="{{ $rapport->dictionnaires?->dico_autre_total ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires'); calculateDictionnairesTotals()"
                               oninput="calculateDictionnairesTotals()"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">En bon état</label>
                        <input type="number" 
                               name="dico_autre_bon_etat"
                               min="0"
                               value="{{ $rapport->dictionnaires?->dico_autre_bon_etat ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires'); calculateDictionnairesTotals()"
                               oninput="calculateDictionnairesTotals()"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Nombre">
                    </div>
                </div>
            </div>
        </div>

        <!-- Besoins et Budget -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-clipboard-list text-gray-500 mr-2 text-xs"></i>
                Besoins et Budget Estimé
            </h4>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Besoins en dictionnaires</label>
                    <textarea name="besoins_dictionnaires"
                              rows="2"
                              data-section="dictionnaires"
                              onchange="autoSave('dictionnaires')"
                              class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                              placeholder="Décrire les besoins en dictionnaires...">{{ $rapport->dictionnaires?->besoins_dictionnaires ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Budget estimé (FCFA)</label>
                        <input type="number" 
                               name="budget_estime_dictionnaires"
                               min="0"
                               value="{{ $rapport->dictionnaires?->budget_estime_dictionnaires ?? '' }}"
                               data-section="dictionnaires"
                               onchange="autoSave('dictionnaires')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Montant en FCFA">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Observations</label>
                        <textarea name="observations_dictionnaires"
                                  rows="1"
                                  data-section="dictionnaires"
                                  onchange="autoSave('dictionnaires')"
                                  class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                  placeholder="Observations...">{{ $rapport->dictionnaires?->observations_dictionnaires ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-gray-500 mr-2 text-xs"></i>
                Statistiques Dictionnaires
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-dictionnaires">0</div>
                    <div class="text-xs text-gray-600">Total Dictionnaires</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-bon-etat">0</div>
                    <div class="text-xs text-gray-600">En bon état</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="taux-bon-etat">0%</div>
                    <div class="text-xs text-gray-600">Taux bon état</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="types-disponibles">0</div>
                    <div class="text-xs text-gray-600">Types disponibles</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
window.initDictionnaires = function() {
    console.log(' Initialisation Dictionnaires...');
    setTimeout(() => {
        calculateDictionnairesTotals();
    }, 150);
};

function calculateDictionnairesTotals() {
    console.log('=== CALCUL TOTAUX DICTIONNAIRES ===');
    
    const francaisTotal = parseInt(document.querySelector('[name="dico_francais_total"]')?.value) || 0;
    const francaisBonEtat = parseInt(document.querySelector('[name="dico_francais_bon_etat"]')?.value) || 0;
    const arabeTotal = parseInt(document.querySelector('[name="dico_arabe_total"]')?.value) || 0;
    const arabeBonEtat = parseInt(document.querySelector('[name="dico_arabe_bon_etat"]')?.value) || 0;
    const autreTotal = parseInt(document.querySelector('[name="dico_autre_total"]')?.value) || 0;
    const autreBonEtat = parseInt(document.querySelector('[name="dico_autre_bon_etat"]')?.value) || 0;
    
    const totalGeneral = francaisTotal + arabeTotal + autreTotal;
    const totalBonEtat = francaisBonEtat + arabeBonEtat + autreBonEtat;
    const tauxBonEtat = totalGeneral > 0 ? Math.round((totalBonEtat / totalGeneral) * 100) : 0;
    
    let typesDisponibles = 0;
    if (francaisTotal > 0) typesDisponibles++;
    if (arabeTotal > 0) typesDisponibles++;
    if (autreTotal > 0) typesDisponibles++;
    
    console.log('Total:', totalGeneral, 'Bon état:', totalBonEtat, 'Taux:', tauxBonEtat + '%');
    
    document.getElementById('total-dictionnaires').textContent = totalGeneral;
    document.getElementById('total-bon-etat').textContent = totalBonEtat;
    document.getElementById('taux-bon-etat').textContent = tauxBonEtat + '%';
    document.getElementById('types-disponibles').textContent = typesDisponibles;
    
    console.log(' Totaux dictionnaires calculés!');
}
</script>
