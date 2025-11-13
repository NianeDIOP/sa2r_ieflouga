<!-- Compétences TIC -->
<div id="competences-tic" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-laptop text-gray-500 mr-2 text-xs"></i>
        Compétences TIC
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Nombre d'enseignants formés aux Technologies de l'Information et de la Communication.
    </p>

    <form id="competences-tic-form" data-save-url="{{ route('etablissement.rapport-rentree.save-personnel', $rapport) }}">
        @csrf
        
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h4 class="text-xs font-semibold text-gray-700 mb-4 text-center">Enseignants Formés aux TIC</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">Hommes</label>
                    <input type="number" 
                           id="enseignants_formes_tic_hommes" 
                           name="enseignants_formes_tic_hommes"
                           min="0"
                           value="{{ $rapport->personnelEnseignant?->enseignants_formes_tic_hommes }}"
                           data-section="personnel"
                           oninput="calculatePersonnelTotals(); autoSave('personnel')"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                           placeholder="Ex: 8">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">Femmes</label>
                    <input type="number" 
                           id="enseignants_formes_tic_femmes" 
                           name="enseignants_formes_tic_femmes"
                           min="0"
                           value="{{ $rapport->personnelEnseignant?->enseignants_formes_tic_femmes }}"
                           data-section="personnel"
                           oninput="calculatePersonnelTotals(); autoSave('personnel')"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                           placeholder="Ex: 12">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-2">Total</label>
                    <input type="number" 
                           id="enseignants_formes_tic_total" 
                           name="enseignants_formes_tic_total"
                           readonly
                           value="{{ $rapport->personnelEnseignant?->enseignants_formes_tic_total }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md bg-emerald-100 font-medium text-emerald-700"
                           placeholder="Calculé automatiquement">
                </div>
            </div>

            <!-- Pourcentage d'enseignants formés -->
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-medium text-blue-700">
                        <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                        Pourcentage d'enseignants formés aux TIC
                    </span>
                    <span id="pourcentage-tic" class="text-sm font-bold text-blue-800">
                        0%
                    </span>
                </div>
                <p class="text-xs text-blue-600 mt-1 italic">
                    Calculé automatiquement par rapport au total général des enseignants
                </p>
            </div>
        </div>
    </form>
</div>