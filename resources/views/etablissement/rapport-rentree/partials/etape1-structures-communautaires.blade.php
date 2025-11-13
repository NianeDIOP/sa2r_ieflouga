<!-- Structures Communautaires -->
<div id="structures" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-users text-gray-500 mr-2 text-xs"></i>
        Structures Communautaires
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Si une structure n'existe pas, laissez la case décochée. Les champs seront automatiquement marqués comme "non disponible".
    </p>
    
    <div class="space-y-4">
        
        <!-- CGE (Comité de Gestion de l'Établissement) -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <h4 class="text-xs font-semibold text-gray-700 mb-2">Comité de Gestion de l'Établissement (CGE)</h4>
            <div class="space-y-3">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="cge_existe" data-section="structures-communautaires" 
                           {{ ($rapport->structuresCommunautaires->cge_existe ?? false) ? 'checked' : '' }}
                           onchange="autoSave('structures-communautaires')"
                           class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span class="text-xs text-gray-700">Existe</span>
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Hommes</label>
                        <input type="number" name="cge_hommes" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->cge_hommes ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Femmes</label>
                        <input type="number" name="cge_femmes" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->cge_femmes ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nom Président(e)</label>
                        <input type="text" name="cge_president_nom" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->cge_president_nom ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Nom complet"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Contact Président(e)</label>
                        <input type="text" name="cge_president_contact" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->cge_president_contact ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Téléphone"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nom Trésorier(ère)</label>
                        <input type="text" name="cge_tresorier_nom" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->cge_tresorier_nom ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Nom complet"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Contact Trésorier(ère)</label>
                        <input type="text" name="cge_tresorier_contact" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->cge_tresorier_contact ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Téléphone"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- G.Scol (Gouvernement Scolaire) -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <h4 class="text-xs font-semibold text-gray-700 mb-2">Gouvernement Scolaire (G.Scol)</h4>
            <div class="space-y-3">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="gscol_existe" data-section="structures-communautaires" 
                           {{ ($rapport->structuresCommunautaires->gscol_existe ?? false) ? 'checked' : '' }}
                           onchange="autoSave('structures-communautaires')"
                           class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span class="text-xs text-gray-700">Existe</span>
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Garçons</label>
                        <input type="number" name="gscol_garcons" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->gscol_garcons ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Filles</label>
                        <input type="number" name="gscol_filles" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->gscol_filles ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nom Président(e)</label>
                        <input type="text" name="gscol_president_nom" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->gscol_president_nom ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Nom complet"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Contact Président(e)</label>
                        <input type="text" name="gscol_president_contact" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->gscol_president_contact ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Téléphone"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- APE (Association des Parents d'Élèves) -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <h4 class="text-xs font-semibold text-gray-700 mb-2">Association des Parents d'Élèves (APE)</h4>
            <div class="space-y-3">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="ape_existe" data-section="structures-communautaires" 
                           {{ ($rapport->structuresCommunautaires->ape_existe ?? false) ? 'checked' : '' }}
                           onchange="autoSave('structures-communautaires')"
                           class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span class="text-xs text-gray-700">Existe</span>
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Hommes</label>
                        <input type="number" name="ape_hommes" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ape_hommes ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Femmes</label>
                        <input type="number" name="ape_femmes" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ape_femmes ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nom Président(e)</label>
                        <input type="text" name="ape_president_nom" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ape_president_nom ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Nom complet"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Contact Président(e)</label>
                        <input type="text" name="ape_president_contact" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ape_president_contact ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Téléphone"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>

        <!-- AME (Association des Mères d'Élèves) -->
        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
            <h4 class="text-xs font-semibold text-gray-700 mb-2">Association des Mères d'Élèves (AME)</h4>
            <div class="space-y-3">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="ame_existe" data-section="structures-communautaires" 
                           {{ ($rapport->structuresCommunautaires->ame_existe ?? false) ? 'checked' : '' }}
                           onchange="autoSave('structures-communautaires')"
                           class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    <span class="text-xs text-gray-700">Existe</span>
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nombre de Membres</label>
                        <input type="number" name="ame_nombre" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ame_nombre ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               min="0" placeholder="Nombre"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Nom Présidente</label>
                        <input type="text" name="ame_president_nom" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ame_president_nom ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Nom complet"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs text-gray-600 mb-1">Contact Présidente</label>
                        <input type="text" name="ame_president_contact" data-section="structures-communautaires" 
                               value="{{ $rapport->structuresCommunautaires->ame_president_contact ?? '' }}"
                               onchange="autoSave('structures-communautaires')"
                               placeholder="Téléphone"
                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
