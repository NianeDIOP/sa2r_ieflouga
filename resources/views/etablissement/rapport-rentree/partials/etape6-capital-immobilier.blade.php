<!-- Capital Immobilier -->
<div id="capital-immobilier" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
    <h3 class="text-xs font-semibold text-gray-700 mb-3 flex items-center uppercase tracking-wide">
        <i class="fas fa-building text-gray-500 mr-2 text-xs"></i>
        Capital Immobilier - Infrastructures
    </h3>
    
    <p class="text-xs text-gray-500 mb-4 italic">
        <i class="fas fa-info-circle text-blue-500 mr-1"></i>
        État des bâtiments et infrastructures de l'établissement (Total et Bon état).
    </p>

    <form id="capital-immobilier-form" data-save-url="{{ route('etablissement.rapport-rentree.save-capital-immobilier', $rapport) }}">
        @csrf
        
        <!-- SALLES DE CLASSE -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chalkboard-teacher text-gray-500 mr-2 text-xs"></i>
                Salles de Classe
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Salles en dur -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Salles en dur</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="salles_dur_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->salles_dur_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="salles_dur_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->salles_dur_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Abris provisoires -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Abris provisoires</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="abris_provisoires_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->abris_provisoires_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="abris_provisoires_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->abris_provisoires_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BÂTIMENTS ADMINISTRATIFS -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-building text-gray-500 mr-2 text-xs"></i>
                Bâtiments Administratifs
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Bloc administratif -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Bloc administratif</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="bloc_admin_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->bloc_admin_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="bloc_admin_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->bloc_admin_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Magasin -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Magasin</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="magasin_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->magasin_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="magasin_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->magasin_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SALLES SPÉCIALISÉES -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-laptop text-gray-500 mr-2 text-xs"></i>
                Salles Spécialisées
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Salle informatique -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Salle informatique</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="salle_informatique_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->salle_informatique_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="salle_informatique_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->salle_informatique_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Bibliothèque -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Bibliothèque</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="salle_bibliotheque_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->salle_bibliotheque_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="salle_bibliotheque_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->salle_bibliotheque_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Cuisine -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Cuisine</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="cuisine_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->cuisine_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="cuisine_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->cuisine_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Réfectoire -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Réfectoire</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="refectoire_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->refectoire_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="refectoire_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->refectoire_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOILETTES -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-restroom text-gray-500 mr-2 text-xs"></i>
                Toilettes et Sanitaires
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Toilettes enseignants -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Toilettes enseignants</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="toilettes_enseignants_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_enseignants_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="toilettes_enseignants_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_enseignants_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Toilettes garçons -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Toilettes garçons</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="toilettes_garcons_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_garcons_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="toilettes_garcons_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_garcons_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Toilettes filles -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Toilettes filles</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="toilettes_filles_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_filles_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="toilettes_filles_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_filles_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Toilettes mixtes -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Toilettes mixtes</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="toilettes_mixtes_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_mixtes_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="toilettes_mixtes_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->toilettes_mixtes_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOGEMENTS -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-home text-gray-500 mr-2 text-xs"></i>
                Logements
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Logement directeur -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Logement directeur</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="logement_directeur_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->logement_directeur_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="logement_directeur_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->logement_directeur_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>

                <!-- Logement gardien -->
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Logement gardien</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="logement_gardien_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->logement_gardien_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="logement_gardien_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->logement_gardien_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AUTRES INFRASTRUCTURES -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50 mb-4">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-warehouse text-gray-500 mr-2 text-xs"></i>
                Autres Infrastructures
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-200 rounded-lg p-3 bg-white">
                    <h5 class="text-xs font-semibold text-gray-700 mb-2">Autres infrastructures</h5>
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Total</label>
                            <input type="number" 
                                   name="autres_infrastructures_total"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->autres_infrastructures_total ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">En bon état</label>
                            <input type="number" 
                                   name="autres_infrastructures_bon_etat"
                                   min="0"
                                   value="{{ $rapport->capitalImmobilier?->autres_infrastructures_bon_etat ?? '' }}"
                                   data-section="capital-immobilier"
                                   onchange="autoSave('capital-immobilier'); calculateCapitalImmobilierTotals()"
                                   oninput="calculateCapitalImmobilierTotals()"
                                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500"
                                   placeholder="Nombre">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTIQUES -->
        <div class="border border-emerald-200 rounded-lg p-4 bg-emerald-50">
            <h4 class="text-xs font-semibold text-emerald-700 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-gray-500 mr-2 text-xs"></i>
                Statistiques Infrastructure
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-infrastructures">
                        @php
                            $total = ($rapport->capitalImmobilier?->salles_dur_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->abris_provisoires_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->bloc_admin_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->magasin_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->salle_informatique_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->salle_bibliotheque_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->cuisine_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->refectoire_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_enseignants_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_garcons_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_filles_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_mixtes_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->logement_directeur_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->logement_gardien_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->autres_infrastructures_total ?? 0);
                            echo $total;
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">Total infrastructures</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="total-bon-etat-infra">
                        @php
                            $totalBonEtat = ($rapport->capitalImmobilier?->salles_dur_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->abris_provisoires_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->bloc_admin_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->magasin_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->salle_informatique_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->salle_bibliotheque_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->cuisine_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->refectoire_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_enseignants_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_garcons_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_filles_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_mixtes_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->logement_directeur_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->logement_gardien_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->autres_infrastructures_bon_etat ?? 0);
                            echo $totalBonEtat;
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">En bon état</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="taux-bon-etat-infra">
                        @php
                            $total = ($rapport->capitalImmobilier?->salles_dur_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->abris_provisoires_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->bloc_admin_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->magasin_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->salle_informatique_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->salle_bibliotheque_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->cuisine_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->refectoire_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_enseignants_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_garcons_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_filles_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->toilettes_mixtes_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->logement_directeur_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->logement_gardien_total ?? 0) + 
                                    ($rapport->capitalImmobilier?->autres_infrastructures_total ?? 0);
                            $totalBonEtat = ($rapport->capitalImmobilier?->salles_dur_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->abris_provisoires_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->bloc_admin_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->magasin_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->salle_informatique_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->salle_bibliotheque_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->cuisine_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->refectoire_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_enseignants_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_garcons_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_filles_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->toilettes_mixtes_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->logement_directeur_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->logement_gardien_bon_etat ?? 0) + 
                                           ($rapport->capitalImmobilier?->autres_infrastructures_bon_etat ?? 0);
                            $taux = $total > 0 ? round(($totalBonEtat / $total) * 100) : 0;
                            echo $taux . '%';
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">Taux bon état</div>
                </div>
                <div class="bg-white rounded-lg p-3 border border-emerald-200">
                    <div class="text-lg font-bold text-emerald-600" id="types-infra">
                        @php
                            $types = 0;
                            if (($rapport->capitalImmobilier?->salles_dur_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->abris_provisoires_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->bloc_admin_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->magasin_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->salle_informatique_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->salle_bibliotheque_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->cuisine_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->refectoire_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->toilettes_enseignants_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->toilettes_garcons_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->toilettes_filles_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->toilettes_mixtes_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->logement_directeur_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->logement_gardien_total ?? 0) > 0) $types++;
                            if (($rapport->capitalImmobilier?->autres_infrastructures_total ?? 0) > 0) $types++;
                            echo $types;
                        @endphp
                    </div>
                    <div class="text-xs text-gray-600">Types d'infrastructures</div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
