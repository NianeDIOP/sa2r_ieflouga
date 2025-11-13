<!-- Instruments de Mesure -->
<div id="mesure" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-weight-hanging text-gray-500 mr-2 text-xs"></i>
        Instruments de Mesure
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire des instruments de mesure avec leur état de conservation.
    </p>

    <form id="mesure-form" data-save-url="{{ route('etablissement.rapport-rentree.save-mesure', $rapport) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Décamètre -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Décamètre</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="decametre_total" 
                               name="decametre_total"
                               min="0"
                               value="{{ $rapport->mesure?->decametre_total }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="decametre_bon_etat" 
                               name="decametre_bon_etat"
                               min="0"
                               value="{{ $rapport->mesure?->decametre_bon_etat }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Chaîne d'arpenteur -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Chaîne d'arpenteur</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="chaine_arpenteur_total" 
                               name="chaine_arpenteur_total"
                               min="0"
                               value="{{ $rapport->mesure?->chaine_arpenteur_total }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="chaine_arpenteur_bon_etat" 
                               name="chaine_arpenteur_bon_etat"
                               min="0"
                               value="{{ $rapport->mesure?->chaine_arpenteur_bon_etat }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Boussole -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Boussole</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="boussole_total" 
                               name="boussole_total"
                               min="0"
                               value="{{ $rapport->mesure?->boussole_total }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="boussole_bon_etat" 
                               name="boussole_bon_etat"
                               min="0"
                               value="{{ $rapport->mesure?->boussole_bon_etat }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Thermomètre -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Thermomètre</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="thermometre_total" 
                               name="thermometre_total"
                               min="0"
                               value="{{ $rapport->mesure?->thermometre_total }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="thermometre_bon_etat" 
                               name="thermometre_bon_etat"
                               min="0"
                               value="{{ $rapport->mesure?->thermometre_bon_etat }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Kit capacité -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Kit Capacité</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="kit_capacite_total" 
                               name="kit_capacite_total"
                               min="0"
                               value="{{ $rapport->mesure?->kit_capacite_total }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="kit_capacite_bon_etat" 
                               name="kit_capacite_bon_etat"
                               min="0"
                               value="{{ $rapport->mesure?->kit_capacite_bon_etat }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Balance -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Balance</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="balance_total" 
                               name="balance_total"
                               min="0"
                               value="{{ $rapport->mesure?->balance_total }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                        <input type="number" 
                               id="balance_bon_etat" 
                               name="balance_bon_etat"
                               min="0"
                               value="{{ $rapport->mesure?->balance_bon_etat }}"
                               data-section="mesure"
                               onchange="autoSave('mesure')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
