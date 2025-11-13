<!-- Situations Spéciales -->
<div id="situations-speciales" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-exclamation-triangle text-gray-500 mr-2 text-xs"></i>
        Situations Spéciales
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Identifiez les élèves orphelins ou sans extrait de naissance par niveau et par sexe.
    </p>
    
    @php
    $situations = [
        'orphelins' => 'Orphelins',
        'sans_extrait' => 'Sans Extrait de Naissance'
    ];
    @endphp
    
    <div class="space-y-4">
        @foreach($situations as $type => $label)
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <h4 class="text-xs font-semibold text-gray-700 mb-2">{{ $label }}</h4>
            <div class="space-y-3">
                @foreach($niveaux as $niveau)
                    @php $data = $effectifsData[$niveau]; @endphp
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center pb-2 border-b border-gray-200 last:border-b-0 last:pb-0">
                        <div class="text-xs font-medium text-emerald-700">{{ $niveau }}</div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Garçons</label>
                            <input type="number" 
                                   name="effectifs[{{ $niveau }}][{{ $type }}_garcons]"
                                   value="{{ $data->{$type . '_garcons'} ?? '' }}"
                                   data-section="effectifs"
                                   data-niveau="{{ $niveau }}"
                                   data-category="{{ $type }}"
                                   onchange="calculateEffectifTotal(this, '{{ $type }}'); autoSave('effectifs')"
                                   min="0"
                                   placeholder="Nombre"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Filles</label>
                            <input type="number" 
                                   name="effectifs[{{ $niveau }}][{{ $type }}_filles]"
                                   value="{{ $data->{$type . '_filles'} ?? '' }}"
                                   data-section="effectifs"
                                   data-niveau="{{ $niveau }}"
                                   data-category="{{ $type }}"
                                   onchange="calculateEffectifTotal(this, '{{ $type }}'); autoSave('effectifs')"
                                   min="0"
                                   placeholder="Nombre"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="effectifs[{{ $niveau }}][{{ $type }}_total]"
                                   value="{{ $data->{$type . '_total'} ?? '' }}"
                                   data-niveau="{{ $niveau }}"
                                   data-total-for="{{ $type }}"
                                   readonly
                                   class="w-full px-2 py-1 text-sm font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
