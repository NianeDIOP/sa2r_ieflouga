<!-- CMG (Classe Multigrades) -->
<div id="cmg" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-users text-gray-500 mr-2 text-xs"></i>
        CMG (Classe Multigrades)
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Renseignez les informations sur les CMG (Classes Multigrades) de votre établissement.
    </p>

    <form id="cmg-form" data-save-url="{{ route('etablissement.rapport-rentree.save-cmg', $rapport) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre de CMG</label>
                <input type="number" 
                       id="cmg_nombre" 
                       name="cmg_nombre" 
                       min="0"
                       value="{{ $rapport->cmg?->cmg_nombre }}"
                       data-section="cmg"
                       onchange="autoSave('cmg')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: 2">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Combinaison 1</label>
                <input type="text" 
                       id="cmg_combinaison_1" 
                       name="cmg_combinaison_1" 
                       value="{{ $rapport->cmg?->cmg_combinaison_1 }}"
                       data-section="cmg"
                       onchange="autoSave('cmg')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: CM1-CM2">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Combinaison 2</label>
                <input type="text" 
                       id="cmg_combinaison_2" 
                       name="cmg_combinaison_2" 
                       value="{{ $rapport->cmg?->cmg_combinaison_2 }}"
                       data-section="cmg"
                       onchange="autoSave('cmg')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: CM1/A-CM2/A">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Combinaison 3</label>
                <input type="text" 
                       id="cmg_combinaison_3" 
                       name="cmg_combinaison_3" 
                       value="{{ $rapport->cmg?->cmg_combinaison_3 }}"
                       data-section="cmg"
                       onchange="autoSave('cmg')"
                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                       placeholder="Ex: CM1/B-CM2/B">
            </div>
        </div>

        <div class="mt-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Autres combinaisons</label>
            <textarea id="cmg_combinaison_autres" 
                      name="cmg_combinaison_autres" 
                      rows="2"
                      data-section="cmg"
                      onchange="autoSave('cmg')"
                      class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-transparent resize-none"
                      placeholder="Décrivez les autres combinaisons de classes...">{{ $rapport->cmg?->cmg_combinaison_autres }}</textarea>
        </div>
    </form>
</div>