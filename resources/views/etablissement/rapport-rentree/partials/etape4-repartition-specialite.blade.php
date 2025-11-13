<!-- Répartition par Spécialité -->
<div id="repartition-specialite" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-users text-gray-500 mr-2 text-xs"></i>
        Répartition par Spécialité
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Renseignez le nombre d'enseignants par spécialité et par sexe. Les totaux sont calculés automatiquement (Hommes + Femmes = Total).
    </p>

    <form id="specialite-form" data-save-url="{{ route('etablissement.rapport-rentree.save-personnel', $rapport) }}">
        @csrf
        
        <div class="space-y-4">
            <!-- Titulaires -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Titulaires</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="titulaires_hommes" 
                               name="titulaires_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->titulaires_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 5">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="titulaires_femmes" 
                               name="titulaires_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->titulaires_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 8">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="titulaires_total" 
                               name="titulaires_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->titulaires_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>

            <!-- Vacataires -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Vacataires</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="vacataires_hommes" 
                               name="vacataires_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->vacataires_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="vacataires_femmes" 
                               name="vacataires_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->vacataires_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 3">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="vacataires_total" 
                               name="vacataires_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->vacataires_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>

            <!-- Volontaires -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Volontaires</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="volontaires_hommes" 
                               name="volontaires_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->volontaires_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 1">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="volontaires_femmes" 
                               name="volontaires_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->volontaires_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 2">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="volontaires_total" 
                               name="volontaires_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->volontaires_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>

            <!-- Contractuels -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Contractuels</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="contractuels_hommes" 
                               name="contractuels_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->contractuels_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 0">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="contractuels_femmes" 
                               name="contractuels_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->contractuels_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 1">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="contractuels_total" 
                               name="contractuels_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->contractuels_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>

            <!-- Communautaires -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Communautaires</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="communautaires_hommes" 
                               name="communautaires_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->communautaires_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 1">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="communautaires_femmes" 
                               name="communautaires_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->communautaires_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 0">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="communautaires_total" 
                               name="communautaires_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->communautaires_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>