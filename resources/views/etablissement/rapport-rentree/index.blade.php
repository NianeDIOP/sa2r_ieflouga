@extends('layouts.etablissement')

@section('title', 'Rapport de Rentrée ' . ($rapport->annee_scolaire ?? ''))

@section('content')
<div class="flex h-[calc(100vh-3.5rem)] bg-gray-50">
    
    <!-- SIDEBAR OPTIMISÉE -->
    <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 sticky top-0 h-screen overflow-y-auto">
        <div class="px-3 py-1.5 border-b bg-blue-900 text-center">
            <h3 class="text-sm font-semibold text-white mb-0.5">Rapport de Rentrée {{ $rapport->annee_scolaire }}</h3>
            <p class="text-xs text-blue-100 mb-1">{{ $etablissement->etablissement }}</p>
            <div class="inline-block px-2 py-0.5 bg-{{ $rapport->statut === 'brouillon' ? 'yellow' : ($rapport->statut === 'soumis' ? 'blue' : 'green') }}-50 rounded text-xs text-{{ $rapport->statut === 'brouillon' ? 'yellow' : ($rapport->statut === 'soumis' ? 'blue' : 'green') }}-700 font-medium">
                {{ strtoupper($rapport->statut) }}
            </div>
        </div>

        <nav class="p-2 space-y-1.5">
            <!-- ÉTAPE 1 - ACTIVE -->
            <div>
                <button id="btn-etape1" onclick="toggleStep(1)" class="w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm">
                    <i class="fas fa-info-circle mr-2.5 text-sm"></i>
                    <span class="flex-1 text-left font-medium">Infos Générales</span>
                    <i class="fas fa-chevron-down text-xs" id="icon-step-1"></i>
                </button>
                <div id="substeps-1" class="mt-1 ml-4 pl-3 border-l-2 border-emerald-200 space-y-0.5">
                    <a href="#info-directeur" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-user mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Info Directeur</span>
                    </a>
                    <a href="#infrastructures" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-building mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Infrastructures</span>
                    </a>
                    <a href="#structures" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-users mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Structures Comm.</span>
                    </a>
                    <a href="#langues" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-language mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Langues & Projets</span>
                    </a>
                    <a href="#finances" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-coins mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Ressources Finance.</span>
                    </a>
                </div>
            </div>

            <!-- ÉTAPE 2 - EFFECTIFS -->
            <div>
                <button id="btn-etape2" onclick="toggleStep(2)" class="w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-users-cog mr-2.5 text-sm"></i>
                    <span class="flex-1 text-left font-medium">Effectifs</span>
                    <i class="fas fa-chevron-down text-xs" id="icon-step-2"></i>
                </button>
                <div id="substeps-2" class="mt-1 ml-4 pl-3 border-l-2 border-emerald-200 space-y-0.5 hidden">
                    <a href="#nb-classes" onclick="switchToEtape(2)" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-door-open mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Nombre Classes</span>
                    </a>
                    <a href="#effectifs-totaux" onclick="switchToEtape(2)" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-users mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Effectifs Totaux</span>
                    </a>
                    <a href="#redoublants" onclick="switchToEtape(2)" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-redo mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Redoublants</span>
                    </a>
                    <a href="#abandons" onclick="switchToEtape(2)" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-user-times mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Abandons</span>
                    </a>
                    <a href="#handicaps" onclick="switchToEtape(2)" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-wheelchair mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Handicaps</span>
                    </a>
                    <a href="#situations-speciales" onclick="switchToEtape(2)" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-exclamation-triangle mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Situations Spéc.</span>
                    </a>
                </div>
            </div>

            <!-- ÉTAPE 3 - EXAMENS ET RECRUTEMENT CI -->
            <div>
                <button id="btn-etape3" onclick="toggleStep(3)" class="w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-graduation-cap mr-2.5 text-sm"></i>
                    <span class="flex-1 text-left font-medium">Examens et Recrutement CI</span>
                    <i class="fas fa-chevron-down text-xs" id="icon-step-3"></i>
                </button>
                <div id="substeps-3" class="mt-1 ml-4 pl-3 border-l-2 border-emerald-200 space-y-0.5 hidden">
                    <a href="#cmg" onclick="switchToEtape(3, 'cmg')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-users mr-1.5 text-gray-500"></i>
                        <span class="flex-1">CMG</span>
                    </a>
                    <a href="#cfee" onclick="switchToEtape(3, 'cfee')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-graduation-cap mr-1.5 text-gray-500"></i>
                        <span class="flex-1">CFEE</span>
                    </a>
                    <a href="#entree-sixieme" onclick="switchToEtape(3, 'entree-sixieme')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-door-open mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Entrée Sixième</span>
                    </a>
                    <a href="#recrutement-ci" onclick="switchToEtape(3, 'recrutement-ci')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-user-plus mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Recrutement CI</span>
                    </a>
                </div>
            </div>

            <!-- ÉTAPE 4 - PERSONNEL -->
            <div>
                <button id="btn-etape4" onclick="toggleStep(4)" class="w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                    <i class="fas fa-chalkboard-teacher mr-2.5 text-sm"></i>
                    <span class="flex-1 text-left font-medium">Personnel</span>
                    <i class="fas fa-chevron-down text-xs" id="icon-step-4"></i>
                </button>
                <div id="substeps-4" class="hidden mt-1 ml-4 pl-3 border-l-2 border-emerald-200 space-y-0.5">
                    <a href="#repartition-specialite" onclick="switchToEtape(4, 'repartition-specialite')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-users mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Par Spécialité</span>
                        <i id="check-repartition-specialite" class="fas fa-check-circle text-emerald-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#repartition-corps" onclick="switchToEtape(4, 'repartition-corps')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-graduation-cap mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Par Corps</span>
                        <i id="check-repartition-corps" class="fas fa-check-circle text-emerald-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#repartition-diplomes" onclick="switchToEtape(4, 'repartition-diplomes')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-certificate mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Par Diplômes</span>
                        <i id="check-repartition-diplomes" class="fas fa-check-circle text-emerald-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#competences-tic" onclick="switchToEtape(4, 'competences-tic')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-laptop mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Compétences TIC</span>
                        <i id="check-competences-tic" class="fas fa-check-circle text-emerald-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#statistiques-personnel" onclick="switchToEtape(4, 'statistiques-personnel')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-chart-bar mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Statistiques</span>
                        <i id="check-statistiques-personnel" class="fas fa-check-circle text-emerald-500 text-xs hidden ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- ÉTAPE 5 - MATÉRIEL PÉDAGOGIQUE -->
            <div>
                <button id="btn-etape5" onclick="toggleStep(5)" class="w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                    <i class="fas fa-box mr-2.5 text-sm"></i>
                    <span class="flex-1 text-left font-medium">Matériel Pédagogique</span>
                    <i class="fas fa-chevron-down text-xs" id="icon-step-5"></i>
                </button>
                <div id="substeps-5" class="hidden mt-1 ml-4 pl-3 border-l-2 border-emerald-200 space-y-0.5">
                    <a href="#manuels-eleves" onclick="switchToEtape(5, 'manuels-eleves')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-book-open mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Manuels Élèves</span>
                        <i id="check-manuels-eleves" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#manuels-maitre" onclick="switchToEtape(5, 'manuels-maitre')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-user-tie mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Manuels Maître</span>
                        <i id="check-manuels-maitre" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#dictionnaires" onclick="switchToEtape(5, 'dictionnaires')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-book mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Dictionnaires</span>
                        <i id="check-dictionnaires" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#materiel-didactique" onclick="switchToEtape(5, 'materiel-didactique')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-graduation-cap mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Matériel Didactique</span>
                        <i id="check-materiel-didactique" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- ÉTAPE 6 - INFRASTRUCTURE & ÉQUIPEMENTS -->
            <div>
                <button id="btn-etape6" onclick="toggleStep(6)" class="w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-emerald-50 hover:text-emerald-600">
                    <i class="fas fa-building mr-2.5 text-sm"></i>
                    <span class="flex-1 text-left font-medium">Infrastructure & Équipements</span>
                    <i class="fas fa-chevron-down text-xs" id="icon-step-6"></i>
                </button>
                <div id="substeps-6" class="hidden mt-1 ml-4 pl-3 border-l-2 border-emerald-200 space-y-0.5">
                    <a href="#capital-immobilier" onclick="switchToEtape(6, 'capital-immobilier')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-building-columns mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Capital Immobilier</span>
                        <i id="check-capital-immobilier" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#capital-mobilier" onclick="switchToEtape(6, 'capital-mobilier')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-chair mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Capital Mobilier</span>
                        <i id="check-capital-mobilier" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#equipements-informatiques" onclick="switchToEtape(6, 'equipements-informatiques')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-laptop mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Équipements Info</span>
                        <i id="check-equipements-informatiques" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                </div>
            </div>
        </nav>
        
        <div class="p-4 border-t mt-4">
            <button disabled class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-xs font-medium rounded cursor-not-allowed transition">
                <i class="fas fa-paper-plane mr-2"></i>Soumettre
            </button>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main id="main-content" class="flex-1 overflow-y-auto">
        <div class="max-w-5xl mx-auto px-6 py-2">
            
            <!-- Alert Sauvegarde -->
            <div id="alert" class="hidden mb-4"></div>

            <!-- FORMULAIRE ÉTAPE 1 -->
            <form id="rapport-form">
                @csrf
                <input type="hidden" id="rapport-id" value="{{ $rapport->id }}">

                <div id="etape1">
                    @include('etablissement.rapport-rentree.partials.etape1-info-directeur')
                    @include('etablissement.rapport-rentree.partials.etape1-infrastructures')
                    @include('etablissement.rapport-rentree.partials.etape1-structures-communautaires')
                    @include('etablissement.rapport-rentree.partials.etape1-langues-projets')
                    @include('etablissement.rapport-rentree.partials.etape1-ressources-financieres')
                </div>
                
                <!-- ÉTAPE 2 : EFFECTIFS -->
                @include('etablissement.rapport-rentree.partials.etape2-effectifs')

                <!-- ÉTAPE 3 : EXAMENS -->
                @include('etablissement.rapport-rentree.partials.etape3-examens')

                <!-- ÉTAPE 4 : PERSONNEL -->
                @include('etablissement.rapport-rentree.partials.etape4-personnel')

                <!-- ÉTAPE 5 : MATÉRIEL PÉDAGOGIQUE -->
                @include('etablissement.rapport-rentree.partials.etape5-pedagogique-wrapper')

                <!-- ÉTAPE 6 : INFRASTRUCTURE & ÉQUIPEMENTS -->
                @include('etablissement.rapport-rentree.partials.etape6-infrastructure-wrapper')

            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
// Toggle step substeps
function toggleStep(step) {
    const substeps = document.getElementById(`substeps-${step}`);
    const icon = document.getElementById(`icon-step-${step}`);
    
    // Gérer l'état initial : substeps-1 visible, substeps-2 hidden, substeps-3 hidden
    if (step === 1) {
        // Pour étape 1, simple toggle
        substeps.classList.toggle('hidden');
        if (!substeps.classList.contains('hidden')) {
            switchToEtape(1, 'info-directeur');
        }
    } else if (step === 2) {
        // Pour étape 2, simple toggle 
        substeps.classList.toggle('hidden');
        if (!substeps.classList.contains('hidden')) {
            switchToEtape(2, 'nombre-classes');
        }
    } else if (step === 3) {
        // Pour étape 3, simple toggle
        substeps.classList.toggle('hidden');
        if (!substeps.classList.contains('hidden')) {
            switchToEtape(3, 'cmg');
        }
    } else if (step === 4) {
        // Pour étape 4, simple toggle
        substeps.classList.toggle('hidden');
        if (!substeps.classList.contains('hidden')) {
            switchToEtape(4, 'repartition-specialite');
        }
    } else if (step === 5) {
        // Pour étape 5, simple toggle
        substeps.classList.toggle('hidden');
        if (!substeps.classList.contains('hidden')) {
            switchToEtape(5, 'materiel-didactique');
        }
    } else if (step === 6) {
        // Pour étape 6, simple toggle
        substeps.classList.toggle('hidden');
        if (!substeps.classList.contains('hidden')) {
            switchToEtape(6, 'capital-immobilier');
        }
    }
    
    // Toggle icône
    if (icon) {
        // Toutes les étapes utilisent la même logique : chevron-down ↔ chevron-up
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    }
}

// Auto-save avec debounce
let saveTimeout;
let invalidFieldBlocked = null; // 🔒 Champ invalide verrouillé

function autoSave(section) {
    console.log('🔍 autoSave() appelé pour:', section);
    
    // 🚫 SI UN CHAMP EST BLOQUÉ, NE PAS SAUVEGARDER
    if (invalidFieldBlocked) {
        console.log('🚫 AutoSave BLOQUÉ - Champ invalide:', invalidFieldBlocked.name);
        invalidFieldBlocked.focus();
        invalidFieldBlocked.select();
        showValidationError(invalidFieldBlocked, '⚠️ Corrigez ce champ avant de continuer !');
        return false; // BLOQUER l'auto-save
    }
    
    console.log('✅ AutoSave autorisé');
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => saveSection(section), 2000); // 2 secondes au lieu de 1
}

// Fonction pour activer/désactiver les champs d'infrastructure
function toggleField(checkbox, fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.disabled = !checkbox.checked;
        if (!checkbox.checked) {
            field.value = '';
            // Supprimer les erreurs de validation
            const errorDiv = field.parentNode.querySelector('.error-message');
            if (errorDiv) {
                errorDiv.remove();
            }
            // Réinitialiser le statut "touché"
            field.removeAttribute('data-was-touched');
        } else {
            // Donner le focus au champ activé
            setTimeout(() => {
                field.focus();
            }, 100);
        }
        console.log(`🔧 Infrastructure ${fieldId}: ${checkbox.checked ? 'Activé' : 'Désactivé'}`);
    }
}

// Sauvegarder une section
async function saveSection(section) {
    const rapportId = document.getElementById('rapport-id').value;
    const formData = new FormData();
    
    // ✅ VALIDATION AVANT SAUVEGARDE
    if (section === 'cfee') {
        const isValid = validateCfeeCandidatsTotal() && 
                       validateCfeeCandidatsFilles() && 
                       validateCfeeAdmisTotal() && 
                       validateCfeeAdmisFilles();
        if (!isValid) {
            showSavingIndicator(section, 'error');
            console.log('❌ Validation CFEE échouée - sauvegarde annulée');
            return;
        }
    }
    
    if (section === 'entree-sixieme') {
        const isValid = validateSixiemeCandidatsTotal() && 
                       validateSixiemeCandidatsFilles() && 
                       validateSixiemeAdmisTotal() && 
                       validateSixiemeAdmisFilles();
        if (!isValid) {
            showSavingIndicator(section, 'error');
            console.log('❌ Validation Entrée Sixième échouée - sauvegarde annulée');
            return;
        }
    }
    
    // ÉTAPE 6 - Infrastructure
    if (section === 'capital-immobilier') {
        if (!validateCapitalImmobilier()) {
            showSavingIndicator(section, 'error');
            console.log('❌ Validation Capital Immobilier échouée - sauvegarde annulée');
            return;
        }
    }
    
    if (section === 'capital-mobilier') {
        if (!validateCapitalMobilier()) {
            showSavingIndicator(section, 'error');
            console.log('❌ Validation Capital Mobilier échouée - sauvegarde annulée');
            return;
        }
    }
    
    if (section === 'equipements-informatiques') {
        if (!validateEquipementsInformatiques()) {
            showSavingIndicator(section, 'error');
            console.log('❌ Validation Équipements Informatiques échouée - sauvegarde annulée');
            return;
        }
    }
    
    // Afficher indicateur de sauvegarde
    showSavingIndicator(section, 'saving');
    
    // Récupérer les champs de la section
    const inputs = document.querySelectorAll(`[data-section="${section}"]`);
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            formData.append(input.name, input.checked ? '1' : '0');
        } else if (input.type === 'number') {
            // Pour les champs number, convertir les valeurs vides en 0
            const value = input.value === '' ? '0' : input.value;
            formData.append(input.name, value);
        } else {
            // Pour les effectifs avec structure imbriquée effectifs[CI][nombre_classes]
            formData.append(input.name, input.value || '');
        }
    });

    try {
        // Récupérer l'URL depuis le data-save-url du formulaire si disponible
        const form = document.querySelector(`#${section}-form`);
        let url;
        
        if (form && form.dataset.saveUrl) {
            url = form.dataset.saveUrl;
        } else {
            // Fallback vers l'ancienne méthode
            let route = section;
            url = `/etablissement/rapport-rentree/${rapportId}/${route}`;
        }
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            },
            body: formData
        });

        const data = await response.json();
        
        // Afficher succès
        showSavingIndicator(section, 'saved');
        showAlert(data.message, 'success');
        
        // Vérifier complétion selon la section
        if (section === 'effectifs') {
            checkSectionCompletionEtape2();
        } else if (['cmg', 'cfee', 'entree-sixieme', 'recrutement-ci'].includes(section)) {
            checkSectionCompletionEtape3();
        } else if (['repartition-specialite', 'repartition-corps', 'repartition-diplomes', 'competences-tic', 'statistiques-personnel'].includes(section)) {
            checkSectionCompletionEtape4();
        } else if (['manuels-eleves', 'manuels-maitre', 'dictionnaires', 'materiel-didactique', 'geometrie'].includes(section)) {
            checkSectionCompletionEtape5();
        } else if (['capital-immobilier', 'capital-mobilier', 'equipements-informatiques'].includes(section)) {
            checkSectionCompletionEtape6();
        } else {
            checkSectionCompletion();
        }
    } catch (error) {
        showSavingIndicator(section, 'error');
        showAlert('Erreur lors de la sauvegarde', 'error');
    }
}

// Indicateur visuel de sauvegarde
function showSavingIndicator(section, status) {
    // Créer ou récupérer l'indicateur
    let indicator = document.getElementById('save-indicator');
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'save-indicator';
        indicator.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 9999; padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.15); transition: all 0.3s ease;';
        document.body.appendChild(indicator);
    }
    
    // Appliquer le style selon le statut
    if (status === 'saving') {
        indicator.style.backgroundColor = '#3b82f6';
        indicator.style.color = 'white';
        indicator.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sauvegarde...';
        indicator.style.display = 'block';
    } else if (status === 'saved') {
        indicator.style.backgroundColor = '#10b981';
        indicator.style.color = 'white';
        indicator.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Sauvegardé !';
        indicator.style.display = 'block';
        
        // Masquer après 2 secondes
        setTimeout(() => {
            indicator.style.opacity = '0';
            setTimeout(() => {
                indicator.style.display = 'none';
                indicator.style.opacity = '1';
            }, 300);
        }, 2000);
    } else if (status === 'error') {
        indicator.style.backgroundColor = '#ef4444';
        indicator.style.color = 'white';
        indicator.innerHTML = '<i class="fas fa-times-circle mr-2"></i>Erreur !';
        indicator.style.display = 'block';
        
        // Masquer après 3 secondes
        setTimeout(() => {
            indicator.style.opacity = '0';
            setTimeout(() => {
                indicator.style.display = 'none';
                indicator.style.opacity = '1';
            }, 300);
        }, 3000);
    }
}

