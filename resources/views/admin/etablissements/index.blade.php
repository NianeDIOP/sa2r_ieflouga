@extends('layouts.admin')

@section('title', 'Gestion des Établissements')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-[#002147] rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#002147]">Établissements</h1>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-xs text-gray-600">Total: <strong class="text-gray-900">{{ $stats['total'] }}</strong></span>
                    <span class="text-xs text-blue-600">Public: <strong>{{ $stats['public'] }}</strong></span>
                    <span class="text-xs text-purple-600">Privé: <strong>{{ $stats['prive'] }}</strong></span>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.etablissements.template') }}" 
               class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-emerald-700 bg-white border border-emerald-300 rounded-lg hover:bg-emerald-50 transition-colors">
                <i class="fas fa-download text-xs"></i>
                Template
            </a>
            <button onclick="openModal('importModal')" 
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-300 rounded-lg hover:bg-blue-50 transition-colors">
                <i class="fas fa-file-import text-xs"></i>
                Importer
            </button>
            <button onclick="openModal('createModal')" 
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-[#002147] rounded-lg hover:bg-blue-900 transition-colors">
                <i class="fas fa-plus text-xs"></i>
                Nouveau
            </button>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 px-4 py-3 rounded-r text-sm flex items-center gap-2" role="alert">
            <i class="fas fa-check-circle text-emerald-600"></i>
            <span class="text-emerald-800">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-r text-sm flex items-center gap-2" role="alert">
            <i class="fas fa-exclamation-circle text-red-600"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('errors') && count(session('errors')) > 0)
        <div class="bg-amber-50 border-l-4 border-amber-500 px-4 py-3 rounded-r text-sm" role="alert">
            <div class="flex items-start gap-2">
                <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
                <div class="flex-1">
                    <p class="font-semibold text-amber-800 mb-1">Erreurs détectées lors de l'import :</p>
                    <ul class="list-disc list-inside text-amber-700 space-y-0.5">
                        @foreach(session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-filter mr-1"></i>
                Filtres de recherche
            </h3>
            @if(request()->hasAny(['search', 'commune', 'zone', 'statut']))
                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                    <i class="fas fa-check-circle mr-1"></i>
                    Filtres actifs
                </span>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.etablissements.index') }}" id="filterForm">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                <div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher (nom, code)..." 
                           oninput="debounceFilter()"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                </div>
                
                <div>
                    <select name="commune" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                        <option value="">Toutes les communes</option>
                        @foreach($lists['communes'] as $commune)
                            <option value="{{ $commune }}" {{ request('commune') == $commune ? 'selected' : '' }}>{{ $commune }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="zone" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                        <option value="">Toutes les zones</option>
                        @foreach($lists['zones'] as $zone)
                            <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <select name="statut" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="Public" {{ request('statut') == 'Public' ? 'selected' : '' }}>Public</option>
                        <option value="Privé" {{ request('statut') == 'Privé' ? 'selected' : '' }}>Privé</option>
                    </select>
                </div>
            </div>
            
            @if(request()->hasAny(['search', 'commune', 'zone', 'statut']))
                <div class="flex justify-end">
                    <a href="{{ route('admin.etablissements.index') }}" 
                       class="inline-flex items-center gap-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                       title="Réinitialiser les filtres">
                        <i class="fas fa-times text-xs"></i>
                        <span>Réinitialiser</span>
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        @if(request()->hasAny(['search', 'commune', 'zone', 'statut']))
            <div class="px-4 py-2 bg-blue-50 border-b border-blue-200">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>{{ $etablissements->total() }}</strong> résultat(s) trouvé(s) sur <strong>{{ $stats['total'] }}</strong> établissements
                </p>
            </div>
        @endif
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-building text-[#002147] mr-1"></i>
                        Établissement
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-barcode text-blue-600 mr-1"></i>
                        Code
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-map-marker-alt text-emerald-600 mr-1"></i>
                        Commune
                    </th>
                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-map-pin text-orange-600 mr-1"></i>
                        Zone
                    </th>
                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-tag text-purple-600 mr-1"></i>
                        Statut
                    </th>
                    <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-cog text-gray-600 mr-1"></i>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($etablissements as $etablissement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-2">
                            <span class="text-sm font-medium text-gray-900">{{ $etablissement->etablissement }}</span>
                        </td>
                        <td class="px-4 py-2">
                            <code class="text-xs font-mono font-semibold text-blue-700">{{ $etablissement->code }}</code>
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            {{ $etablissement->commune ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-sm text-gray-700">
                            {{ $etablissement->zone ?? '—' }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            @if($etablissement->statut == 'Public')
                                <span class="text-xs font-semibold text-blue-700">
                                    <i class="fas fa-landmark text-[10px] mr-1"></i>
                                    Public
                                </span>
                            @else
                                <span class="text-xs font-semibold text-purple-700">
                                    <i class="fas fa-building text-[10px] mr-1"></i>
                                    Privé
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex items-center justify-center gap-1">
                                <button onclick="showDetails({{ $etablissement->id }})" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        title="Voir les détails">
                                    <i class="far fa-eye text-sm"></i>
                                </button>
                                
                                <button onclick='openEditModal(@json($etablissement))' 
                                        class="p-1.5 text-amber-600 hover:bg-amber-50 rounded transition-colors"
                                        title="Modifier"
                                        data-id="{{ $etablissement->id }}"
                                        data-etablissement="{{ $etablissement->etablissement }}"
                                        data-code="{{ $etablissement->code }}"
                                        data-statut="{{ $etablissement->statut }}"
                                        data-type-statut="{{ $etablissement->type_statut }}"
                                        data-arrondissement="{{ $etablissement->arrondissement }}"
                                        data-commune="{{ $etablissement->commune }}"
                                        data-district="{{ $etablissement->district }}"
                                        data-zone="{{ $etablissement->zone }}"
                                        data-geo-ref-x="{{ $etablissement->geo_ref_x }}"
                                        data-geo-ref-y="{{ $etablissement->geo_ref_y }}"
                                        data-date-creation="{{ $etablissement->date_creation }}"
                                        data-date-ouverture="{{ $etablissement->date_ouverture }}">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>

                                @if($etablissement->user)
                                    <button onclick="toggleUserStatus({{ $etablissement->id }}, {{ $etablissement->user->is_active ? 'true' : 'false' }})" 
                                            id="toggle-btn-{{ $etablissement->id }}"
                                            class="p-1.5 {{ $etablissement->user->is_active ? 'text-green-600' : 'text-gray-400' }} hover:bg-green-50 rounded transition-colors"
                                            title="{{ $etablissement->user->is_active ? 'Compte actif - Cliquer pour désactiver' : 'Compte inactif - Cliquer pour activer' }}">
                                        <i class="fas fa-user-check text-sm"></i>
                                    </button>

                                    <button onclick="resetPassword({{ $etablissement->id }})" 
                                            class="p-1.5 text-purple-600 hover:bg-purple-50 rounded transition-colors"
                                            title="Réinitialiser le mot de passe">
                                        <i class="fas fa-key text-sm"></i>
                                    </button>
                                @else
                                    <button disabled
                                            class="p-1.5 text-gray-300 cursor-not-allowed rounded"
                                            title="Aucun compte associé">
                                        <i class="fas fa-user-slash text-sm"></i>
                                    </button>
                                @endif
                                
                                <form action="{{ route('admin.etablissements.destroy', $etablissement) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet établissement ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                                            title="Supprimer">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Aucun établissement</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($etablissements->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Affichage de <strong>{{ $etablissements->firstItem() }}</strong> à <strong>{{ $etablissements->lastItem() }}</strong> sur <strong>{{ $etablissements->total() }}</strong> établissements
            </div>
            <div>
                {{ $etablissements->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Import -->
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-blue-600">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-file-import mr-2"></i>
                Importer depuis Excel
            </h3>
            <button onclick="closeImportModal()" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="importForm" action="{{ route('admin.etablissements.import') }}" method="POST" enctype="multipart/form-data" class="flex-1 overflow-y-auto">
            @csrf
            
            <div class="p-5 space-y-4">
                <!-- Sélection du fichier -->
                <div id="fileSelectionSection">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-excel text-green-600 mr-1"></i>
                        Sélectionner un fichier Excel <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           id="excelFile"
                           name="file" 
                           accept=".xlsx,.xls"
                           onchange="previewExcelFile(event)"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           required>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded p-3 mt-3">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Le fichier doit respecter le format du template. Un compte utilisateur sera créé pour chaque établissement avec le mot de passe : <strong>sa2r2025</strong>
                        </p>
                    </div>
                </div>

                <!-- Prévisualisation -->
                <div id="previewSection" class="hidden">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-semibold text-gray-700">
                            <i class="fas fa-eye text-blue-600 mr-1"></i>
                            Aperçu du fichier
                        </h4>
                        <span id="previewCount" class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded"></span>
                    </div>
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto max-h-64">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">#</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Établissement</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Code</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Commune</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Zone</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Statut</th>
                                    </tr>
                                </thead>
                                <tbody id="previewTableBody" class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Barre de progression -->
                <div id="progressSection" class="hidden">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-700">
                                <i class="fas fa-spinner fa-spin text-blue-600 mr-1"></i>
                                Import en cours...
                            </h4>
                            <span id="progressText" class="text-xs text-gray-600"></span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div id="progressBar" class="bg-blue-600 h-3 rounded-full transition-all duration-300 flex items-center justify-center" style="width: 0%">
                                <span id="progressPercent" class="text-xs font-semibold text-white"></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            Cette opération peut prendre plusieurs minutes...
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2 px-5 py-3 border-t bg-gray-50">
                <button type="button" 
                        id="cancelBtn"
                        onclick="closeImportModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        id="importBtn"
                        disabled
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm font-medium disabled:bg-gray-300 disabled:cursor-not-allowed">
                    <i class="fas fa-upload text-xs mr-1"></i> <span id="importBtnText">Importer</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Création -->
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-[#002147] sticky top-0">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-plus mr-2"></i>
                Nouvel Établissement
            </h3>
            <button onclick="closeModal('createModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.etablissements.store') }}" method="POST" class="p-5 space-y-3">
            @csrf
            
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-school text-[#002147] mr-1"></i>
                        Établissement <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="etablissement" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-barcode text-blue-600 mr-1"></i>
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" maxlength="10" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-tag text-purple-600 mr-1"></i>
                        Statut
                    </label>
                    <select name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        <option value="Public">Public</option>
                        <option value="Privé">Privé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-list-alt text-indigo-600 mr-1"></i>
                        Type Statut
                    </label>
                    <input type="text" name="type_statut" maxlength="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-map-marked-alt text-red-600 mr-1"></i>
                        Arrondissement
                    </label>
                    <select name="arrondissement" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['arrondissements'] as $arr)
                            <option value="{{ $arr }}">{{ $arr }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-map-marker-alt text-emerald-600 mr-1"></i>
                        Commune
                    </label>
                    <select name="commune" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['communes'] as $com)
                            <option value="{{ $com }}">{{ $com }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-city text-teal-600 mr-1"></i>
                        District
                    </label>
                    <select name="district" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['districts'] as $dist)
                            <option value="{{ $dist }}">{{ $dist }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-map-pin text-orange-600 mr-1"></i>
                        Zone
                    </label>
                    <select name="zone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['zones'] as $zone)
                            <option value="{{ $zone }}">{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-crosshairs text-blue-500 mr-1"></i>
                        Geo Ref X
                    </label>
                    <input type="number" name="geo_ref_x"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-crosshairs text-green-500 mr-1"></i>
                        Geo Ref Y
                    </label>
                    <input type="number" name="geo_ref_y"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-plus text-pink-600 mr-1"></i>
                        Date Création
                    </label>
                    <input type="number" name="date_creation" min="1900" max="2100" placeholder="YYYY"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-check text-cyan-600 mr-1"></i>
                        Date Ouverture
                    </label>
                    <input type="number" name="date_ouverture" min="1900" max="2100" placeholder="YYYY"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" 
                        onclick="closeModal('createModal')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#002147] text-white rounded hover:bg-blue-900 transition-colors text-sm font-medium">
                    <i class="fas fa-save text-xs mr-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Édition -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-amber-600 sticky top-0">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-edit mr-2"></i>
                Modifier l'Établissement
            </h3>
            <button onclick="closeModal('editModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" class="p-5 space-y-3">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-school text-[#002147] mr-1"></i>
                        Établissement <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_etablissement" name="etablissement" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-barcode text-blue-600 mr-1"></i>
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="edit_code" name="code" maxlength="10" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm font-mono">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-tag text-purple-600 mr-1"></i>
                        Statut
                    </label>
                    <select id="edit_statut" name="statut" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        <option value="Public">Public</option>
                        <option value="Privé">Privé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-list-alt text-indigo-600 mr-1"></i>
                        Type Statut
                    </label>
                    <input type="text" id="edit_type_statut" name="type_statut" maxlength="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-map-marked-alt text-red-600 mr-1"></i>
                        Arrondissement
                    </label>
                    <select id="edit_arrondissement" name="arrondissement" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['arrondissements'] as $arr)
                            <option value="{{ $arr }}">{{ $arr }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-map-marker-alt text-emerald-600 mr-1"></i>
                        Commune
                    </label>
                    <select id="edit_commune" name="commune" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['communes'] as $com)
                            <option value="{{ $com }}">{{ $com }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-city text-teal-600 mr-1"></i>
                        District
                    </label>
                    <select id="edit_district" name="district" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['districts'] as $dist)
                            <option value="{{ $dist }}">{{ $dist }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-map-pin text-orange-600 mr-1"></i>
                        Zone
                    </label>
                    <select id="edit_zone" name="zone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                        <option value="">Sélectionner...</option>
                        @foreach($lists['zones'] as $zone)
                            <option value="{{ $zone }}">{{ $zone }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-crosshairs text-blue-500 mr-1"></i>
                        Geo Ref X
                    </label>
                    <input type="number" id="edit_geo_ref_x" name="geo_ref_x"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-crosshairs text-green-500 mr-1"></i>
                        Geo Ref Y
                    </label>
                    <input type="number" id="edit_geo_ref_y" name="geo_ref_y"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-plus text-pink-600 mr-1"></i>
                        Date Création
                    </label>
                    <input type="number" id="edit_date_creation" name="date_creation" min="1900" max="2100" placeholder="YYYY"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-calendar-check text-cyan-600 mr-1"></i>
                        Date Ouverture
                    </label>
                    <input type="number" id="edit_date_ouverture" name="date_ouverture" min="1900" max="2100" placeholder="YYYY"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" 
                        onclick="closeModal('editModal')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-amber-600 text-white rounded hover:bg-amber-700 transition-colors text-sm font-medium">
                    <i class="fas fa-save text-xs mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Détails -->
<div id="showModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-indigo-600 sticky top-0">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-info-circle mr-2"></i>
                Détails de l'Établissement
            </h3>
            <button onclick="closeModal('showModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="showContent" class="p-5">
            <!-- Contenu chargé dynamiquement -->
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openEditModal(etablissementOrEvent) {
    let etablissement;
    
    // Si c'est un événement de clic, récupérer les données depuis les attributs data-*
    if (etablissementOrEvent.target || etablissementOrEvent.currentTarget) {
        const button = etablissementOrEvent.target.closest('button') || etablissementOrEvent.currentTarget;
        etablissement = {
            id: button.dataset.id,
            etablissement: button.dataset.etablissement,
            code: button.dataset.code,
            statut: button.dataset.statut,
            type_statut: button.dataset.typeStatut,
            arrondissement: button.dataset.arrondissement,
            commune: button.dataset.commune,
            district: button.dataset.district,
            zone: button.dataset.zone,
            geo_ref_x: button.dataset.geoRefX,
            geo_ref_y: button.dataset.geoRefY,
            date_creation: button.dataset.dateCreation,
            date_ouverture: button.dataset.dateOuverture
        };
    } else {
        // Sinon, utiliser l'objet passé directement
        etablissement = etablissementOrEvent;
    }
    
    console.log('Données reçues:', etablissement); // Debug
    
    document.getElementById('editForm').action = `/admin/etablissements/${etablissement.id}`;
    document.getElementById('edit_etablissement').value = etablissement.etablissement || '';
    document.getElementById('edit_code').value = etablissement.code || '';
    document.getElementById('edit_statut').value = etablissement.statut || '';
    document.getElementById('edit_type_statut').value = etablissement.type_statut || '';
    document.getElementById('edit_arrondissement').value = etablissement.arrondissement || '';
    document.getElementById('edit_commune').value = etablissement.commune || '';
    document.getElementById('edit_district').value = etablissement.district || '';
    document.getElementById('edit_zone').value = etablissement.zone || '';
    document.getElementById('edit_geo_ref_x').value = etablissement.geo_ref_x || '';
    document.getElementById('edit_geo_ref_y').value = etablissement.geo_ref_y || '';
    document.getElementById('edit_date_creation').value = etablissement.date_creation || '';
    document.getElementById('edit_date_ouverture').value = etablissement.date_ouverture || '';
    
    // Vérifier que les valeurs sont bien assignées
    console.log('District assigné:', document.getElementById('edit_district').value);
    console.log('Zone assignée:', document.getElementById('edit_zone').value);
    
    openModal('editModal');
}

function toggleUserStatus(etablissementId, currentStatus) {
    if (!confirm(currentStatus ? 'Désactiver ce compte ?' : 'Activer ce compte ?')) {
        return;
    }

    fetch(`/admin/etablissements/${etablissementId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const btn = document.getElementById(`toggle-btn-${etablissementId}`);
            if (data.is_active) {
                btn.classList.remove('text-gray-400');
                btn.classList.add('text-green-600');
                btn.title = 'Compte actif - Cliquer pour désactiver';
            } else {
                btn.classList.remove('text-green-600');
                btn.classList.add('text-gray-400');
                btn.title = 'Compte inactif - Cliquer pour activer';
            }
            btn.setAttribute('onclick', `toggleUserStatus(${etablissementId}, ${data.is_active})`);
            
            alert(data.message);
        } else {
            alert(data.error || 'Erreur lors de la modification du statut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la modification du statut');
    });
}

function resetPassword(etablissementId) {
    if (!confirm('Réinitialiser le mot de passe à "sa2r2025" ?')) {
        return;
    }

    fetch(`/admin/etablissements/${etablissementId}/reset-password`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert(data.error || 'Erreur lors de la réinitialisation du mot de passe');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la réinitialisation du mot de passe');
    });
}

function showDetails(id) {
    fetch(`/admin/etablissements/${id}`)
        .then(response => response.json())
        .then(data => {
            const etab = data.etablissement;
            const user = data.user;
            
            const content = `
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Établissement</p>
                        <p class="text-sm font-semibold text-gray-900">${etab.etablissement}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Code</p>
                        <p class="text-sm font-mono font-semibold text-gray-900">${etab.code}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Statut</p>
                        <p class="text-sm text-gray-900">${etab.statut || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Type Statut</p>
                        <p class="text-sm text-gray-900">${etab.type_statut || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Arrondissement</p>
                        <p class="text-sm text-gray-900">${etab.arrondissement || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Commune</p>
                        <p class="text-sm text-gray-900">${etab.commune || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">District</p>
                        <p class="text-sm text-gray-900">${etab.district || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Zone</p>
                        <p class="text-sm text-gray-900">${etab.zone || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Coordonnées GPS</p>
                        <p class="text-sm text-gray-900">X: ${etab.geo_ref_x || '-'} | Y: ${etab.geo_ref_y || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Dates</p>
                        <p class="text-sm text-gray-900">Création: ${etab.date_creation || '-'} | Ouverture: ${etab.date_ouverture || '-'}</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-4 border-t">
                    <p class="text-xs text-gray-500 uppercase mb-2">Compte Utilisateur</p>
                    ${user ? `
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                            <p class="text-sm text-emerald-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Login: <strong>${user.code}</strong> | 
                                Statut: ${user.is_active ? '<span class="text-emerald-600">Actif</span>' : '<span class="text-red-600">Inactif</span>'}
                            </p>
                        </div>
                    ` : `
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-sm text-amber-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Aucun compte utilisateur associé
                            </p>
                        </div>
                    `}
                </div>
            `;
            
            document.getElementById('showContent').innerHTML = content;
            openModal('showModal');
        });
}

// Fermer modal en cliquant à l'extérieur
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Fonction pour fermer le modal d'import
function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importForm').reset();
    document.getElementById('previewSection').classList.add('hidden');
    document.getElementById('fileSelectionSection').classList.remove('hidden');
    document.getElementById('progressSection').classList.add('hidden');
    document.getElementById('importBtn').disabled = true;
}

// Prévisualisation du fichier Excel
function previewExcelFile(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, { type: 'array' });
            const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
            
            // Ignorer la première ligne (en-têtes)
            const rows = jsonData.slice(1).filter(row => row.some(cell => cell !== undefined && cell !== ''));
            
            // Afficher le nombre de lignes
            document.getElementById('previewCount').textContent = `${rows.length} établissement(s) détecté(s)`;
            
            // Créer le tableau de prévisualisation (max 10 lignes)
            const previewRows = rows.slice(0, 10);
            const tbody = document.getElementById('previewTableBody');
            tbody.innerHTML = '';
            
            previewRows.forEach((row, index) => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                tr.innerHTML = `
                    <td class="px-3 py-2 text-xs text-gray-500">${index + 1}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">${row[0] || '-'}</td>
                    <td class="px-3 py-2 text-xs text-gray-900 font-mono">${row[5] || '-'}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">${row[2] || '-'}</td>
                    <td class="px-3 py-2 text-xs text-gray-900">${row[4] || '-'}</td>
                    <td class="px-3 py-2 text-xs">
                        ${row[8] ? `<span class="px-2 py-1 text-xs rounded ${row[8] === 'Public' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'}">${row[8]}</span>` : '-'}
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            if (rows.length > 10) {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="6" class="px-3 py-2 text-xs text-center text-gray-500 italic">... et ${rows.length - 10} autre(s) ligne(s)</td>`;
                tbody.appendChild(tr);
            }
            
            // Afficher la prévisualisation
            document.getElementById('previewSection').classList.remove('hidden');
            document.getElementById('importBtn').disabled = false;
            
        } catch (error) {
            alert('Erreur lors de la lecture du fichier. Assurez-vous qu\'il s\'agit d\'un fichier Excel valide.');
            console.error(error);
        }
    };
    reader.readAsArrayBuffer(file);
}

// Gestion de la soumission du formulaire d'import
document.getElementById('importForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Masquer les sections et afficher la barre de progression
    document.getElementById('fileSelectionSection').classList.add('hidden');
    document.getElementById('previewSection').classList.add('hidden');
    document.getElementById('progressSection').classList.remove('hidden');
    document.getElementById('importBtn').disabled = true;
    document.getElementById('importBtn').innerHTML = '<i class="fas fa-spinner fa-spin text-xs mr-1"></i> Import en cours...';
    document.getElementById('cancelBtn').disabled = true;
    
    // Simuler la progression (puisque l'import se fait côté serveur)
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressText = document.getElementById('progressText');
    
    const interval = setInterval(() => {
        progress += 1;
        if (progress <= 90) {
            progressBar.style.width = progress + '%';
            progressPercent.textContent = progress + '%';
            progressText.textContent = 'Traitement en cours...';
        }
    }, 500);
    
    // Soumettre le formulaire
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        clearInterval(interval);
        progressBar.style.width = '100%';
        progressPercent.textContent = '100%';
        progressText.textContent = 'Terminé !';
        
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    })
    .catch(error => {
        clearInterval(interval);
        alert('Erreur lors de l\'import: ' + error.message);
        closeImportModal();
    });
});

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
</script>

<!-- Bibliothèque SheetJS pour lire les fichiers Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

@endsection
