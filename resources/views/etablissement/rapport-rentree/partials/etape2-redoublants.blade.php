<!-- Redoublants -->
<div id="redoublants" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-redo text-gray-500 mr-2 text-xs"></i>
        Redoublants par Niveau
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Indiquez le nombre d'élèves redoublants par niveau et par sexe.
    </p>
    
    <div class="space-y-4">
        @foreach($niveaux as $niveau)
            @php $data = $effectifsData[$niveau]; @endphp
            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                <h4 class="text-xs font-semibold text-gray-700 mb-2">{{ $niveau }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Garçons</label>
                        <input type="number" 
                               name="effectifs[{{ $niveau }}][redoublants_garcons]"
                               value="{{ $data->redoublants_garcons ?? '' }}"
                               data-section="effectifs"
                               data-niveau="{{ $niveau }}"
                               data-category="redoublants"
                               onchange="calculateEffectifTotal(this, 'redoublants'); autoSave('effectifs')"
                               min="0"
                               placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Filles</label>
                        <input type="number" 
                               name="effectifs[{{ $niveau }}][redoublants_filles]"
                               value="{{ $data->redoublants_filles ?? '' }}"
                               data-section="effectifs"
                               data-niveau="{{ $niveau }}"
                               data-category="redoublants"
                               onchange="calculateEffectifTotal(this, 'redoublants'); autoSave('effectifs')"
                               min="0"
                               placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Total</label>
                        <input type="number" 
                               name="effectifs[{{ $niveau }}][redoublants_total]"
                               value="{{ $data->redoublants_total ?? '' }}"
                               data-niveau="{{ $niveau }}"
                               data-total-for="redoublants"
                               readonly
                               class="w-full px-2 py-1 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