// Afficher alert
function showAlert(message, type) {
    const alert = document.getElementById('alert');
    alert.className = `p-3 rounded-lg text-sm font-medium ${type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'}`;
    alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>${message}`;
    alert.classList.remove('hidden');
    setTimeout(() => alert.classList.add('hidden'), 3000);
}

// Smooth scroll - gérer uniquement dans le contenu principal, pas la sidebar
const mainContent = document.getElementById('main-content');
if (mainContent) {
    mainContent.addEventListener('click', function(e) {
        const anchor = e.target.closest('a[href^="#"]');
        if (anchor && !anchor.hasAttribute('onclick')) {
            e.preventDefault();
            const targetId = anchor.getAttribute('href').substring(1);
            const target = document.getElementById(targetId);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    });
}

// Scroll spy - Activer les sous-sections au scroll pour les deux étapes
const sectionsEtape1 = ['info-directeur', 'infrastructures', 'structures', 'langues', 'finances'];
const sectionsEtape2 = ['nb-classes', 'effectifs-totaux', 'redoublants', 'abandons', 'handicaps', 'situations-speciales'];
const sectionsEtape3 = ['cmg', 'cfee', 'entree-sixieme', 'recrutement-ci'];

if (mainContent) {
    mainContent.addEventListener('scroll', function() {
        // Déterminer quelle étape est active
        const etape1 = document.getElementById('etape1');
        const etape2 = document.getElementById('etape2');
        const etape3 = document.getElementById('etape3');
        const etape4 = document.getElementById('etape4');
        const etape5 = document.getElementById('etape5');
        const etape6 = document.getElementById('etape6');
        
        if (etape1 && !etape1.classList.contains('hidden')) {
            // Scroll spy pour étape 1
            handleScrollSpyEtape1();
        } else if (etape2 && !etape2.classList.contains('hidden')) {
            // Scroll spy pour étape 2
            handleScrollSpyEtape2();
        } else if (etape3 && !etape3.classList.contains('hidden')) {
            // Scroll spy pour étape 3
            handleScrollSpyEtape3();
        } else if (etape4 && !etape4.classList.contains('hidden')) {
            // Scroll spy pour étape 4
            handleScrollSpyEtape4();
        } else if (etape5 && !etape5.classList.contains('hidden')) {
            // Scroll spy pour étape 5
            handleScrollSpyEtape5();
        } else if (etape6 && !etape6.classList.contains('hidden')) {
            // Scroll spy pour étape 6
            handleScrollSpyEtape6();
        }
    });
}

function handleScrollSpyEtape1() {
    let currentSection = '';
    
    sectionsEtape1.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour les liens actifs de l'étape 1
    document.querySelectorAll('#substeps-1 a').forEach(link => {
        link.classList.remove('bg-emerald-100', 'text-emerald-700');
        if (!link.classList.contains('text-gray-700')) {
            link.classList.add('text-gray-700');
        }
    });

    if (currentSection) {
        const activeLink = document.querySelector(`#substeps-1 a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.remove('text-gray-700');
            activeLink.classList.add('bg-emerald-100', 'text-emerald-700');
        }
    }
}

function handleScrollSpyEtape2() {
    let currentSection = '';
    
    sectionsEtape2.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour les liens actifs de l'étape 2
    document.querySelectorAll('#substeps-2 a').forEach(link => {
        link.classList.remove('bg-emerald-100', 'text-emerald-700');
        if (!link.classList.contains('text-gray-700')) {
            link.classList.add('text-gray-700');
        }
    });

    if (currentSection) {
        const activeLink = document.querySelector(`#substeps-2 a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.remove('text-gray-700');
            activeLink.classList.add('bg-emerald-100', 'text-emerald-700');
        }
    }
}

function handleScrollSpyEtape3() {
    let currentSection = '';
    
    sectionsEtape3.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour les liens actifs de l'étape 3
    document.querySelectorAll('#substeps-3 a').forEach(link => {
        link.classList.remove('bg-emerald-100', 'text-emerald-700');
        if (!link.classList.contains('text-gray-700')) {
            link.classList.add('text-gray-700');
        }
    });

    if (currentSection) {
        const activeLink = document.querySelector(`#substeps-3 a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.remove('text-gray-700');
            activeLink.classList.add('bg-emerald-100', 'text-emerald-700');
        }
    }
}

function handleScrollSpyEtape4() {
    const sectionsEtape4 = ['repartition-specialite', 'repartition-corps', 'repartition-diplomes', 'competences-tic', 'statistiques-personnel'];
    let currentSection = '';
    
    sectionsEtape4.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour les liens actifs de l'étape 4
    document.querySelectorAll('#substeps-4 a').forEach(link => {
        link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
        if (!link.classList.contains('text-gray-700')) {
            link.classList.add('text-gray-700');
        }
    });

    if (currentSection) {
        const activeLink = document.querySelector(`#substeps-4 a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.remove('text-gray-700');
            activeLink.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
        }
    }
}

function handleScrollSpyEtape5() {
    const sectionsEtape5 = ['manuels-eleves', 'manuels-maitre', 'dictionnaires', 'materiel-didactique', 'geometrie', 'materiel'];
    let currentSection = '';
    
    sectionsEtape5.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour les liens actifs de l'étape 5
    document.querySelectorAll('#substeps-5 a').forEach(link => {
        link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
        if (!link.classList.contains('text-gray-700')) {
            link.classList.add('text-gray-700');
        }
    });

    if (currentSection) {
        const activeLink = document.querySelector(`#substeps-5 a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.remove('text-gray-700');
            activeLink.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
        }
    }
}

function handleScrollSpyEtape6() {
    const sectionsEtape6 = ['capital-immobilier', 'capital-mobilier', 'equipements-informatiques'];
    let currentSection = '';
    
    sectionsEtape6.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour les liens actifs de l'étape 6
    document.querySelectorAll('#substeps-6 a').forEach(link => {
        link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
        if (!link.classList.contains('text-gray-700')) {
            link.classList.add('text-gray-700');
        }
    });

    if (currentSection) {
        const activeLink = document.querySelector(`#substeps-6 a[href="#${currentSection}"]`);
        if (activeLink) {
            activeLink.classList.remove('text-gray-700');
            activeLink.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
        }
    }
}

// Vérifier la complétion des sections et afficher les checkmarks
function checkSectionCompletion() {
    sectionsEtape1.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        
        const inputs = section.querySelectorAll('input:not([type="hidden"]):not([type="checkbox"]), select, textarea');
        const checkboxes = section.querySelectorAll('input[type="checkbox"]');
        let filled = 0;
        let total = 0;
        
        // Compter les champs texte/select/textarea
        inputs.forEach(input => {
            if (input.disabled) return;
            total++;
            if (input.value && input.value.trim() !== '') {
                filled++;
            }
        });
        
        // Compter les checkboxes cochées
        checkboxes.forEach(checkbox => {
            total++;
            if (checkbox.checked) {
                filled++;
            }
        });
        
        const link = document.querySelector(`#substeps-1 a[href="#${sectionId}"]`);
        if (link) {
            // Supprimer les anciens indicateurs
            const oldIcon = link.querySelector('.completion-icon');
            if (oldIcon) oldIcon.remove();
            
            // Ajouter le nouvel indicateur si au moins un champ rempli
            if (total > 0 && filled > 0) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-check-circle text-emerald-500 text-xs completion-icon';
                icon.style.marginLeft = 'auto';
                link.appendChild(icon);
            }
        }
    });
}

// Vérifier la complétion des sections Étape 2 et afficher les checkmarks
function checkSectionCompletionEtape2() {
    sectionsEtape2.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        
        const inputs = section.querySelectorAll('input:not([type="hidden"]):not([type="readonly"]), select, textarea');
        let filled = 0;
        let total = 0;
        
        // Compter les champs non-readonly
        inputs.forEach(input => {
            if (input.disabled || input.readOnly) return;
            total++;
            if (input.value && input.value.trim() !== '' && input.value !== '0') {
                filled++;
            }
        });
        
        const link = document.querySelector(`#substeps-2 a[href="#${sectionId}"]`);
        if (link) {
            // Supprimer les anciens indicateurs
            const oldIcon = link.querySelector('.completion-icon');
            if (oldIcon) oldIcon.remove();
            
            // Ajouter le nouvel indicateur si au moins un champ rempli
            if (total > 0 && filled > 0) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-check-circle text-emerald-500 text-xs completion-icon';
                icon.style.marginLeft = 'auto';
                link.appendChild(icon);
            }
        }
    });
}

function checkSectionCompletionEtape3() {
    sectionsEtape3.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        
        const inputs = section.querySelectorAll('input:not([type="hidden"]):not([type="readonly"]), select, textarea');
        let filled = 0;
        let total = 0;
        
        // Compter les champs non-readonly
        inputs.forEach(input => {
            if (input.disabled || input.readOnly) return;
            total++;
            if (input.value && input.value.trim() !== '' && input.value !== '0') {
                filled++;
            }
        });
        
        const link = document.querySelector(`#substeps-3 a[href="#${sectionId}"]`);
        if (link) {
            // Supprimer les anciens indicateurs
            const oldIcon = link.querySelector('.completion-icon');
            if (oldIcon) oldIcon.remove();
            
            // Ajouter le nouvel indicateur si au moins un champ rempli
            if (total > 0 && filled > 0) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-check-circle text-emerald-500 text-xs completion-icon';
                icon.style.marginLeft = 'auto';
                link.appendChild(icon);
            }
        }
    });
}

// Fonction pour gérer l'affichage des champs dépendants des projets
function toggleProjetDetails(checkbox) {
    const textarea = document.querySelector('#projet-details textarea');
    if (checkbox.checked) {
        textarea.disabled = false;
    } else {
        textarea.disabled = true;
        textarea.value = ''; // Vider si on décoche
    }
}

// Fonction générique pour gérer les champs dépendants des checkboxes
document.addEventListener('DOMContentLoaded', function() {
    // =================== 🚫 VALIDATION GLOBALE : BLOQUER VALEURS NÉGATIVES ===================
    console.log('🚫 Initialisation validation globale - Blocage valeurs négatives');
    
    // Attacher un listener sur TOUS les inputs de type number dans le formulaire
    const allNumberInputs = document.querySelectorAll('input[type="number"]');
    console.log(`📊 ${allNumberInputs.length} champs numériques trouvés`);
    
    allNumberInputs.forEach(input => {
        // Validation au blur
        input.addEventListener('blur', function() {
            const value = parseInt(this.value);
            
            // Si valeur négative, afficher erreur
            if (!isNaN(value) && value < 0) {
                showValidationError(this, `⚠️ Valeur négative interdite`);
            }
        });
        
        // Bloquer la saisie de "-" au clavier (prévention)
        input.addEventListener('keydown', function(e) {
            // Empêcher la touche "-" (code 189 ou 109)
            if (e.key === '-' || e.keyCode === 189 || e.keyCode === 109) {
                e.preventDefault();
                return false;
            }
        });
        
        // Empêcher le paste de valeurs négatives
        input.addEventListener('paste', function(e) {
            setTimeout(() => {
                const value = parseInt(this.value);
                if (!isNaN(value) && value < 0) {
                    this.value = '';
                    showValidationError(this, `⚠️ Valeur négative interdite`);
                }
            }, 10);
        });
    });
    
    // =================== FIN VALIDATION GLOBALE ===================
    
    // Gérer automatiquement tous les champs avec préfixe de structure
    const structures = ['cge', 'gscol', 'ape', 'ame'];
    
    structures.forEach(prefix => {
        const checkbox = document.querySelector(`input[name="${prefix}_existe"]`);
        if (checkbox) {
            // Initialiser l'état au chargement
            toggleStructureFields(prefix, checkbox.checked);
            
            // Ajouter l'événement
            checkbox.addEventListener('change', function() {
                toggleStructureFields(prefix, this.checked);
            });
        }
    });
    
    // Gérer les ressources financières
    const ressources = ['subvention_etat', 'subvention_partenaires', 'subvention_collectivites', 'subvention_communaute', 'ressources_generees'];
    ressources.forEach(prefix => {
        const checkbox = document.querySelector(`input[name="${prefix}_existe"]`);
        if (checkbox) {
            const montantField = document.querySelector(`input[name="${prefix}_montant"]`);
            if (montantField) {
                // Initialiser
                montantField.disabled = !checkbox.checked;
                if (!checkbox.checked) montantField.value = '';
                
                // Événement
                checkbox.addEventListener('change', function() {
                    montantField.disabled = !this.checked;
                    if (!this.checked) montantField.value = '';
                });
            }
        }
    });
});

function toggleStructureFields(prefix, isEnabled) {
    const fields = document.querySelectorAll(`input[name^="${prefix}_"]:not([name="${prefix}_existe"])`);
    fields.forEach(field => {
        field.disabled = !isEnabled;
        if (!isEnabled) field.value = '';
    });
}

// Vérifier la complétion au chargement et périodiquement
window.addEventListener('load', function() {
    checkSectionCompletion();
    checkSectionCompletionEtape2();
    checkSectionCompletionEtape3();
    // Calculer les statistiques initiales des étapes 6
    calculateCapitalImmobilierTotals();
    calculateCapitalMobilierTotals();
    calculateEquipementsInformatiquesTotals();
    // Déclencher le scroll spy initial
    if (mainContent) {
        mainContent.dispatchEvent(new Event('scroll'));
    }
});

