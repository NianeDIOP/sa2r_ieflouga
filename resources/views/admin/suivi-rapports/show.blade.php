@extends('layouts.admin')

@section('title', 'Détail du Rapport - ' . $rapport->etablissement->etablissement)

@section('content')
        
        <!-- En-tête compact -->
        <div class="mb-3 flex items-center justify-between bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.suivi-rapports.index') }}" 
                   class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Retour
                </a>
                <div class="border-l border-gray-300 pl-2.5">
                    <h1 class="text-sm font-bold text-gray-900">{{ $rapport->etablissement->etablissement }}</h1>
                    <p class="text-[10px] text-gray-600">
                        <span class="font-medium">{{ $rapport->etablissement->code }}</span> • 
                        {{ $rapport->etablissement->commune }} - {{ $rapport->etablissement->zone }} • 
                        <span class="font-semibold text-blue-600">{{ $rapport->annee_scolaire }}</span>
                    </p>
                </div>
            </div>
            
            <!-- Statut et progression -->
            <div class="flex items-center gap-3">
                <!-- Progression -->
                <div class="text-right">
                    <p class="text-[10px] text-gray-500 mb-0.5">Progression</p>
                    <div class="flex items-center gap-1.5">
                        <div class="w-20 bg-gray-200 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full {{ $progression['pourcentage'] < 30 ? 'bg-red-500' : ($progression['pourcentage'] < 70 ? 'bg-amber-500' : 'bg-green-500') }}"
                                 style="width: {{ $progression['pourcentage'] }}%"></div>
                        </div>
                        <span class="text-xs font-bold {{ $progression['pourcentage'] < 30 ? 'text-red-600' : ($progression['pourcentage'] < 70 ? 'text-amber-600' : 'text-green-600') }}">
                            {{ $progression['pourcentage'] }}%
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-0.5">{{ $progression['completes'] }}/{{ $progression['total'] }} sections</p>
                </div>
                
                <!-- Statut Badge -->
                @if($rapport->statut === 'brouillon')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold text-amber-700 bg-amber-50 rounded-lg border border-amber-200">
                        <i class="fas fa-edit"></i>
                        Brouillon
                    </span>
                @elseif($rapport->statut === 'soumis')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold text-purple-700 bg-purple-50 rounded-lg border border-purple-200">
                        <i class="fas fa-paper-plane"></i>
                        Soumis
                    </span>
                @elseif($rapport->statut === 'valide')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold text-green-700 bg-green-50 rounded-lg border border-green-200">
                        <i class="fas fa-check-circle"></i>
                        Validé
                    </span>
                @elseif($rapport->statut === 'rejete')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-semibold text-red-700 bg-red-50 rounded-lg border border-red-200">
                        <i class="fas fa-times-circle"></i>
                        Rejeté
                    </span>
                @endif
            </div>
        </div>

        <!-- Statistiques par étape (compact) -->
        <div class="grid grid-cols-6 gap-2 mb-4">
            @php
                $nomsEtapes = [
                    1 => 'Informations Générales',
                    2 => 'Effectifs',
                    3 => 'Examens',
                    4 => 'Personnel',
                    5 => 'Matériel Pédagogique',
                    6 => 'Infrastructure'
                ];
            @endphp
            @foreach($progression['etapes_stats'] as $numEtape => $stats)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $nomsEtapes[$numEtape] ?? "Étape $numEtape" }}</span>
                        <span class="text-xs font-bold {{ $stats['pourcentage'] < 50 ? 'text-red-600' : ($stats['pourcentage'] < 100 ? 'text-amber-600' : 'text-green-600') }}">
                            {{ $stats['pourcentage'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="h-1.5 rounded-full {{ $stats['pourcentage'] < 50 ? 'bg-red-500' : ($stats['pourcentage'] < 100 ? 'bg-amber-500' : 'bg-green-500') }}"
                             style="width: {{ $stats['pourcentage'] }}%"></div>
                    </div>
                    <p class="text-[10px] text-gray-600 mt-1">{{ $stats['completes'] }}/{{ $stats['total'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Layout avec sidebar et contenu principal -->
        <div class="flex gap-4">
            <!-- Barre latérale des sections (à gauche) -->
            <div class="w-80 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden h-[calc(100vh-200px)] flex flex-col">
                    <!-- En-tête de la sidebar -->
                    <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                        <h2 class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-list-check text-blue-600 mr-2"></i>
                            28 Sous-Sections
                        </h2>
                        <p class="text-xs text-gray-600">Cliquez pour voir les détails</p>
                    </div>

                    <!-- Liste des sections (scrollable) -->
                    <div class="flex-1 overflow-y-auto">
                        <div class="divide-y divide-gray-100">
                            @foreach($progression['etapes'] as $key => $etape)
                                <button type="button" 
                                        onclick="showSectionContent('{{ $key }}')"
                                        class="w-full px-4 py-3 flex items-center gap-3 hover:bg-gray-50 transition-colors text-left section-btn"
                                        data-section="{{ $key }}">
                                    <!-- Icône de la section -->
                                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-{{ $etape['color'] }}-100 flex items-center justify-center">
                                        <i class="fas {{ $etape['icon'] }} text-{{ $etape['color'] }}-600 text-xs"></i>
                                    </div>
                                    
                                    <!-- Nom et statut -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xs font-semibold text-gray-900 truncate">{{ $etape['nom'] }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] text-gray-500">Étape {{ $etape['etape'] }}</span>
                                            @if($etape['complete'])
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-medium text-green-700 bg-green-50 rounded-full border border-green-200">
                                                    <i class="fas fa-check-circle"></i>
                                                    OK
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] font-medium text-gray-500 bg-gray-100 rounded-full">
                                                    <i class="fas fa-circle text-[7px]"></i>
                                                    Vide
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Indicateur de sélection -->
                                    <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenu principal (à droite) -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Message initial -->
                    <div id="no-selection" class="p-8 text-center">
                        <div class="max-w-sm mx-auto">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-mouse-pointer text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Sélectionnez une section</h3>
                            <p class="text-sm text-gray-600">Cliquez sur une section dans la barre latérale pour voir son contenu détaillé.</p>
                        </div>
                    </div>

                    <!-- Conteneurs pour chaque section (cachés par défaut) -->
                    @foreach($progression['etapes'] as $key => $etape)
                        @php
                            $sectionData = null;
                            $hasData = $etape['complete'];
                            
                            // Récupérer les données selon la section
                            switch($key) {
                                case 'info_directeur':
                                    $sectionData = $rapport->infoDirecteur;
                                    break;
                                case 'infrastructures_base':
                                    $sectionData = $rapport->infrastructuresBase;
                                    break;
                                case 'structures_communautaires':
                                    $sectionData = $rapport->structuresCommunautaires;
                                    break;
                                case 'langues_projets':
                                    $sectionData = $rapport->languesProjets;
                                    break;
                                case 'ressources_financieres':
                                    $sectionData = $rapport->ressourcesFinancieres;
                                    break;
                                case 'nombre_classes':
                                case 'effectifs_totaux':
                                case 'redoublants':
                                case 'abandons':
                                case 'handicaps':
                                case 'situations_speciales':
                                    $sectionData = $rapport->effectifs;
                                    break;
                                case 'cmg':
                                    $sectionData = $rapport->cmg;
                                    break;
                                case 'cfee':
                                    $sectionData = $rapport->cfee;
                                    break;
                                case 'entree_sixieme':
                                    $sectionData = $rapport->entreeSixieme;
                                    break;
                                case 'recrutement_ci':
                                    $sectionData = $rapport->recrutementCi;
                                    break;
                                case 'personnel_specialite':
                                case 'personnel_corps':
                                case 'personnel_diplomes':
                                case 'personnel_tic':
                                case 'personnel_statistiques':
                                    $sectionData = $rapport->personnelEnseignant;
                                    break;
                                case 'manuels_eleves':
                                    $sectionData = $rapport->manuelsEleves;
                                    break;
                                case 'manuels_maitre':
                                    $sectionData = $rapport->manuelsMaitre;
                                    break;
                                case 'dictionnaires':
                                case 'materiel_didactique':
                                case 'geometrie':
                                    $sectionData = $rapport->materielDidactique;
                                    break;
                                case 'capital_immobilier':
                                    $sectionData = $rapport->capitalImmobilier;
                                    break;
                                case 'capital_mobilier':
                                    $sectionData = $rapport->capitalMobilier;
                                    break;
                                case 'equipement_informatique':
                                    $sectionData = $rapport->equipementInformatique;
                                    break;
                            }
                        @endphp

                        <div id="content-{{ $key }}" class="section-content hidden">
                            <!-- En-tête de la section -->
                            <div class="px-4 py-2.5 bg-{{ $etape['color'] }}-50 border-b border-{{ $etape['color'] }}-100">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-{{ $etape['color'] }}-100 flex items-center justify-center">
                                        <i class="fas {{ $etape['icon'] }} text-{{ $etape['color'] }}-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $etape['nom'] }}</h3>
                                        <p class="text-xs text-gray-600">
                                            Étape {{ $etape['etape'] }} • 
                                            @if($etape['complete'])
                                                <span class="text-green-600 font-medium">Section complète</span>
                                            @else
                                                <span class="text-gray-500">Section vide</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu de la section -->
                            <div class="p-4">
                                @if($hasData && $sectionData)
                                    @if(is_a($sectionData, 'Illuminate\Database\Eloquent\Collection'))
                                        <!-- Collection (effectifs, manuels, etc.) -->
                                        @if($sectionData->count() > 0)
                                            <div class="space-y-2">
                                                @foreach($sectionData as $index => $item)
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <h4 class="text-[10px] font-semibold text-gray-700 mb-1.5">Enregistrement #{{ $index + 1 }}</h4>
                                                        <div class="grid grid-cols-4 gap-2">
                                                            @foreach($item->getAttributes() as $field => $value)
                                                                @if(!in_array($field, ['id', 'rapport_id', 'created_at', 'updated_at']))
                                                                    <div class="text-[11px]">
                                                                        <span class="text-gray-500 font-medium block mb-0.5">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                                                        <span class="text-gray-900 font-semibold">
                                                                            @if(is_bool($value))
                                                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] rounded-full {{ $value ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                                                    <i class="fas fa-{{ $value ? 'check' : 'times' }}"></i>
                                                                                    {{ $value ? 'Oui' : 'Non' }}
                                                                                </span>
                                                                            @elseif(is_numeric($value) && $value > 0)
                                                                                <span class="text-blue-600 font-bold">{{ number_format($value, 0, ',', ' ') }}</span>
                                                                            @else
                                                                                {{ $value ?? '—' }}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-6">
                                                <i class="fas fa-inbox text-2xl text-gray-300 mb-2"></i>
                                                <h4 class="text-xs font-medium text-gray-500">Aucune donnée</h4>
                                                <p class="text-[10px] text-gray-400">Cette collection ne contient aucun enregistrement</p>
                                            </div>
                                        @endif
                                    @else
                                        <!-- Objet unique -->
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach($sectionData->getAttributes() as $field => $value)
                                                @if(!in_array($field, ['id', 'rapport_id', 'created_at', 'updated_at']))
                                                    <div class="bg-white rounded-lg p-2 border border-gray-200">
                                                        <label class="block text-[10px] font-medium text-gray-500 mb-0.5">
                                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                        </label>
                                                        <p class="text-xs font-semibold text-gray-900">
                                                            @if(is_bool($value))
                                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[9px] rounded-full {{ $value ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                                    <i class="fas fa-{{ $value ? 'check' : 'times' }}"></i>
                                                                    {{ $value ? 'Oui' : 'Non' }}
                                                                </span>
                                                            @elseif(is_numeric($value) && $value > 0)
                                                                <span class="text-blue-600 font-bold">{{ number_format($value, 0, ',', ' ') }}</span>
                                                            @elseif(!empty($value))
                                                                <span class="text-gray-900">{{ $value }}</span>
                                                            @else
                                                                <span class="text-gray-400 italic">Non renseigné</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-6">
                                        <div class="w-10 h-10 mx-auto mb-2 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-inbox text-sm text-gray-400"></i>
                                        </div>
                                        <h4 class="text-xs font-medium text-gray-500 mb-1">Section vide</h4>
                                        <p class="text-[10px] text-gray-400">Aucune donnée n'a été saisie pour cette section</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions admin (si soumis) -->
        @if($rapport->statut === 'soumis')
            <div class="mt-4 flex gap-3">
                <button onclick="openValiderModal({{ $rapport->id }})" 
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-check-circle"></i>
                    Valider le rapport
                </button>
                <button onclick="openRejeterModal({{ $rapport->id }})" 
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-times-circle"></i>
                    Rejeter le rapport
                </button>
            </div>
        @endif

<!-- Modals (Valider/Rejeter) -->
<div id="validerModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Valider le rapport</h3>
        </div>
        <div class="px-6 py-4">
            <input type="hidden" id="valider-rapport-id">
            <label class="block text-sm font-medium text-gray-700 mb-2">Commentaire (optionnel)</label>
            <textarea id="valider-commentaire" rows="3" 
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                      placeholder="Ajouter un commentaire..."></textarea>
        </div>
        <div class="px-6 py-4 bg-gray-50 flex gap-3">
            <button onclick="closeValiderModal()" 
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
            </button>
            <button onclick="validerRapport()" 
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                Confirmer
            </button>
        </div>
    </div>
</div>

<div id="rejeterModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Rejeter le rapport</h3>
        </div>
        <div class="px-6 py-4">
            <input type="hidden" id="rejeter-rapport-id">
            <label class="block text-sm font-medium text-gray-700 mb-2">Motif du rejet <span class="text-red-500">*</span></label>
            <textarea id="rejeter-motif" rows="3" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      placeholder="Indiquer la raison du rejet..."></textarea>
        </div>
        <div class="px-6 py-4 bg-gray-50 flex gap-3">
            <button onclick="closeRejeterModal()" 
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                Annuler
            </button>
            <button onclick="rejeterRapport()" 
                    class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                Confirmer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales
let currentActiveSection = null;

// Fonction pour afficher le contenu d'une section
function showSectionContent(sectionKey) {
    // Cacher toutes les sections
    document.querySelectorAll('.section-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Cacher le message "aucune sélection"
    document.getElementById('no-selection').classList.add('hidden');
    
    // Afficher la section sélectionnée
    const targetContent = document.getElementById('content-' + sectionKey);
    if (targetContent) {
        targetContent.classList.remove('hidden');
    }
    
    // Mettre à jour les styles des boutons de la sidebar
    document.querySelectorAll('.section-btn').forEach(btn => {
        btn.classList.remove('bg-blue-50', 'border-r-2', 'border-blue-500');
        btn.classList.add('hover:bg-gray-50');
    });
    
    // Marquer le bouton actif
    const activeBtn = document.querySelector(`[data-section="${sectionKey}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('hover:bg-gray-50');
        activeBtn.classList.add('bg-blue-50', 'border-r-2', 'border-blue-500');
    }
    
    currentActiveSection = sectionKey;
}

// Navigation au clavier (optionnel)
document.addEventListener('keydown', function(event) {
    if (!currentActiveSection) return;
    
    const sections = Array.from(document.querySelectorAll('.section-btn')).map(btn => btn.dataset.section);
    const currentIndex = sections.indexOf(currentActiveSection);
    
    if (event.key === 'ArrowDown' && currentIndex < sections.length - 1) {
        event.preventDefault();
        showSectionContent(sections[currentIndex + 1]);
    } else if (event.key === 'ArrowUp' && currentIndex > 0) {
        event.preventDefault();
        showSectionContent(sections[currentIndex - 1]);
    }
});

function openValiderModal(rapportId) {
    document.getElementById('valider-rapport-id').value = rapportId;
    document.getElementById('validerModal').classList.remove('hidden');
}

function closeValiderModal() {
    document.getElementById('validerModal').classList.add('hidden');
}

function openRejeterModal(rapportId) {
    document.getElementById('rejeter-rapport-id').value = rapportId;
    document.getElementById('rejeterModal').classList.remove('hidden');
}

function closeRejeterModal() {
    document.getElementById('rejeterModal').classList.add('hidden');
}

async function validerRapport() {
    const rapportId = document.getElementById('valider-rapport-id').value;
    const commentaire = document.getElementById('valider-commentaire').value;
    
    try {
        const response = await fetch(`/admin/suivi-rapports/${rapportId}/valider`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ commentaire_admin: commentaire })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch (error) {
        alert('Erreur lors de la validation');
    }
}

async function rejeterRapport() {
    const rapportId = document.getElementById('rejeter-rapport-id').value;
    const motif = document.getElementById('rejeter-motif').value;
    
    if (!motif) {
        alert('Veuillez indiquer un motif de rejet');
        return;
    }
    
    try {
        const response = await fetch(`/admin/suivi-rapports/${rapportId}/rejeter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ motif_rejet: motif })
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.reload();
        } else {
            alert('Erreur: ' + data.message);
        }
    } catch (error) {
        alert('Erreur lors du rejet');
    }
}
</script>
@endpush

@endsection
