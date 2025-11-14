@extends('layouts.app')

@section('title', 'Historique des Rapports')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0"><i class="fas fa-history me-2"></i>Historique des Rapports de Rentrée</h2>
            <p class="text-muted">{{ $etablissement->etablissement }}</p>
        </div>
    </div>

    @if($rapports->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>
        Aucun rapport soumis pour le moment.
    </div>
    @else
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Année Scolaire</th>
                            <th>Date de Soumission</th>
                            <th>Statut</th>
                            <th>Soumis par</th>
                            <th>Dernière Modification</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rapports as $rapport)
                        <tr>
                            <td><strong>{{ $rapport->annee_scolaire }}</strong></td>
                            <td>
                                @if($rapport->date_soumission)
                                    {{ $rapport->date_soumission->format('d/m/Y à H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($rapport->statut === 'soumis')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($rapport->statut === 'validé')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Validé
                                    </span>
                                @elseif($rapport->statut === 'rejeté')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Rejeté
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($rapport->soumis_par)
                                    {{ $rapport->soumis_par->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $rapport->updated_at->format('d/m/Y à H:i') }}</td>
                            <td>
                                <a href="{{ route('etablissement.rapport-rentree.historique.show', $rapport) }}" 
                                   class="btn btn-sm btn-primary" 
                                   title="Voir le rapport">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('etablissement.rapport-rentree.historique.show', $rapport) }}?print=1" 
                                   target="_blank"
                                   class="btn btn-sm btn-secondary" 
                                   title="Imprimer">
                                    <i class="fas fa-print"></i>
                                </a>

                                @if($rapport->statut === 'rejeté' && $rapport->motif_rejet)
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#motifRejetModal{{ $rapport->id }}"
                                        title="Voir le motif de rejet">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>

                                <!-- Modal Motif de Rejet -->
                                <div class="modal fade" id="motifRejetModal{{ $rapport->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Motif de Rejet
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-2"><strong>Date de rejet :</strong> {{ $rapport->date_rejet?->format('d/m/Y à H:i') }}</p>
                                                <p class="mb-2"><strong>Motif :</strong></p>
                                                <div class="alert alert-light">
                                                    {{ $rapport->motif_rejet }}
                                                </div>
                                                @if($rapport->commentaire_admin)
                                                <p class="mb-2"><strong>Commentaire administrateur :</strong></p>
                                                <div class="alert alert-light">
                                                    {{ $rapport->commentaire_admin }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{ route('etablissement.rapport-rentree.index') }}" class="btn btn-primary">
                                                    <i class="fas fa-edit me-2"></i>Modifier le rapport
                                                </a>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Historique détaillé -->
    <div class="row mt-4">
        @foreach($rapports as $rapport)
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>{{ $rapport->annee_scolaire }}</strong>
                    @if($rapport->statut === 'validé')
                        <span class="badge bg-success float-end">Validé</span>
                    @elseif($rapport->statut === 'soumis')
                        <span class="badge bg-warning text-dark float-end">En attente</span>
                    @else
                        <span class="badge bg-danger float-end">Rejeté</span>
                    @endif
                </div>
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-timeline me-2"></i>Chronologie</h6>
                    <div class="timeline">
                        @foreach($rapport->historique->sortByDesc('created_at') as $h)
                        <div class="timeline-item mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    @if($h->action === 'soumission')
                                        <i class="fas fa-paper-plane text-primary"></i>
                                    @elseif($h->action === 'validation')
                                        <i class="fas fa-check-circle text-success"></i>
                                    @elseif($h->action === 'rejet')
                                        <i class="fas fa-times-circle text-danger"></i>
                                    @else
                                        <i class="fas fa-edit text-info"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">{{ $h->created_at->format('d/m/Y à H:i') }}</div>
                                    <div><strong>{{ ucfirst($h->action) }}</strong></div>
                                    <div class="small">
                                        Par : {{ $h->user->name ?? 'Système' }}
                                    </div>
                                    @if($h->commentaire)
                                    <div class="small text-muted mt-1">
                                        <em>"{{ $h->commentaire }}"</em>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('etablissement.rapport-rentree.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour au formulaire
        </a>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    padding-left: 10px;
    border-left: 2px solid #e9ecef;
}

.timeline-item:last-child {
    border-left: 2px solid transparent;
}
</style>
@endsection