// Fonction pour basculer entre les étapes
function switchToEtape(etapeNum, sectionTarget = null) {
    // Scroll instantané vers le haut de la page
    window.scrollTo(0, 0);
    
    // Scroll du contenu principal (formulaire) vers le haut
    const mainContent = document.getElementById('main-content');
    if (mainContent) {
        mainContent.scrollTop = 0;
    }
    
    // Cacher toutes les étapes
    const etape1 = document.getElementById('etape1');
    const etape2 = document.getElementById('etape2');
    const etape3 = document.getElementById('etape3');
    const etape4 = document.getElementById('etape4');
    const etape5 = document.getElementById('etape5');
    const etape6 = document.getElementById('etape6');
    
    // Récupérer les boutons
    const btnInfos = document.getElementById('btn-etape1');
    const btnEffectifs = document.getElementById('btn-etape2');
    const btnExamens = document.getElementById('btn-etape3');
    const btnPersonnel = document.getElementById('btn-etape4');
    const btnMateriel = document.getElementById('btn-etape5');
    const btnInfrastructure = document.getElementById('btn-etape6');
    
    if (etapeNum === 1) {
        if (etape1) etape1.classList.remove('hidden');
        if (etape2) etape2.classList.add('hidden');
        if (etape3) etape3.classList.add('hidden');
        if (etape4) etape4.classList.add('hidden');
        if (etape5) etape5.classList.add('hidden');
        if (etape6) etape6.classList.add('hidden');
        // Ouvrir substeps-1, fermer substeps-2, substeps-3, substeps-4, substeps-5 et substeps-6
        const substeps1 = document.getElementById('substeps-1');
        const substeps2 = document.getElementById('substeps-2');
        const substeps3 = document.getElementById('substeps-3');
        const substeps4 = document.getElementById('substeps-4');
        const substeps5 = document.getElementById('substeps-5');
        const substeps6 = document.getElementById('substeps-6');
        if (substeps1) substeps1.classList.remove('hidden');
        if (substeps2) substeps2.classList.add('hidden');
        if (substeps3) substeps3.classList.add('hidden');
        if (substeps4) substeps4.classList.add('hidden');
        if (substeps5) substeps5.classList.add('hidden');
        if (substeps6) substeps6.classList.add('hidden');
        
        // Styles des boutons - Activer Infos, désactiver autres
        if (btnInfos) {
            btnInfos.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm';
        }
        if (btnEffectifs) {
            btnEffectifs.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-emerald-50 hover:text-emerald-600';
        }
        if (btnExamens) {
            btnExamens.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnPersonnel) {
            btnPersonnel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnMateriel) {
            btnMateriel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        
        // Scroll vers le haut
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Vérifier complétion Étape 1
        checkSectionCompletion();
    } else if (etapeNum === 2) {
        if (etape1) etape1.classList.add('hidden');
        if (etape2) etape2.classList.remove('hidden');
        if (etape3) etape3.classList.add('hidden');
        if (etape4) etape4.classList.add('hidden');
        if (etape5) etape5.classList.add('hidden');
        if (etape6) etape6.classList.add('hidden');
        // Fermer substeps-1, substeps-3, substeps-4, substeps-5, substeps-6, ouvrir substeps-2
        const substeps1 = document.getElementById('substeps-1');
        const substeps2 = document.getElementById('substeps-2');
        const substeps3 = document.getElementById('substeps-3');
        const substeps4 = document.getElementById('substeps-4');
        const substeps5 = document.getElementById('substeps-5');
        const substeps6 = document.getElementById('substeps-6');
        if (substeps1) substeps1.classList.add('hidden');
        if (substeps2) substeps2.classList.remove('hidden');
        if (substeps3) substeps3.classList.add('hidden');
        if (substeps4) substeps4.classList.add('hidden');
        if (substeps5) substeps5.classList.add('hidden');
        if (substeps6) substeps6.classList.add('hidden');
        
        // Styles des boutons - Activer Effectifs, désactiver autres
        if (btnInfos) {
            btnInfos.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnEffectifs) {
            btnEffectifs.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm';
        }
        if (btnExamens) {
            btnExamens.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnPersonnel) {
            btnPersonnel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-emerald-50 hover:text-emerald-600';
        }
        if (btnMateriel) {
            btnMateriel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        
        // Scroll vers le haut
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Vérifier complétion Étape 2
        checkSectionCompletionEtape2();
    } else if (etapeNum === 3) {
        if (etape1) etape1.classList.add('hidden');
        if (etape2) etape2.classList.add('hidden');
        if (etape3) etape3.classList.remove('hidden');
        if (etape4) etape4.classList.add('hidden');
        if (etape5) etape5.classList.add('hidden');
        if (etape6) etape6.classList.add('hidden');
        // Fermer substeps-1, substeps-2, substeps-4, substeps-5, substeps-6, ouvrir substeps-3
        const substeps1 = document.getElementById('substeps-1');
        const substeps2 = document.getElementById('substeps-2');
        const substeps3 = document.getElementById('substeps-3');
        const substeps4 = document.getElementById('substeps-4');
        const substeps5 = document.getElementById('substeps-5');
        const substeps6 = document.getElementById('substeps-6');
        if (substeps1) substeps1.classList.add('hidden');
        if (substeps2) substeps2.classList.add('hidden');
        if (substeps3) substeps3.classList.remove('hidden');
        if (substeps4) substeps4.classList.add('hidden');
        if (substeps5) substeps5.classList.add('hidden');
        if (substeps6) substeps6.classList.add('hidden');
        
        // Styles des boutons - Activer Examens, désactiver autres
        if (btnInfos) {
            btnInfos.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnEffectifs) {
            btnEffectifs.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnExamens) {
            btnExamens.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm';
        }
        if (btnPersonnel) {
            btnPersonnel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-blue-50 hover:text-blue-600';
        }
        if (btnMateriel) {
            btnMateriel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        
        // Scroll vers le haut
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Vérifier complétion Étape 3
        if (typeof checkSectionCompletionEtape3 === 'function') {
            checkSectionCompletionEtape3();
        }
        
        // Scroll vers la section spécifiée si fournie
        if (sectionTarget) {
            setTimeout(() => {
                const targetSection = document.getElementById(sectionTarget);
                if (targetSection && mainContent) {
                    const offsetTop = targetSection.offsetTop - 20; // 20px de marge
                    mainContent.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            }, 100); // Petit délai pour laisser le temps au contenu de s'afficher
        }
    } else if (etapeNum === 4) {
        if (etape1) etape1.classList.add('hidden');
        if (etape2) etape2.classList.add('hidden');
        if (etape3) etape3.classList.add('hidden');
        if (etape4) etape4.classList.remove('hidden');
        if (etape5) etape5.classList.add('hidden');
        if (etape6) etape6.classList.add('hidden');
        // Fermer substeps-1, substeps-2, substeps-3, substeps-5, ouvrir substeps-4
        const substeps1 = document.getElementById('substeps-1');
        const substeps2 = document.getElementById('substeps-2');
        const substeps3 = document.getElementById('substeps-3');
        const substeps4 = document.getElementById('substeps-4');
        const substeps5 = document.getElementById('substeps-5');
        if (substeps1) substeps1.classList.add('hidden');
        if (substeps2) substeps2.classList.add('hidden');
        if (substeps3) substeps3.classList.add('hidden');
        if (substeps4) substeps4.classList.remove('hidden');
        if (substeps5) substeps5.classList.add('hidden');
        
        // Styles des boutons - Activer Personnel, désactiver autres
        if (btnInfos) {
            btnInfos.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnEffectifs) {
            btnEffectifs.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnExamens) {
            btnExamens.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnPersonnel) {
            btnPersonnel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm';
        }
        if (btnMateriel) {
            btnMateriel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-emerald-50 hover:text-emerald-600';
        }
        
        // Scroll vers le haut
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Vérifier complétion Étape 4
        if (typeof checkSectionCompletionEtape4 === 'function') {
            checkSectionCompletionEtape4();
        }
        
        // Scroll vers la section spécifiée si fournie
        if (sectionTarget) {
            setTimeout(() => {
                const targetSection = document.getElementById(sectionTarget);
                if (targetSection && mainContent) {
                    const offsetTop = targetSection.offsetTop - 20; // 20px de marge
                    mainContent.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            }, 100);
        }
    } else if (etapeNum === 5) {
        if (etape1) etape1.classList.add('hidden');
        if (etape2) etape2.classList.add('hidden');
        if (etape3) etape3.classList.add('hidden');
        if (etape4) etape4.classList.add('hidden');
        if (etape5) etape5.classList.remove('hidden');
        if (etape6) etape6.classList.add('hidden');
        // Fermer substeps-1, substeps-2, substeps-3, substeps-4, ouvrir substeps-5
        const substeps1 = document.getElementById('substeps-1');
        const substeps2 = document.getElementById('substeps-2');
        const substeps3 = document.getElementById('substeps-3');
        const substeps4 = document.getElementById('substeps-4');
        const substeps5 = document.getElementById('substeps-5');
        if (substeps1) substeps1.classList.add('hidden');
        if (substeps2) substeps2.classList.add('hidden');
        if (substeps3) substeps3.classList.add('hidden');
        if (substeps4) substeps4.classList.add('hidden');
        if (substeps5) substeps5.classList.remove('hidden');
        
        // Styles des boutons - Activer Matériel, désactiver autres
        if (btnInfos) {
            btnInfos.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnEffectifs) {
            btnEffectifs.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnExamens) {
            btnExamens.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnPersonnel) {
            btnPersonnel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnMateriel) {
            btnMateriel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm';
        }
        
        // Scroll vers le haut
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Vérifier complétion Étape 5
        if (typeof checkSectionCompletionEtape5 === 'function') {
            checkSectionCompletionEtape5();
        }
        
        // Initialiser les calculs pour toutes les sections Étape 5
        setTimeout(() => {
            console.log('🎯 Initialisation des calculs Étape 5...');
            
            // Manuels Élèves
            if (typeof initManuelsEleves === 'function') {
                initManuelsEleves();
            }
            
            // Manuels Maître
            if (typeof initManuelsMaitre === 'function') {
                initManuelsMaitre();
            }
            
            // Dictionnaires
            if (typeof initDictionnaires === 'function') {
                initDictionnaires();
            }
            
            // Matériel Didactique
            if (typeof initMaterielDidactique === 'function') {
                initMaterielDidactique();
            }
            
            // Géométrie - SUPPRIMÉ (instruments inclus dans Matériel Didactique)
        }, 300);
        
        // Scroll vers la section spécifiée si fournie
        if (sectionTarget) {
            setTimeout(() => {
                const targetSection = document.getElementById(sectionTarget);
                if (targetSection && mainContent) {
                    const offsetTop = targetSection.offsetTop - 20; // 20px de marge
                    mainContent.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            }, 200);
        }
    } else if (etapeNum === 6) {
        if (etape1) etape1.classList.add('hidden');
        if (etape2) etape2.classList.add('hidden');
        if (etape3) etape3.classList.add('hidden');
        if (etape4) etape4.classList.add('hidden');
        if (etape5) etape5.classList.add('hidden');
        if (etape6) etape6.classList.remove('hidden');
        // Fermer substeps-1, substeps-2, substeps-3, substeps-4, substeps-5, ouvrir substeps-6
        const substeps1 = document.getElementById('substeps-1');
        const substeps2 = document.getElementById('substeps-2');
        const substeps3 = document.getElementById('substeps-3');
        const substeps4 = document.getElementById('substeps-4');
        const substeps5 = document.getElementById('substeps-5');
        const substeps6 = document.getElementById('substeps-6');
        if (substeps1) substeps1.classList.add('hidden');
        if (substeps2) substeps2.classList.add('hidden');
        if (substeps3) substeps3.classList.add('hidden');
        if (substeps4) substeps4.classList.add('hidden');
        if (substeps5) substeps5.classList.add('hidden');
        if (substeps6) substeps6.classList.remove('hidden');
        
        // Styles des boutons - Activer Infrastructure, désactiver autres
        if (btnInfos) {
            btnInfos.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnEffectifs) {
            btnEffectifs.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnExamens) {
            btnExamens.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnPersonnel) {
            btnPersonnel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnMateriel) {
            btnMateriel.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition text-gray-700 hover:bg-gray-100';
        }
        if (btnInfrastructure) {
            btnInfrastructure.className = 'w-full flex items-center px-3 py-2 rounded text-xs transition bg-emerald-600 text-white shadow-sm';
        }
        
        // Scroll vers le haut
        if (mainContent) mainContent.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Vérifier complétion Étape 6
        if (typeof checkSectionCompletionEtape6 === 'function') {
            checkSectionCompletionEtape6();
        }
        
        // Initialiser les calculs pour toutes les sections Étape 6
        setTimeout(() => {
            console.log('🎯 Initialisation des calculs Étape 6...');
            
            // Capital Immobilier
            if (typeof initCapitalImmobilier === 'function') {
                initCapitalImmobilier();
            }
            
            // Capital Mobilier (si existe)
            if (typeof initCapitalMobilier === 'function') {
                initCapitalMobilier();
            }
            
            // Équipements Informatiques
            if (typeof initEquipementsInformatiques === 'function') {
                initEquipementsInformatiques();
            }
        }, 300);
        
        // Scroll vers la section spécifiée si fournie
        if (sectionTarget) {
            setTimeout(() => {
                const targetSection = document.getElementById(sectionTarget);
                if (targetSection && mainContent) {
                    const offsetTop = targetSection.offsetTop - 20; // 20px de marge
                    mainContent.scrollTo({ top: offsetTop, behavior: 'smooth' });
                }
            }, 100);
        }
    }
    
    // Si sectionTarget est fourni pour les autres étapes, faire le scroll aussi
    if (sectionTarget && etapeNum !== 3 && etapeNum !== 4 && etapeNum !== 5 && etapeNum !== 6) {
        setTimeout(() => {
            const targetElement = document.getElementById(sectionTarget);
            if (targetElement && mainContent) {
                const offsetTop = targetElement.offsetTop - 20;
                mainContent.scrollTo({ top: offsetTop, behavior: 'smooth' });
            }
        }, 100);
    }
}

// Fonction de vérification completion Étape 4 - Personnel
function checkSectionCompletionEtape4() {
    const sections = [
        'repartition-specialite',
        'repartition-corps', 
        'repartition-diplomes',
        'competences-tic',
        'statistiques-personnel'
    ];
    
    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        
        const inputs = section.querySelectorAll('input:not([type="hidden"]):not([type="readonly"]), select, textarea');
        let filled = 0;
        let total = 0;
        
        // Compter les champs non-readonly
        inputs.forEach(input => {
            if (input.disabled || input.readOnly) return;
            total++;
            if (input.value && input.value.trim() !== '' && input.value !== '0') {
                filled++;
            }
        });
        
        const link = document.querySelector(`#substeps-4 a[href="#${sectionId}"]`);
        if (link) {
            // Supprimer les anciens indicateurs
            const oldIcon = link.querySelector('.completion-icon');
            if (oldIcon) oldIcon.remove();
            
            // Ajouter le nouvel indicateur si au moins un champ rempli
            if (total > 0 && filled > 0) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-check-circle text-emerald-500 text-xs completion-icon';
                icon.style.marginLeft = 'auto';
                link.appendChild(icon);
            }
        }
    });
}

function checkSectionCompletionEtape5() {
    const sections = [
        'manuels-eleves', 
        'manuels-maitre',
        'dictionnaires',
        'materiel-didactique',
        'geometrie'
    ];
    
    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        
        const inputs = section.querySelectorAll('input:not([type="hidden"]):not([type="readonly"]), select, textarea');
        let filled = 0;
        let total = 0;
        
        // Compter les champs non-readonly
        inputs.forEach(input => {
            if (input.disabled || input.readOnly) return;
            total++;
            if (input.value && input.value.trim() !== '' && input.value !== '0') {
                filled++;
            }
        });
        
        const link = document.querySelector(`#substeps-5 a[href="#${sectionId}"]`);
        if (link) {
            // Supprimer les anciens indicateurs
            const oldIcon = link.querySelector('.completion-icon');
            if (oldIcon) oldIcon.remove();
            
            // Ajouter le nouvel indicateur si au moins un champ rempli
            if (total > 0 && filled > 0) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-check-circle text-emerald-500 text-xs completion-icon';
                icon.style.marginLeft = 'auto';
                link.appendChild(icon);
            }
        }
    });
}

function checkSectionCompletionEtape6() {
    const sections = [
        'capital-immobilier',
        'capital-mobilier',
        'equipements-informatiques'
    ];
    
    sections.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (!section) return;
        
        const inputs = section.querySelectorAll('input:not([type="hidden"]):not([type="readonly"]), select, textarea');
        let filled = 0;
        let total = 0;
        
        // Compter les champs non-readonly
        inputs.forEach(input => {
            if (input.disabled || input.readOnly) return;
            total++;
            if (input.value && input.value.trim() !== '' && input.value !== '0') {
                filled++;
            }
        });
        
        const link = document.querySelector(`#substeps-6 a[href="#${sectionId}"]`);
        if (link) {
            // Supprimer les anciens indicateurs
            const oldIcon = link.querySelector('.completion-icon');
            if (oldIcon) oldIcon.remove();
            
            // Ajouter le nouvel indicateur si au moins un champ rempli
            if (total > 0 && filled > 0) {
                const icon = document.createElement('i');
                icon.className = 'fas fa-check-circle text-emerald-500 text-xs completion-icon';
                icon.style.marginLeft = 'auto';
                link.appendChild(icon);
            }
        }
    });
}

// Scroll spy pour Étape 4
function setupScrollSpyEtape4() {
    const mainContent = document.getElementById('main-content');
    const sections = [
        'repartition-specialite',
        'repartition-corps', 
        'repartition-diplomes',
        'competences-tic',
        'statistiques-personnel'
    ];
    
    if (!mainContent) return;
    
    mainContent.addEventListener('scroll', () => {
        const scrollPosition = mainContent.scrollTop + 100; // Offset
        
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            const link = document.querySelector(`a[href="#${sectionId}"]`);
            
            if (!section || !link) return;
            
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                // Activer la section courante
                link.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.remove('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            } else {
                // Désactiver les autres sections
                link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.add('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            }
        });
    });
}

// Scroll spy pour Étape 5
function setupScrollSpyEtape5() {
    const mainContent = document.getElementById('main-content');
    const sections = [
        'materiel-didactique',
        'manuels-eleves', 
        'manuels-maitre'
    ];
    
    if (!mainContent) return;
    
    mainContent.addEventListener('scroll', () => {
        const scrollPosition = mainContent.scrollTop + 100; // Offset
        
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            const link = document.querySelector(`a[href="#${sectionId}"]`);
            
            if (!section || !link) return;
            
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                // Activer la section courante
                link.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.remove('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            } else {
                // Désactiver les autres sections
                link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.add('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            }
        });
    });
}

// Scroll spy pour Étape 6
function setupScrollSpyEtape6() {
    const mainContent = document.getElementById('main-content');
    const sections = [
        'capital-immobilier',
        'capital-mobilier',
        'equipements-informatiques'
    ];
    
    if (!mainContent) return;
    
    mainContent.addEventListener('scroll', () => {
        const scrollPosition = mainContent.scrollTop + 100; // Offset
        
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            const link = document.querySelector(`a[href="#${sectionId}"]`);
            
            if (!section || !link) return;
            
            const sectionTop = section.offsetTop;
            const sectionBottom = sectionTop + section.offsetHeight;
            
            if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                // Activer la section courante
                link.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.remove('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            } else {
                // Désactiver les autres sections
                link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.add('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            }
        });
    });
}

// Initialiser scroll spy au chargement
document.addEventListener('DOMContentLoaded', function() {
    setupScrollSpyEtape4();
    setupScrollSpyEtape5();
    setupScrollSpyEtape6();
});

// Fonctions de sauvegarde pour l'Étape 5 (suite)
// ⚠️ saveDictionnaires() est obsolète - on utilise maintenant autoSave('dictionnaires')

// Fonctions de sauvegarde pour l'Étape 6
async function saveCapitalImmobilier() {
    const formData = new FormData();
    
    // Récupérer tous les champs du capital immobilier
    const fields = [
        'salles_dur', 'salles_hangar', 'salles_provisoires',
        'bureau_directeur', 'magasin', 'logement_instituteur',
        'latrines', 'urinoirs', 'points_eau',
        'terrain_sport', 'aire_jeux', 'cloture', 'portail',
        'etat_general', 'besoins_prioritaires', 'observations_immobilier'
    ];
    
    fields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) {
            formData.append(field, element.value);
        }
    });
    
    try {
        await fetch('{{ route("etablissement.rapport-rentree.save-capital-immobilier", $rapport->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        calculateCapitalImmobilierTotals();
    } catch (error) {
        console.error('Erreur lors de la sauvegarde du capital immobilier:', error);
    }
}

async function saveCapitalMobilier() {
    const formData = new FormData();
    
    // Récupérer tous les champs du capital mobilier
    const fields = [
        'tables_bancs_total', 'tables_bancs_bon_etat',
        'chaises_eleves_total', 'chaises_eleves_bon_etat',
        'tables_individuelles_total', 'tables_individuelles_bon_etat',
        'bureaux_maitre_total', 'bureaux_maitre_bon_etat',
        'chaises_maitre_total', 'chaises_maitre_bon_etat',
        'tableaux_total', 'tableaux_bon_etat',
        'armoires_total', 'armoires_bon_etat',
        'etageres_total', 'etageres_bon_etat',
        'bancs_total', 'bancs_bon_etat',
        'materiel_specialise', 'etat_general_mobilier',
        'besoins_mobilier', 'observations_mobilier'
    ];
    
    fields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) {
            formData.append(field, element.value);
        }
    });
    
    try {
        await fetch('{{ route("etablissement.rapport-rentree.save-capital-mobilier", $rapport->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        calculateCapitalMobilierTotals();
    } catch (error) {
        console.error('Erreur lors de la sauvegarde du capital mobilier:', error);
    }
}

async function saveEquipementsInformatiques() {
    const formData = new FormData();
    
    // Récupérer tous les champs des équipements informatiques
    const fields = [
        'ordinateurs_bureau', 'etat_ordinateurs', 'ordinateurs_portables', 'tablettes',
        'imprimantes', 'scanners', 'projecteurs', 'ecrans_projection',
        'televisions', 'systemes_audio', 'tableaux_interactifs', 'appareils_photo',
        'connexion_internet', 'reseau_wifi', 'reseau_local', 'alimentation_electrique',
        'logiciels_educatifs', 'formation_informatique', 'maintenance_equipements',
        'utilisation_pedagogique', 'besoins_informatiques', 'budget_estime',
        'projets_informatiques'
    ];
    
    fields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) {
            formData.append(field, element.value);
        }
    });
    
    try {
        await fetch('{{ route("etablissement.rapport-rentree.save-equipements-informatiques", $rapport->id) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        calculateEquipementsInformatiquesTotals();
    } catch (error) {
        console.error('Erreur lors de la sauvegarde des équipements informatiques:', error);
    }
}

// Fonctions de calcul des statistiques pour l'Étape 6
function calculateCapitalImmobilierTotals() {
    const sallesDur = parseInt(document.querySelector('[name="salles_dur"]')?.value) || 0;
    const sallesHangar = parseInt(document.querySelector('[name="salles_hangar"]')?.value) || 0;
    const sallesProvisoires = parseInt(document.querySelector('[name="salles_provisoires"]')?.value) || 0;
    const latrines = parseInt(document.querySelector('[name="latrines"]')?.value) || 0;
    const urinoirs = parseInt(document.querySelector('[name="urinoirs"]')?.value) || 0;
    const pointsEau = parseInt(document.querySelector('[name="points_eau"]')?.value) || 0;
    
    const totalSalles = sallesDur + sallesHangar + sallesProvisoires;
    const installationsSanitaires = latrines + urinoirs + pointsEau;
    
    // Compter infrastructures disponibles
    let infrastructuresDisponibles = 0;
    const infrastructures = ['bureau_directeur', 'magasin', 'logement_instituteur', 'terrain_sport', 'aire_jeux'];
    infrastructures.forEach(infra => {
        const value = document.querySelector(`[name="${infra}"]`)?.value;
        if (value === 'existe' || value === 'disponible' || value === 'complete') infrastructuresDisponibles++;
    });
    
    // Calculer taux d'équipement (sur base de 8 infrastructures essentielles)
    const tauxEquipement = Math.round((infrastructuresDisponibles / 8) * 100);
    
    // Mettre à jour l'affichage
    document.getElementById('total-salles').textContent = totalSalles;
    document.getElementById('infrastructures-disponibles').textContent = infrastructuresDisponibles;
    document.getElementById('installations-sanitaires').textContent = installationsSanitaires;
    document.getElementById('taux-equipement').textContent = tauxEquipement + '%';
}

function calculateCapitalMobilierTotals() {
    const mobilierFields = [
        'tables_bancs_total', 'chaises_eleves_total', 'tables_individuelles_total',
        'bureaux_maitre_total', 'chaises_maitre_total', 'tableaux_total',
        'armoires_total', 'etageres_total', 'bancs_total'
    ];
    
    const mobilierBonEtatFields = [
        'tables_bancs_bon_etat', 'chaises_eleves_bon_etat', 'tables_individuelles_bon_etat',
        'bureaux_maitre_bon_etat', 'chaises_maitre_bon_etat', 'tableaux_bon_etat',
        'armoires_bon_etat', 'etageres_bon_etat', 'bancs_bon_etat'
    ];
    
    let totalMobilier = 0;
    let mobilierBonEtat = 0;
    let mobilierEleves = 0;
    let mobilierEnseignants = 0;
    
    mobilierFields.forEach((field, index) => {
        const total = parseInt(document.querySelector(`[name="${field}"]`)?.value) || 0;
        const bonEtat = parseInt(document.querySelector(`[name="${mobilierBonEtatFields[index]}"]`)?.value) || 0;
        
        totalMobilier += total;
        mobilierBonEtat += bonEtat;
        
        // Calculer mobilier par catégorie
        if (field.includes('eleves') || field.includes('tables_bancs') || field.includes('tables_individuelles') || field.includes('bancs')) {
            mobilierEleves += total;
        } else if (field.includes('maitre') || field.includes('bureaux') || field.includes('chaises_maitre') || field.includes('tableaux')) {
            mobilierEnseignants += total;
        }
    });
    
    const tauxConservation = totalMobilier > 0 ? Math.round((mobilierBonEtat / totalMobilier) * 100) : 0;
    
    // Mettre à jour l'affichage
    document.getElementById('total-mobilier').textContent = totalMobilier;
    document.getElementById('mobilier-bon-etat').textContent = mobilierBonEtat;
    document.getElementById('taux-conservation').textContent = tauxConservation + '%';
    document.getElementById('mobilier-eleves').textContent = mobilierEleves;
    document.getElementById('mobilier-enseignants').textContent = mobilierEnseignants;
}

