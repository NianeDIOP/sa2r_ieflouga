<!-- Nombre de Classes -->
<div id="nb-classes" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-door-open text-gray-500 mr-2 text-xs"></i>
        Nombre de Classes par Niveau
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Indiquez le nombre de classes pour chaque niveau. Si aucune classe n'existe pour un niveau, laissez vide.
    </p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($niveaux as $niveau)
            @php $data = $effectifsData[$niveau]; @endphp
            <div>
                <label class="block text-xs text-gray-600 mb-1">{{ $niveau }}</label>
                <input type="number" 
                       name="effectifs[{{ $niveau }}][nombre_classes]"
                       value="{{ $data->nombre_classes ?? '' }}"
                       data-section="effectifs"
                       onchange="autoSave('effectifs')"
                       min="0"
                       placeholder="Nombre"
                       class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
            </div>
        @endforeach
    </div>
</div>
