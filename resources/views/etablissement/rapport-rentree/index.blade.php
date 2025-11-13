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
                    <a href="#geometrie" onclick="switchToEtape(5, 'geometrie')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-drafting-compass mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Géométrie</span>
                        <i id="check-geometrie" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    <a href="#materiel" onclick="switchToEtape(5, 'materiel')" class="flex items-center px-2 py-1.5 text-xs text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition rounded font-medium">
                        <i class="fas fa-tools mr-1.5 text-gray-500"></i>
                        <span class="flex-1">Matériel Général</span>
                        <i id="check-materiel" class="fas fa-check text-green-500 text-xs hidden ml-1"></i>
                    </a>
                    {{-- Mesure supprimé - déjà dans materiel-didactique --}}
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
function autoSave(section) {
    clearTimeout(saveTimeout);
    saveTimeout = setTimeout(() => saveSection(section), 1000);
}

// Sauvegarder une section
async function saveSection(section) {
    const rapportId = document.getElementById('rapport-id').value;
    const formData = new FormData();
    
    // Récupérer les champs de la section
    const inputs = document.querySelectorAll(`[data-section="${section}"]`);
    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            formData.append(input.name, input.checked ? '1' : '0');
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
        showAlert('Erreur lors de la sauvegarde', 'error');
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
            
            // Géométrie
            if (typeof initGeometrie === 'function') {
                initGeometrie();
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

async function saveGeometrie() {
    const formData = new FormData();
    
    // Récupérer tous les champs de géométrie
    const fields = [
        'regles_graduees', 'equerres', 'rapporteurs', 'compas', 'metres_ruban',
        'etat_instruments_geometrie', 'solides_geometriques', 'planches_geometrie',
        'kit_geometrie_enseignant', 'besoins_geometrie', 'budget_estime_geometrie', 'observations_geometrie'
    ];

    fields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) {
            formData.append(field, element.value || '');
        }
    });

    try {
        await fetch('{{ route("etablissement.rapport-rentree.save-geometrie", $rapport->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });
        console.log('Matériel de Géométrie sauvegardé');
    } catch (error) {
        console.error('Erreur lors de la sauvegarde de la géométrie:', error);
    }
}

async function saveMesure() {
    const formData = new FormData();
    
    // Récupérer tous les champs de mesure
    const fields = [
        'decametres', 'metres_plies', 'centimetres', 'reglets',
        'balances_plateaux', 'balances_electroniques', 'poids_masses',
        'recipients_gradues', 'eprouvettes', 'verres_doseurs',
        'chronometres', 'horloges_demonstration', 'sabliers',
        'etat_instruments_mesure', 'besoins_mesure', 'budget_estime_mesure', 'observations_mesure'
    ];

    fields.forEach(field => {
        const element = document.querySelector(`[name="${field}"]`);
        if (element) {
            formData.append(field, element.value || '');
        }
    });

    try {
        await fetch('{{ route("etablissement.rapport-rentree.save-mesure", $rapport->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData
        });
        console.log('Matériel de Mesure sauvegardé');
    } catch (error) {
        console.error('Erreur lors de la sauvegarde de la mesure:', error);
    }
}

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

</script>
@endpush

@endsection