function calculateEquipementsInformatiquesTotals() {
    const ordinateursBureau = parseInt(document.querySelector('[name="ordinateurs_bureau"]')?.value) || 0;
    const ordinateursPortables = parseInt(document.querySelector('[name="ordinateurs_portables"]')?.value) || 0;
    const tablettes = parseInt(document.querySelector('[name="tablettes"]')?.value) || 0;
    const projecteurs = parseInt(document.querySelector('[name="projecteurs"]')?.value) || 0;
    const televisions = parseInt(document.querySelector('[name="televisions"]')?.value) || 0;
    const tableauxInteractifs = parseInt(document.querySelector('[name="tableaux_interactifs"]')?.value) || 0;
    
    const totalOrdinateurs = ordinateursBureau + ordinateursPortables + tablettes;
    const equipementsAudiovisuels = projecteurs + televisions + tableauxInteractifs;
    
    // Niveau de connectivité
    const connexionInternet = document.querySelector('[name="connexion_internet"]')?.value;
    let niveauConnectivite = 'Aucun';
    if (connexionInternet === 'haut_debit') niveauConnectivite = 'Excellent';
    else if (connexionInternet === 'bas_debit') niveauConnectivite = 'Bon';
    else if (connexionInternet === 'intermittent') niveauConnectivite = 'Moyen';
    
    // Calculer ratio élève/ordinateur (estimation avec 200 élèves)
    const ratioEleveOrdinateur = totalOrdinateurs > 0 ? Math.round(200 / totalOrdinateurs) : '∞';
    
    // Taux d'utilisation basé sur formation et utilisation pédagogique
    const formation = document.querySelector('[name="formation_informatique"]')?.value;
    const utilisation = document.querySelector('[name="utilisation_pedagogique"]')?.value;
    let tauxUtilisation = 0;
    if (formation === 'reguliere' && utilisation && utilisation.length > 10) tauxUtilisation = 80;
    else if (formation === 'occasionnelle' && utilisation) tauxUtilisation = 50;
    else if (utilisation) tauxUtilisation = 30;
    
    // Mettre à jour l'affichage
    document.getElementById('total-ordinateurs').textContent = totalOrdinateurs;
    document.getElementById('equipements-audiovisuels').textContent = equipementsAudiovisuels;
    document.getElementById('niveau-connectivite').textContent = niveauConnectivite;
    document.getElementById('ratio-eleve-ordinateur').textContent = ratioEleveOrdinateur + ':1';
    document.getElementById('taux-utilisation').textContent = tauxUtilisation + '%';
}

// Fonction de scroll spy pour l'Étape 6
function setupScrollSpyEtape6() {
    const mainContent = document.querySelector('.overflow-y-auto');
    if (!mainContent) return;

    mainContent.addEventListener('scroll', handleScrollSpyEtape6);
}

function handleScrollSpyEtape6() {
    const sectionsEtape6 = ['capital-immobilier', 'capital-mobilier', 'equipements-informatiques'];
    let currentSection = '';
    
    sectionsEtape6.forEach(sectionId => {
        const section = document.getElementById(sectionId);
        if (section) {
            const rect = section.getBoundingClientRect();
            if (rect.top <= 200 && rect.bottom >= 100) {
                currentSection = sectionId;
            }
        }
    });

    // Mettre à jour la navigation
    sectionsEtape6.forEach(sectionId => {
        const link = document.querySelector(`a[href="#${sectionId}"]`);
        if (link) {
            if (currentSection === sectionId) {
                // Activer la section courante
                link.classList.add('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.remove('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            } else {
                // Désactiver les autres sections
                link.classList.remove('bg-emerald-100', 'text-emerald-700', 'font-semibold');
                link.classList.add('text-gray-700', 'hover:text-emerald-600', 'hover:bg-emerald-50');
            }
        }
    });
}

// Fonction pour finaliser le rapport
function submitRapport() {
    if (confirm('Êtes-vous sûr de vouloir finaliser ce rapport ? Cette action ne pourra pas être annulée.')) {
        // Ici on peut ajouter la logique pour changer le statut du rapport à "final"
        alert('Rapport finalisé avec succès !');
        // Optionnel: redirection vers le dashboard
        // window.location.href = '{{ route("etablissement.dashboard") }}';
    }
}

// FONCTIONS DE CALCUL POUR L'ÉTAPE 5 
// NOTE: Les fonctions calculateManuelsElevesTotals() et calculateManuelsMaitreTotals()
// sont définies dans leurs partials respectifs (etape5-manuels-eleves.blade.php et etape5-manuels-maitre.blade.php)

// ⚠️ calculateDictionnairesTotals() est maintenant définie dans etape5-dictionnaires.blade.php

function calculateMaterielDidactiqueTotals() {
    // Calculer totaux pour matériel didactique
    const cartes = parseInt(document.querySelector('[name="cartes_geographiques"]')?.value) || 0;
    const globes = parseInt(document.querySelector('[name="globes_terrestres"]')?.value) || 0;
    const tableaux = parseInt(document.querySelector('[name="tableaux_sciences"]')?.value) || 0;
    const maquettes = parseInt(document.querySelector('[name="maquettes_corps_humain"]')?.value) || 0;
    const instruments = parseInt(document.querySelector('[name="instruments_musique"]')?.value) || 0;
    const materielSport = parseInt(document.querySelector('[name="materiel_sport"]')?.value) || 0;
    const livres = parseInt(document.querySelector('[name="livres_bibliotheque"]')?.value) || 0;
    
    const total = cartes + globes + tableaux + maquettes + instruments + materielSport + livres;
    
    // Mettre à jour l'affichage du total
    const totalElement = document.getElementById('total-materiel-didactique');
    if (totalElement) {
        totalElement.textContent = total;
    }
    
    // Calculer taux d'équipement didactique (sur 100 items de base estimés)
    const tauxEquipement = total > 0 ? Math.round((total / 100) * 100) : 0;
    const tauxElement = document.getElementById('taux-equipement-didactique');
    if (tauxElement) {
        tauxElement.textContent = tauxEquipement + '%';
    }
}

// ⚠️ calculateGeometrieTotals() SUPPRIMÉE - maintenant dans etape5-geometrie.blade.php avec nouveaux champs

function calculateMaterielTotals() {
    // Calculer totaux pour matériel général
    const ardoises = parseInt(document.querySelector('[name="ardoises"]')?.value) || 0;
    const craies = parseInt(document.querySelector('[name="craies_blanches"]')?.value) || 0;
    const crayonsTableau = parseInt(document.querySelector('[name="crayons_tableau"]')?.value) || 0;
    const eponges = parseInt(document.querySelector('[name="eponges"]')?.value) || 0;
    const cahiers = parseInt(document.querySelector('[name="cahiers_brouillon"]')?.value) || 0;
    const stylos = parseInt(document.querySelector('[name="stylos"]')?.value) || 0;
    const crayons = parseInt(document.querySelector('[name="crayons_couleur"]')?.value) || 0;
    const feutres = parseInt(document.querySelector('[name="feutres"]')?.value) || 0;
    
    const total = ardoises + craies + crayonsTableau + eponges + cahiers + stylos + crayons + feutres;
    
    // Mettre à jour l'affichage du total
    const totalElement = document.getElementById('total-materiel-general');
    if (totalElement) {
        totalElement.textContent = total;
    }
    
    // Calculer stock disponible (en mois, basé sur consommation estimée)
    const stockMois = total > 0 ? Math.round(total / 50) : 0; // 50 unités/mois estimé
    const stockElement = document.getElementById('stock-materiel-mois');
    if (stockElement) {
        stockElement.textContent = stockMois + ' mois';
    }
}

// ⚠️ calculateMesureTotals() supprimée - Mesure maintenant dans materiel-didactique

// =================== 🎯 VALIDATION INFO DIRECTEUR - NOM ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Info Directeur (5 champs)');
    
    const nomField = document.querySelector('input[name="directeur_nom"]');
    const contact1Field = document.querySelector('input[name="directeur_contact_1"]');
    const contact2Field = document.querySelector('input[name="directeur_contact_2"]');
    const emailField = document.querySelector('input[name="directeur_email"]');
    const distanceField = document.querySelector('input[name="distance_siege"]');
    
    if (!nomField || !contact1Field) {
        console.log('⚠️ Champs obligatoires non trouvés');
        return;
    }
    
    console.log('✅ Tous les champs Info Directeur trouvés');
    
    // ========== VALIDATION NOM ==========
    nomField.addEventListener('input', function(e) {
        this.value = this.value.replace(/[0-9]/g, '');
    });
    
    nomField.addEventListener('blur', function(e) {
        const isValid = validateNomDirecteur(this);
        invalidFieldBlocked = !isValid ? this : null;
    });
    
    nomField.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && !validateNomDirecteur(this)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            invalidFieldBlocked = this;
            setTimeout(() => { this.focus(); this.select(); }, 10);
            return false;
        }
    });
    
    // ========== VALIDATION CONTACT 1 (OBLIGATOIRE) ==========
    contact1Field.addEventListener('input', function(e) {
        // Garder seulement les chiffres
        this.value = this.value.replace(/\D/g, '');
        // Limiter à 9 chiffres
        if (this.value.length > 9) {
            this.value = this.value.substring(0, 9);
        }
    });
    
    contact1Field.addEventListener('blur', function(e) {
        const isValid = validateContact1(this);
        invalidFieldBlocked = !isValid ? this : null;
    });
    
    contact1Field.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && !validateContact1(this)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            invalidFieldBlocked = this;
            setTimeout(() => { this.focus(); this.select(); }, 10);
            return false;
        }
    });
    
    // ========== VALIDATION CONTACT 2 (OPTIONNEL) ==========
    if (contact2Field) {
        contact2Field.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 9) {
                this.value = this.value.substring(0, 9);
            }
        });
        
        contact2Field.addEventListener('blur', function(e) {
            const isValid = validateContact2(this, contact1Field);
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        contact2Field.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateContact2(this, contact1Field)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); this.select(); }, 10);
                return false;
            }
        });
    }
    
    // ========== VALIDATION EMAIL (OPTIONNEL) ==========
    if (emailField) {
        emailField.addEventListener('blur', function(e) {
            const isValid = validateEmail(this);
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        emailField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateEmail(this)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); this.select(); }, 10);
                return false;
            }
        });
    }
    
    // ========== VALIDATION DISTANCE (OPTIONNEL) ==========
    if (distanceField) {
        distanceField.addEventListener('blur', function(e) {
            const isValid = validateDistance(this);
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        distanceField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateDistance(this)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); this.select(); }, 10);
                return false;
            }
        });
    }
    
    // SURVEILLANCE GLOBALE - Bloquer tout focus/clic ailleurs si champ invalide
    document.addEventListener('focusin', function(e) {
        if (invalidFieldBlocked && e.target !== invalidFieldBlocked) {
            e.preventDefault();
            e.stopImmediatePropagation();
            setTimeout(() => {
                invalidFieldBlocked.focus();
                invalidFieldBlocked.select();
                showValidationError(invalidFieldBlocked, '🚫 Corrigez ce champ avant de continuer !');
            }, 1);
            return false;
        }
    }, true);
    
    document.addEventListener('mousedown', function(e) {
        if (invalidFieldBlocked && e.target !== invalidFieldBlocked && e.target.tagName === 'INPUT') {
            e.preventDefault();
            e.stopImmediatePropagation();
            invalidFieldBlocked.focus();
            invalidFieldBlocked.select();
            return false;
        }
    }, true);
    
    console.log('🎯 Validation Info Directeur (5 champs) + surveillance globale activée');
});

// ========== FONCTIONS DE VALIDATION ==========

// Validation Nom
function validateNomDirecteur(field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    if (!value) {
        showValidationError(field, 'Le nom du directeur est obligatoire');
        return false;
    }
    if (value.length < 3) {
        showValidationError(field, 'Le nom doit contenir au moins 3 caractères');
        return false;
    }
    if (value.length > 100) {
        showValidationError(field, 'Le nom ne peut pas dépasser 100 caractères');
        return false;
    }
    
    const words = value.split(/\s+/).filter(w => w.length > 0);
    if (words.length < 2) {
        showValidationError(field, 'Veuillez saisir au moins un prénom et un nom (2 mots minimum)');
        return false;
    }
    
    if (/\d/.test(value)) {
        showValidationError(field, 'Le nom ne doit pas contenir de chiffres');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// Validation Contact 1 (OBLIGATOIRE)
function validateContact1(field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    if (!value) {
        showValidationError(field, 'Le numéro de contact 1 est obligatoire');
        return false;
    }
    
    if (value.length !== 9) {
        showValidationError(field, 'Le numéro doit contenir exactement 9 chiffres');
        return false;
    }
    
    const prefix = value.substring(0, 2);
    const validPrefixes = ['77', '78', '76', '70', '75'];
    if (!validPrefixes.includes(prefix)) {
        showValidationError(field, 'Le numéro doit commencer par 77, 78, 76, 70 ou 75');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// Validation Contact 2 (OPTIONNEL)
function validateContact2(field, contact1Field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    // Si vide, c'est OK (optionnel)
    if (!value) {
        field.style.border = '';
        field.style.backgroundColor = '';
        return true;
    }
    
    if (value.length !== 9) {
        showValidationError(field, 'Le numéro doit contenir exactement 9 chiffres');
        return false;
    }
    
    const prefix = value.substring(0, 2);
    const validPrefixes = ['77', '78', '76', '70', '75'];
    if (!validPrefixes.includes(prefix)) {
        showValidationError(field, 'Le numéro doit commencer par 77, 78, 76, 70 ou 75');
        return false;
    }
    
    // Doit être différent du Contact 1
    if (value === contact1Field.value.trim()) {
        showValidationError(field, 'Le contact 2 doit être différent du contact 1');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// Validation Email (OPTIONNEL)
function validateEmail(field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    // Si vide, c'est OK (optionnel)
    if (!value) {
        field.style.border = '';
        field.style.backgroundColor = '';
        return true;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        showValidationError(field, 'Veuillez saisir une adresse email valide');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// Validation Distance (OPTIONNEL)
function validateDistance(field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    // Si vide, c'est OK (optionnel)
    if (!value) {
        field.style.border = '';
        field.style.backgroundColor = '';
        return true;
    }
    
    const distance = parseFloat(value);
    
    if (isNaN(distance)) {
        showValidationError(field, 'Veuillez saisir un nombre valide');
        return false;
    }
    
    if (distance < 0) {
        showValidationError(field, 'La distance ne peut pas être négative');
        return false;
    }
    
    if (distance > 500) {
        showValidationError(field, 'La distance ne peut pas dépasser 500 km');
        return false;
    }
    
    // Vérifier 1 décimale max
    if (value.includes('.')) {
        const decimals = value.split('.')[1];
        if (decimals && decimals.length > 1) {
            showValidationError(field, 'Maximum 1 chiffre après la virgule');
            return false;
        }
    }
    
    showValidationSuccess(field);
    return true;
}

// ========== FONCTIONS D'AFFICHAGE ==========

// Afficher erreur de validation
function showValidationError(field, message) {
    field.style.border = '2px solid #dc2626';
    field.style.backgroundColor = '#fef2f2';
    
    clearValidationError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-error text-xs text-red-600 mt-1 font-medium';
    errorDiv.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>' + message;
    field.parentElement.appendChild(errorDiv);
}

// Afficher succès
function showValidationSuccess(field) {
    field.style.border = '2px solid #16a34a';
    field.style.backgroundColor = '#f0fdf4';
    clearValidationError(field);
}

// Effacer erreur
function clearValidationError(field) {
    field.style.border = '';
    field.style.backgroundColor = '';
    
    const errorDiv = field.parentElement.querySelector('.validation-error');
    if (errorDiv) errorDiv.remove();
}

// Valider qu'un nombre est >= 0 (pas de négatifs)
function validateNumberNotNegative(field) {
    const value = parseInt(field.value);
    
    if (isNaN(value)) return true; // Champ vide ou invalide, pas notre problème ici
    
    if (value < 0) {
        showValidationError(field, `⚠️ Valeur négative interdite`);
        return false;
    }
    
    return true;
}

// =================== 🎯 VALIDATION INFRASTRUCTURES ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Infrastructures');
    
    const infrastructures = [
        { checkbox: 'cpe_existe', field: 'cpe_nombre', fieldType: 'number', label: 'CPE' },
        { checkbox: 'cloture_existe', field: 'cloture_type', fieldType: 'select', label: 'Clôture' },
        { checkbox: 'eau_existe', field: 'eau_type', fieldType: 'select', label: 'Eau' },
        { checkbox: 'electricite_existe', field: 'electricite_type', fieldType: 'select', label: 'Électricité' },
        { checkbox: 'connexion_internet_existe', field: 'connexion_internet_type', fieldType: 'select', label: 'Internet' },
        { checkbox: 'cantine_existe', field: 'cantine_type', fieldType: 'select', label: 'Cantine' }
    ];
    
    infrastructures.forEach(infra => {
        const checkbox = document.querySelector(`input[name="${infra.checkbox}"]`);
        const field = document.getElementById(infra.field);
        
        if (!checkbox || !field) return;
        
        // VALIDATION au blur du champ
        field.addEventListener('blur', function() {
            const isValid = validateInfrastructureField(checkbox, field, infra.fieldType, infra.label);
            invalidFieldBlocked = !isValid ? field : null;
        });
        
        // BLOQUER Tab si invalide
        field.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateInfrastructureField(checkbox, field, infra.fieldType, infra.label)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = field;
                setTimeout(() => { field.focus(); }, 10);
                return false;
            }
        });
        
        // VALIDATION quand on coche/décoche
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                field.disabled = false;
                setTimeout(() => field.focus(), 100);
            } else {
                field.disabled = true;
                field.value = '';
                clearValidationError(field);
                invalidFieldBlocked = null;
            }
        });
    });
    
    console.log('✅ Validation Infrastructures activée');
});

// Validation champ infrastructure
function validateInfrastructureField(checkbox, field, fieldType, label) {
    clearValidationError(field);
    
    // Si checkbox non cochée, pas de validation
    if (!checkbox.checked) {
        return true;
    }
    
    // Si checkbox cochée, le champ DOIT être rempli
    const value = field.value.trim();
    
    if (!value || value === '') {
        showValidationError(field, `Veuillez renseigner ${label}`);
        return false;
    }
    
    // Validation spécifique pour nombre
    if (fieldType === 'number') {
        const num = parseInt(value);
        if (isNaN(num) || num < 1) {
            showValidationError(field, 'Le nombre doit être supérieur à 0');
            return false;
        }
    }
    
    showValidationSuccess(field);
    return true;
}

// =================== 🎯 VALIDATION STRUCTURES COMMUNAUTAIRES ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Structures Communautaires');
    
    // Noms à valider (optionnels, mais si remplis → lettres uniquement, min 3)
    const nomFields = [
        'cge_president_nom', 'cge_tresorier_nom',
        'gscol_president_nom',
        'ape_president_nom',
        'ame_president_nom'
    ];
    
    nomFields.forEach(fieldName => {
        const field = document.querySelector(`input[name="${fieldName}"]`);
        if (!field) return;
        
        // Bloquer chiffres en temps réel
        field.addEventListener('input', function() {
            this.value = this.value.replace(/[0-9]/g, '');
        });
        
        // Validation au blur
        field.addEventListener('blur', function() {
            const isValid = validateStructureNom(this);
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        // Bloquer Tab si invalide
        field.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateStructureNom(this)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    });
    
    // Contacts à valider
    const contactFields = [
        'cge_president_contact', 'cge_tresorier_contact',
        'gscol_president_contact',
        'ape_president_contact',
        'ame_president_contact'
    ];
    
    contactFields.forEach(fieldName => {
        const field = document.querySelector(`input[name="${fieldName}"]`);
        if (!field) return;
        
        // Formater en temps réel (chiffres seulement, max 9)
        field.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 9);
        });
        
        // Validation au blur
        field.addEventListener('blur', function() {
            const isValid = validateStructureContact(this);
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        // Bloquer Tab si invalide
        field.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateStructureContact(this)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    });
    
    console.log('✅ Validation Structures Communautaires activée');
});

