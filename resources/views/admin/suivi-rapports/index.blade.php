@extends('layouts.admin')

@section('title', 'Suivi des Rapports')

@section('content')
<div class="space-y-4">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mt-0.5 mr-3"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    @if(session('warnings') && count(session('warnings')) > 0)
                        <div class="mt-2 space-y-1">
                            <p class="text-xs font-semibold text-amber-700">Avertissements :</p>
                            @foreach(session('warnings') as $warning)
                                <p class="text-xs text-amber-600">• {{ $warning }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    @if(session('import_errors') && count(session('import_errors')) > 0)
                        <div class="mt-2 space-y-1">
                            <p class="text-xs font-semibold text-red-700">Erreurs détectées :</p>
                            @foreach(session('import_errors') as $error)
                                <p class="text-xs text-red-600">• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if(session('warnings') && count(session('warnings')) > 0)
                        <div class="mt-2 space-y-1">
                            <p class="text-xs font-semibold text-amber-700">Avertissements :</p>
                            @foreach(session('warnings') as $warning)
                                <p class="text-xs text-amber-600">• {{ $warning }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800">Erreurs de validation :</p>
                    <ul class="mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-xs text-red-600">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

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
        
        <!-- Boutons Actions Excel -->
        <div class="flex items-center gap-2">
            <!-- Bouton Télécharger Template -->
            <a href="{{ route('admin.suivi-rapports.template-excel') }}" 
               class="inline-flex items-center gap-2 px-3 py-1.5 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors shadow-sm"
               target="_blank">
                <i class="fas fa-file-excel text-sm"></i>
                <span class="font-medium">Template</span>
            </a>
            
            <!-- Bouton Importer Excel -->
            <button type="button"
                    onclick="document.getElementById('modalImportExcel').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                <i class="fas fa-upload text-sm"></i>
                <span class="font-medium">Importer</span>
            </button>
        </div>
    </div>

    <!-- Filtre par Année Scolaire -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-calendar-alt text-indigo-600"></i>
                <h3 class="text-sm font-semibold text-gray-700">Année Scolaire</h3>
            </div>
            <form method="GET" action="{{ route('admin.suivi-rapports.index') }}" class="flex items-center gap-3">
                <!-- Préserver les autres filtres -->
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('commune'))
                    <input type="hidden" name="commune" value="{{ request('commune') }}">
                @endif
                @if(request('zone'))
                    <input type="hidden" name="zone" value="{{ request('zone') }}">
                @endif
                @if(request('statut_rapport'))
                    <input type="hidden" name="statut_rapport" value="{{ request('statut_rapport') }}">
                @endif
                
                <select name="annee" 
                        onchange="this.form.submit()"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent bg-white">
                    @foreach($anneesDisponibles as $annee)
                        <option value="{{ $annee->annee }}" 
                                {{ $anneeSelectionnee == $annee->annee ? 'selected' : '' }}>
                            {{ $annee->annee }}
                            @if($annee->is_active)
                                ⭐ (Année Active)
                            @endif
                        </option>
                    @endforeach
                </select>
                
                @if($anneeSelectionnee != $anneeActive?->annee)
                    <a href="{{ route('admin.suivi-rapports.index', array_filter(request()->except('annee'))) }}" 
                       class="inline-flex items-center gap-1 px-3 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                       title="Revenir à l'année active">
                        <i class="fas fa-undo text-xs"></i>
                        <span>Année Active</span>
                    </a>
                @endif
                
                <!-- Badge Année -->
                @if($anneeSelectionnee == $anneeActive?->annee)
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                        <i class="fas fa-check-circle"></i>
                        Année Active
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                        <i class="fas fa-archive"></i>
                        Année Archivée
                    </span>
                @endif
            </form>
        </div>
    </div>

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-semibold text-gray-700">
                    <i class="fas fa-filter mr-1"></i>
                    Filtres de recherche
                </h3>
                <span class="text-xs text-gray-500 italic">
                    (pour l'année {{ $anneeSelectionnee }})
                </span>
            </div>
            @if(request()->hasAny(['search', 'commune', 'zone', 'statut_rapport']))
                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                    <i class="fas fa-check-circle mr-1"></i>
                    Filtres actifs
                </span>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.suivi-rapports.index') }}" id="filterForm">
            <!-- Préserver l'année sélectionnée -->
            @if(request('annee'))
                <input type="hidden" name="annee" value="{{ request('annee') }}">
            @endif
            
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
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent"
                            {{ empty($lists['communes']) ? 'disabled' : '' }}>
                        <option value="">{{ empty($lists['communes']) ? 'Aucune commune disponible' : 'Toutes les communes' }}</option>
                        @foreach($lists['communes'] as $commune)
                            <option value="{{ $commune }}" {{ request('commune') == $commune ? 'selected' : '' }}>{{ $commune }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="zone" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent"
                            {{ empty($lists['zones']) ? 'disabled' : '' }}>
                        <option value="">{{ empty($lists['zones']) ? 'Aucune zone disponible' : 'Toutes les zones' }}</option>
                        @foreach($lists['zones'] as $zone)
                            <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="statut_rapport" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent"
                            {{ empty($lists['statuts']) ? 'disabled' : '' }}>
                        <option value="">{{ empty($lists['statuts']) ? 'Aucun statut disponible' : 'Tous les statuts' }}</option>
                        @foreach($lists['statuts'] as $statut)
                            <option value="{{ $statut }}" {{ request('statut_rapport') == $statut ? 'selected' : '' }}>
                                @if($statut == 'brouillon')
                                    📝 Brouillon
                                @elseif($statut == 'soumis')
                                    📤 Soumis
                                @elseif($statut == 'validé')
                                    ✅ Validé
                                @elseif($statut == 'rejeté')
                                    ❌ Rejeté
                                @else
                                    {{ ucfirst($statut) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            @if(request()->hasAny(['search', 'commune', 'zone', 'statut_rapport']))
                <div class="flex justify-end">
                    <a href="{{ route('admin.suivi-rapports.index', request('annee') ? ['annee' => request('annee')] : []) }}" 
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
                                    @elseif($rapport->statut === 'validé')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-700 bg-green-50 rounded-full">
                                            <i class="fas fa-check-circle text-[10px]"></i>
                                            Validé
                                        </span>
                                    @elseif($rapport->statut === 'rejeté')
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

<!-- Modal Import Excel - VERSION COMPACTE -->
<div id="modalImportExcel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl max-w-lg w-full">
        <!-- Header Compact -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3 rounded-t-lg flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-upload text-white"></i>
                <h3 class="text-base font-bold text-white">Importer un Rapport Excel</h3>
            </div>
            <button type="button" 
                    onclick="closeModalImport()"
                    class="text-white hover:bg-white hover:bg-opacity-20 rounded p-1 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body Compact -->
        <form action="{{ route('admin.suivi-rapports.import-excel') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="p-5 space-y-4">
            @csrf

            <!-- Instructions Compactes -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-blue-600 text-sm mt-0.5"></i>
                    <p class="text-xs text-blue-800">
                        <strong>Avant d'importer :</strong> Téléchargez le template, remplissez-le avec les données de l'établissement, puis importez-le ici.
                    </p>
                </div>
            </div>

            <!-- Sélection Établissement avec Recherche -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    <i class="fas fa-school text-indigo-600 mr-1"></i>
                    Établissement <span class="text-red-500">*</span>
                </label>
                <select name="etablissement_id" 
                        id="select-etablissement"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    <option value="">Rechercher un établissement...</option>
                    @foreach(\App\Models\Etablissement::orderBy('etablissement')->get() as $etab)
                        <option value="{{ $etab->id }}" 
                                data-commune="{{ $etab->commune }}" 
                                data-zone="{{ $etab->zone }}">
                            {{ $etab->etablissement }} • {{ $etab->commune }} • {{ $etab->zone }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sélection Année Scolaire -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    <i class="fas fa-calendar-alt text-indigo-600 mr-1"></i>
                    Année Scolaire <span class="text-red-500">*</span>
                </label>
                <select name="annee_scolaire" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    @foreach(\App\Models\AnneeScolaire::orderBy('annee', 'desc')->get() as $annee)
                        <option value="{{ $annee->annee }}" {{ $annee->is_active ? 'selected' : '' }}>
                            {{ $annee->annee }} {{ $annee->is_active ? '⭐' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Upload Fichier Excel Compact -->
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    <i class="fas fa-file-excel text-green-600 mr-1"></i>
                    Fichier Excel <span class="text-red-500">*</span>
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors">
                    <input type="file" 
                           name="excel_file" 
                           id="excel_file"
                           accept=".xlsx,.xls"
                           required
                           class="hidden"
                           onchange="updateFileName(this)">
                    <label for="excel_file" class="cursor-pointer">
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-cloud-upload-alt text-green-600 text-xl"></i>
                            </div>
                            <p class="text-xs font-medium text-gray-700" id="file-label">
                                Sélectionner le fichier
                            </p>
                            <p class="text-[10px] text-gray-500">.xlsx, .xls (max 10 Mo)</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Avertissement Compact -->
            <div class="bg-amber-50 border-l-4 border-amber-500 p-2.5 rounded">
                <div class="flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-xs mt-0.5"></i>
                    <p class="text-[10px] text-amber-800">
                        <strong>Attention :</strong> Si un rapport existe déjà, il sera écrasé. Le rapport sera créé en statut "brouillon".
                    </p>
                </div>
            </div>

            <!-- Boutons Compacts -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t">
                <button type="button"
                        onclick="closeModalImport()"
                        class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Annuler
                </button>
                <button type="submit"
                        class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-upload mr-1.5"></i>
                    Importer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeModalImport() {
    document.getElementById('modalImportExcel').classList.add('hidden');
    // Reset form
    document.querySelector('#modalImportExcel form').reset();
    document.getElementById('file-label').textContent = 'Sélectionner le fichier';
    // Reset select
    if (window.etablissementSelect) {
        $(window.etablissementSelect).val('').trigger('change');
    }
}

function updateFileName(input) {
    const label = document.getElementById('file-label');
    if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2); // MB
        label.innerHTML = `<i class="fas fa-file-excel text-green-600 mr-1"></i>${fileName} <span class="text-[10px] text-gray-500">(${fileSize} Mo)</span>`;
    }
}

// Initialiser Select2 pour la recherche d'établissements
document.addEventListener('DOMContentLoaded', function() {
    // Charger jQuery et Select2 depuis CDN si pas déjà chargés
    if (typeof jQuery === 'undefined') {
        const jqueryScript = document.createElement('script');
        jqueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        jqueryScript.onload = function() {
            loadSelect2();
        };
        document.head.appendChild(jqueryScript);
    } else {
        loadSelect2();
    }
});

function loadSelect2() {
    if (typeof $.fn.select2 === 'undefined') {
        // Charger Select2 CSS
        const select2CSS = document.createElement('link');
        select2CSS.rel = 'stylesheet';
        select2CSS.href = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css';
        document.head.appendChild(select2CSS);
        
        // Charger Select2 JS
        const select2Script = document.createElement('script');
        select2Script.src = 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
        select2Script.onload = function() {
            initSelect2();
        };
        document.head.appendChild(select2Script);
    } else {
        initSelect2();
    }
}

function initSelect2() {
    window.etablissementSelect = $('#select-etablissement').select2({
        placeholder: 'Rechercher un établissement...',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modalImportExcel'),
        language: {
            noResults: function() {
                return "Aucun établissement trouvé";
            },
            searching: function() {
                return "Recherche en cours...";
            },
            inputTooShort: function() {
                return "Tapez au moins 2 caractères";
            }
        },
        minimumInputLength: 0,
        templateResult: formatEtablissement,
        templateSelection: formatEtablissementSelection
    });
}

function formatEtablissement(etab) {
    if (!etab.id) {
        return etab.text;
    }
    
    const $etab = $(etab.element);
    const commune = $etab.data('commune');
    const zone = $etab.data('zone');
    
    return $(`
        <div class="select2-result-etablissement">
            <div class="font-medium text-sm">${etab.text.split('•')[0].trim()}</div>
            <div class="text-xs text-gray-500">${commune} • ${zone}</div>
        </div>
    `);
}

function formatEtablissementSelection(etab) {
    if (!etab.id) {
        return etab.text;
    }
    return etab.text.split('•')[0].trim();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('modalImportExcel')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalImport();
    }
});
</script>

<style>
/* Styles personnalisés Select2 */
.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px;
    padding-left: 12px;
    font-size: 0.875rem;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

.select2-dropdown {
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.select2-search--dropdown .select2-search__field {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 6px 12px;
    font-size: 0.875rem;
}

.select2-results__option {
    padding: 8px 12px;
    font-size: 0.875rem;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #3b82f6;
}

.select2-result-etablissement {
    padding: 4px 0;
}
</style>

@endsection
