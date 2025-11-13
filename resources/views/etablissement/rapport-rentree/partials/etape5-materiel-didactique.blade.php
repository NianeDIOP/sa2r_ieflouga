<!-- Matériel Didactique -->
<div id="materiel-didactique" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-graduation-cap text-gray-500 mr-2 text-xs"></i>
        Matériel Didactique
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        Inventaire du matériel pédagogique et des outils d'apprentissage.
    </p>

    <form id="materiel-didactique-form" data-save-url="{{ route('etablissement.rapport-rentree.save-materiel-didactique', $rapport) }}">
        @csrf
        
        <!-- Dictionnaires -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-book text-gray-500 mr-2 text-xs"></i>
                Dictionnaires
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Dictionnaires Français -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Dictionnaires Français</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="dico_francais_total" 
                                   name="dico_francais_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->dico_francais_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   oninput="calculateMaterielDidactiqueStats()"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="Ex: 15">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="dico_francais_bon_etat" 
                                   name="dico_francais_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->dico_francais_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500"
                                   placeholder="Ex: 12">
                        </div>
                    </div>
                </div>

                <!-- Dictionnaires Arabe -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Dictionnaires Arabe</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="dico_arabe_total" 
                                   name="dico_arabe_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->dico_arabe_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="dico_arabe_bon_etat" 
                                   name="dico_arabe_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->dico_arabe_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Autres Dictionnaires -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Autres Dictionnaires</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="dico_autre_total" 
                                   name="dico_autre_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->dico_autre_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="dico_autre_bon_etat" 
                                   name="dico_autre_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->dico_autre_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matériel de Géométrie -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-compass text-gray-500 mr-2 text-xs"></i>
                Matériel de Géométrie
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <!-- Règles -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Règles</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="regle_plate_total" 
                                   name="regle_plate_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->regle_plate_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="regle_plate_bon_etat" 
                                   name="regle_plate_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->regle_plate_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Équerres -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Équerres</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="equerre_total" 
                                   name="equerre_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->equerre_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="equerre_bon_etat" 
                                   name="equerre_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->equerre_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Compas -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Compas</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="compas_total" 
                                   name="compas_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->compas_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="compas_bon_etat" 
                                   name="compas_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->compas_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Rapporteurs -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Rapporteurs</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="rapporteur_total" 
                                   name="rapporteur_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->rapporteur_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="rapporteur_bon_etat" 
                                   name="rapporteur_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->rapporteur_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matériel de Mesure -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-ruler text-gray-500 mr-2 text-xs"></i>
                Matériel de Mesure
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Décamètre -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Décamètre</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="decametre_total" 
                                   name="decametre_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->decametre_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="decametre_bon_etat" 
                                   name="decametre_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->decametre_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Chaîne d'arpenteur -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Chaîne d'arpenteur</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="chaine_arpenteur_total" 
                                   name="chaine_arpenteur_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->chaine_arpenteur_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="chaine_arpenteur_bon_etat" 
                                   name="chaine_arpenteur_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->chaine_arpenteur_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Boussole -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Boussole</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="boussole_total" 
                                   name="boussole_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->boussole_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="boussole_bon_etat" 
                                   name="boussole_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->boussole_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Thermomètre -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Thermomètre</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="thermometre_total" 
                                   name="thermometre_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->thermometre_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="thermometre_bon_etat" 
                                   name="thermometre_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->thermometre_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Kit capacité -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Kit capacité</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="kit_capacite_total" 
                                   name="kit_capacite_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->kit_capacite_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="kit_capacite_bon_etat" 
                                   name="kit_capacite_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->kit_capacite_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Balance -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Balance</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="balance_total" 
                                   name="balance_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->balance_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="balance_bon_etat" 
                                   name="balance_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->balance_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matériel Pédagogique -->
        <div class="mb-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chalkboard-teacher text-gray-500 mr-2 text-xs"></i>
                Supports Pédagogiques
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <!-- Globe -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Globe terrestre</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="globe_total" 
                                   name="globe_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->globe_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="globe_bon_etat" 
                                   name="globe_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->globe_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Cartes murales -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Cartes murales</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="cartes_murales_total" 
                                   name="cartes_murales_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->cartes_murales_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="cartes_murales_bon_etat" 
                                   name="cartes_murales_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->cartes_murales_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Planches illustrées -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Planches illustrées</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="planches_illustrees_total" 
                                   name="planches_illustrees_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->planches_illustrees_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="planches_illustrees_bon_etat" 
                                   name="planches_illustrees_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->planches_illustrees_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Kit scientifique -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Kit scientifique</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="kit_materiel_scientifique_total" 
                                   name="kit_materiel_scientifique_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->kit_materiel_scientifique_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="kit_materiel_scientifique_bon_etat" 
                                   name="kit_materiel_scientifique_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->kit_materiel_scientifique_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Autres matériels -->
                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Autres matériels</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   id="autres_materiel_total" 
                                   name="autres_materiel_total"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->autres_materiel_total }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Bon état</label>
                            <input type="number" 
                                   id="autres_materiel_bon_etat" 
                                   name="autres_materiel_bon_etat"
                                   min="0"
                                   value="{{ $rapport->materielDidactique?->autres_materiel_bon_etat }}"
                                   data-section="materiel-didactique"
                                   onchange="autoSave('materiel-didactique')"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-emerald-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        @php
            $totalMateriel = 0;
            $totalBonEtat = 0;
            
            if ($rapport->materielDidactique) {
                $champs = [
                    'dico_francais', 'dico_arabe', 'dico_autre',
                    'regle_plate', 'equerre', 'compas', 'rapporteur',
                    'decametre', 'chaine_arpenteur', 'boussole',
                    'thermometre', 'kit_capacite', 'balance',
                    'globe', 'cartes_murales', 'planches_illustrees',
                    'kit_materiel_scientifique', 'autres_materiel'
                ];
                
                foreach ($champs as $champ) {
                    $totalMateriel += ($rapport->materielDidactique->{$champ . '_total'} ?? 0);
                    $totalBonEtat += ($rapport->materielDidactique->{$champ . '_bon_etat'} ?? 0);
                }
            }
            
            $tauxConservation = $totalMateriel > 0 ? round(($totalBonEtat / $totalMateriel) * 100, 1) : 0;
        @endphp

        <div class="bg-gradient-to-r from-emerald-50 to-blue-50 rounded-lg p-4 border border-emerald-200 mt-6">
            <h4 class="text-xs font-semibold text-gray-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-emerald-600 mr-2"></i>
                Synthèse du Matériel Didactique
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Total matériel</div>
                    <div id="total-materiel-didactique" class="text-lg font-bold text-gray-700">{{ number_format($totalMateriel) }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">En bon état</div>
                    <div id="bon-etat-materiel-didactique" class="text-lg font-bold text-emerald-600">{{ number_format($totalBonEtat) }}</div>
                </div>
                <div class="bg-white rounded-lg p-3 text-center">
                    <div class="text-xs text-gray-500 mb-1">Taux de conservation</div>
                    <div id="taux-conservation-materiel-didactique" class="text-lg font-bold {{ $tauxConservation >= 70 ? 'text-emerald-600' : ($tauxConservation >= 50 ? 'text-yellow-600' : 'text-red-600') }}">{{ $tauxConservation }}%</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Initialisation des statistiques Matériel Didactique
window.initMaterielDidactique = function() {
    console.log('✅ Init Matériel Didactique');
    
    // Calculer les stats initiales
    calculateMaterielDidactiqueStats();
    
    // Écouter tous les inputs pour recalculer en temps réel
    const form = document.getElementById('materiel-didactique-form');
    if (form) {
        const inputs = form.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', calculateMaterielDidactiqueStats);
        });
    }
};