// Validation nom structure (optionnel, mais si rempli → lettres uniquement, min 3)
function validateStructureNom(field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    // Si vide, c'est OK (optionnel)
    if (!value) {
        field.style.border = '';
        field.style.backgroundColor = '';
        return true;
    }
    
    // Si rempli, doit être valide
    if (value.length < 3) {
        showValidationError(field, 'Le nom doit contenir au moins 3 caractères');
        return false;
    }
    
    // Seulement lettres et espaces
    if (!/^[a-zA-ZÀ-ÿ\s'-]+$/.test(value)) {
        showValidationError(field, 'Le nom ne doit contenir que des lettres');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// Validation contact structure (optionnel mais si rempli, doit être valide)
function validateStructureContact(field) {
    const value = field.value.trim();
    clearValidationError(field);
    
    // Si vide, c'est OK (optionnel)
    if (!value) {
        field.style.border = '';
        field.style.backgroundColor = '';
        return true;
    }
    
    // Si rempli, doit être valide
    if (value.length !== 9) {
        showValidationError(field, 'Le contact doit contenir 9 chiffres');
        return false;
    }
    
    const prefix = value.substring(0, 2);
    const validPrefixes = ['77', '78', '76', '70', '75'];
    if (!validPrefixes.includes(prefix)) {
        showValidationError(field, 'Doit commencer par 77, 78, 76, 70 ou 75');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// =================== 🎯 VALIDATION LANGUES & PROJETS ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Langues & Projets');
    
    const projetCheckbox = document.querySelector('input[name="projets_informatiques_existe"]');
    const projetTextarea = document.querySelector('textarea[name="projets_informatiques_nom"]');
    
    if (!projetCheckbox || !projetTextarea) {
        console.log('⚠️ Champs projets non trouvés');
        return;
    }
    
    // Si checkbox cochée, le textarea DOIT être rempli
    projetTextarea.addEventListener('blur', function() {
        const isValid = validateProjetInformatique(projetCheckbox, projetTextarea);
        invalidFieldBlocked = !isValid ? projetTextarea : null;
    });
    
    projetTextarea.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && !validateProjetInformatique(projetCheckbox, projetTextarea)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            invalidFieldBlocked = projetTextarea;
            setTimeout(() => { projetTextarea.focus(); }, 10);
            return false;
        }
    });
    
    // Quand on coche/décoche
    projetCheckbox.addEventListener('change', function() {
        if (!this.checked) {
            projetTextarea.disabled = true;
            projetTextarea.value = '';
            clearValidationError(projetTextarea);
            invalidFieldBlocked = null;
        } else {
            projetTextarea.disabled = false;
            setTimeout(() => projetTextarea.focus(), 100);
        }
    });
    
    console.log('✅ Validation Langues & Projets activée');
});

// Validation projet informatique
function validateProjetInformatique(checkbox, textarea) {
    clearValidationError(textarea);
    
    // Si checkbox non cochée, pas de validation
    if (!checkbox.checked) {
        return true;
    }
    
    // Si cochée, le textarea DOIT être rempli
    const value = textarea.value.trim();
    
    if (!value) {
        showValidationError(textarea, 'Veuillez décrire le(s) projet(s) informatique(s)');
        return false;
    }
    
    if (value.length < 5) {
        showValidationError(textarea, 'La description doit contenir au moins 5 caractères');
        return false;
    }
    
    showValidationSuccess(textarea);
    return true;
}

// =================== 🎯 VALIDATION RESSOURCES FINANCIÈRES ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Ressources Financières');
    
    const ressources = [
        { checkbox: 'subvention_etat_existe', field: 'subvention_etat_montant', label: 'Subvention État' },
        { checkbox: 'subvention_partenaires_existe', field: 'subvention_partenaires_montant', label: 'Subvention Partenaires' },
        { checkbox: 'subvention_collectivites_existe', field: 'subvention_collectivites_montant', label: 'Subvention Collectivités' },
        { checkbox: 'subvention_communaute_existe', field: 'subvention_communaute_montant', label: 'Contribution Communauté' },
        { checkbox: 'ressources_generees_existe', field: 'ressources_generees_montant', label: 'Ressources Générées' }
    ];
    
    ressources.forEach(res => {
        const checkbox = document.querySelector(`input[name="${res.checkbox}"]`);
        const field = document.getElementById(res.field);
        
        if (!checkbox || !field) return;
        
        // Validation au blur
        field.addEventListener('blur', function() {
            const isValid = validateRessourceFinanciere(checkbox, field, res.label);
            invalidFieldBlocked = !isValid ? field : null;
        });
        
        // Bloquer Tab si invalide
        field.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateRessourceFinanciere(checkbox, field, res.label)) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = field;
                setTimeout(() => { field.focus(); }, 10);
                return false;
            }
        });
        
        // Quand on coche/décoche
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                field.disabled = true;
                field.value = '';
                clearValidationError(field);
                invalidFieldBlocked = null;
            } else {
                field.disabled = false;
                setTimeout(() => field.focus(), 100);
            }
        });
    });
    
    console.log('✅ Validation Ressources Financières activée');
});

// Validation ressource financière
function validateRessourceFinanciere(checkbox, field, label) {
    clearValidationError(field);
    
    // Si checkbox non cochée, pas de validation
    if (!checkbox.checked) {
        return true;
    }
    
    // Si cochée, le montant DOIT être rempli et > 0
    const value = field.value.trim();
    
    if (!value) {
        showValidationError(field, `Veuillez saisir le montant pour ${label}`);
        return false;
    }
    
    const montant = parseFloat(value);
    
    if (isNaN(montant) || montant <= 0) {
        showValidationError(field, 'Le montant doit être supérieur à 0');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// =================== 🎯 VALIDATION ÉTAPE 2 - EFFECTIFS ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Étape 2 - Effectifs');
    
    const niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    
    niveaux.forEach(niveau => {
        // 1. NOMBRE DE CLASSES
        const nbClassesField = document.querySelector(`input[name="effectifs[${niveau}][nombre_classes]"]`);
        
        if (nbClassesField) {
            nbClassesField.addEventListener('blur', function() {
                const isValid = validateNombreClasses(this, niveau);
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            nbClassesField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateNombreClasses(this, niveau)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        }
        
        // 2. EFFECTIFS TOTAUX (Garçons, Filles)
        const effectifGarconsField = document.querySelector(`input[name="effectifs[${niveau}][effectif_garcons]"]`);
        const effectifFillesField = document.querySelector(`input[name="effectifs[${niveau}][effectif_filles]"]`);
        
        [effectifGarconsField, effectifFillesField].forEach(field => {
            if (!field) return;
            
            field.addEventListener('blur', function() {
                // Valider l'effectif total
                const isValidEffectif = validateEffectifTotal(niveau);
                
                // AUSSI valider redoublants, abandons, handicaps et situations (cohérence inverse)
                const isValidRedoubl = validateRedoublants(niveau);
                const isValidAbandon = validateAbandons(niveau);
                
                // Valider tous les handicaps
                const handicapTypes = ['handicap_moteur', 'handicap_visuel', 'handicap_sourd_muet', 'handicap_deficience_intel'];
                const isValidHandicaps = handicapTypes.every(type => validateHandicap(niveau, type));
                
                // Valider toutes les situations
                const situationTypes = ['orphelins', 'sans_extrait'];
                const isValidSituations = situationTypes.every(type => validateSituation(niveau, type));
                
                const isValid = isValidEffectif && isValidRedoubl && isValidAbandon && isValidHandicaps && isValidSituations;
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            field.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    const isValidEffectif = validateEffectifTotal(niveau);
                    const isValidRedoubl = validateRedoublants(niveau);
                    const isValidAbandon = validateAbandons(niveau);
                    
                    const handicapTypes = ['handicap_moteur', 'handicap_visuel', 'handicap_sourd_muet', 'handicap_deficience_intel'];
                    const isValidHandicaps = handicapTypes.every(type => validateHandicap(niveau, type));
                    
                    const situationTypes = ['orphelins', 'sans_extrait'];
                    const isValidSituations = situationTypes.every(type => validateSituation(niveau, type));
                    
                    if (!isValidEffectif || !isValidRedoubl || !isValidAbandon || !isValidHandicaps || !isValidSituations) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        invalidFieldBlocked = this;
                        setTimeout(() => { this.focus(); }, 10);
                        return false;
                    }
                }
            });
        });
        
        // 3. REDOUBLANTS (doivent être ≤ effectifs)
        const redoublGarconsField = document.querySelector(`input[name="effectifs[${niveau}][redoublants_garcons]"]`);
        const redoublFillesField = document.querySelector(`input[name="effectifs[${niveau}][redoublants_filles]"]`);
        
        [redoublGarconsField, redoublFillesField].forEach(field => {
            if (!field) return;
            
            field.addEventListener('blur', function() {
                const isValid = validateRedoublants(niveau);
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            field.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateRedoublants(niveau)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        });
        
        // 4. ABANDONS (doivent être ≤ effectifs)
        const abandonGarconsField = document.querySelector(`input[name="effectifs[${niveau}][abandons_garcons]"]`);
        const abandonFillesField = document.querySelector(`input[name="effectifs[${niveau}][abandons_filles]"]`);
        
        [abandonGarconsField, abandonFillesField].forEach(field => {
            if (!field) return;
            
            field.addEventListener('blur', function() {
                const isValid = validateAbandons(niveau);
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            field.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateAbandons(niveau)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        });
        
        // 5. HANDICAPS (doivent être ≤ effectifs)
        const handicapTypes = ['handicap_moteur', 'handicap_visuel', 'handicap_sourd_muet', 'handicap_deficience_intel'];
        
        handicapTypes.forEach(type => {
            const handicapGarconsField = document.querySelector(`input[name="effectifs[${niveau}][${type}_garcons]"]`);
            const handicapFillesField = document.querySelector(`input[name="effectifs[${niveau}][${type}_filles]"]`);
            
            [handicapGarconsField, handicapFillesField].forEach(field => {
                if (!field) return;
                
                field.addEventListener('blur', function() {
                    const isValid = validateHandicap(niveau, type);
                    invalidFieldBlocked = !isValid ? this : null;
                });
                
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab' && !validateHandicap(niveau, type)) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        invalidFieldBlocked = this;
                        setTimeout(() => { this.focus(); }, 10);
                        return false;
                    }
                });
            });
        });
        
        // 6. SITUATIONS SPÉCIALES (doivent être ≤ effectifs)
        const situationTypes = ['orphelins', 'sans_extrait'];
        
        situationTypes.forEach(type => {
            const situationGarconsField = document.querySelector(`input[name="effectifs[${niveau}][${type}_garcons]"]`);
            const situationFillesField = document.querySelector(`input[name="effectifs[${niveau}][${type}_filles]"]`);
            
            [situationGarconsField, situationFillesField].forEach(field => {
                if (!field) return;
                
                field.addEventListener('blur', function() {
                    const isValid = validateSituation(niveau, type);
                    invalidFieldBlocked = !isValid ? this : null;
                });
                
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab' && !validateSituation(niveau, type)) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        invalidFieldBlocked = this;
                        setTimeout(() => { this.focus(); }, 10);
                        return false;
                    }
                });
            });
        });
    });
    
    console.log('✅ Validation Étape 2 - Effectifs activée');
});