window.initCapitalImmobilier = function() {
    console.log('🏢 Initialisation Capital Immobilier - DÉSACTIVÉE pour affichage PHP');
};

function calculateCapitalImmobilierTotals() {
    console.log('=== CALCUL TOTAUX CAPITAL IMMOBILIER ===');
    
    // Vérifier si les éléments statistiques existent
    const elemTotal = document.getElementById('total-infrastructures');
    if (!elemTotal) {
        console.log('⚠️ Éléments statistiques pas encore dans le DOM, annulation calcul');
        return;
    }
    const elemBonEtat = document.getElementById('total-bon-etat-infra');
    const elemTaux = document.getElementById('taux-bon-etat-infra');
    const elemTypes = document.getElementById('types-infra');
    
    // Récupérer toutes les valeurs
    const sallesDurTotal = parseInt(document.querySelector('[name="salles_dur_total"]')?.value) || 0;
    const sallesDurBonEtat = parseInt(document.querySelector('[name="salles_dur_bon_etat"]')?.value) || 0;
    const abrisTotal = parseInt(document.querySelector('[name="abris_provisoires_total"]')?.value) || 0;
    const abrisBonEtat = parseInt(document.querySelector('[name="abris_provisoires_bon_etat"]')?.value) || 0;
    const blocAdminTotal = parseInt(document.querySelector('[name="bloc_admin_total"]')?.value) || 0;
    const blocAdminBonEtat = parseInt(document.querySelector('[name="bloc_admin_bon_etat"]')?.value) || 0;
    const magasinTotal = parseInt(document.querySelector('[name="magasin_total"]')?.value) || 0;
    const magasinBonEtat = parseInt(document.querySelector('[name="magasin_bon_etat"]')?.value) || 0;
    const salleInfoTotal = parseInt(document.querySelector('[name="salle_informatique_total"]')?.value) || 0;
    const salleInfoBonEtat = parseInt(document.querySelector('[name="salle_informatique_bon_etat"]')?.value) || 0;
    const salleBiblioTotal = parseInt(document.querySelector('[name="salle_bibliotheque_total"]')?.value) || 0;
    const salleBiblioBonEtat = parseInt(document.querySelector('[name="salle_bibliotheque_bon_etat"]')?.value) || 0;
    const cuisineTotal = parseInt(document.querySelector('[name="cuisine_total"]')?.value) || 0;
    const cuisineBonEtat = parseInt(document.querySelector('[name="cuisine_bon_etat"]')?.value) || 0;
    const refectoireTotal = parseInt(document.querySelector('[name="refectoire_total"]')?.value) || 0;
    const refectoireBonEtat = parseInt(document.querySelector('[name="refectoire_bon_etat"]')?.value) || 0;
    const toilettesEnsTotal = parseInt(document.querySelector('[name="toilettes_enseignants_total"]')?.value) || 0;
    const toilettesEnsBonEtat = parseInt(document.querySelector('[name="toilettes_enseignants_bon_etat"]')?.value) || 0;
    const toilettesGarconsTotal = parseInt(document.querySelector('[name="toilettes_garcons_total"]')?.value) || 0;
    const toilettesGarconsBonEtat = parseInt(document.querySelector('[name="toilettes_garcons_bon_etat"]')?.value) || 0;
    const toilettesFillesTotal = parseInt(document.querySelector('[name="toilettes_filles_total"]')?.value) || 0;
    const toilettesFillesBonEtat = parseInt(document.querySelector('[name="toilettes_filles_bon_etat"]')?.value) || 0;
    const toilettesMixtesTotal = parseInt(document.querySelector('[name="toilettes_mixtes_total"]')?.value) || 0;
    const toilettesMixtesBonEtat = parseInt(document.querySelector('[name="toilettes_mixtes_bon_etat"]')?.value) || 0;
    const logementDirTotal = parseInt(document.querySelector('[name="logement_directeur_total"]')?.value) || 0;
    const logementDirBonEtat = parseInt(document.querySelector('[name="logement_directeur_bon_etat"]')?.value) || 0;
    const logementGardienTotal = parseInt(document.querySelector('[name="logement_gardien_total"]')?.value) || 0;
    const logementGardienBonEtat = parseInt(document.querySelector('[name="logement_gardien_bon_etat"]')?.value) || 0;
    const autresTotal = parseInt(document.querySelector('[name="autres_infrastructures_total"]')?.value) || 0;
    const autresBonEtat = parseInt(document.querySelector('[name="autres_infrastructures_bon_etat"]')?.value) || 0;
    
    const totalGeneral = sallesDurTotal + abrisTotal + blocAdminTotal + magasinTotal + salleInfoTotal + 
                        salleBiblioTotal + cuisineTotal + refectoireTotal + toilettesEnsTotal + 
                        toilettesGarconsTotal + toilettesFillesTotal + toilettesMixtesTotal + 
                        logementDirTotal + logementGardienTotal + autresTotal;
    
    const totalBonEtat = sallesDurBonEtat + abrisBonEtat + blocAdminBonEtat + magasinBonEtat + salleInfoBonEtat + 
                        salleBiblioBonEtat + cuisineBonEtat + refectoireBonEtat + toilettesEnsBonEtat + 
                        toilettesGarconsBonEtat + toilettesFillesBonEtat + toilettesMixtesBonEtat + 
                        logementDirBonEtat + logementGardienBonEtat + autresBonEtat;
    
    const tauxBonEtat = totalGeneral > 0 ? Math.round((totalBonEtat / totalGeneral) * 100) : 0;
    
    let types = 0;
    if (sallesDurTotal > 0) types++;
    if (abrisTotal > 0) types++;
    if (blocAdminTotal > 0) types++;
    if (magasinTotal > 0) types++;
    if (salleInfoTotal > 0) types++;
    if (salleBiblioTotal > 0) types++;
    if (cuisineTotal > 0) types++;
    if (refectoireTotal > 0) types++;
    if (toilettesEnsTotal > 0) types++;
    if (toilettesGarconsTotal > 0) types++;
    if (toilettesFillesTotal > 0) types++;
    if (toilettesMixtesTotal > 0) types++;
    if (logementDirTotal > 0) types++;
    if (logementGardienTotal > 0) types++;
    if (autresTotal > 0) types++;
    
    console.log('Résultats:', {totalGeneral, totalBonEtat, tauxBonEtat, types});
    
    // Mise à jour des affichages (déjà vérifié en début de fonction)
    elemTotal.textContent = totalGeneral;
    elemBonEtat.textContent = totalBonEtat;
    elemTaux.textContent = tauxBonEtat + '%';
    elemTypes.textContent = types;
    
    console.log('✅ Totaux capital immobilier calculés!');
}
</script>
