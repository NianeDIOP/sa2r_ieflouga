@extends('layouts.etablissement')

@section('title', 'Historique des Rapports')

@section('content')
<div class="flex h-[calc(100vh-3.5rem)] bg-gray-50">
    
    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-y-auto">
        <div class="max-w-7xl mx-auto p-4 space-y-4">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-history text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">Historique des Rapports</h1>
                        <p class="text-xs text-gray-600">{{ $etablissement->etablissement }}</p>
                    </div>
                </div>
                <a href="{{ route('etablissement.rapport-rentree.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>

            <!-- Filtre par année -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-4">
                <form method="GET" action="{{ route('etablissement.rapport-rentree.historique.index') }}" class="flex items-center gap-4">
                    <div class="flex-1">
                        <label for="annee" class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-calendar-alt text-emerald-600 mr-1"></i>
                            Filtrer par année scolaire
                        </label>
                        <select name="annee" id="annee" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                onchange="this.form.submit()">
                            <option value="">📚 Toutes les années</option>
                            @foreach($anneesDisponibles as $annee)
                                <option value="{{ $annee->annee }}" {{ $anneeSelectionnee == $annee->annee ? 'selected' : '' }}>
                                    {{ $annee->annee }} {{ $annee->is_active ? '(Active)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if($anneeSelectionnee)
                    <div class="pt-6">
                        <a href="{{ route('etablissement.rapport-rentree.historique.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                            <i class="fas fa-times mr-1"></i>Réinitialiser
                        </a>
                    </div>
                    @endif
                </form>
                @if($anneeSelectionnee)
                <div class="mt-2 text-xs text-gray-600">
                    <i class="fas fa-filter mr-1"></i>
                    Affichage des rapports pour l'année <strong>{{ $anneeSelectionnee }}</strong>
                </div>
                @endif
            </div>

            @if($rapports->isEmpty())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Aucun rapport soumis pour le moment.
                </p>
            </div>
            @else
            <!-- Tableau -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Année</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Soumission</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Statut</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700">Par</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($rapports as $rapport)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $rapport->annee_scolaire }}</td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                {{ $rapport->date_soumission ? $rapport->date_soumission->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3">
                                @if($rapport->statut === 'soumis')
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                        <i class="fas fa-clock"></i> En attente
                                    </span>
                                @elseif($rapport->statut === 'validé')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                        <i class="fas fa-check-circle"></i> Validé
                                    </span>
                                @elseif($rapport->statut === 'rejeté')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">
                                        <i class="fas fa-times-circle"></i> Rejeté
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                @php
                                    $soumission = $rapport->historique->where('action', 'soumission')->first();
                                @endphp
                                {{ $soumission?->user?->name ?? ($rapport->soumis_par?->name ?? '-') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('etablissement.rapport-rentree.historique.show', $rapport) }}" 
                                       class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs"
                                       title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('etablissement.rapport-rentree.historique.excel', $rapport) }}" 
                                       class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs"
                                       title="Télécharger Excel">
                                        <i class="fas fa-file-excel"></i>
                                    </a>
                                    <a href="{{ route('etablissement.rapport-rentree.historique.show', $rapport) }}?print=1" 
                                       target="_blank"
                                       class="px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 text-xs"
                                       title="Imprimer">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @if($rapport->statut === 'rejeté' && $rapport->motif_rejet)
                                    <button onclick="showMotif{{ $rapport->id }}()" 
                                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs"
                                            title="Motif rejet">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Chronologie -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($rapports as $rapport)
                <div class="bg-white rounded-lg border border-gray-200">
                    <div class="bg-gray-50 px-4 py-2.5 border-b flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-900">{{ $rapport->annee_scolaire }}</span>
                        @if($rapport->statut === 'validé')
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs font-semibold rounded">Validé</span>
                        @elseif($rapport->statut === 'soumis')
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs font-semibold rounded">En attente</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs font-semibold rounded">Rejeté</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-xs font-semibold text-gray-700 mb-3">
                            <i class="fas fa-history text-emerald-600 mr-1"></i>
                            Chronologie
                        </h3>
                        <div class="space-y-2">
                            @foreach($rapport->historique->sortByDesc('created_at') as $h)
                            <div class="flex gap-2 pb-2 border-b border-gray-100 last:border-0">
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($h->action === 'soumission')
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-paper-plane text-blue-600" style="font-size: 10px;"></i>
                                        </div>
                                    @elseif($h->action === 'validation')
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check-circle text-green-600" style="font-size: 10px;"></i>
                                        </div>
                                    @elseif($h->action === 'rejet')
                                        <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-times-circle text-red-600" style="font-size: 10px;"></i>
                                        </div>
                                    @else
                                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-edit text-gray-600" style="font-size: 10px;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-gray-500">{{ $h->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-sm font-semibold text-gray-900 capitalize">{{ $h->action }}</div>
                                    <div class="text-xs text-gray-600">Par : {{ $h->user->name ?? 'Système' }}</div>
                                    @if($h->commentaire)
                                    <div class="text-xs text-gray-500 italic mt-1 bg-gray-50 rounded px-2 py-1">
                                        "{{ $h->commentaire }}"
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Modal Rejet -->
                @if($rapport->statut === 'rejeté' && $rapport->motif_rejet)
                <div id="motifModal{{ $rapport->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center" onclick="if(event.target === this) hideMotif{{ $rapport->id }}()">
                    <div class="bg-white rounded-lg border border-gray-200 w-full max-w-md mx-4">
                        <div class="bg-red-600 text-white px-4 py-3 rounded-t-lg flex items-center justify-between">
                            <h3 class="font-bold text-sm">Motif de Rejet</h3>
                            <button onclick="hideMotif{{ $rapport->id }}()" class="text-white hover:text-gray-200">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="mb-3">
                                <p class="text-xs text-gray-600 mb-1">Date de rejet</p>
                                <p class="text-sm font-semibold">{{ $rapport->date_rejet?->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <p class="text-xs text-gray-600 mb-1">Motif</p>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm">
                                    {{ $rapport->motif_rejet }}
                                </div>
                            </div>
                            @if($rapport->commentaire_admin)
                            <div class="mb-3">
                                <p class="text-xs text-gray-600 mb-1">Commentaire admin</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-sm">
                                    {{ $rapport->commentaire_admin }}
                                </div>
                            </div>
                            @endif
                            <div class="flex justify-end gap-2 pt-3 border-t">
                                <button onclick="hideMotif{{ $rapport->id }}()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-semibold">
                                    Fermer
                                </button>
                                <a href="{{ route('etablissement.rapport-rentree.index') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-semibold">
                                    <i class="fas fa-edit mr-1"></i>Modifier
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                function showMotif{{ $rapport->id }}() {
                    document.getElementById('motifModal{{ $rapport->id }}').classList.remove('hidden');
                    document.getElementById('motifModal{{ $rapport->id }}').classList.add('flex');
                }
                function hideMotif{{ $rapport->id }}() {
                    document.getElementById('motifModal{{ $rapport->id }}').classList.add('hidden');
                    document.getElementById('motifModal{{ $rapport->id }}').classList.remove('flex');
                }
                </script>
                @endif
                @endforeach
            </div>
            @endif
            
        </div>
    </main>
    
</div>
@endsection
