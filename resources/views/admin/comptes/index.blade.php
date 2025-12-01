@extends('layouts.admin')

@section('title', 'Gestion des Comptes')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-shield text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-[#002147]">Gestion des Comptes</h1>
                <div class="flex items-center gap-3 mt-0.5">
                    <span class="text-xs text-gray-600">Total: <strong class="text-gray-900">{{ $stats['total'] }}</strong></span>
                    <span class="text-xs text-green-600">Actifs: <strong>{{ $stats['actifs'] }}</strong></span>
                    <span class="text-xs text-red-600">Inactifs: <strong>{{ $stats['inactifs'] }}</strong></span>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="exportComptes()" 
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-emerald-700 bg-white border border-emerald-300 rounded-lg hover:bg-emerald-50 transition-colors">
                <i class="fas fa-file-excel text-xs"></i>
                Exporter
            </button>
            <button onclick="openModal('resetAllModal')" 
                    class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-purple-700 bg-white border border-purple-300 rounded-lg hover:bg-purple-50 transition-colors">
                <i class="fas fa-key text-xs"></i>
                Réinitialiser Tous
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

    <!-- Filtres de recherche -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-gray-700">
                <i class="fas fa-filter mr-1"></i>
                Filtres de recherche
            </h3>
            @if(request()->hasAny(['search', 'commune', 'zone', 'status']))
                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                    <i class="fas fa-check-circle mr-1"></i>
                    Filtres actifs
                </span>
            @endif
        </div>
        <form method="GET" action="{{ route('admin.comptes.index') }}" id="filterForm">
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
                    <select name="status" 
                            onchange="submitFilterForm()"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('status') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('status') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>
            
            @if(request()->hasAny(['search', 'commune', 'zone', 'status']))
                <div class="flex justify-end">
                    <a href="{{ route('admin.comptes.index') }}" 
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
        @if(request()->hasAny(['search', 'commune', 'zone', 'status']))
            <div class="px-4 py-2 bg-blue-50 border-b border-blue-200">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>{{ $comptes->total() }}</strong> résultat(s) trouvé(s) sur <strong>{{ $stats['total'] }}</strong> comptes
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
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-id-card text-blue-600 mr-1"></i>
                            Code (Login)
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-key text-amber-600 mr-1"></i>
                            Mot de passe
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-user-tie text-purple-600 mr-1"></i>
                            Directeur
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-history text-emerald-600 mr-1"></i>
                            Connexions
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-toggle-on text-teal-600 mr-1"></i>
                            Statut
                        </th>
                        <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            <i class="fas fa-cog text-gray-600 mr-1"></i>
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($comptes as $compte)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-gray-900">{{ $compte->etablissement->etablissement ?? 'N/A' }}</span>
                                    <span class="text-xs text-gray-500">{{ $compte->etablissement->commune ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <code class="text-xs font-mono font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded">{{ $compte->code }}</code>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <input type="password" 
                                           id="pwd-{{ $compte->id }}" 
                                           value="sa2r2025" 
                                           readonly
                                           class="w-24 px-2 py-1 text-xs font-mono border border-gray-300 rounded bg-gray-50">
                                    <button onclick="togglePassword({{ $compte->id }})" type="button"
                                            class="p-1 text-gray-600 hover:text-indigo-600 transition-colors"
                                            title="Afficher/Masquer">
                                        <i id="eye-{{ $compte->id }}" class="fas fa-eye text-xs"></i>
                                    </button>
                                    <button onclick="copyPassword('{{ $compte->code }}')" 
                                            class="p-1 text-gray-600 hover:text-emerald-600 transition-colors"
                                            title="Copier">
                                        <i class="fas fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    @php
                                        $rapport = $compte->etablissement->rapports()->where('annee_scolaire', '2024-2025')->first();
                                        $infoDirecteur = $rapport?->infoDirecteur;
                                    @endphp
                                    <span class="text-xs text-gray-900">{{ $infoDirecteur?->directeur_nom ?? '—' }}</span>
                                    @if($infoDirecteur?->directeur_contact_1)
                                        <a href="tel:{{ $infoDirecteur->directeur_contact_1 }}" class="text-xs text-blue-600 hover:underline">
                                            <i class="fas fa-phone text-[10px] mr-1"></i>{{ $infoDirecteur->directeur_contact_1 }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-xs font-semibold text-gray-900">{{ $compte->login_count }}</span>
                                    @if($compte->last_login_at)
                                        <span class="text-xs text-gray-500" title="{{ $compte->last_login_at->format('d/m/Y H:i') }}">
                                            {{ $compte->last_login_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-xs text-amber-600">Jamais</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="toggleStatus({{ $compte->id }}, {{ $compte->is_active ? 'true' : 'false' }})" type="button"
                                        id="status-btn-{{ $compte->id }}"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $compte->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $compte->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    @php
                                        $rapport = $compte->etablissement->rapports()->where('annee_scolaire', '2024-2025')->first();
                                        $infoDirecteur = $rapport?->infoDirecteur;
                                    @endphp
                                    <button onclick="openEditDirecteurModal({{ $compte->id }}, '{{ addslashes($infoDirecteur?->directeur_nom ?? '') }}', '{{ addslashes($infoDirecteur?->directeur_contact_1 ?? '') }}')" type="button"
                                            class="p-1.5 text-purple-600 hover:bg-purple-50 rounded transition-colors"
                                            title="Modifier infos directeur">
                                        <i class="fas fa-user-edit text-sm"></i>
                                    </button>
                                    
                                    <button onclick="openChangePasswordModal({{ $compte->id }}, '{{ addslashes($compte->code) }}')" type="button"
                                            class="p-1.5 text-amber-600 hover:bg-amber-50 rounded transition-colors"
                                            title="Modifier mot de passe">
                                        <i class="fas fa-key text-sm"></i>
                                    </button>
                                    
                                    <button onclick="resetToDefault({{ $compte->id }})" type="button"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                            title="Réinitialiser (sa2r2025)">
                                        <i class="fas fa-undo text-sm"></i>
                                    </button>
                                    
                                    <button onclick="viewHistory({{ $compte->id }})" type="button"
                                            class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded transition-colors"
                                            title="Voir l'historique">
                                        <i class="fas fa-history text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                                <p class="text-sm text-gray-500">Aucun compte</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($comptes->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-600">
                Affichage de <strong>{{ $comptes->firstItem() }}</strong> à <strong>{{ $comptes->lastItem() }}</strong> sur <strong>{{ $comptes->total() }}</strong> comptes
            </div>
            <div>
                {{ $comptes->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Modal Modifier Directeur -->
<div id="editDirecteurModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-purple-600">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-user-tie mr-2"></i>
                Informations du Directeur
            </h3>
            <button onclick="closeModal('editDirecteurModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editDirecteurForm" class="p-5 space-y-4">
            <input type="hidden" id="directeur_user_id">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-user text-purple-600 mr-1"></i>
                    Nom complet du directeur
                </label>
                <input type="text" 
                       id="directeur_nom" 
                       name="directeur_nom"
                       placeholder="Ex: Cheikh Abdou DIOP"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-phone text-emerald-600 mr-1"></i>
                    Numéro de téléphone
                </label>
                <input type="tel" 
                       id="directeur_telephone" 
                       name="directeur_telephone"
                       placeholder="Ex: 77 123 45 67"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent text-sm">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" 
                        onclick="closeModal('editDirecteurModal')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition-colors text-sm font-medium">
                    <i class="fas fa-save text-xs mr-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Modifier Mot de passe -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-amber-600">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-key mr-2"></i>
                Modifier le Mot de passe
            </h3>
            <button onclick="closeModal('changePasswordModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="changePasswordForm" class="p-5 space-y-4">
            <input type="hidden" id="password_user_id">
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-blue-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Code de connexion: <strong id="password_user_code" class="font-mono"></strong>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-lock text-amber-600 mr-1"></i>
                    Nouveau mot de passe <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" 
                           id="new_password" 
                           name="new_password"
                           required
                           minlength="6"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-600 focus:border-transparent text-sm">
                    <button type="button" 
                            onclick="toggleNewPassword()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-600 hover:text-amber-600">
                        <i id="eye-new-pwd" class="fas fa-eye text-sm"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimum 6 caractères</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-lock text-amber-600 mr-1"></i>
                    Confirmer le mot de passe <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       id="confirm_password" 
                       name="confirm_password"
                       required
                       minlength="6"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-600 focus:border-transparent text-sm">
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" 
                        onclick="closeModal('changePasswordModal')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-amber-600 text-white rounded hover:bg-amber-700 transition-colors text-sm font-medium">
                    <i class="fas fa-save text-xs mr-1"></i> Modifier
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Réinitialiser Tous -->
<div id="resetAllModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-red-600">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Confirmation
            </h3>
            <button onclick="closeModal('resetAllModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="p-5 space-y-4">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <p class="text-sm text-amber-800 mb-2">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Êtes-vous sûr de vouloir réinitialiser <strong>TOUS</strong> les mots de passe ?
                </p>
                <p class="text-xs text-amber-700">
                    Tous les comptes seront réinitialisés avec le mot de passe par défaut : <strong>sa2r2025</strong>
                </p>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button type="button" 
                        onclick="closeModal('resetAllModal')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                    Annuler
                </button>
                <button onclick="resetAllPasswords()" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm font-medium">
                    <i class="fas fa-key text-xs mr-1"></i> Réinitialiser Tous
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Historique -->
<div id="historyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] flex flex-col">
        <div class="flex justify-between items-center px-5 py-3 border-b bg-emerald-600">
            <h3 class="text-base font-semibold text-white">
                <i class="fas fa-history mr-2"></i>
                Historique des Connexions
            </h3>
            <button onclick="closeModal('historyModal')" class="text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div id="historyContent" class="p-5 overflow-y-auto flex-1">
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

function togglePassword(userId) {
    const input = document.getElementById(`pwd-${userId}`);
    const icon = document.getElementById(`eye-${userId}`);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function toggleNewPassword() {
    const input = document.getElementById('new_password');
    const icon = document.getElementById('eye-new-pwd');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function copyPassword(code) {
    navigator.clipboard.writeText('sa2r2025').then(() => {
        alert(`Code: ${code}\nMot de passe: sa2r2025\n\nCopié dans le presse-papier !`);
    });
}

function toggleStatus(userId, currentStatus) {
    fetch(`/admin/comptes/${userId}/toggle-status`, {
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
            const btn = document.getElementById(`status-btn-${userId}`);
            const span = btn.querySelector('span');
            
            if (data.is_active) {
                btn.classList.remove('bg-gray-300');
                btn.classList.add('bg-green-500');
                span.classList.remove('translate-x-1');
                span.classList.add('translate-x-6');
            } else {
                btn.classList.remove('bg-green-500');
                btn.classList.add('bg-gray-300');
                span.classList.remove('translate-x-6');
                span.classList.add('translate-x-1');
            }
            
            btn.setAttribute('onclick', `toggleStatus(${userId}, ${data.is_active})`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la modification du statut');
    });
}

function openEditDirecteurModal(userId, nom, telephone) {
    document.getElementById('directeur_user_id').value = userId;
    document.getElementById('directeur_nom').value = nom || '';
    document.getElementById('directeur_telephone').value = telephone || '';
    openModal('editDirecteurModal');
}

document.getElementById('editDirecteurForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('directeur_user_id').value;
    const nom = document.getElementById('directeur_nom').value;
    const telephone = document.getElementById('directeur_telephone').value;
    
    fetch(`/admin/comptes/${userId}/update-directeur`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            directeur_nom: nom,
            directeur_telephone: telephone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert(data.error || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
});

function openChangePasswordModal(userId, code) {
    document.getElementById('password_user_id').value = userId;
    document.getElementById('password_user_code').textContent = code;
    document.getElementById('new_password').value = '';
    document.getElementById('confirm_password').value = '';
    openModal('changePasswordModal');
}

document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('password_user_id').value;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas !');
        return;
    }
    
    fetch(`/admin/comptes/${userId}/change-password`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            password: newPassword
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeModal('changePasswordModal');
            window.location.reload();
        } else {
            alert(data.error || 'Erreur lors de la modification');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la modification');
    });
});

function resetToDefault(userId) {
    if (!confirm('Réinitialiser le mot de passe à "sa2r2025" ?')) {
        return;
    }
    
    fetch(`/admin/comptes/${userId}/reset-password`, {
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
            alert(data.error || 'Erreur lors de la réinitialisation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la réinitialisation');
    });
}

function resetAllPasswords() {
    fetch('/admin/comptes/reset-all-passwords', {
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
            closeModal('resetAllModal');
            window.location.reload();
        } else {
            alert(data.error || 'Erreur lors de la réinitialisation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la réinitialisation');
    });
}

function viewHistory(userId) {
    fetch(`/admin/comptes/${userId}/history`)
        .then(response => response.json())
        .then(data => {
            let content = `
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Établissement</p>
                                <p class="text-sm font-semibold text-gray-900">${data.user.etablissement?.etablissement || 'N/A'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Code</p>
                                <p class="text-sm font-mono font-semibold text-blue-700">${data.user.code}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Total connexions</p>
                                <p class="text-sm font-semibold text-gray-900">${data.user.login_count}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Dernière connexion</p>
                                <p class="text-sm font-semibold text-gray-900">${data.user.last_login_at || 'Jamais'}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-list mr-1"></i>
                            Informations complémentaires
                        </h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                            <p class="text-xs text-gray-700">
                                <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                                Directeur: <strong>${data.user.directeur_nom || '—'}</strong>
                            </p>
                            <p class="text-xs text-gray-700">
                                <i class="fas fa-phone text-emerald-600 mr-2"></i>
                                Téléphone: <strong>${data.user.directeur_telephone || '—'}</strong>
                            </p>
                            <p class="text-xs text-gray-700">
                                <i class="fas fa-calendar text-blue-600 mr-2"></i>
                                Compte créé: <strong>${data.user.created_at}</strong>
                            </p>
                            <p class="text-xs text-gray-700">
                                <i class="fas fa-toggle-on text-teal-600 mr-2"></i>
                                Statut: <strong class="${data.user.is_active ? 'text-green-600' : 'text-red-600'}">${data.user.is_active ? 'Actif' : 'Inactif'}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('historyContent').innerHTML = content;
            openModal('historyModal');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du chargement de l\'historique');
        });
}

function exportComptes() {
    window.location.href = '/admin/comptes/export';
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
