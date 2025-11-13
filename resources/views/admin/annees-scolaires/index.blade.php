@extends('layouts.admin')

@section('title', 'Années Scolaires')

@section('content')
<div class="space-y-4">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <i class="fas fa-calendar-alt text-[#002147]"></i>
            Années Scolaires
        </h1>
        <button onclick="openModal('createModal')" 
                class="bg-[#002147] text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition flex items-center gap-2 text-sm">
            <i class="fas fa-plus"></i>
            Nouvelle Année
        </button>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded text-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tableau -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Année</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date Début</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Date Fin</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Description</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Statut</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($annees as $annee)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $annee->annee }}</span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $annee->date_debut->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $annee->date_fin->format('d/m/Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $annee->description ?? '-' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($annee->is_active)
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(!$annee->is_active)
                                    <form action="{{ route('admin.annees-scolaires.activate', $annee) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="text-emerald-600 hover:text-emerald-900 transition"
                                                title="Activer">
                                            <i class="fas fa-check-circle text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <button onclick='openEditModal(@json($annee))' 
                                        class="text-blue-600 hover:text-blue-900 transition"
                                        title="Modifier">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                
                                @if(!$annee->is_active)
                                    <form action="{{ route('admin.annees-scolaires.destroy', $annee) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette année scolaire ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition"
                                                title="Supprimer">
                                            <i class="fas fa-trash text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p class="text-sm">Aucune année scolaire enregistrée</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Création -->
<div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center px-6 py-3 border-b bg-gray-50">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-plus-circle text-[#002147]"></i>
                Nouvelle Année Scolaire
            </h3>
            <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="{{ route('admin.annees-scolaires.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Année Scolaire <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="annee" 
                       placeholder="Ex: 2024-2025"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"
                       required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Début <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="date_debut" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Fin <span class="text-red-500">*</span></label>
                    <input type="date" 
                           name="date_fin" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"
                           required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" 
                          rows="2"
                          placeholder="Description optionnelle..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" 
                        onclick="closeModal('createModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#002147] text-white rounded-lg hover:bg-opacity-90 transition text-sm">
                    <i class="fas fa-save mr-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Édition -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-between items-center px-6 py-3 border-b bg-gray-50">
            <h3 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i>
                Modifier l'Année Scolaire
            </h3>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Année Scolaire <span class="text-red-500">*</span></label>
                <input type="text" 
                       id="edit_annee"
                       name="annee" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"
                       required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Début <span class="text-red-500">*</span></label>
                    <input type="date" 
                           id="edit_date_debut"
                           name="date_debut" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Fin <span class="text-red-500">*</span></label>
                    <input type="date" 
                           id="edit_date_fin"
                           name="date_fin" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"
                           required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="edit_description"
                          name="description" 
                          rows="2"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#002147] focus:border-transparent text-sm"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" 
                        onclick="closeModal('editModal')"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-[#002147] text-white rounded-lg hover:bg-opacity-90 transition text-sm">
                    <i class="fas fa-save mr-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openEditModal(annee) {
    document.getElementById('editForm').action = `/admin/annees-scolaires/${annee.id}`;
    document.getElementById('edit_annee').value = annee.annee;
    document.getElementById('edit_date_debut').value = annee.date_debut.split('T')[0];
    document.getElementById('edit_date_fin').value = annee.date_fin.split('T')[0];
    document.getElementById('edit_description').value = annee.description || '';
    openModal('editModal');
}

// Fermer modal en cliquant à l'extérieur
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});
</script>
@endsection
