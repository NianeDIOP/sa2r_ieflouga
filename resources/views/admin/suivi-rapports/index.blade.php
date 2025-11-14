@extends('layouts.admin')

@section('title', 'Suivi des Rapports')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#002147]">Suivi des Rapports</h1>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-xs text-gray-600">Total: <strong class="text-gray-900">{{ $stats['total'] }}</strong></span>
                    <span class="text-xs text-blue-600">Avec rapport: <strong>{{ $stats['avec_rapport'] }}</strong></span>
                    <span class="text-xs text-amber-600">Brouillons: <strong>{{ $stats['brouillons'] }}</strong></span>
                    <span class="text-xs text-purple-600">Soumis: <strong>{{ $stats['soumis'] }}</strong></span>
                    <span class="text-xs text-green-600">Validés: <strong>{{ $stats['valides'] }}</strong></span>
                    <span class="text-xs text-red-600">Rejetés: <strong>{{ $stats['rejetes'] }}</strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-filter mr-1"></i>
                Filtres de recherche
            </h3>
            @if(request()->hasAny(['search', 'commune', 'zone', 'statut_rapport']))
                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                    <i class="fas fa-check-circle mr-1"></i>
                    Filtres actifs
                </span>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.suivi-rapports.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                <div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher (nom, code)..." 
                           oninput="debounceFilter()"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                </div>
                
                <div>
                    <select name="commune" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        <option value="">Toutes les communes</option>
                        @foreach($lists['communes'] as $commune)
                            <option value="{{ $commune }}" {{ request('commune') == $commune ? 'selected' : '' }}>{{ $commune }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="zone" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        <option value="">Toutes les zones</option>
                        @foreach($lists['zones'] as $zone)
                            <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="statut_rapport" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="brouillon" {{ request('statut_rapport') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="soumis" {{ request('statut_rapport') == 'soumis' ? 'selected' : '' }}>Soumis</option>
                        <option value="valide" {{ request('statut_rapport') == 'valide' ? 'selected' : '' }}>Validé</option>
                        <option value="rejete" {{ request('statut_rapport') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>
            </div>
            
            @if(request()->hasAny(['search', 'commune', 'zone', 'statut_rapport']))
                <div class="flex justify-end">
                    <a href="{{ route('admin.suivi-rapports.index') }}" 
                       class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                       title="Réinitialiser les filtres">
                        <i class="fas fa-times text-xs"></i>
                        <span>Réinitialiser</span>
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Tableau des rapports -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @if(request()->hasAny(['search', 'commune', 'zone', 'statut_rapport']))
            <div class="px-4 py-2 bg-blue-50 border-b border-blue-200">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>{{ $etablissements->total() }}</strong> résultat(s) trouvé(s) sur <strong>{{ $stats['total'] }}</strong> établissements
                </p>
            </div>
        @endif
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-school text-indigo-600 mr-1"></i>
                            Établissement
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-file-alt text-blue-600 mr-1"></i>
                            Rapport
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-tasks text-purple-600 mr-1"></i>
                            Progression
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-traffic-light text-amber-600 mr-1"></i>
                            Statut
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-calendar text-teal-600 mr-1"></i>
                            Dates
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog text-gray-600 mr-1"></i>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($etablissements as $etablissement)
                        @php
                            $rapport = $etablissement->rapports->first();
                            if ($rapport && isset($rapport->progression_data)) {
                                $progression = $rapport->progression_data['pourcentage'];
                                $etapesCompletes = $rapport->progression_data['completes'];
                                $etapesTotal = $rapport->progression_data['total'];
                            } else {
                                $progression = 0;
                                $etapesCompletes = 0;
                                $etapesTotal = 28;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ $etablissement->etablissement }}</span>
                                    <span class="text-xs text-gray-500">{{ $etablissement->commune }} - {{ $etablissement->zone }}</span>
                                    <code class="text-xs font-mono text-blue-600 mt-0.5">{{ $etablissement->code }}</code>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($rapport)
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-semibold text-gray-900">{{ $rapport->annee_scolaire ?? 'N/A' }}</span>
                                        <span class="text-xs text-gray-500">Créé le {{ $rapport->created_at->format('d/m/Y') }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-red-600 font-medium">
                                        <i class="fas fa-exclamation-circle mr-1"></i>
                                        Aucun rapport
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($rapport)
                                    <div class="flex flex-col items-center gap-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full transition-all duration-300 
                                                {{ $progression < 30 ? 'bg-red-500' : ($progression < 70 ? 'bg-amber-500' : 'bg-green-500') }}"
                                                style="width: {{ $progression }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $progression < 30 ? 'text-red-600' : ($progression < 70 ? 'text-amber-600' : 'text-green-600') }}">
                                            {{ $progression }}% ({{ $etapesCompletes }}/{{ $etapesTotal }})
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($rapport)
                                    @if($rapport->statut === 'brouillon')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-amber-700 bg-amber-50 rounded-full">
                                            <i class="fas fa-edit text-[10px]"></i>
                                            Brouillon
                                        </span>
                                    @elseif($rapport->statut === 'soumis')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-purple-700 bg-purple-50 rounded-full">
                                            <i class="fas fa-paper-plane text-[10px]"></i>
                                            Soumis
                                        </span>
                                    @elseif($rapport->statut === 'valide')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-full">
                                            <i class="fas fa-check-circle text-[10px]"></i>
                                            Validé
                                        </span>
                                    @elseif($rapport->statut === 'rejete')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-red-700 bg-red-50 rounded-full">
                                            <i class="fas fa-times-circle text-[10px]"></i>
                                            Rejeté
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($rapport)
                                    <div class="flex flex-col items-center text-xs">
                                        @if($rapport->date_soumission)
                                            <span class="text-purple-600" title="Date de soumission">
                                                <i class="fas fa-upload text-[10px] mr-1"></i>
                                                {{ $rapport->date_soumission->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        @if($rapport->date_validation)
                                            <span class="text-green-600" title="Date de validation">
                                                <i class="fas fa-check text-[10px] mr-1"></i>
                                                {{ $rapport->date_validation->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        @if($rapport->date_rejet)
                                            <span class="text-red-600" title="Date de rejet">
                                                <i class="fas fa-times text-[10px] mr-1"></i>
                                                {{ $rapport->date_rejet->format('d/m/Y') }}
                                            </span>
                                        @endif
                                        @if(!$rapport->date_soumission && !$rapport->date_validation && !$rapport->date_rejet)
                                            <span class="text-gray-400">En cours</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    @if($rapport)
                                        <a href="{{ route('admin.suivi-rapports.show', $rapport) }}" 
                                           class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                           title="Voir les détails">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        @if($rapport->statut === 'soumis')
                                            <button onclick="openValiderModal({{ $rapport->id }})" type="button"
                                                    class="p-1.5 text-green-600 hover:bg-green-50 rounded transition-colors"
                                                    title="Valider le rapport">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                            
                                            <button onclick="openRejeterModal({{ $rapport->id }})" type="button"
                                                    class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                                                    title="Rejeter le rapport">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400 italic">Aucune action</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                    <p class="text-sm text-gray-500">Aucun établissement trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($etablissements->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $etablissements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Valider -->
<div id="validerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                Valider le rapport
            </h3>
        </div>
        <form id="validerForm" onsubmit="validerRapport(event)">
            <div class="px-6 py-4 space-y-4">
                <p class="text-sm text-gray-600">
                    Êtes-vous sûr de vouloir valider ce rapport ? Cette action indique que le rapport est conforme et complet.
                </p>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Commentaire (optionnel)
                    </label>
                    <textarea name="commentaire_admin" 
                              rows="3" 
                              placeholder="Ajouter un commentaire..."
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-2">
                <button type="button" 
                        onclick="closeModal('validerModal')" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-check mr-1"></i>
                    Valider
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Rejeter -->
<div id="rejeterModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-times-circle text-red-600"></i>
                Rejeter le rapport
            </h3>
        </div>
        <form id="rejeterForm" onsubmit="rejeterRapport(event)">
            <div class="px-6 py-4 space-y-4">
                <p class="text-sm text-gray-600">
                    Le rapport sera renvoyé à l'établissement pour correction. Veuillez indiquer le motif du rejet.
                </p>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Motif du rejet <span class="text-red-500">*</span>
                    </label>
                    <textarea name="motif_rejet" 
                              rows="4" 
                              required
                              placeholder="Expliquez pourquoi le rapport est rejeté..."
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-2">
                <button type="button" 
                        onclick="closeModal('rejeterModal')" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-1"></i>
                    Rejeter
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentRapportId = null;

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}

function openValiderModal(rapportId) {
    currentRapportId = rapportId;
    document.getElementById('validerForm').reset();
    openModal('validerModal');
}

function openRejeterModal(rapportId) {
    currentRapportId = rapportId;
    document.getElementById('rejeterForm').reset();
    openModal('rejeterModal');
}

function validerRapport(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch(`/admin/suivi-rapports/${currentRapportId}/valider`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('validerModal');
            window.location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
}

function rejeterRapport(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    
    fetch(`/admin/suivi-rapports/${currentRapportId}/rejeter`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal('rejeterModal');
            window.location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Une erreur est survenue');
    });
}

// Fonctions pour les filtres automatiques
let filterTimeout;
function debounceFilter() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
}

function submitFilterForm() {
    document.getElementById('filterForm').submit();
}

// Fermer les modals en cliquant à l'extérieur
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});
</script>

@endsection
