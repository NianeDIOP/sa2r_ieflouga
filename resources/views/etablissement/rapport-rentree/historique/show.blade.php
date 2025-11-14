<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Rentrée - {{ $rapport->annee_scolaire }} - {{ $rapport->etablissement->etablissement }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            body { font-size: 11pt; }
            h1 { font-size: 18pt; }
            h2 { font-size: 16pt; }
            h3 { font-size: 14pt; }
            .table { font-size: 10pt; }
        }

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .header-section {
            border-bottom: 3px solid #0056b3;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #0056b3;
            color: white;
            padding: 10px 15px;
            margin-top: 30px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .subsection-title {
            background-color: #6c757d;
            color: white;
            padding: 8px 12px;
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 14pt;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .info-table td:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 35%;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .status-valide {
            background-color: #28a745;
            color: white;
        }

        .status-soumis {
            background-color: #ffc107;
            color: #000;
        }

        .status-rejete {
            background-color: #dc3545;
            color: white;
        }

        .footer-signature {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

<!-- Boutons d'action (non imprimables) -->
<div class="no-print fixed-top bg-white border-bottom p-3 shadow-sm">
    <div class="container-fluid">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimer
        </button>
        <a href="{{ route('etablissement.rapport-rentree.historique.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="container" style="margin-top: 80px;">
    <!-- En-tête -->
    <div class="header-section">
        <div class="logo-section">
            <h1>RÉPUBLIQUE DU SÉNÉGAL</h1>
            <p><em>Un Peuple - Un But - Une Foi</em></p>
            <hr style="width: 200px; margin: 10px auto;">
            <h2>MINISTÈRE DE L'ÉDUCATION NATIONALE</h2>
            <h3>INSPECTION DE L'ÉDUCATION ET DE LA FORMATION DE LOUGA</h3>
        </div>

        <div class="text-center mt-4">
            <h1 style="color: #0056b3;">RAPPORT DE RENTRÉE SCOLAIRE</h1>
            <h2>Année Scolaire : {{ $rapport->annee_scolaire }}</h2>
        </div>

        <table class="info-table mt-4">
            <tr>
                <td>Établissement</td>
                <td><strong>{{ $rapport->etablissement->etablissement }}</strong></td>
            </tr>
            <tr>
                <td>Code Établissement</td>
                <td>{{ $rapport->etablissement->code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Commune</td>
                <td>{{ $rapport->etablissement->commune ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Zone</td>
                <td>{{ $rapport->etablissement->zone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Statut du Rapport</td>
                <td>
                    <span class="status-badge status-{{ $rapport->statut === 'validé' ? 'valide' : ($rapport->statut === 'soumis' ? 'soumis' : 'rejete') }}">
                        {{ strtoupper($rapport->statut) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Date de Soumission</td>
                <td>{{ $rapport->date_soumission?->format('d/m/Y à H:i') ?? 'N/A' }}</td>
            </tr>
            @if($rapport->date_validation)
            <tr>
                <td>Date de Validation</td>
                <td>{{ $rapport->date_validation->format('d/m/Y à H:i') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- ÉTAPE 1 : Informations Générales -->
    <div class="section-title">ÉTAPE 1 : INFORMATIONS GÉNÉRALES ET FINANCIÈRES</div>

    @if($rapport->infoDirecteur)
    <div class="subsection-title">Informations du Directeur</div>
    <table class="info-table">
        <tr>
            <td>Nom et Prénom</td>
            <td>{{ $rapport->infoDirecteur->nom_directeur }} {{ $rapport->infoDirecteur->prenom_directeur }}</td>
        </tr>
        <tr>
            <td>Date de Naissance</td>
            <td>{{ $rapport->infoDirecteur->date_naissance ? \Carbon\Carbon::parse($rapport->infoDirecteur->date_naissance)->format('d/m/Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td>Matricule</td>
            <td>{{ $rapport->infoDirecteur->matricule ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Grade</td>
            <td>{{ $rapport->infoDirecteur->grade ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Téléphone</td>
            <td>{{ $rapport->infoDirecteur->telephone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $rapport->infoDirecteur->email ?? 'N/A' }}</td>
        </tr>
    </table>
    @endif

    @if($rapport->infrastructuresBase)
    <div class="subsection-title">Infrastructures de Base</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Infrastructure</th>
                <th>Existence</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>CPE (Centre Préscolaire)</td>
                <td>{{ $rapport->infrastructuresBase->cpe_existe ? 'Oui' : 'Non' }}</td>
                <td>{{ $rapport->infrastructuresBase->cpe_etat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Clôture</td>
                <td>{{ $rapport->infrastructuresBase->cloture_existe ? 'Oui' : 'Non' }}</td>
                <td>{{ $rapport->infrastructuresBase->cloture_etat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Point d'Eau</td>
                <td>{{ $rapport->infrastructuresBase->eau_existe ? 'Oui' : 'Non' }}</td>
                <td>{{ $rapport->infrastructuresBase->eau_etat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Électricité</td>
                <td>{{ $rapport->infrastructuresBase->electricite_existe ? 'Oui' : 'Non' }}</td>
                <td>{{ $rapport->infrastructuresBase->electricite_etat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Cantine Scolaire</td>
                <td>{{ $rapport->infrastructuresBase->cantine_existe ? 'Oui' : 'Non' }}</td>
                <td>{{ $rapport->infrastructuresBase->cantine_etat ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="page-break"></div>

    @if($rapport->ressourcesFinancieres)
    <div class="subsection-title">Ressources Financières</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Source de Financement</th>
                <th>Montant (FCFA)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Subvention de l'État</td>
                <td>{{ number_format($rapport->ressourcesFinancieres->subvention_etat_montant ?? 0, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>Subvention des Partenaires</td>
                <td>{{ number_format($rapport->ressourcesFinancieres->subvention_partenaires_montant ?? 0, 0, ',', ' ') }}</td>
            </tr>
            <tr>
                <td>Subvention des Collectivités</td>
                <td>{{ number_format($rapport->ressourcesFinancieres->subvention_collectivites_montant ?? 0, 0, ',', ' ') }}</td>
            </tr>
            <tr class="table-info">
                <td><strong>TOTAL</strong></td>
                <td><strong>{{ number_format(($rapport->ressourcesFinancieres->subvention_etat_montant ?? 0) + ($rapport->ressourcesFinancieres->subvention_partenaires_montant ?? 0) + ($rapport->ressourcesFinancieres->subvention_collectivites_montant ?? 0), 0, ',', ' ') }}</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- ÉTAPE 2 : Effectifs -->
    <div class="section-title">ÉTAPE 2 : EFFECTIFS DES ÉLÈVES</div>

    @if($rapport->effectifs && $rapport->effectifs->count() > 0)
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Niveau</th>
                <th>Classes</th>
                <th>Garçons</th>
                <th>Filles</th>
                <th>Total</th>
                <th>Redoublants</th>
                <th>Abandons</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalClasses = 0;
                $totalGarcons = 0;
                $totalFilles = 0;
                $totalEffectif = 0;
                $totalRedoublants = 0;
                $totalAbandons = 0;
            @endphp
            @foreach($rapport->effectifs as $effectif)
            @php
                $totalClasses += $effectif->nombre_classes;
                $totalGarcons += $effectif->effectif_garcons;
                $totalFilles += $effectif->effectif_filles;
                $totalEffectif += $effectif->effectif_total;
                $totalRedoublants += $effectif->redoublants_total;
                $totalAbandons += $effectif->abandons_total;
            @endphp
            <tr>
                <td>{{ $effectif->niveau }}</td>
                <td>{{ $effectif->nombre_classes }}</td>
                <td>{{ $effectif->effectif_garcons }}</td>
                <td>{{ $effectif->effectif_filles }}</td>
                <td><strong>{{ $effectif->effectif_total }}</strong></td>
                <td>{{ $effectif->redoublants_total }}</td>
                <td>{{ $effectif->abandons_total }}</td>
            </tr>
            @endforeach
            <tr class="table-info">
                <td><strong>TOTAL</strong></td>
                <td><strong>{{ $totalClasses }}</strong></td>
                <td><strong>{{ $totalGarcons }}</strong></td>
                <td><strong>{{ $totalFilles }}</strong></td>
                <td><strong>{{ $totalEffectif }}</strong></td>
                <td><strong>{{ $totalRedoublants }}</strong></td>
                <td><strong>{{ $totalAbandons }}</strong></td>
            </tr>
        </tbody>
    </table>
    @else
    <p class="text-muted">Aucune donnée d'effectifs saisie.</p>
    @endif

    <div class="page-break"></div>

    <!-- ÉTAPE 3 : Examens et Recrutement -->
    <div class="section-title">ÉTAPE 3 : EXAMENS ET RECRUTEMENT</div>

    @if($rapport->cfee)
    <div class="subsection-title">Résultats CFEE</div>
    <table class="info-table">
        <tr>
            <td>Candidats (Garçons)</td>
            <td>{{ $rapport->cfee->cfee_candidats_garcons ?? 0 }}</td>
        </tr>
        <tr>
            <td>Candidats (Filles)</td>
            <td>{{ $rapport->cfee->cfee_candidats_filles ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Total Candidats</strong></td>
            <td><strong>{{ $rapport->cfee->cfee_candidats_total ?? 0 }}</strong></td>
        </tr>
        <tr>
            <td>Admis (Garçons)</td>
            <td>{{ $rapport->cfee->cfee_admis_garcons ?? 0 }}</td>
        </tr>
        <tr>
            <td>Admis (Filles)</td>
            <td>{{ $rapport->cfee->cfee_admis_filles ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Total Admis</strong></td>
            <td><strong>{{ $rapport->cfee->cfee_admis_total ?? 0 }}</strong></td>
        </tr>
        <tr class="table-success">
            <td><strong>Taux de Réussite</strong></td>
            <td><strong>{{ number_format($rapport->cfee->cfee_taux_reussite ?? 0, 2) }} %</strong></td>
        </tr>
    </table>
    @endif

    @if($rapport->entreeSixieme)
    <div class="subsection-title">Entrée en Sixième</div>
    <table class="info-table">
        <tr>
            <td>Candidats (Garçons)</td>
            <td>{{ $rapport->entreeSixieme->sixieme_candidats_garcons ?? 0 }}</td>
        </tr>
        <tr>
            <td>Candidats (Filles)</td>
            <td>{{ $rapport->entreeSixieme->sixieme_candidats_filles ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Total Candidats</strong></td>
            <td><strong>{{ $rapport->entreeSixieme->sixieme_candidats_total ?? 0 }}</strong></td>
        </tr>
        <tr>
            <td>Admis (Garçons)</td>
            <td>{{ $rapport->entreeSixieme->sixieme_admis_garcons ?? 0 }}</td>
        </tr>
        <tr>
            <td>Admis (Filles)</td>
            <td>{{ $rapport->entreeSixieme->sixieme_admis_filles ?? 0 }}</td>
        </tr>
        <tr>
            <td><strong>Total Admis</strong></td>
            <td><strong>{{ $rapport->entreeSixieme->sixieme_admis_total ?? 0 }}</strong></td>
        </tr>
        <tr class="table-success">
            <td><strong>Taux d'Admission</strong></td>
            <td><strong>{{ number_format($rapport->entreeSixieme->sixieme_taux_admission ?? 0, 2) }} %</strong></td>
        </tr>
    </table>
    @endif

    <div class="page-break"></div>

    <!-- ÉTAPE 4 : Personnel Enseignant -->
    <div class="section-title">ÉTAPE 4 : PERSONNEL ENSEIGNANT</div>

    @if($rapport->personnelEnseignant)
    <div class="subsection-title">Répartition par Spécialité</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Catégorie</th>
                <th>Hommes</th>
                <th>Femmes</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Titulaires</td>
                <td>{{ $rapport->personnelEnseignant->titulaires_hommes ?? 0 }}</td>
                <td>{{ $rapport->personnelEnseignant->titulaires_femmes ?? 0 }}</td>
                <td><strong>{{ $rapport->personnelEnseignant->titulaires_total ?? 0 }}</strong></td>
            </tr>
            <tr>
                <td>Vacataires</td>
                <td>{{ $rapport->personnelEnseignant->vacataires_hommes ?? 0 }}</td>
                <td>{{ $rapport->personnelEnseignant->vacataires_femmes ?? 0 }}</td>
                <td><strong>{{ $rapport->personnelEnseignant->vacataires_total ?? 0 }}</strong></td>
            </tr>
            <tr>
                <td>Volontaires</td>
                <td>{{ $rapport->personnelEnseignant->volontaires_hommes ?? 0 }}</td>
                <td>{{ $rapport->personnelEnseignant->volontaires_femmes ?? 0 }}</td>
                <td><strong>{{ $rapport->personnelEnseignant->volontaires_total ?? 0 }}</strong></td>
            </tr>
            <tr class="table-info">
                <td><strong>TOTAL</strong></td>
                <td><strong>{{ ($rapport->personnelEnseignant->titulaires_hommes ?? 0) + ($rapport->personnelEnseignant->vacataires_hommes ?? 0) + ($rapport->personnelEnseignant->volontaires_hommes ?? 0) }}</strong></td>
                <td><strong>{{ ($rapport->personnelEnseignant->titulaires_femmes ?? 0) + ($rapport->personnelEnseignant->vacataires_femmes ?? 0) + ($rapport->personnelEnseignant->volontaires_femmes ?? 0) }}</strong></td>
                <td><strong>{{ ($rapport->personnelEnseignant->titulaires_total ?? 0) + ($rapport->personnelEnseignant->vacataires_total ?? 0) + ($rapport->personnelEnseignant->volontaires_total ?? 0) }}</strong></td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="page-break"></div>

    <!-- ÉTAPE 5 : Matériel Pédagogique -->
    <div class="section-title">ÉTAPE 5 : MATÉRIEL PÉDAGOGIQUE</div>

    @if($rapport->manuelsEleves && $rapport->manuelsEleves->count() > 0)
    <div class="subsection-title">Manuels Élèves</div>
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Niveau</th>
                <th>Discipline</th>
                <th>Total</th>
                <th>Bon État</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rapport->manuelsEleves as $manuel)
            <tr>
                <td>{{ $manuel->niveau }}</td>
                <td>{{ $manuel->discipline }}</td>
                <td>{{ $manuel->total }}</td>
                <td>{{ $manuel->bon_etat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if($rapport->materielDidactique)
    <div class="subsection-title">Matériel Didactique</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Matériel</th>
                <th>Total</th>
                <th>Bon État</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Globes Terrestres</td>
                <td>{{ $rapport->materielDidactique->globe_total ?? 0 }}</td>
                <td>{{ $rapport->materielDidactique->globe_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Cartes Murales</td>
                <td>{{ $rapport->materielDidactique->cartes_murales_total ?? 0 }}</td>
                <td>{{ $rapport->materielDidactique->cartes_murales_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Planches Illustrées</td>
                <td>{{ $rapport->materielDidactique->planches_illustrees_total ?? 0 }}</td>
                <td>{{ $rapport->materielDidactique->planches_illustrees_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Kits Matériel Scientifique</td>
                <td>{{ $rapport->materielDidactique->kit_materiel_scientifique_total ?? 0 }}</td>
                <td>{{ $rapport->materielDidactique->kit_materiel_scientifique_bon_etat ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div class="page-break"></div>

    <!-- ÉTAPE 6 : Infrastructure & Équipements -->
    <div class="section-title">ÉTAPE 6 : INFRASTRUCTURE & ÉQUIPEMENTS</div>

    @if($rapport->capitalImmobilier)
    <div class="subsection-title">Capital Immobilier</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Infrastructure</th>
                <th>Total</th>
                <th>Bon État</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Salles de Classe</td>
                <td>{{ $rapport->capitalImmobilier->salles_classe_total ?? 0 }}</td>
                <td>{{ $rapport->capitalImmobilier->salles_classe_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Salles de Réunion</td>
                <td>{{ $rapport->capitalImmobilier->salles_reunion_total ?? 0 }}</td>
                <td>{{ $rapport->capitalImmobilier->salles_reunion_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Blocs Administratifs</td>
                <td>{{ $rapport->capitalImmobilier->blocs_administratifs_total ?? 0 }}</td>
                <td>{{ $rapport->capitalImmobilier->blocs_administratifs_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Toilettes</td>
                <td>{{ $rapport->capitalImmobilier->toilettes_total ?? 0 }}</td>
                <td>{{ $rapport->capitalImmobilier->toilettes_bon_etat ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    @if($rapport->equipementInformatique)
    <div class="subsection-title">Équipement Informatique</div>
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Équipement</th>
                <th>Total</th>
                <th>Bon État</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ordinateurs Fixes</td>
                <td>{{ $rapport->equipementInformatique->ordinateurs_fixes_total ?? 0 }}</td>
                <td>{{ $rapport->equipementInformatique->ordinateurs_fixes_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Ordinateurs Portables</td>
                <td>{{ $rapport->equipementInformatique->ordinateurs_portables_total ?? 0 }}</td>
                <td>{{ $rapport->equipementInformatique->ordinateurs_portables_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Tablettes</td>
                <td>{{ $rapport->equipementInformatique->tablettes_total ?? 0 }}</td>
                <td>{{ $rapport->equipementInformatique->tablettes_bon_etat ?? 0 }}</td>
            </tr>
            <tr>
                <td>Vidéoprojecteurs</td>
                <td>{{ $rapport->equipementInformatique->videoprojecteurs_total ?? 0 }}</td>
                <td>{{ $rapport->equipementInformatique->videoprojecteurs_bon_etat ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Signatures -->
    <div class="footer-signature">
        <div class="row">
            <div class="col-6">
                <p><strong>Le Directeur de l'Établissement</strong></p>
                <p>Nom : {{ $rapport->infoDirecteur->nom_directeur ?? '' }} {{ $rapport->infoDirecteur->prenom_directeur ?? '' }}</p>
                <p>Date : {{ $rapport->date_soumission?->format('d/m/Y') ?? now()->format('d/m/Y') }}</p>
                <br><br>
                <p>Signature : ___________________</p>
            </div>
            @if($rapport->statut === 'validé' && $rapport->valide_par)
            <div class="col-6">
                <p><strong>Le Validateur</strong></p>
                <p>Nom : {{ $rapport->valide_par->name ?? '' }}</p>
                <p>Date : {{ $rapport->date_validation?->format('d/m/Y') ?? '' }}</p>
                <br><br>
                <p>Signature : ___________________</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-5 text-muted">
        <small>
            Document généré le {{ now()->format('d/m/Y à H:i') }} - 
            Inspection de l'Éducation et de la Formation de Louga
        </small>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
