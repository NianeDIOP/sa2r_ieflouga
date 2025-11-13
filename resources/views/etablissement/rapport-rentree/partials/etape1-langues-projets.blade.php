<!-- Langues Nationales & Projets -->
<div id="langues" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-language text-gray-500 mr-2 text-xs"></i>
        Langues Nationales & Projets Informatiques
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Sélectionnez la langue nationale enseignée. Si aucune case n'est cochée, cela indique l'absence de cette activité dans l'établissement.
    </p>
    
    <div class="space-y-4">
        
        <!-- Langue Nationale -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="block text-xs font-medium text-gray-700 mb-2">
                Langue Nationale Enseignée
            </label>
            <select name="langue_nationale" data-section="langues-projets" 
                    onchange="autoSave('langues-projets')"
                    class="w-full md:w-1/2 px-3 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500 focus:border-emerald-500">
                <option value="">-- Sélectionner --</option>
                <option value="wolof" {{ ($rapport->languesProjets->langue_nationale ?? '') === 'wolof' ? 'selected' : '' }}>Wolof</option>
                <option value="pulaar" {{ ($rapport->languesProjets->langue_nationale ?? '') === 'pulaar' ? 'selected' : '' }}>Pulaar</option>
                <option value="serer" {{ ($rapport->languesProjets->langue_nationale ?? '') === 'serer' ? 'selected' : '' }}>Serer</option>
                <option value="mandinka" {{ ($rapport->languesProjets->langue_nationale ?? '') === 'mandinka' ? 'selected' : '' }}>Mandinka</option>
                <option value="soninke" {{ ($rapport->languesProjets->langue_nationale ?? '') === 'soninke' ? 'selected' : '' }}>Soninke</option>
                <option value="autre" {{ ($rapport->languesProjets->langue_nationale ?? '') === 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Enseignement de l'Arabe -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <label class="flex items-center space-x-2">
                <input type="checkbox" name="enseignement_arabe_existe" value="1" 
                       data-section="langues-projets" 
                       {{ ($rapport->languesProjets->enseignement_arabe_existe ?? false) ? 'checked' : '' }}
                       onchange="autoSave('langues-projets')"
                       class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                <span class="text-xs font-medium text-gray-700">Enseignement de l'Arabe</span>
            </label>
        </div>

        <!-- Projets Informatiques -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <div class="mb-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="projets-checkbox" name="projets_informatiques_existe" value="1" 
                           data-section="langues-projets" 
                           {{ ($rapport->languesProjets->projets_informatiques_existe ?? false) ? 'checked' : '' }}
                           onchange="autoSave('langues-projets'); toggleProjetDetails(this)"
                           class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span class="text-xs font-medium text-gray-700">Projets Informatiques</span>
                </label>
            </div>
            
            <div id="projet-details">
                <label class="block text-xs font-medium text-gray-700 mb-2">
                    Nom et détails des projets
                </label>
                <textarea name="projets_informatiques_nom" data-section="langues-projets" 
                          onchange="autoSave('langues-projets')" rows="3"
                          {{ ($rapport->languesProjets->projets_informatiques_existe ?? false) ? '' : 'disabled' }}
                          placeholder="Ex: Projet PAQUEB, Équipement USAID, Don de matériel par une ONG..."
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:cursor-not-allowed">{{ $rapport->languesProjets->projets_informatiques_nom ?? '' }}</textarea>
            </div>
        </div>

    </div>
</div>
