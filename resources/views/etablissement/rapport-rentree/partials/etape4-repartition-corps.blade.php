<!-- Répartition par Corps -->
<div id="repartition-corps" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-graduation-cap text-gray-500 mr-2 text-xs"></i>
        Répartition par Corps
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Classification du personnel enseignant selon leur corps d'appartenance.
    </p>

    <form id="corps-form" data-save-url="{{ route('etablissement.rapport-rentree.save-personnel', $rapport) }}">
        @csrf
        
        <div class="space-y-4">
            <!-- Instituteurs -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Instituteurs</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="instituteurs_hommes" 
                               name="instituteurs_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->instituteurs_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 10">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="instituteurs_femmes" 
                               name="instituteurs_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->instituteurs_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 15">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="instituteurs_total" 
                               name="instituteurs_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->instituteurs_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>

            <!-- Instituteurs adjoints -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Instituteurs Adjoints</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="instituteurs_adjoints_hommes" 
                               name="instituteurs_adjoints_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->instituteurs_adjoints_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 3">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="instituteurs_adjoints_femmes" 
                               name="instituteurs_adjoints_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->instituteurs_adjoints_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 7">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="instituteurs_adjoints_total" 
                               name="instituteurs_adjoints_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->instituteurs_adjoints_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>

            <!-- Professeurs -->
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">Professeurs</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Hommes</label>
                        <input type="number" 
                               id="professeurs_hommes" 
                               name="professeurs_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->professeurs_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 5">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Femmes</label>
                        <input type="number" 
                               id="professeurs_femmes" 
                               name="professeurs_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->professeurs_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Ex: 4">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               id="professeurs_total" 
                               name="professeurs_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->professeurs_total }}"
                               class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                               placeholder="Calculé automatiquement">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>