function calculateMaterielDidactiqueStats() {
    const champs = [
        'dico_francais', 'dico_arabe', 'dico_autre',
        'regle_plate', 'equerre', 'compas', 'rapporteur',
        'decametre', 'chaine_arpenteur', 'boussole',
        'thermometre', 'kit_capacite', 'balance',
        'globe', 'cartes_murales', 'planches_illustrees',
        'kit_materiel_scientifique', 'autres_materiel'
    ];
    
    let totalMateriel = 0;
    let totalBonEtat = 0;
    
    champs.forEach(champ => {
        const totalInput = document.getElementById(champ + '_total');
        const bonEtatInput = document.getElementById(champ + '_bon_etat');
        
        if (totalInput) totalMateriel += parseInt(totalInput.value || 0);
        if (bonEtatInput) totalBonEtat += parseInt(bonEtatInput.value || 0);
    });
    
    const tauxConservation = totalMateriel > 0 ? Math.round((totalBonEtat / totalMateriel) * 100 * 10) / 10 : 0;
    
    const totalElement = document.getElementById('total-materiel-didactique');
    const bonEtatElement = document.getElementById('bon-etat-materiel-didactique');
    const tauxElement = document.getElementById('taux-conservation-materiel-didactique');
    
    if (totalElement) totalElement.textContent = totalMateriel.toLocaleString('fr-FR');
    if (bonEtatElement) bonEtatElement.textContent = totalBonEtat.toLocaleString('fr-FR');
    if (tauxElement) {
        tauxElement.textContent = tauxConservation + '%';
        // Couleur dynamique
        tauxElement.className = 'text-lg font-bold ' + 
            (tauxConservation >= 70 ? 'text-emerald-600' : 
             tauxConservation >= 50 ? 'text-yellow-600' : 'text-red-600');
    }
}
</script>