// =================== 🎯 VALIDATION ÉTAPE 3 - EXAMENS ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Étape 3 - Examens');
    
    // ===== CMG - CLASSES MULTIGRADES =====
    const cmgNombreField = document.getElementById('cmg_nombre');
    const cmgCombinaison1Field = document.getElementById('cmg_combinaison_1');
    const cmgCombinaison2Field = document.getElementById('cmg_combinaison_2');
    const cmgCombinaison3Field = document.getElementById('cmg_combinaison_3');
    const cmgCombinaisonAutresField = document.getElementById('cmg_combinaison_autres');
    
    // PAS de validation sur cmg_nombre en blur (laisse l'utilisateur saisir les combinaisons d'abord)
    // La validation se fait uniquement sur le dernier champ de combinaison (cmg_combinaison_autres)
    
    // Valider uniquement quand l'utilisateur quitte le dernier champ de combinaison
    if (cmgCombinaisonAutresField) {
        cmgCombinaisonAutresField.addEventListener('blur', function() {
            const isValid = validateCmgCombinaisonRequired();
            invalidFieldBlocked = !isValid ? cmgNombreField : null;
        });
        
        cmgCombinaisonAutresField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCmgCombinaisonRequired()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = cmgNombreField;
                // Remettre le focus sur le champ cmg_nombre pour forcer la correction
                setTimeout(() => { 
                    if (cmgCombinaison1Field && !cmgCombinaison1Field.value.trim()) {
                        cmgCombinaison1Field.focus();
                    } else {
                        cmgNombreField.focus(); 
                    }
                }, 10);
                return false;
            }
        });
    }
    
    // Retirer l'erreur dès qu'une combinaison est remplie
    [cmgCombinaison1Field, cmgCombinaison2Field, cmgCombinaison3Field].forEach(field => {
        if (field) {
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    clearValidationError(cmgNombreField);
                    invalidFieldBlocked = null;
                }
            });
        }
    });
    
    // ===== CFEE - CERTIFICAT DE FIN D'ÉTUDES =====
    const cfeeCandidatsTotalField = document.getElementById('cfee_candidats_total');
    const cfeeCandidatsFillesField = document.getElementById('cfee_candidats_filles');
    const cfeeAdmisTotalField = document.getElementById('cfee_admis_total');
    const cfeeAdmisFillesField = document.getElementById('cfee_admis_filles');
    
    // Candidats Total
    if (cfeeCandidatsTotalField) {
        cfeeCandidatsTotalField.addEventListener('blur', function() {
            const isValid = validateCfeeCandidatsTotal();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        cfeeCandidatsTotalField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCfeeCandidatsTotal()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Candidats Filles
    if (cfeeCandidatsFillesField) {
        cfeeCandidatsFillesField.addEventListener('blur', function() {
            const isValid = validateCfeeCandidatsFilles();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        cfeeCandidatsFillesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCfeeCandidatsFilles()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Admis Total
    if (cfeeAdmisTotalField) {
        cfeeAdmisTotalField.addEventListener('blur', function() {
            const isValid = validateCfeeAdmisTotal();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        cfeeAdmisTotalField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCfeeAdmisTotal()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Admis Filles
    if (cfeeAdmisFillesField) {
        cfeeAdmisFillesField.addEventListener('blur', function() {
            const isValid = validateCfeeAdmisFilles();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        cfeeAdmisFillesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCfeeAdmisFilles()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // ===== ENTRÉE EN SIXIÈME =====
    const sixiemeCandidatsTotalField = document.getElementById('sixieme_candidats_total');
    const sixiemeCandidatsFillesField = document.getElementById('sixieme_candidats_filles');
    const sixiemeAdmisTotalField = document.getElementById('sixieme_admis_total');
    const sixiemeAdmisFillesField = document.getElementById('sixieme_admis_filles');
    
    // Candidats Total
    if (sixiemeCandidatsTotalField) {
        sixiemeCandidatsTotalField.addEventListener('blur', function() {
            const isValid = validateSixiemeCandidatsTotal();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        sixiemeCandidatsTotalField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateSixiemeCandidatsTotal()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Candidats Filles
    if (sixiemeCandidatsFillesField) {
        sixiemeCandidatsFillesField.addEventListener('blur', function() {
            const isValid = validateSixiemeCandidatsFilles();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        sixiemeCandidatsFillesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateSixiemeCandidatsFilles()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Admis Total
    if (sixiemeAdmisTotalField) {
        sixiemeAdmisTotalField.addEventListener('blur', function() {
            const isValid = validateSixiemeAdmisTotal();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        sixiemeAdmisTotalField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateSixiemeAdmisTotal()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Admis Filles
    if (sixiemeAdmisFillesField) {
        sixiemeAdmisFillesField.addEventListener('blur', function() {
            const isValid = validateSixiemeAdmisFilles();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        sixiemeAdmisFillesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateSixiemeAdmisFilles()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // ===== RECRUTEMENT CI - COURS D'INITIATION =====
    const ciOctobreGarconsField = document.getElementById('ci_octobre_garcons');
    const ciOctobreFillesField = document.getElementById('ci_octobre_filles');
    const ciMaiGarconsField = document.getElementById('ci_mai_garcons');
    const ciMaiFillesField = document.getElementById('ci_mai_filles');
    
    // Octobre Garçons
    if (ciOctobreGarconsField) {
        ciOctobreGarconsField.addEventListener('blur', function() {
            const isValid = validateCiOctobreGarcons();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        ciOctobreGarconsField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCiOctobreGarcons()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Octobre Filles
    if (ciOctobreFillesField) {
        ciOctobreFillesField.addEventListener('blur', function() {
            const isValid = validateCiOctobreFilles();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        ciOctobreFillesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCiOctobreFilles()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Mai Garçons
    if (ciMaiGarconsField) {
        ciMaiGarconsField.addEventListener('blur', function() {
            const isValid = validateCiMaiGarcons();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        ciMaiGarconsField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCiMaiGarcons()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Mai Filles
    if (ciMaiFillesField) {
        ciMaiFillesField.addEventListener('blur', function() {
            const isValid = validateCiMaiFilles();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        ciMaiFillesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateCiMaiFilles()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    console.log('✅ Validation Étape 3 - Examens activée');
    
    // Initialiser les calculs de taux (fonctions définies dans les partials)
    setTimeout(() => {
        if (typeof calculateCfeeTotals === 'function') {
            calculateCfeeTotals();
        }
        if (typeof calculateSixiemeTotals === 'function') {
            calculateSixiemeTotals();
        }
        if (typeof calculateRecrutementCiTotals === 'function') {
            calculateRecrutementCiTotals();
        }
    }, 100); // Petit délai pour s'assurer que les partials sont chargés
});

// =================== 🎯 VALIDATION ÉTAPE 4 - PERSONNEL ===================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Initialisation validation Étape 4 - Personnel');
    
    // ===== TIC - COMPÉTENCES TIC =====
    const ticHommesField = document.getElementById('enseignants_formes_tic_hommes');
    const ticFemmesField = document.getElementById('enseignants_formes_tic_femmes');
    
    // Validation TIC Hommes
    if (ticHommesField) {
        ticHommesField.addEventListener('blur', function() {
            const isValid = validateTicHommes();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        ticHommesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateTicHommes()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // Validation TIC Femmes
    if (ticFemmesField) {
        ticFemmesField.addEventListener('blur', function() {
            const isValid = validateTicFemmes();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        ticFemmesField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateTicFemmes()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
    }
    
    // ===== VALIDATION BIDIRECTIONNELLE =====
    // Quand on modifie les champs de spécialité, revalider TIC + CORPS + DIPLÔMES
    const specialiteCategories = ['titulaires', 'vacataires', 'volontaires', 'contractuels', 'communautaires'];
    
    specialiteCategories.forEach(category => {
        const hommesField = document.getElementById(category + '_hommes');
        const femmesField = document.getElementById(category + '_femmes');
        
        if (hommesField) {
            hommesField.addEventListener('blur', function() {
                // Revalider TIC + CORPS + DIPLÔMES quand le total général change
                validateAllPersonnelClassifications();
            });
        }
        
        if (femmesField) {
            femmesField.addEventListener('blur', function() {
                // Revalider TIC + CORPS + DIPLÔMES quand le total général change
                validateAllPersonnelClassifications();
            });
        }
    });
    
    // ===== VALIDATION CORPS =====
    const corpsCategories = ['instituteurs', 'instituteurs_adjoints', 'professeurs'];
    
    corpsCategories.forEach(category => {
        const hommesField = document.getElementById(category + '_hommes');
        const femmesField = document.getElementById(category + '_femmes');
        
        if (hommesField) {
            hommesField.addEventListener('blur', function() {
                const isValid = validateCorpsHommes();
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            hommesField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateCorpsHommes()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        }
        
        if (femmesField) {
            femmesField.addEventListener('blur', function() {
                const isValid = validateCorpsFemmes();
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            femmesField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateCorpsFemmes()) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        }
    });
    
    // ===== VALIDATION DIPLÔMES (INDIVIDUELLE) =====
    // Note: Un enseignant peut avoir plusieurs diplômes, donc validation individuelle
    const diplomesCategories = ['bac', 'bfem', 'cfee', 'licence', 'master', 'autres_diplomes'];
    
    diplomesCategories.forEach(category => {
        const hommesField = document.getElementById(category + '_hommes');
        const femmesField = document.getElementById(category + '_femmes');
        
        if (hommesField) {
            hommesField.addEventListener('blur', function() {
                const isValid = validateDiplomeHommes(this.id);
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            hommesField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateDiplomeHommes(this.id)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        }
        
        if (femmesField) {
            femmesField.addEventListener('blur', function() {
                const isValid = validateDiplomeFemmes(this.id);
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            femmesField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateDiplomeFemmes(this.id)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
        }
    });
    
    console.log('✅ Validation Étape 4 - Personnel activée (TIC + CORPS + DIPLÔMES)');
    
    // Initialiser le calcul des totaux (fonction définie dans les partials)
    setTimeout(() => {
        if (typeof calculatePersonnelTotals === 'function') {
            calculatePersonnelTotals();
        }
    }, 100);
    
    // ============================================
    // ÉTAPE 5 - MATÉRIEL PÉDAGOGIQUE : EVENT LISTENERS
    // ============================================
    
    // 1. MANUELS ÉLÈVES - Limite 1000 par niveau
    const niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    const matieres = ['lc_francais', 'mathematiques', 'edd', 'dm', 'manuel_classe', 'livret_maison', 
                      'livret_devoir_gradue', 'planche_alphabetique', 'manuel_arabe', 'manuel_religion', 
                      'autre_religion', 'autres_manuels'];
    
    niveaux.forEach(niveau => {
        matieres.forEach(matiere => {
            const fieldId = niveau.toLowerCase() + '_' + matiere;
            const field = document.getElementById(fieldId);
            
            if (field) {
                // Validation au blur
                field.addEventListener('blur', function() {
                    const isValid = validateManuelsElevesNiveau(niveau);
                    invalidFieldBlocked = !isValid ? this : null;
                });
                
                // Blocage au Tab si erreur
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab' && !validateManuelsElevesNiveau(niveau)) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        invalidFieldBlocked = this;
                        setTimeout(() => { this.focus(); }, 10);
                        return false;
                    }
                });
            }
        });
    });
    
    // 2. MANUELS MAÎTRE - Limite 1000 par niveau
    const guidesMaitre = ['guide_lc_francais', 'guide_mathematiques', 'guide_edd', 'guide_dm', 
                          'guide_pedagogique', 'guide_arabe_religieux', 'guide_langue_nationale', 
                          'cahier_recits', 'autres_manuels_maitre'];
    
    niveaux.forEach(niveau => {
        guidesMaitre.forEach(guide => {
            const fieldId = niveau.toLowerCase() + '_' + guide;
            const field = document.getElementById(fieldId);
            
            if (field) {
                // Validation au blur
                field.addEventListener('blur', function() {
                    const isValid = validateManuelsMaitreNiveau(niveau);
                    invalidFieldBlocked = !isValid ? this : null;
                });
                
                // Blocage au Tab si erreur
                field.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab' && !validateManuelsMaitreNiveau(niveau)) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        invalidFieldBlocked = this;
                        setTimeout(() => { this.focus(); }, 10);
                        return false;
                    }
                });
            }
        });
    });
    
    // 3. DICTIONNAIRES - Bon état ≤ Total (3 paires)
    const dictionnaires = ['dico_francais', 'dico_arabe', 'dico_autre'];
    
    dictionnaires.forEach(dico => {
        const totalField = document.querySelector(`[name="${dico}_total"]`);
        const bonEtatField = document.querySelector(`[name="${dico}_bon_etat"]`);
        
        if (totalField && bonEtatField) {
            // Validation bon_etat au blur
            bonEtatField.addEventListener('blur', function() {
                const isValid = validateBonEtat(dico + '_total', dico + '_bon_etat', dico);
                invalidFieldBlocked = !isValid ? this : null;
            });
            
            // Blocage Tab si erreur
            bonEtatField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab' && !validateBonEtat(dico + '_total', dico + '_bon_etat', dico)) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    invalidFieldBlocked = this;
                    setTimeout(() => { this.focus(); }, 10);
                    return false;
                }
            });
            
            // Revalidation bidirectionnelle quand total change
            totalField.addEventListener('blur', function() {
                validateBonEtat(dico + '_total', dico + '_bon_etat', dico);
            });
        }
    });
    
    // 4. MATÉRIEL DIDACTIQUE - Bon état ≤ Total (18 paires)
    const materiels = [
        'dico_francais', 'dico_arabe', 'dico_autre',
        'regle_plate', 'equerre', 'compas', 'rapporteur',
        'decametre', 'chaine_arpenteur', 'boussole',
        'thermometre', 'kit_capacite', 'balance',
        'globe', 'cartes_murales', 'planches_illustrees',
        'kit_materiel_scientifique', 'autres_materiel'
    ];
    
    materiels.forEach(materiel => {
        const totalField = document.getElementById(materiel + '_total');
        const bonEtatField = document.getElementById(materiel + '_bon_etat');
        
        if (totalField && bonEtatField) {
            // Validation bon_etat
            bonEtatField.addEventListener('blur', function() {
                const total = parseInt(totalField.value);
                const bonEtat = parseInt(bonEtatField.value);
                
                clearValidationError(this);
                clearValidationError(totalField);
                
                // Vérifier valeurs négatives
                if (total < 0) {
                    showValidationError(totalField, `⚠️ Valeur négative interdite`);
                    invalidFieldBlocked = totalField;
                } else if (bonEtat < 0) {
                    showValidationError(this, `⚠️ Valeur négative interdite`);
                    invalidFieldBlocked = this;
                } else if (bonEtat > total) {
                    showValidationError(this, `⚠️ Bon état (${bonEtat}) > Total (${total})`);
                    showValidationError(totalField, `⚠️ Total (${total}) < Bon état (${bonEtat})`);
                    invalidFieldBlocked = this;
                } else {
                    if (bonEtat > 0) showValidationSuccess(this);
                    if (total > 0) showValidationSuccess(totalField);
                    invalidFieldBlocked = null;
                }
            });
            
            // Blocage Tab
            bonEtatField.addEventListener('keydown', function(e) {
                if (e.key === 'Tab') {
                    const total = parseInt(totalField.value);
                    const bonEtat = parseInt(bonEtatField.value);
                    
                    if (bonEtat < 0 || total < 0 || bonEtat > total) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        invalidFieldBlocked = this;
                        setTimeout(() => { this.focus(); }, 10);
                        return false;
                    }
                }
            });
            
            // Revalidation bidirectionnelle
            totalField.addEventListener('blur', function() {
                const total = parseInt(this.value);
                const bonEtat = parseInt(bonEtatField.value);
                
                clearValidationError(this);
                clearValidationError(bonEtatField);
                
                // Vérifier valeurs négatives
                if (total < 0) {
                    showValidationError(this, `⚠️ Valeur négative interdite`);
                } else if (bonEtat < 0) {
                    showValidationError(bonEtatField, `⚠️ Valeur négative interdite`);
                } else if (bonEtat > total) {
                    showValidationError(bonEtatField, `⚠️ Bon état (${bonEtat}) > Total (${total})`);
                    showValidationError(this, `⚠️ Total (${total}) < Bon état (${bonEtat})`);
                } else {
                    if (bonEtat > 0) showValidationSuccess(bonEtatField);
                    if (total > 0) showValidationSuccess(this);
                }
            });
        }
    });
    
    // 5. GÉOMÉTRIE - SUPPRIMÉ (instruments de géométrie déjà validés dans Matériel Didactique)
    
    console.log('✅ Validation Étape 5 - Matériel Pédagogique activée (33 validations)');
    
    // ========================================
    // ÉTAPE 6 - INFRASTRUCTURE (33 paires)
    // ========================================
    
    // Helper pour ajouter les event listeners sur une paire
    function addBonEtatListeners(totalName, bonEtatName, validateFn) {
        const totalField = document.querySelector(`[name="${totalName}"]`);
        const bonEtatField = document.querySelector(`[name="${bonEtatName}"]`);
        
        if (!totalField || !bonEtatField) return;
        
        // Validation sur bon_état (blur + Tab)
        bonEtatField.addEventListener('blur', function() {
            const isValid = validateFn();
            invalidFieldBlocked = !isValid ? this : null;
        });
        
        bonEtatField.addEventListener('keydown', function(e) {
            if (e.key === 'Tab' && !validateFn()) {
                e.preventDefault();
                e.stopImmediatePropagation();
                invalidFieldBlocked = this;
                setTimeout(() => { this.focus(); }, 10);
                return false;
            }
        });
        
        // Revalidation bidirectionnelle quand total change
        totalField.addEventListener('blur', function() {
            validateFn();
        });
    }
    
    // 1. CAPITAL IMMOBILIER - 15 paires
    const immobilierPairs = [
        ['salles_dur_total', 'salles_dur_bon_etat'],
        ['abris_provisoires_total', 'abris_provisoires_bon_etat'],
        ['bloc_admin_total', 'bloc_admin_bon_etat'],
        ['magasin_total', 'magasin_bon_etat'],
        ['salle_informatique_total', 'salle_informatique_bon_etat'],
        ['salle_bibliotheque_total', 'salle_bibliotheque_bon_etat'],
        ['cuisine_total', 'cuisine_bon_etat'],
        ['refectoire_total', 'refectoire_bon_etat'],
        ['toilettes_enseignants_total', 'toilettes_enseignants_bon_etat'],
        ['toilettes_garcons_total', 'toilettes_garcons_bon_etat'],
        ['toilettes_filles_total', 'toilettes_filles_bon_etat'],
        ['toilettes_mixtes_total', 'toilettes_mixtes_bon_etat'],
        ['logement_directeur_total', 'logement_directeur_bon_etat'],
        ['logement_gardien_total', 'logement_gardien_bon_etat'],
        ['autres_infrastructures_total', 'autres_infrastructures_bon_etat']
    ];
    
    immobilierPairs.forEach(([totalName, bonEtatName]) => {
        addBonEtatListeners(totalName, bonEtatName, validateCapitalImmobilier);
    });
    
    // 2. CAPITAL MOBILIER - 9 paires
    const mobilierPairs = [
        ['tables_bancs_total', 'tables_bancs_bon_etat'],
        ['chaises_eleves_total', 'chaises_eleves_bon_etat'],
        ['tables_individuelles_total', 'tables_individuelles_bon_etat'],
        ['bureaux_maitre_total', 'bureaux_maitre_bon_etat'],
        ['chaises_maitre_total', 'chaises_maitre_bon_etat'],
        ['tableaux_total', 'tableaux_bon_etat'],
        ['armoires_total', 'armoires_bon_etat'],
        ['bureaux_admin_total', 'bureaux_admin_bon_etat'],
        ['chaises_admin_total', 'chaises_admin_bon_etat']
    ];
    
    mobilierPairs.forEach(([totalName, bonEtatName]) => {
        addBonEtatListeners(totalName, bonEtatName, validateCapitalMobilier);
    });
    
    // 3. ÉQUIPEMENTS INFORMATIQUES - 9 paires
    const equipementsPairs = [
        ['ordinateurs_fixes_total', 'ordinateurs_fixes_bon_etat'],
        ['ordinateurs_portables_total', 'ordinateurs_portables_bon_etat'],
        ['tablettes_total', 'tablettes_bon_etat'],
        ['imprimantes_laser_total', 'imprimantes_laser_bon_etat'],
        ['imprimantes_jet_encre_total', 'imprimantes_jet_encre_bon_etat'],
        ['imprimantes_multifonction_total', 'imprimantes_multifonction_bon_etat'],
        ['photocopieuses_total', 'photocopieuses_bon_etat'],
        ['videoprojecteurs_total', 'videoprojecteurs_bon_etat'],
        ['autres_equipements_total', 'autres_equipements_bon_etat']
    ];
    
    equipementsPairs.forEach(([totalName, bonEtatName]) => {
        addBonEtatListeners(totalName, bonEtatName, validateEquipementsInformatiques);
    });
    
    console.log('✅ Validation Étape 6 - Infrastructure activée (33 validations)');
});

// Validation nombre de classes
function validateNombreClasses(field, niveau) {
    const value = field.value.trim();
    clearValidationError(field);
    
    // Optionnel
    if (!value) {
        field.style.border = '';
        field.style.backgroundColor = '';
        return true;
    }
    
    const nb = parseInt(value);
    
    if (isNaN(nb) || nb < 0) {
        showValidationError(field, 'Nombre de classes invalide');
        return false;
    }
    
    showValidationSuccess(field);
    return true;
}

// Validation effectif total (cohérence avec nombre de classes)
function validateEffectifTotal(niveau) {
    const nbClassesField = document.querySelector(`input[name="effectifs[${niveau}][nombre_classes]"]`);
    const garconsField = document.querySelector(`input[name="effectifs[${niveau}][effectif_garcons]"]`);
    const fillesField = document.querySelector(`input[name="effectifs[${niveau}][effectif_filles]"]`);
    
    if (!nbClassesField || !garconsField || !fillesField) return true;
    
    const nbClasses = parseInt(nbClassesField.value) || 0;
    const garcons = parseInt(garconsField.value) || 0;
    const filles = parseInt(fillesField.value) || 0;
    const total = garcons + filles;
    
    clearValidationError(garconsField);
    clearValidationError(fillesField);
    
    // Si nombre de classes > 0, l'effectif total DOIT être > 0
    if (nbClasses > 0 && total === 0) {
        showValidationError(garconsField, `Si ${niveau} a ${nbClasses} classe(s), l'effectif doit être > 0`);
        return false;
    }
    
    // Valeurs négatives
    if (garcons < 0 || filles < 0) {
        showValidationError(garconsField, 'Les effectifs ne peuvent pas être négatifs');
        return false;
    }
    
    showValidationSuccess(garconsField);
    showValidationSuccess(fillesField);
    return true;
}

// Validation redoublants (≤ effectifs)
function validateRedoublants(niveau) {
    const effectifGarconsField = document.querySelector(`input[name="effectifs[${niveau}][effectif_garcons]"]`);
    const effectifFillesField = document.querySelector(`input[name="effectifs[${niveau}][effectif_filles]"]`);
    const redoublGarconsField = document.querySelector(`input[name="effectifs[${niveau}][redoublants_garcons]"]`);
    const redoublFillesField = document.querySelector(`input[name="effectifs[${niveau}][redoublants_filles]"]`);
    
    if (!effectifGarconsField || !redoublGarconsField) return true;
    
    const effectifGarcons = parseInt(effectifGarconsField.value) || 0;
    const effectifFilles = parseInt(effectifFillesField.value) || 0;
    const redoublGarcons = parseInt(redoublGarconsField.value) || 0;
    const redoublFilles = parseInt(redoublFillesField.value) || 0;
    
    clearValidationError(redoublGarconsField);
    clearValidationError(redoublFillesField);
    clearValidationError(effectifGarconsField);
    clearValidationError(effectifFillesField);
    
    let isValid = true;
    
    // Redoublants garçons ≤ effectif garçons
    if (redoublGarcons > effectifGarcons) {
        showValidationError(redoublGarconsField, `⚠️ Redoublants G (${redoublGarcons}) > Effectif G (${effectifGarcons})`);
        showValidationError(effectifGarconsField, `⚠️ Effectif G (${effectifGarcons}) < Redoublants G (${redoublGarcons})`);
        isValid = false;
    }
    
    // Redoublants filles ≤ effectif filles
    if (redoublFilles > effectifFilles) {
        showValidationError(redoublFillesField, `⚠️ Redoublants F (${redoublFilles}) > Effectif F (${effectifFilles})`);
        showValidationError(effectifFillesField, `⚠️ Effectif F (${effectifFilles}) < Redoublants F (${redoublFilles})`);
        isValid = false;
    }
    
    if (isValid) {
        if (redoublGarcons > 0) showValidationSuccess(redoublGarconsField);
        if (redoublFilles > 0) showValidationSuccess(redoublFillesField);
    }
    
    return isValid;
}

// Validation abandons (≤ effectifs)
function validateAbandons(niveau) {
    const effectifGarconsField = document.querySelector(`input[name="effectifs[${niveau}][effectif_garcons]"]`);
    const effectifFillesField = document.querySelector(`input[name="effectifs[${niveau}][effectif_filles]"]`);
    const abandonGarconsField = document.querySelector(`input[name="effectifs[${niveau}][abandons_garcons]"]`);
    const abandonFillesField = document.querySelector(`input[name="effectifs[${niveau}][abandons_filles]"]`);
    
    if (!effectifGarconsField || !abandonGarconsField) return true;
    
    const effectifGarcons = parseInt(effectifGarconsField.value) || 0;
    const effectifFilles = parseInt(effectifFillesField.value) || 0;
    const abandonGarcons = parseInt(abandonGarconsField.value) || 0;
    const abandonFilles = parseInt(abandonFillesField.value) || 0;
    
    clearValidationError(abandonGarconsField);
    clearValidationError(abandonFillesField);
    
    let isValid = true;
    
    // Abandons garçons ≤ effectif garçons
    if (abandonGarcons > effectifGarcons) {
        showValidationError(abandonGarconsField, `⚠️ Abandons G (${abandonGarcons}) > Effectif G (${effectifGarcons})`);
        if (!effectifGarconsField.parentElement.querySelector('.validation-error')) {
            showValidationError(effectifGarconsField, `⚠️ Effectif G (${effectifGarcons}) < Abandons G (${abandonGarcons})`);
        }
        isValid = false;
    }
    
    // Abandons filles ≤ effectif filles
    if (abandonFilles > effectifFilles) {
        showValidationError(abandonFillesField, `⚠️ Abandons F (${abandonFilles}) > Effectif F (${effectifFilles})`);
        if (!effectifFillesField.parentElement.querySelector('.validation-error')) {
            showValidationError(effectifFillesField, `⚠️ Effectif F (${effectifFilles}) < Abandons F (${abandonFilles})`);
        }
        isValid = false;
    }
    
    if (isValid) {
        if (abandonGarcons > 0) showValidationSuccess(abandonGarconsField);
        if (abandonFilles > 0) showValidationSuccess(abandonFillesField);
    }
    
    return isValid;
}

// Validation handicaps (≤ effectifs)
function validateHandicap(niveau, type) {
    const effectifGarconsField = document.querySelector(`input[name="effectifs[${niveau}][effectif_garcons]"]`);
    const effectifFillesField = document.querySelector(`input[name="effectifs[${niveau}][effectif_filles]"]`);
    const handicapGarconsField = document.querySelector(`input[name="effectifs[${niveau}][${type}_garcons]"]`);
    const handicapFillesField = document.querySelector(`input[name="effectifs[${niveau}][${type}_filles]"]`);
    
    if (!effectifGarconsField || !handicapGarconsField) return true;
    
    const effectifGarcons = parseInt(effectifGarconsField.value) || 0;
    const effectifFilles = parseInt(effectifFillesField.value) || 0;
    const handicapGarcons = parseInt(handicapGarconsField.value) || 0;
    const handicapFilles = parseInt(handicapFillesField.value) || 0;
    
    clearValidationError(handicapGarconsField);
    clearValidationError(handicapFillesField);
    
    let isValid = true;
    
    // Handicaps garçons ≤ effectif garçons
    if (handicapGarcons > effectifGarcons) {
        showValidationError(handicapGarconsField, `⚠️ Handicap G (${handicapGarcons}) > Effectif G (${effectifGarcons})`);
        isValid = false;
    }
    
    // Handicaps filles ≤ effectif filles
    if (handicapFilles > effectifFilles) {
        showValidationError(handicapFillesField, `⚠️ Handicap F (${handicapFilles}) > Effectif F (${effectifFilles})`);
        isValid = false;
    }
    
    if (isValid) {
        if (handicapGarcons > 0) showValidationSuccess(handicapGarconsField);
        if (handicapFilles > 0) showValidationSuccess(handicapFillesField);
    }
    
    return isValid;
}

// Validation situations spéciales (≤ effectifs)
function validateSituation(niveau, type) {
    const effectifGarconsField = document.querySelector(`input[name="effectifs[${niveau}][effectif_garcons]"]`);
    const effectifFillesField = document.querySelector(`input[name="effectifs[${niveau}][effectif_filles]"]`);
    const situationGarconsField = document.querySelector(`input[name="effectifs[${niveau}][${type}_garcons]"]`);
    const situationFillesField = document.querySelector(`input[name="effectifs[${niveau}][${type}_filles]"]`);
    
    if (!effectifGarconsField || !situationGarconsField) return true;
    
    const effectifGarcons = parseInt(effectifGarconsField.value) || 0;
    const effectifFilles = parseInt(effectifFillesField.value) || 0;
    const situationGarcons = parseInt(situationGarconsField.value) || 0;
    const situationFilles = parseInt(situationFillesField.value) || 0;
    
    clearValidationError(situationGarconsField);
    clearValidationError(situationFillesField);
    
    let isValid = true;
    
    // Situations garçons ≤ effectif garçons
    if (situationGarcons > effectifGarcons) {
        showValidationError(situationGarconsField, `⚠️ Situation G (${situationGarcons}) > Effectif G (${effectifGarcons})`);
        isValid = false;
    }
    
    // Situations filles ≤ effectif filles
    if (situationFilles > effectifFilles) {
        showValidationError(situationFillesField, `⚠️ Situation F (${situationFilles}) > Effectif F (${effectifFilles})`);
        isValid = false;
    }
    
    if (isValid) {
        if (situationGarcons > 0) showValidationSuccess(situationGarconsField);
        if (situationFilles > 0) showValidationSuccess(situationFillesField);
    }
    
    return isValid;
}

// ============================================
// ÉTAPE 3 - EXAMENS - VALIDATIONS
// ============================================

// Validation CMG (Classes Multigrades)
function validateCmgCombinaisonRequired() {
    const nombreField = document.getElementById('cmg_nombre');
    const combinaison1Field = document.getElementById('cmg_combinaison_1');
    const combinaison2Field = document.getElementById('cmg_combinaison_2');
    const combinaison3Field = document.getElementById('cmg_combinaison_3');
    const combinaisonAutresField = document.getElementById('cmg_combinaison_autres');
    
    if (!nombreField) return true;
    
    const nombre = parseInt(nombreField.value) || 0;
    
    // Si nombre > 0, au moins une combinaison doit être remplie
    if (nombre > 0) {
        const combi1 = (combinaison1Field?.value || '').trim();
        const combi2 = (combinaison2Field?.value || '').trim();
        const combi3 = (combinaison3Field?.value || '').trim();
        const combiAutres = (combinaisonAutresField?.value || '').trim();
        
        if (!combi1 && !combi2 && !combi3 && !combiAutres) {
            showValidationError(nombreField, `⚠️ ${nombre} classe(s) CMG → Au moins 1 combinaison requise`);
            return false;
        }
    }
    
    showValidationSuccess(nombreField);
    return true;
}

function validateCmgCombinaisonField(field) {
    if (!field) return true;
    
    const nombreField = document.getElementById('cmg_nombre');
    const nombre = parseInt(nombreField?.value) || 0;
    
    // Revalider si nombre > 0
    if (nombre > 0) {
        return validateCmgCombinaisonRequired();
    }
    
    return true;
}

// Validation CFEE - Candidats
function validateCfeeCandidatsTotal() {
    const totalField = document.getElementById('cfee_candidats_total');
    const fillesField = document.getElementById('cfee_candidats_filles');
    
    if (!totalField || !fillesField) return true;
    
    const total = parseInt(totalField.value) || 0;
    const filles = parseInt(fillesField.value) || 0;
    
    clearValidationError(totalField);
    clearValidationError(fillesField);
    
    // Total doit être ≥ filles
    if (total < filles) {
        showValidationError(totalField, `⚠️ Candidats total (${total}) < Candidates filles (${filles})`);
        showValidationError(fillesField, `⚠️ Candidates filles (${filles}) > Candidats total (${total})`);
        return false;
    }
    
    if (total > 0 || filles > 0) {
        showValidationSuccess(totalField);
        showValidationSuccess(fillesField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateCfeeTotals === 'function') {
        calculateCfeeTotals();
    }
    
    // Revalider admis (bidirectionnel)
    validateCfeeAdmisTotal();
    validateCfeeAdmisFilles();
    
    return true;
}

function validateCfeeCandidatsFilles() {
    const totalField = document.getElementById('cfee_candidats_total');
    const fillesField = document.getElementById('cfee_candidats_filles');
    
    if (!totalField || !fillesField) return true;
    
    const total = parseInt(totalField.value) || 0;
    const filles = parseInt(fillesField.value) || 0;
    
    clearValidationError(totalField);
    clearValidationError(fillesField);
    
    // Filles ne peuvent pas dépasser total
    if (filles > total) {
        showValidationError(fillesField, `⚠️ Candidates filles (${filles}) > Candidats total (${total})`);
        showValidationError(totalField, `⚠️ Candidats total (${total}) < Candidates filles (${filles})`);
        return false;
    }
    
    if (total > 0 || filles > 0) {
        showValidationSuccess(totalField);
        showValidationSuccess(fillesField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateCfeeTotals === 'function') {
        calculateCfeeTotals();
    }
    
    // Revalider admis filles (bidirectionnel)
    validateCfeeAdmisFilles();
    
    return true;
}

// Validation CFEE - Admis
function validateCfeeAdmisTotal() {
    const admisField = document.getElementById('cfee_admis_total');
    const candidatsField = document.getElementById('cfee_candidats_total');
    const admisFillesField = document.getElementById('cfee_admis_filles');
    
    if (!admisField || !candidatsField) return true;
    
    const admis = parseInt(admisField.value) || 0;
    const candidats = parseInt(candidatsField.value) || 0;
    const admisFilles = parseInt(admisFillesField?.value) || 0;
    
    clearValidationError(admisField);
    
    let isValid = true;
    
    // Admis total ≥ admis filles
    if (admis < admisFilles) {
        showValidationError(admisField, `⚠️ Admis total (${admis}) < Admises filles (${admisFilles})`);
        isValid = false;
    }
    
    // Admis ≤ candidats
    if (admis > candidats) {
        showValidationError(admisField, `⚠️ Admis total (${admis}) > Candidats total (${candidats})`);
        clearValidationError(candidatsField);
        showValidationError(candidatsField, `⚠️ Candidats total (${candidats}) < Admis total (${admis})`);
        isValid = false;
    }
    
    if (isValid && admis > 0) {
        showValidationSuccess(admisField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateCfeeTotals === 'function') {
        calculateCfeeTotals();
    }
    
    return isValid;
}

function validateCfeeAdmisFilles() {
    const admisFillesField = document.getElementById('cfee_admis_filles');
    const candidatsFillesField = document.getElementById('cfee_candidats_filles');
    const admisTotalField = document.getElementById('cfee_admis_total');
    const candidatsTotalField = document.getElementById('cfee_candidats_total');
    
    if (!admisFillesField || !candidatsFillesField) return true;
    
    const admisFilles = parseInt(admisFillesField.value) || 0;
    const candidatsFilles = parseInt(candidatsFillesField.value) || 0;
    const admisTotal = parseInt(admisTotalField?.value) || 0;
    const candidatsTotal = parseInt(candidatsTotalField?.value) || 0;
    
    clearValidationError(admisFillesField);
    
    let isValid = true;
    
    // Admises filles ≤ candidates filles
    if (admisFilles > candidatsFilles) {
        showValidationError(admisFillesField, `⚠️ Admises filles (${admisFilles}) > Candidates filles (${candidatsFilles})`);
        clearValidationError(candidatsFillesField);
        showValidationError(candidatsFillesField, `⚠️ Candidates filles (${candidatsFilles}) < Admises filles (${admisFilles})`);
        isValid = false;
    }
    
    // Admises filles ≤ admis total
    if (admisFilles > admisTotal) {
        showValidationError(admisFillesField, `⚠️ Admises filles (${admisFilles}) > Admis total (${admisTotal})`);
        isValid = false;
    }
    
    // VALIDATION DE COHÉRENCE : Si 100% d'admis, alors filles admises = filles candidates
    if (admisTotal > 0 && candidatsTotal > 0 && admisTotal === candidatsTotal) {
        // 100% d'admission → les filles admises DOIVENT être égales aux filles candidates
        if (admisFilles !== candidatsFilles) {
            showValidationError(admisFillesField, `⚠️ 100% admis (${admisTotal}/${candidatsTotal}) → Admises filles (${admisFilles}) doit = Candidates filles (${candidatsFilles})`);
            isValid = false;
        }
    }
    
    if (isValid && admisFilles > 0) {
        showValidationSuccess(admisFillesField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateCfeeTotals === 'function') {
        calculateCfeeTotals();
    }
    
    // Revalider admis total (bidirectionnel)
    validateCfeeAdmisTotal();
    
    return isValid;
}

// Validation Entrée Sixième - Candidats
function validateSixiemeCandidatsTotal() {
    const totalField = document.getElementById('sixieme_candidats_total');
    const fillesField = document.getElementById('sixieme_candidats_filles');
    
    if (!totalField || !fillesField) return true;
    
    const total = parseInt(totalField.value) || 0;
    const filles = parseInt(fillesField.value) || 0;
    
    clearValidationError(totalField);
    clearValidationError(fillesField);
    
    // Total doit être ≥ filles
    if (total < filles) {
        showValidationError(totalField, `⚠️ Candidats total (${total}) < Candidates filles (${filles})`);
        showValidationError(fillesField, `⚠️ Candidates filles (${filles}) > Candidats total (${total})`);
        return false;
    }
    
    if (total > 0 || filles > 0) {
        showValidationSuccess(totalField);
        showValidationSuccess(fillesField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateSixiemeTotals === 'function') {
        calculateSixiemeTotals();
    }
    
    // Revalider admis (bidirectionnel)
    validateSixiemeAdmisTotal();
    validateSixiemeAdmisFilles();
    
    return true;
}

function validateSixiemeCandidatsFilles() {
    const totalField = document.getElementById('sixieme_candidats_total');
    const fillesField = document.getElementById('sixieme_candidats_filles');
    
    if (!totalField || !fillesField) return true;
    
    const total = parseInt(totalField.value) || 0;
    const filles = parseInt(fillesField.value) || 0;
    
    clearValidationError(totalField);
    clearValidationError(fillesField);
    
    // Filles ne peuvent pas dépasser total
    if (filles > total) {
        showValidationError(fillesField, `⚠️ Candidates filles (${filles}) > Candidats total (${total})`);
        showValidationError(totalField, `⚠️ Candidats total (${total}) < Candidates filles (${filles})`);
        return false;
    }
    
    if (total > 0 || filles > 0) {
        showValidationSuccess(totalField);
        showValidationSuccess(fillesField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateSixiemeTotals === 'function') {
        calculateSixiemeTotals();
    }
    
    // Revalider admis filles (bidirectionnel)
    validateSixiemeAdmisFilles();
    
    return true;
}

// Validation Entrée Sixième - Admis
function validateSixiemeAdmisTotal() {
    const admisField = document.getElementById('sixieme_admis_total');
    const candidatsField = document.getElementById('sixieme_candidats_total');
    const admisFillesField = document.getElementById('sixieme_admis_filles');
    
    if (!admisField || !candidatsField) return true;
    
    const admis = parseInt(admisField.value) || 0;
    const candidats = parseInt(candidatsField.value) || 0;
    const admisFilles = parseInt(admisFillesField?.value) || 0;
    
    clearValidationError(admisField);
    
    let isValid = true;
    
    // Admis total ≥ admis filles
    if (admis < admisFilles) {
        showValidationError(admisField, `⚠️ Admis total (${admis}) < Admises filles (${admisFilles})`);
        isValid = false;
    }
    
    // Admis ≤ candidats
    if (admis > candidats) {
        showValidationError(admisField, `⚠️ Admis total (${admis}) > Candidats total (${candidats})`);
        clearValidationError(candidatsField);
        showValidationError(candidatsField, `⚠️ Candidats total (${candidats}) < Admis total (${admis})`);
        isValid = false;
    }
    
    if (isValid && admis > 0) {
        showValidationSuccess(admisField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateSixiemeTotals === 'function') {
        calculateSixiemeTotals();
    }
    
    return isValid;
}

function validateSixiemeAdmisFilles() {
    const admisFillesField = document.getElementById('sixieme_admis_filles');
    const candidatsFillesField = document.getElementById('sixieme_candidats_filles');
    const admisTotalField = document.getElementById('sixieme_admis_total');
    const candidatsTotalField = document.getElementById('sixieme_candidats_total');
    
    if (!admisFillesField || !candidatsFillesField) return true;
    
    const admisFilles = parseInt(admisFillesField.value) || 0;
    const candidatsFilles = parseInt(candidatsFillesField.value) || 0;
    const admisTotal = parseInt(admisTotalField?.value) || 0;
    const candidatsTotal = parseInt(candidatsTotalField?.value) || 0;
    
    clearValidationError(admisFillesField);
    
    let isValid = true;
    
    // Admises filles ≤ candidates filles
    if (admisFilles > candidatsFilles) {
        showValidationError(admisFillesField, `⚠️ Admises filles (${admisFilles}) > Candidates filles (${candidatsFilles})`);
        clearValidationError(candidatsFillesField);
        showValidationError(candidatsFillesField, `⚠️ Candidates filles (${candidatsFilles}) < Admises filles (${admisFilles})`);
        isValid = false;
    }
    
    // Admises filles ≤ admis total
    if (admisFilles > admisTotal) {
        showValidationError(admisFillesField, `⚠️ Admises filles (${admisFilles}) > Admis total (${admisTotal})`);
        isValid = false;
    }
    
    // VALIDATION DE COHÉRENCE : Si 100% d'admis, alors filles admises = filles candidates
    if (admisTotal > 0 && candidatsTotal > 0 && admisTotal === candidatsTotal) {
        // 100% d'admission → les filles admises DOIVENT être égales aux filles candidates
        if (admisFilles !== candidatsFilles) {
            showValidationError(admisFillesField, `⚠️ 100% admis (${admisTotal}/${candidatsTotal}) → Admises filles (${admisFilles}) doit = Candidates filles (${candidatsFilles})`);
            isValid = false;
        }
    }
    
    if (isValid && admisFilles > 0) {
        showValidationSuccess(admisFillesField);
    }
    
    // Mettre à jour les taux
    if (typeof calculateSixiemeTotals === 'function') {
        calculateSixiemeTotals();
    }
    
    // Revalider admis total (bidirectionnel)
    validateSixiemeAdmisTotal();
    
    return isValid;
}

// Validation Recrutement CI - Effectifs Mai ≤ Octobre
function validateCiMaiGarcons() {
    const octobreField = document.getElementById('ci_octobre_garcons');
    const maiField = document.getElementById('ci_mai_garcons');
    
    if (!octobreField || !maiField) return true;
    
    const octobre = parseInt(octobreField.value) || 0;
    const mai = parseInt(maiField.value) || 0;
    
    clearValidationError(maiField);
    
    // Mai ne peut pas dépasser Octobre (abandons possibles entre octobre et mai)
    if (mai > octobre) {
        showValidationError(maiField, `⚠️ Mai garçons (${mai}) > Octobre garçons (${octobre})`);
        clearValidationError(octobreField);
        showValidationError(octobreField, `⚠️ Octobre garçons (${octobre}) < Mai garçons (${mai})`);
        return false;
    }
    
    if (mai > 0) showValidationSuccess(maiField);
    if (octobre > 0) showValidationSuccess(octobreField);
    
    return true;
}

function validateCiMaiFilles() {
    const octobreField = document.getElementById('ci_octobre_filles');
    const maiField = document.getElementById('ci_mai_filles');
    
    if (!octobreField || !maiField) return true;
    
    const octobre = parseInt(octobreField.value) || 0;
    const mai = parseInt(maiField.value) || 0;
    
    clearValidationError(maiField);
    
    // Mai ne peut pas dépasser Octobre
    if (mai > octobre) {
        showValidationError(maiField, `⚠️ Mai filles (${mai}) > Octobre filles (${octobre})`);
        clearValidationError(octobreField);
        showValidationError(octobreField, `⚠️ Octobre filles (${octobre}) < Mai filles (${mai})`);
        return false;
    }
    
    if (mai > 0) showValidationSuccess(maiField);
    if (octobre > 0) showValidationSuccess(octobreField);
    
    return true;
}

function validateCiOctobreGarcons() {
    const octobreField = document.getElementById('ci_octobre_garcons');
    const maiField = document.getElementById('ci_mai_garcons');
    
    if (!octobreField || !maiField) return true;
    
    const octobre = parseInt(octobreField.value) || 0;
    const mai = parseInt(maiField.value) || 0;
    
    clearValidationError(octobreField);
    
    // Octobre doit être ≥ Mai
    if (octobre < mai) {
        showValidationError(octobreField, `⚠️ Octobre garçons (${octobre}) < Mai garçons (${mai})`);
        clearValidationError(maiField);
        showValidationError(maiField, `⚠️ Mai garçons (${mai}) > Octobre garçons (${octobre})`);
        return false;
    }
    
    if (mai > 0) showValidationSuccess(maiField);
    if (octobre > 0) showValidationSuccess(octobreField);
    
    return true;
}

function validateCiOctobreFilles() {
    const octobreField = document.getElementById('ci_octobre_filles');
    const maiField = document.getElementById('ci_mai_filles');
    
    if (!octobreField || !maiField) return true;
    
    const octobre = parseInt(octobreField.value) || 0;
    const mai = parseInt(maiField.value) || 0;
    
    clearValidationError(octobreField);
    
    // Octobre doit être ≥ Mai
    if (octobre < mai) {
        showValidationError(octobreField, `⚠️ Octobre filles (${octobre}) < Mai filles (${mai})`);
        clearValidationError(maiField);
        showValidationError(maiField, `⚠️ Mai filles (${mai}) > Octobre filles (${octobre})`);
        return false;
    }
    
    if (mai > 0) showValidationSuccess(maiField);
    if (octobre > 0) showValidationSuccess(octobreField);
    
    return true;
}

// ============================================
// ÉTAPE 4 - PERSONNEL - VALIDATIONS
// ============================================

// Fonction helper pour calculer le total général des enseignants (depuis spécialité)
function getTotalGeneralEnseignants() {
    const specialiteCategories = ['titulaires', 'vacataires', 'volontaires', 'contractuels', 'communautaires'];
    let totalHommes = 0;
    let totalFemmes = 0;
    
    specialiteCategories.forEach(category => {
        const hommes = parseInt(document.getElementById(category + '_hommes')?.value) || 0;
        const femmes = parseInt(document.getElementById(category + '_femmes')?.value) || 0;
        totalHommes += hommes;
        totalFemmes += femmes;
    });
    
    return {
        hommes: totalHommes,
        femmes: totalFemmes,
        total: totalHommes + totalFemmes
    };
}

// Validation TIC Hommes ≤ Total Hommes
function validateTicHommes() {
    const ticHommesField = document.getElementById('enseignants_formes_tic_hommes');
    
    if (!ticHommesField) return true;
    
    const ticHommes = parseInt(ticHommesField.value) || 0;
    const totaux = getTotalGeneralEnseignants();
    
    clearValidationError(ticHommesField);
    
    // TIC Hommes ne peut pas dépasser le total des hommes enseignants
    if (ticHommes > totaux.hommes) {
        showValidationError(ticHommesField, `⚠️ Formés TIC H (${ticHommes}) > Total enseignants H (${totaux.hommes})`);
        return false;
    }
    
    if (ticHommes > 0) showValidationSuccess(ticHommesField);
    
    return true;
}

// Validation TIC Femmes ≤ Total Femmes
function validateTicFemmes() {
    const ticFemmesField = document.getElementById('enseignants_formes_tic_femmes');
    
    if (!ticFemmesField) return true;
    
    const ticFemmes = parseInt(ticFemmesField.value) || 0;
    const totaux = getTotalGeneralEnseignants();
    
    clearValidationError(ticFemmesField);
    
    // TIC Femmes ne peut pas dépasser le total des femmes enseignantes
    if (ticFemmes > totaux.femmes) {
        showValidationError(ticFemmesField, `⚠️ Formés TIC F (${ticFemmes}) > Total enseignants F (${totaux.femmes})`);
        return false;
    }
    
    if (ticFemmes > 0) showValidationSuccess(ticFemmesField);
    
    return true;
}

// Validation inverse : quand on modifie les totaux, revalider TIC
function validateTicWhenTotalChanges() {
    // Revalider les TIC quand les totaux changent
    validateTicHommes();
    validateTicFemmes();
}

// Validation Corps Hommes ≤ Total Hommes
function validateCorpsHommes() {
    const corpsCategories = ['instituteurs', 'instituteurs_adjoints', 'professeurs'];
    let totalCorpsHommes = 0;
    
    // Calculer la somme des corps hommes
    corpsCategories.forEach(category => {
        const value = parseInt(document.getElementById(category + '_hommes')?.value) || 0;
        totalCorpsHommes += value;
    });
    
    const totaux = getTotalGeneralEnseignants();
    
    // Vérifier que la somme des corps ne dépasse pas le total spécialité
    if (totalCorpsHommes > totaux.hommes) {
        // Afficher erreur sur chaque champ de corps
        corpsCategories.forEach(category => {
            const field = document.getElementById(category + '_hommes');
            if (field && parseInt(field.value) > 0) {
                showValidationError(field, `⚠️ Somme Corps H (${totalCorpsHommes}) > Total H (${totaux.hommes})`);
            }
        });
        return false;
    }
    
    // Clear errors si tout est OK
    corpsCategories.forEach(category => {
        const field = document.getElementById(category + '_hommes');
        if (field) clearValidationError(field);
    });
    
    return true;
}

// Validation Corps Femmes ≤ Total Femmes
function validateCorpsFemmes() {
    const corpsCategories = ['instituteurs', 'instituteurs_adjoints', 'professeurs'];
    let totalCorpsFemmes = 0;
    
    // Calculer la somme des corps femmes
    corpsCategories.forEach(category => {
        const value = parseInt(document.getElementById(category + '_femmes')?.value) || 0;
        totalCorpsFemmes += value;
    });
    
    const totaux = getTotalGeneralEnseignants();
    
    // Vérifier que la somme des corps ne dépasse pas le total spécialité
    if (totalCorpsFemmes > totaux.femmes) {
        // Afficher erreur sur chaque champ de corps
        corpsCategories.forEach(category => {
            const field = document.getElementById(category + '_femmes');
            if (field && parseInt(field.value) > 0) {
                showValidationError(field, `⚠️ Somme Corps F (${totalCorpsFemmes}) > Total F (${totaux.femmes})`);
            }
        });
        return false;
    }
    
    // Clear errors si tout est OK
    corpsCategories.forEach(category => {
        const field = document.getElementById(category + '_femmes');
        if (field) clearValidationError(field);
    });
    
    return true;
}

// Validation INDIVIDUELLE : Chaque diplôme H ≤ Total H
// Note: Un enseignant peut avoir plusieurs diplômes, donc on ne somme PAS
function validateDiplomeHommes(diplomeId) {
    const field = document.getElementById(diplomeId);
    if (!field) return true;
    
    const value = parseInt(field.value) || 0;
    const totaux = getTotalGeneralEnseignants();
    
    clearValidationError(field);
    
    // Chaque diplôme individuellement ne peut pas dépasser le total
    if (value > totaux.hommes) {
        showValidationError(field, `⚠️ ${value} enseignants H avec ce diplôme > Total H (${totaux.hommes})`);
        return false;
    }
    
    if (value > 0) showValidationSuccess(field);
    return true;
}

// Validation INDIVIDUELLE : Chaque diplôme F ≤ Total F
function validateDiplomeFemmes(diplomeId) {
    const field = document.getElementById(diplomeId);
    if (!field) return true;
    
    const value = parseInt(field.value) || 0;
    const totaux = getTotalGeneralEnseignants();
    
    clearValidationError(field);
    
    // Chaque diplôme individuellement ne peut pas dépasser le total
    if (value > totaux.femmes) {
        showValidationError(field, `⚠️ ${value} enseignants F avec ce diplôme > Total F (${totaux.femmes})`);
        return false;
    }
    
    if (value > 0) showValidationSuccess(field);
    return true;
}

// Revalider TOUS les diplômes quand le total change
function validateAllDiplomes() {
    const diplomesCategories = ['bac', 'bfem', 'cfee', 'licence', 'master', 'autres_diplomes'];
    
    diplomesCategories.forEach(category => {
        validateDiplomeHommes(category + '_hommes');
        validateDiplomeFemmes(category + '_femmes');
    });
}

// Validation globale : revalider tout quand spécialité ou classifications changent
function validateAllPersonnelClassifications() {
    validateTicHommes();
    validateTicFemmes();
    validateCorpsHommes();
    validateCorpsFemmes();
    validateAllDiplomes(); // Valide tous les diplômes individuellement
}

// ============================================
// ÉTAPE 5 - MATÉRIEL PÉDAGOGIQUE : VALIDATIONS
// ============================================

// 1. MANUELS ÉLÈVES - Limite 1000 par niveau
function validateManuelsElevesNiveau(niveau) {
    const niveauLower = niveau.toLowerCase();
    const matieres = ['lc_francais', 'mathematiques', 'edd', 'dm', 'manuel_classe', 'livret_maison', 
                      'livret_devoir_gradue', 'planche_alphabetique', 'manuel_arabe', 'manuel_religion', 
                      'autre_religion', 'autres_manuels'];
    
    let totalNiveau = 0;
    const fields = [];
    let hasNegative = false;
    
    // Calculer le total du niveau et vérifier les négatifs
    matieres.forEach(matiere => {
        const fieldId = niveauLower + '_' + matiere;
        const field = document.getElementById(fieldId);
        if (field) {
            const value = parseInt(field.value);
            
            // Vérifier si négatif
            if (value < 0) {
                showValidationError(field, `⚠️ Valeur négative interdite`);
                hasNegative = true;
            } else {
                totalNiveau += (value || 0);
            }
            
            fields.push(field);
        }
    });
    
    // Si valeurs négatives, bloquer
    if (hasNegative) {
        return false;
    }
    
    // Vérifier la limite de 1000
    if (totalNiveau > 1000) {
        // Afficher erreur sur tous les champs du niveau
        fields.forEach(field => {
            if (parseInt(field.value) > 0) {
                showValidationError(field, `⚠️ Total ${niveau} (${totalNiveau}) > 1000 manuels max`);
            }
        });
        return false;
    }
    
    // Clear errors si OK
    fields.forEach(field => {
        clearValidationError(field);
    });
    
    return true;
}

// Revalider tous les niveaux de manuels élèves
function validateAllManuelsElevesNiveaux() {
    const niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    let allValid = true;
    
    niveaux.forEach(niveau => {
        if (!validateManuelsElevesNiveau(niveau)) {
            allValid = false;
        }
    });
    
    return allValid;
}

// 2. MANUELS MAÎTRE - Limite 1000 par niveau
function validateManuelsMaitreNiveau(niveau) {
    const niveauLower = niveau.toLowerCase();
    const guides = ['guide_lc_francais', 'guide_mathematiques', 'guide_edd', 'guide_dm', 
                   'guide_pedagogique', 'guide_arabe_religieux', 'guide_langue_nationale', 
                   'cahier_recits', 'autres_manuels_maitre'];
    
    let totalNiveau = 0;
    const fields = [];
    let hasNegative = false;
    
    // Calculer le total du niveau et vérifier les négatifs
    guides.forEach(guide => {
        const fieldId = niveauLower + '_' + guide;
        const field = document.getElementById(fieldId);
        if (field) {
            const value = parseInt(field.value);
            
            // Vérifier si négatif
            if (value < 0) {
                showValidationError(field, `⚠️ Valeur négative interdite`);
                hasNegative = true;
            } else {
                totalNiveau += (value || 0);
            }
            
            fields.push(field);
        }
    });
    
    // Si valeurs négatives, bloquer
    if (hasNegative) {
        return false;
    }
    
    // Vérifier la limite de 1000
    if (totalNiveau > 1000) {
        // Afficher erreur sur tous les champs du niveau
        fields.forEach(field => {
            if (parseInt(field.value) > 0) {
                showValidationError(field, `⚠️ Total guides ${niveau} (${totalNiveau}) > 1000 max`);
            }
        });
        return false;
    }
    
    // Clear errors si OK
    fields.forEach(field => {
        clearValidationError(field);
    });
    
    return true;
}

// Revalider tous les niveaux de manuels maître
function validateAllManuelsMaitreNiveaux() {
    const niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
    let allValid = true;
    
    niveaux.forEach(niveau => {
        if (!validateManuelsMaitreNiveau(niveau)) {
            allValid = false;
        }
    });
    
    return allValid;
}

// 3. FONCTION GÉNÉRIQUE - Bon état ≤ Total
function validateBonEtat(totalFieldName, bonEtatFieldName, label) {
    const totalField = document.querySelector(`[name="${totalFieldName}"]`);
    const bonEtatField = document.querySelector(`[name="${bonEtatFieldName}"]`);
    
    if (!totalField || !bonEtatField) return true;
    
    const total = parseInt(totalField.value);
    const bonEtat = parseInt(bonEtatField.value);
    
    clearValidationError(bonEtatField);
    clearValidationError(totalField);
    
    // Vérifier valeurs négatives
    if (total < 0) {
        showValidationError(totalField, `⚠️ Valeur négative interdite`);
        return false;
    }
    
    if (bonEtat < 0) {
        showValidationError(bonEtatField, `⚠️ Valeur négative interdite`);
        return false;
    }
    
    // Bon état ne peut pas dépasser le total
    if (bonEtat > total) {
        showValidationError(bonEtatField, `⚠️ Bon état (${bonEtat}) > Total (${total})`);
        showValidationError(totalField, `⚠️ Total (${total}) < Bon état (${bonEtat})`);
        return false;
    }
    
    // Success si OK
    if (bonEtat > 0) showValidationSuccess(bonEtatField);
    if (total > 0) showValidationSuccess(totalField);
    
    return true;
}

// 4. DICTIONNAIRES - 3 validations
function validateDictionnaires() {
    let valid = true;
    
    valid = validateBonEtat('dico_francais_total', 'dico_francais_bon_etat', 'Dictionnaires Français') && valid;
    valid = validateBonEtat('dico_arabe_total', 'dico_arabe_bon_etat', 'Dictionnaires Arabe') && valid;
    valid = validateBonEtat('dico_autre_total', 'dico_autre_bon_etat', 'Autres Dictionnaires') && valid;
    
    return valid;
}

// 5. MATÉRIEL DIDACTIQUE - 18 validations
function validateMaterielDidactique() {
    const materiels = [
        'dico_francais', 'dico_arabe', 'dico_autre',
        'regle_plate', 'equerre', 'compas', 'rapporteur',
        'decametre', 'chaine_arpenteur', 'boussole',
        'thermometre', 'kit_capacite', 'balance',
        'globe', 'cartes_murales', 'planches_illustrees',
        'kit_materiel_scientifique', 'autres_materiel'
    ];
    
    let valid = true;
    
    materiels.forEach(materiel => {
        const totalField = document.getElementById(materiel + '_total');
        const bonEtatField = document.getElementById(materiel + '_bon_etat');
        
        if (totalField && bonEtatField) {
            const total = parseInt(totalField.value);
            const bonEtat = parseInt(bonEtatField.value);
            
            clearValidationError(bonEtatField);
            clearValidationError(totalField);
            
            // Vérifier valeurs négatives
            if (total < 0) {
                showValidationError(totalField, `⚠️ Valeur négative interdite`);
                valid = false;
            } else if (bonEtat < 0) {
                showValidationError(bonEtatField, `⚠️ Valeur négative interdite`);
                valid = false;
            } else if (bonEtat > total) {
                showValidationError(bonEtatField, `⚠️ Bon état (${bonEtat}) > Total (${total})`);
                showValidationError(totalField, `⚠️ Total (${total}) < Bon état (${bonEtat})`);
                valid = false;
            } else {
                if (bonEtat > 0) showValidationSuccess(bonEtatField);
                if (total > 0) showValidationSuccess(totalField);
            }
        }
    });
    
    return valid;
}

// 6. GÉOMÉTRIE - 4 validations
// validateGeometrie() - SUPPRIMÉ (instruments de géométrie validés dans validateMaterielDidactique)

// ========================================
// ÉTAPE 6 - INFRASTRUCTURE
// ========================================

// Fonction générique pour valider une paire (total, bon_état)
function validateBonEtatPair(totalFieldName, bonEtatFieldName, label) {
    const totalField = document.querySelector(`[name="${totalFieldName}"]`);
    const bonEtatField = document.querySelector(`[name="${bonEtatFieldName}"]`);
    
    if (!totalField || !bonEtatField) return true;
    
    const total = parseInt(totalField.value) || 0;
    const bonEtat = parseInt(bonEtatField.value) || 0;
    
    clearValidationError(totalField);
    clearValidationError(bonEtatField);
    
    let isValid = true;
    
    // Vérifier valeurs négatives
    if (total < 0) {
        showValidationError(totalField, `⚠️ ${label} : valeur négative interdite`);
        isValid = false;
    }
    
    if (bonEtat < 0) {
        showValidationError(bonEtatField, `⚠️ ${label} : valeur négative interdite`);
        isValid = false;
    }
    
    // Vérifier bon_état ≤ total
    if (isValid && bonEtat > total) {
        showValidationError(bonEtatField, `⚠️ ${label} : Bon état (${bonEtat}) > Total (${total})`);
        showValidationError(totalField, `⚠️ ${label} : Total (${total}) < Bon état (${bonEtat})`);
        isValid = false;
    }
    
    // Afficher succès si valide et non vide
    if (isValid) {
        if (total > 0) showValidationSuccess(totalField);
        if (bonEtat > 0) showValidationSuccess(bonEtatField);
    }
    
    return isValid;
}

// 1. CAPITAL IMMOBILIER - 15 validations
function validateCapitalImmobilier() {
    let valid = true;
    
    // Salles de classe
    valid = validateBonEtatPair('salles_dur_total', 'salles_dur_bon_etat', 'Salles en dur') && valid;
    valid = validateBonEtatPair('abris_provisoires_total', 'abris_provisoires_bon_etat', 'Abris provisoires') && valid;
    
    // Bâtiments administratifs
    valid = validateBonEtatPair('bloc_admin_total', 'bloc_admin_bon_etat', 'Bloc administratif') && valid;
    valid = validateBonEtatPair('magasin_total', 'magasin_bon_etat', 'Magasin') && valid;
    
    // Salles spécialisées
    valid = validateBonEtatPair('salle_informatique_total', 'salle_informatique_bon_etat', 'Salle informatique') && valid;
    valid = validateBonEtatPair('salle_bibliotheque_total', 'salle_bibliotheque_bon_etat', 'Bibliothèque') && valid;
    valid = validateBonEtatPair('cuisine_total', 'cuisine_bon_etat', 'Cuisine') && valid;
    valid = validateBonEtatPair('refectoire_total', 'refectoire_bon_etat', 'Réfectoire') && valid;
    
    // Toilettes
    valid = validateBonEtatPair('toilettes_enseignants_total', 'toilettes_enseignants_bon_etat', 'Toilettes enseignants') && valid;
    valid = validateBonEtatPair('toilettes_garcons_total', 'toilettes_garcons_bon_etat', 'Toilettes garçons') && valid;
    valid = validateBonEtatPair('toilettes_filles_total', 'toilettes_filles_bon_etat', 'Toilettes filles') && valid;
    valid = validateBonEtatPair('toilettes_mixtes_total', 'toilettes_mixtes_bon_etat', 'Toilettes mixtes') && valid;
    
    // Logements
    valid = validateBonEtatPair('logement_directeur_total', 'logement_directeur_bon_etat', 'Logement directeur') && valid;
    valid = validateBonEtatPair('logement_gardien_total', 'logement_gardien_bon_etat', 'Logement gardien') && valid;
    
    // Autres
    valid = validateBonEtatPair('autres_infrastructures_total', 'autres_infrastructures_bon_etat', 'Autres infrastructures') && valid;
    
    return valid;
}

// 2. CAPITAL MOBILIER - 9 validations
function validateCapitalMobilier() {
    let valid = true;
    
    // Mobilier élèves
    valid = validateBonEtatPair('tables_bancs_total', 'tables_bancs_bon_etat', 'Tables-bancs') && valid;
    valid = validateBonEtatPair('chaises_eleves_total', 'chaises_eleves_bon_etat', 'Chaises élèves') && valid;
    valid = validateBonEtatPair('tables_individuelles_total', 'tables_individuelles_bon_etat', 'Tables individuelles') && valid;
    
    // Mobilier enseignants
    valid = validateBonEtatPair('bureaux_maitre_total', 'bureaux_maitre_bon_etat', 'Bureaux maître') && valid;
    valid = validateBonEtatPair('chaises_maitre_total', 'chaises_maitre_bon_etat', 'Chaises maître') && valid;
    valid = validateBonEtatPair('tableaux_total', 'tableaux_bon_etat', 'Tableaux') && valid;
    valid = validateBonEtatPair('armoires_total', 'armoires_bon_etat', 'Armoires') && valid;
    
    // Mobilier administratif
    valid = validateBonEtatPair('bureaux_admin_total', 'bureaux_admin_bon_etat', 'Bureaux administratifs') && valid;
    valid = validateBonEtatPair('chaises_admin_total', 'chaises_admin_bon_etat', 'Chaises administratives') && valid;
    
    return valid;
}

// 3. ÉQUIPEMENTS INFORMATIQUES - 9 validations
function validateEquipementsInformatiques() {
    let valid = true;
    
    // Ordinateurs
    valid = validateBonEtatPair('ordinateurs_fixes_total', 'ordinateurs_fixes_bon_etat', 'Ordinateurs fixes') && valid;
    valid = validateBonEtatPair('ordinateurs_portables_total', 'ordinateurs_portables_bon_etat', 'Ordinateurs portables') && valid;
    valid = validateBonEtatPair('tablettes_total', 'tablettes_bon_etat', 'Tablettes') && valid;
    
    // Imprimantes
    valid = validateBonEtatPair('imprimantes_laser_total', 'imprimantes_laser_bon_etat', 'Imprimantes laser') && valid;
    valid = validateBonEtatPair('imprimantes_jet_encre_total', 'imprimantes_jet_encre_bon_etat', 'Imprimantes jet d\'encre') && valid;
    valid = validateBonEtatPair('imprimantes_multifonction_total', 'imprimantes_multifonction_bon_etat', 'Imprimantes multifonction') && valid;
    valid = validateBonEtatPair('photocopieuses_total', 'photocopieuses_bon_etat', 'Photocopieuses') && valid;
    
    // Audiovisuel
    valid = validateBonEtatPair('videoprojecteurs_total', 'videoprojecteurs_bon_etat', 'Vidéoprojecteurs') && valid;
    valid = validateBonEtatPair('autres_equipements_total', 'autres_equipements_bon_etat', 'Autres équipements') && valid;
    
    return valid;
}

</script>

@endpush

@endsection
