<!-- Répartition par Diplômes -->
<div id="repartition-diplomes" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-certificate text-gray-500 mr-2 text-xs"></i>
        Répartition par Diplômes
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Classification du personnel enseignant selon leur niveau de qualification.
    </p>

    <form id="diplomes-form" data-save-url="{{ route('etablissement.rapport-rentree.save-personnel', $rapport) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- BAC -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">BAC</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">H</label>
                        <input type="number" 
                               id="bac_hommes" 
                               name="bac_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->bac_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">F</label>
                        <input type="number" 
                               id="bac_femmes" 
                               name="bac_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->bac_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">T</label>
                        <input type="number" 
                               id="bac_total" 
                               name="bac_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->bac_total }}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-emerald-100 text-emerald-700 font-medium">
                    </div>
                </div>
            </div>

            <!-- BFEM -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">BFEM</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">H</label>
                        <input type="number" 
                               id="bfem_hommes" 
                               name="bfem_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->bfem_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">F</label>
                        <input type="number" 
                               id="bfem_femmes" 
                               name="bfem_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->bfem_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">T</label>
                        <input type="number" 
                               id="bfem_total" 
                               name="bfem_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->bfem_total }}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-emerald-100 text-emerald-700 font-medium">
                    </div>
                </div>
            </div>

            <!-- CFEE -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">CFEE</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">H</label>
                        <input type="number" 
                               id="cfee_hommes" 
                               name="cfee_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->cfee_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">F</label>
                        <input type="number" 
                               id="cfee_femmes" 
                               name="cfee_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->cfee_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">T</label>
                        <input type="number" 
                               id="cfee_total" 
                               name="cfee_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->cfee_total }}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-emerald-100 text-emerald-700 font-medium">
                    </div>
                </div>
            </div>

            <!-- Licence -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Licence</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">H</label>
                        <input type="number" 
                               id="licence_hommes" 
                               name="licence_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->licence_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">F</label>
                        <input type="number" 
                               id="licence_femmes" 
                               name="licence_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->licence_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">T</label>
                        <input type="number" 
                               id="licence_total" 
                               name="licence_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->licence_total }}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-emerald-100 text-emerald-700 font-medium">
                    </div>
                </div>
            </div>

            <!-- Master -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Master</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">H</label>
                        <input type="number" 
                               id="master_hommes" 
                               name="master_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->master_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">F</label>
                        <input type="number" 
                               id="master_femmes" 
                               name="master_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->master_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">T</label>
                        <input type="number" 
                               id="master_total" 
                               name="master_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->master_total }}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-emerald-100 text-emerald-700 font-medium">
                    </div>
                </div>
            </div>

            <!-- Autres -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                <h4 class="text-xs font-semibold text-gray-700 mb-3 text-center">Autres</h4>
                <div class="space-y-2">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">H</label>
                        <input type="number" 
                               id="autres_diplomes_hommes" 
                               name="autres_diplomes_hommes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->autres_diplomes_hommes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">F</label>
                        <input type="number" 
                               id="autres_diplomes_femmes" 
                               name="autres_diplomes_femmes"
                               min="0"
                               value="{{ $rapport->personnelEnseignant?->autres_diplomes_femmes }}"
                               data-section="personnel"
                               oninput="calculatePersonnelTotals(); autoSave('personnel')"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-emerald-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-xs text-gray-600 mb-1">T</label>
                        <input type="number" 
                               id="autres_diplomes_total" 
                               name="autres_diplomes_total"
                               readonly
                               value="{{ $rapport->personnelEnseignant?->autres_diplomes_total }}"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded bg-emerald-100 text-emerald-700 font-medium">
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>