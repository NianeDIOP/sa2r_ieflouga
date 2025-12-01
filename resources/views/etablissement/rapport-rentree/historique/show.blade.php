<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Rentrée - {{ $rapport->annee_scolaire }} - {{ $rapport->etablissement->etablissement }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Format A4 */
        body {
            margin: 0;
            padding: 0;
        }
        
        /* Pas de container, pleine largeur */

        /* Nouveau style d'en-tête */
        .header {
            padding: 8px 15px;
            margin-bottom: 0;
            background: #002147;
        }

        /* Partie haute : drapeau + devise */
        .top-center {
            text-align: center;
            margin-bottom: 8px;
            font-size: 9px;
            font-weight: 600;
            color: white;
        }
        .top-center img {
            max-height: 35px;
            display: block;
            margin: 0 auto 3px;
        }

        /* Partie inférieure : gauche */
        .bottom {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .left {
            text-align: center;
        }
        .left img {
            max-width: 70px;
            display: block;
            margin: 0 auto 5px;
        }
        .left div {
            font-size: 10px;
            line-height: 1.5;
            font-weight: 600;
            color: white;
        }

        /* Style pour le titre principal du rapport */
        .report-title {
            text-align: center;
            margin: 0;
            padding: 8px;
            background: #002147;
            border: none;
            border-radius: 0;
        }
        .report-title h1 {
            font-size: 14px;
            font-weight: bold;
            color: white;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .report-title .year {
            font-size: 11px;
            color: white;
            margin-top: 2px;
            font-weight: 600;
        }

        /* Styles pour le contenu du rapport */
        .content-wrapper {
            background: white;
            padding: 20px;
            margin-top: 20px;
        }

        /* Titre de section principal */
        .section-title {
            background: #002147;
            color: white;
            padding: 10px 15px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0 10px 0;
            border-left: 4px solid #10b981;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Sous-titre de section */
        .subsection-title {
            background: #10b981;
            color: white;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 600;
            margin: 15px 0 8px 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .info-table td, .info-table th {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }

        .info-table th {
            background: #f3f4f6;
            font-weight: 600;
            font-size: 10px;
        }

        .info-table td:first-child {
            background: #f9fafb;
            font-weight: 500;
            width: 35%;
        }

        /* Badge statut */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-valide {
            background: #d1fae5;
            color: #065f46;
        }

        .status-soumis {
            background: #fef3c7;
            color: #92400e;
        }

        .status-rejete {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-brouillon {
            background: #e5e7eb;
            color: #1f2937;
        }

        @media print {
            .no-print { display: none !important; }
            @page {
                size: A4;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            div[style*="padding-top"] {
                padding-top: 0 !important;
            }
            .header {
                padding: 8px 15px !important;
                background: #002147 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0 !important;
            }
            .report-title {
                padding: 8px !important;
                background: #002147 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                margin: 0 !important;
            }
            .top-center, .left div, .report-title h1, .report-title .year {
                color: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<!-- Boutons d'action (non imprimables) -->
<div class="no-print fixed-top bg-white border-bottom p-3 shadow-sm">
    <div class="container">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="fas fa-print"></i> Imprimer
        </button>
        <a href="{{ route('etablissement.rapport-rentree.historique.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div style="padding-top: 70px;">
    <div class="container">
        <div class="header">
        <!-- Partie haute : Drapeau + Devise -->
        <div class="top-center">
            <img src="{{ asset('images/senegal.png') }}" alt="Drapeau Sénégal">
            <div>République du Sénégal<br>
            <em>Un Peuple – Un But – Une Foi</em></div>
        </div>

        <!-- Partie inférieure : Logo MEN + Textes -->
        <div class="bottom">
            <div class="left">
                <img src="{{ asset('images/men.jpg') }}" alt="Logo Ministère">
                <div>
                    Ministère de l'Éducation Nationale<br>
                    Inspection d'Académie de Louga<br>
                    Inspection de l'Éducation et de la Formation de Louga<br>
                    <span style="font-family: 'Edwardian Script ITC', cursive; font-size: 22px; font-weight: bold;">La Planification</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Titre principal du rapport -->
    <div class="report-title">
        <h1>Rapport de Rentrée Scolaire</h1>
        <div class="year">Année Scolaire : {{ $rapport->annee_scolaire }}</div>
        <div class="year" style="margin-top: 5px;">{{ $rapport->etablissement->etablissement }}@if($rapport->etablissement->code) - Code : {{ $rapport->etablissement->code }}@endif</div>
    </div>

    <!-- Contenu du rapport -->
    <div class="content-wrapper">
        
        <!-- Informations de Base de l'Établissement -->
        <div class="subsection-title">Informations de Base</div>
        <table class="info-table">
            <tr>
                <td style="width: 25%; font-weight: 600;">Établissement</td>
                <td style="width: 25%;">{{ $rapport->etablissement->etablissement }}</td>
                <td style="width: 25%; font-weight: 600;">Code</td>
                <td style="width: 25%;">{{ $rapport->etablissement->code ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Statut</td>
                <td>{{ $rapport->etablissement->statut ?? 'N/A' }}</td>
                <td style="font-weight: 600;">Type</td>
                <td>{{ $rapport->etablissement->type_statut ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Arrondissement</td>
                <td>{{ $rapport->etablissement->arrondissement ?? 'N/A' }}</td>
                <td style="font-weight: 600;">Commune</td>
                <td>{{ $rapport->etablissement->commune ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">District</td>
                <td>{{ $rapport->etablissement->district ?? 'N/A' }}</td>
                <td style="font-weight: 600;">Zone</td>
                <td>{{ $rapport->etablissement->zone ?? 'N/A' }}</td>
            </tr>
            @if($rapport->etablissement->geo_ref_x && $rapport->etablissement->geo_ref_y)
            <tr>
                <td style="font-weight: 600;">Coordonnées GPS</td>
                <td colspan="3">Latitude: {{ $rapport->etablissement->geo_ref_x }} | Longitude: {{ $rapport->etablissement->geo_ref_y }}</td>
            </tr>
            @endif
            @if($rapport->etablissement->date_creation)
            <tr>
                <td style="font-weight: 600;">Date de Création</td>
                <td>{{ \Carbon\Carbon::parse($rapport->etablissement->date_creation)->format('d/m/Y') }}</td>
                @if($rapport->etablissement->date_ouverture)
                <td style="font-weight: 600;">Date d'Ouverture</td>
                <td>{{ \Carbon\Carbon::parse($rapport->etablissement->date_ouverture)->format('d/m/Y') }}</td>
                @else
                <td colspan="2"></td>
                @endif
            </tr>
            @elseif($rapport->etablissement->date_ouverture)
            <tr>
                <td style="font-weight: 600;">Date d'Ouverture</td>
                <td colspan="3">{{ \Carbon\Carbon::parse($rapport->etablissement->date_ouverture)->format('d/m/Y') }}</td>
            </tr>
            @endif
            <tr>
                <td style="font-weight: 600;">Année Scolaire</td>
                <td>{{ $rapport->annee_scolaire }}</td>
                <td style="font-weight: 600;">Statut du Rapport</td>
                <td>
                    <span class="status-badge status-{{ $rapport->statut }}">
                        @if($rapport->statut === 'brouillon')
                            BROUILLON
                        @elseif($rapport->statut === 'soumis')
                            SOUMIS
                        @elseif($rapport->statut === 'valide')
                            VALIDÉ
                        @elseif($rapport->statut === 'rejete')
                            REJETÉ
                        @endif
                    </span>
                </td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Date de Soumission</td>
                <td>{{ $rapport->date_soumission?->format('d/m/Y à H:i') ?? 'Non soumis' }}</td>
                @if($rapport->date_validation)
                <td style="font-weight: 600;">Date de Validation</td>
                <td>{{ $rapport->date_validation->format('d/m/Y à H:i') }}</td>
                @else
                <td colspan="2"></td>
                @endif
            </tr>
            @if($rapport->soumis_par)
            <tr>
                <td style="font-weight: 600;">Soumis par</td>
                <td>{{ $rapport->soumis_par->name }}</td>
                @if($rapport->valide_par)
                <td style="font-weight: 600;">Validé par</td>
                <td>{{ $rapport->valide_par->name }}</td>
                @else
                <td colspan="2"></td>
                @endif
            </tr>
            @endif
            @if($rapport->motif_rejet)
            <tr>
                <td style="font-weight: 600;">Motif de Rejet</td>
                <td colspan="3" style="color: #dc2626;">{{ $rapport->motif_rejet }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- SECTION 1 : Informations Générales et Financières -->
        <div class="section-title">SECTION 1 : INFORMATIONS GÉNÉRALES ET FINANCIÈRES</div>

        @if($rapport->infoDirecteur)
        <div class="subsection-title">Informations du Directeur</div>
        <table class="info-table">
            <tr>
                <td style="width: 30%; font-weight: 600;">Nom du Directeur</td>
                <td style="width: 70%;">{{ $rapport->infoDirecteur->directeur_nom ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Contact Principal</td>
                <td>{{ $rapport->infoDirecteur->directeur_contact_1 ?? 'N/A' }}</td>
            </tr>
            @if($rapport->infoDirecteur->directeur_contact_2)
            <tr>
                <td style="font-weight: 600;">Contact Secondaire</td>
                <td>{{ $rapport->infoDirecteur->directeur_contact_2 }}</td>
            </tr>
            @endif
            <tr>
                <td style="font-weight: 600;">Email</td>
                <td>{{ $rapport->infoDirecteur->directeur_email ?? 'N/A' }}</td>
            </tr>
            @if($rapport->infoDirecteur->distance_siege)
            <tr>
                <td style="font-weight: 600;">Distance au Siège IEF</td>
                <td>{{ number_format($rapport->infoDirecteur->distance_siege, 2) }} km</td>
            </tr>
            @endif
        </table>
        @endif

        @if($rapport->infrastructuresBase)
        <div class="subsection-title">Infrastructures de Base</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Existence</th>
                    <th style="width: 40%;">Type / Détails</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CPE (Centre Préscolaire)</td>
                    <td>{{ $rapport->infrastructuresBase->cpe_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->infrastructuresBase->cpe_existe && $rapport->infrastructuresBase->cpe_nombre ? $rapport->infrastructuresBase->cpe_nombre . ' centre(s)' : '-' }}</td>
                </tr>
                <tr>
                    <td>Clôture</td>
                    <td>{{ $rapport->infrastructuresBase->cloture_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->infrastructuresBase->cloture_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Point d'Eau</td>
                    <td>{{ $rapport->infrastructuresBase->eau_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->infrastructuresBase->eau_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Électricité</td>
                    <td>{{ $rapport->infrastructuresBase->electricite_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->infrastructuresBase->electricite_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Connexion Internet</td>
                    <td>{{ $rapport->infrastructuresBase->connexion_internet_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->infrastructuresBase->connexion_internet_type ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Cantine Scolaire</td>
                    <td>{{ $rapport->infrastructuresBase->cantine_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->infrastructuresBase->cantine_type ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        @if($rapport->ressourcesFinancieres)
        <div class="subsection-title">Ressources Financières</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Source de Financement</th>
                    <th style="width: 15%;">Existe</th>
                    <th style="width: 35%;">Montant (FCFA)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Subvention de l'État</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_etat_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_etat_existe ? number_format($rapport->ressourcesFinancieres->subvention_etat_montant ?? 0, 0, ',', ' ') : '-' }}</td>
                </tr>
                <tr>
                    <td>Subvention des Partenaires</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_partenaires_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_partenaires_existe ? number_format($rapport->ressourcesFinancieres->subvention_partenaires_montant ?? 0, 0, ',', ' ') : '-' }}</td>
                </tr>
                <tr>
                    <td>Subvention des Collectivités Locales</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_collectivites_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_collectivites_existe ? number_format($rapport->ressourcesFinancieres->subvention_collectivites_montant ?? 0, 0, ',', ' ') : '-' }}</td>
                </tr>
                <tr>
                    <td>Contribution Communauté (CGE/APE)</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_communaute_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->ressourcesFinancieres->subvention_communaute_existe ? number_format($rapport->ressourcesFinancieres->subvention_communaute_montant ?? 0, 0, ',', ' ') : '-' }}</td>
                </tr>
                <tr>
                    <td>Ressources Générées (AGR)</td>
                    <td>{{ $rapport->ressourcesFinancieres->ressources_generees_existe ? 'Oui' : 'Non' }}</td>
                    <td>{{ $rapport->ressourcesFinancieres->ressources_generees_existe ? number_format($rapport->ressourcesFinancieres->ressources_generees_montant ?? 0, 0, ',', ' ') : '-' }}</td>
                </tr>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td colspan="2">TOTAL DES RESSOURCES</td>
                    <td>{{ number_format($rapport->ressourcesFinancieres->total_ressources ?? 0, 0, ',', ' ') }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        @if($rapport->structuresCommunautaires)
        <div class="subsection-title">Structures Communautaires</div>
        
        <!-- CGE -->
        @if($rapport->structuresCommunautaires->cge_existe)
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Comité de Gestion de l'Établissement (CGE)</p>
        <table class="info-table">
            <tr>
                <td style="width: 30%; font-weight: 600;">Hommes</td>
                <td style="width: 70%;">{{ $rapport->structuresCommunautaires->cge_hommes ?? 0 }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Femmes</td>
                <td>{{ $rapport->structuresCommunautaires->cge_femmes ?? 0 }}</td>
            </tr>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td>Total Membres</td>
                <td>{{ $rapport->structuresCommunautaires->cge_total ?? 0 }}</td>
            </tr>
            @if($rapport->structuresCommunautaires->cge_president_nom)
            <tr>
                <td style="font-weight: 600;">Président(e)</td>
                <td>{{ $rapport->structuresCommunautaires->cge_president_nom }}@if($rapport->structuresCommunautaires->cge_president_contact) - {{ $rapport->structuresCommunautaires->cge_president_contact }}@endif</td>
            </tr>
            @endif
            @if($rapport->structuresCommunautaires->cge_tresorier_nom)
            <tr>
                <td style="font-weight: 600;">Trésorier(ère)</td>
                <td>{{ $rapport->structuresCommunautaires->cge_tresorier_nom }}@if($rapport->structuresCommunautaires->cge_tresorier_contact) - {{ $rapport->structuresCommunautaires->cge_tresorier_contact }}@endif</td>
            </tr>
            @endif
        </table>
        @endif

        <!-- G.Scol -->
        @if($rapport->structuresCommunautaires->gscol_existe)
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Gouvernement Scolaire (G.Scol)</p>
        <table class="info-table">
            <tr>
                <td style="width: 30%; font-weight: 600;">Garçons</td>
                <td style="width: 70%;">{{ $rapport->structuresCommunautaires->gscol_garcons ?? 0 }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Filles</td>
                <td>{{ $rapport->structuresCommunautaires->gscol_filles ?? 0 }}</td>
            </tr>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td>Total Membres</td>
                <td>{{ $rapport->structuresCommunautaires->gscol_total ?? 0 }}</td>
            </tr>
            @if($rapport->structuresCommunautaires->gscol_president_nom)
            <tr>
                <td style="font-weight: 600;">Président(e)</td>
                <td>{{ $rapport->structuresCommunautaires->gscol_president_nom }}@if($rapport->structuresCommunautaires->gscol_president_contact) - {{ $rapport->structuresCommunautaires->gscol_president_contact }}@endif</td>
            </tr>
            @endif
        </table>
        @endif

        <!-- APE -->
        @if($rapport->structuresCommunautaires->ape_existe)
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Association des Parents d'Élèves (APE)</p>
        <table class="info-table">
            <tr>
                <td style="width: 30%; font-weight: 600;">Hommes</td>
                <td style="width: 70%;">{{ $rapport->structuresCommunautaires->ape_hommes ?? 0 }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Femmes</td>
                <td>{{ $rapport->structuresCommunautaires->ape_femmes ?? 0 }}</td>
            </tr>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td>Total Membres</td>
                <td>{{ $rapport->structuresCommunautaires->ape_total ?? 0 }}</td>
            </tr>
            @if($rapport->structuresCommunautaires->ape_president_nom)
            <tr>
                <td style="font-weight: 600;">Président(e)</td>
                <td>{{ $rapport->structuresCommunautaires->ape_president_nom }}@if($rapport->structuresCommunautaires->ape_president_contact) - {{ $rapport->structuresCommunautaires->ape_president_contact }}@endif</td>
            </tr>
            @endif
        </table>
        @endif

        <!-- AME -->
        @if($rapport->structuresCommunautaires->ame_existe)
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Association des Mères d'Élèves (AME)</p>
        <table class="info-table">
            <tr>
                <td style="width: 30%; font-weight: 600;">Nombre de Membres</td>
                <td style="width: 70%;">{{ $rapport->structuresCommunautaires->ame_nombre ?? 0 }}</td>
            </tr>
            @if($rapport->structuresCommunautaires->ame_president_nom)
            <tr>
                <td style="font-weight: 600;">Présidente</td>
                <td>{{ $rapport->structuresCommunautaires->ame_president_nom }}@if($rapport->structuresCommunautaires->ame_president_contact) - {{ $rapport->structuresCommunautaires->ame_president_contact }}@endif</td>
            </tr>
            @endif
        </table>
        @endif
        @endif

        @if($rapport->languesProjets)
        <div class="subsection-title">Langues Nationales & Projets Informatiques</div>
        <table class="info-table">
            <tr>
                <td style="width: 40%; font-weight: 600;">Langue Nationale Enseignée</td>
                <td style="width: 60%;">{{ $rapport->languesProjets->langue_nationale ? ucfirst($rapport->languesProjets->langue_nationale) : 'Aucune' }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Projets Informatiques</td>
                <td>{{ $rapport->languesProjets->projets_informatiques_existe ? 'Oui' : 'Non' }}</td>
            </tr>
            @if($rapport->languesProjets->projets_informatiques_existe && $rapport->languesProjets->projets_informatiques_nom)
            <tr>
                <td style="font-weight: 600;">Nom des Projets</td>
                <td>{{ $rapport->languesProjets->projets_informatiques_nom }}</td>
            </tr>
            @endif
        </table>
        @endif

        <!-- SECTION 2 : Effectifs des Élèves -->
        <div class="section-title">SECTION 2 : EFFECTIFS DES ÉLÈVES</div>

        @if($rapport->effectifs && $rapport->effectifs->count() > 0)
        <div class="subsection-title">Effectifs par Niveau</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Niveau</th>
                    <th>Classes</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalClasses = 0;
                    $totalGarcons = 0;
                    $totalFilles = 0;
                    $totalEffectif = 0;
                @endphp
                @foreach($rapport->effectifs as $effectif)
                @php
                    $totalClasses += $effectif->nombre_classes;
                    $totalGarcons += $effectif->effectif_garcons;
                    $totalFilles += $effectif->effectif_filles;
                    $totalEffectif += $effectif->effectif_total;
                @endphp
                <tr>
                    <td>{{ $effectif->niveau }}</td>
                    <td>{{ $effectif->nombre_classes }}</td>
                    <td>{{ $effectif->effectif_garcons }}</td>
                    <td>{{ $effectif->effectif_filles }}</td>
                    <td style="font-weight: bold;">{{ $effectif->effectif_total }}</td>
                </tr>
                @endforeach
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL GÉNÉRAL</td>
                    <td>{{ $totalClasses }}</td>
                    <td>{{ $totalGarcons }}</td>
                    <td>{{ $totalFilles }}</td>
                    <td>{{ $totalEffectif }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Redoublants par Niveau</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Niveau</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalRedoublantsG = 0; $totalRedoublantsF = 0; $totalRedoublants = 0; @endphp
                @foreach($rapport->effectifs as $effectif)
                @php
                    $totalRedoublantsG += $effectif->redoublants_garcons ?? 0;
                    $totalRedoublantsF += $effectif->redoublants_filles ?? 0;
                    $totalRedoublants += $effectif->redoublants_total ?? 0;
                @endphp
                <tr>
                    <td>{{ $effectif->niveau }}</td>
                    <td>{{ $effectif->redoublants_garcons ?? 0 }}</td>
                    <td>{{ $effectif->redoublants_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $effectif->redoublants_total ?? 0 }}</td>
                </tr>
                @endforeach
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL</td>
                    <td>{{ $totalRedoublantsG }}</td>
                    <td>{{ $totalRedoublantsF }}</td>
                    <td>{{ $totalRedoublants }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Abandons par Niveau</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Niveau</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAbandonsG = 0; $totalAbandonsF = 0; $totalAbandons = 0; @endphp
                @foreach($rapport->effectifs as $effectif)
                @php
                    $totalAbandonsG += $effectif->abandons_garcons ?? 0;
                    $totalAbandonsF += $effectif->abandons_filles ?? 0;
                    $totalAbandons += $effectif->abandons_total ?? 0;
                @endphp
                <tr>
                    <td>{{ $effectif->niveau }}</td>
                    <td>{{ $effectif->abandons_garcons ?? 0 }}</td>
                    <td>{{ $effectif->abandons_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $effectif->abandons_total ?? 0 }}</td>
                </tr>
                @endforeach
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL</td>
                    <td>{{ $totalAbandonsG }}</td>
                    <td>{{ $totalAbandonsF }}</td>
                    <td>{{ $totalAbandons }}</td>
                </tr>
            </tbody>
        </table>
        @else
        <p style="color: #6b7280; font-size: 10px; padding: 10px; background: #f9fafb;">Aucune donnée d'effectifs saisie.</p>
        @endif

        @if($rapport->effectifs && $rapport->effectifs->count() > 0)
            @php
                $totalHandicapMoteurG = $rapport->effectifs->sum('handicap_moteur_garcons');
                $totalHandicapMoteurF = $rapport->effectifs->sum('handicap_moteur_filles');
                $totalHandicapMoteur = $rapport->effectifs->sum('handicap_moteur_total');
                
                $totalHandicapVisuelG = $rapport->effectifs->sum('handicap_visuel_garcons');
                $totalHandicapVisuelF = $rapport->effectifs->sum('handicap_visuel_filles');
                $totalHandicapVisuel = $rapport->effectifs->sum('handicap_visuel_total');
                
                $totalHandicapSourdMuetG = $rapport->effectifs->sum('handicap_sourd_muet_garcons');
                $totalHandicapSourdMuetF = $rapport->effectifs->sum('handicap_sourd_muet_filles');
                $totalHandicapSourdMuet = $rapport->effectifs->sum('handicap_sourd_muet_total');
                
                $totalHandicapDeficienceG = $rapport->effectifs->sum('handicap_deficience_intel_garcons');
                $totalHandicapDeficienceF = $rapport->effectifs->sum('handicap_deficience_intel_filles');
                $totalHandicapDeficience = $rapport->effectifs->sum('handicap_deficience_intel_total');
                
                $totalOrphelinsG = $rapport->effectifs->sum('orphelins_garcons');
                $totalOrphelinsF = $rapport->effectifs->sum('orphelins_filles');
                $totalOrphelins = $rapport->effectifs->sum('orphelins_total');
                
                $totalSansExtraitG = $rapport->effectifs->sum('sans_extrait_garcons');
                $totalSansExtraitF = $rapport->effectifs->sum('sans_extrait_filles');
                $totalSansExtrait = $rapport->effectifs->sum('sans_extrait_total');
            @endphp

            @if($totalHandicapMoteur + $totalHandicapVisuel + $totalHandicapSourdMuet + $totalHandicapDeficience > 0)
            <div class="subsection-title">Élèves en Situation de Handicap</div>
            <table class="info-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Type de Handicap</th>
                        <th style="width: 15%;">Garçons</th>
                        <th style="width: 15%;">Filles</th>
                        <th style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if($totalHandicapMoteur > 0)
                    <tr>
                        <td>Handicap Moteur</td>
                        <td>{{ $totalHandicapMoteurG }}</td>
                        <td>{{ $totalHandicapMoteurF }}</td>
                        <td style="font-weight: bold;">{{ $totalHandicapMoteur }}</td>
                    </tr>
                    @endif
                    @if($totalHandicapVisuel > 0)
                    <tr>
                        <td>Handicap Visuel</td>
                        <td>{{ $totalHandicapVisuelG }}</td>
                        <td>{{ $totalHandicapVisuelF }}</td>
                        <td style="font-weight: bold;">{{ $totalHandicapVisuel }}</td>
                    </tr>
                    @endif
                    @if($totalHandicapSourdMuet > 0)
                    <tr>
                        <td>Handicap Sourd/Muet</td>
                        <td>{{ $totalHandicapSourdMuetG }}</td>
                        <td>{{ $totalHandicapSourdMuetF }}</td>
                        <td style="font-weight: bold;">{{ $totalHandicapSourdMuet }}</td>
                    </tr>
                    @endif
                    @if($totalHandicapDeficience > 0)
                    <tr>
                        <td>Déficience Intellectuelle</td>
                        <td>{{ $totalHandicapDeficienceG }}</td>
                        <td>{{ $totalHandicapDeficienceF }}</td>
                        <td style="font-weight: bold;">{{ $totalHandicapDeficience }}</td>
                    </tr>
                    @endif
                    <tr style="background: #f3f4f6; font-weight: bold;">
                        <td>TOTAL HANDICAPS</td>
                        <td>{{ $totalHandicapMoteurG + $totalHandicapVisuelG + $totalHandicapSourdMuetG + $totalHandicapDeficienceG }}</td>
                        <td>{{ $totalHandicapMoteurF + $totalHandicapVisuelF + $totalHandicapSourdMuetF + $totalHandicapDeficienceF }}</td>
                        <td>{{ $totalHandicapMoteur + $totalHandicapVisuel + $totalHandicapSourdMuet + $totalHandicapDeficience }}</td>
                    </tr>
                </tbody>
            </table>
            @endif

            @if($totalOrphelins + $totalSansExtrait > 0)
            <div class="subsection-title">Situations Spéciales</div>
            <table class="info-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Situation</th>
                        <th style="width: 15%;">Garçons</th>
                        <th style="width: 15%;">Filles</th>
                        <th style="width: 20%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if($totalOrphelins > 0)
                    <tr>
                        <td>Orphelins</td>
                        <td>{{ $totalOrphelinsG }}</td>
                        <td>{{ $totalOrphelinsF }}</td>
                        <td style="font-weight: bold;">{{ $totalOrphelins }}</td>
                    </tr>
                    @endif
                    @if($totalSansExtrait > 0)
                    <tr>
                        <td>Sans Extrait de Naissance</td>
                        <td>{{ $totalSansExtraitG }}</td>
                        <td>{{ $totalSansExtraitF }}</td>
                        <td style="font-weight: bold;">{{ $totalSansExtrait }}</td>
                    </tr>
                    @endif
                    <tr style="background: #f3f4f6; font-weight: bold;">
                        <td>TOTAL SITUATIONS SPÉCIALES</td>
                        <td>{{ $totalOrphelinsG + $totalSansExtraitG }}</td>
                        <td>{{ $totalOrphelinsF + $totalSansExtraitF }}</td>
                        <td>{{ $totalOrphelins + $totalSansExtrait }}</td>
                    </tr>
                </tbody>
            </table>
            @endif
        @endif

        <!-- SECTION 3 : Examens et Recrutement -->
        <div class="section-title">SECTION 3 : EXAMENS ET RECRUTEMENT</div>

        @if($rapport->cfee)
        <div class="subsection-title">Résultats CFEE (Certificat de Fin d'Études Élémentaires)</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Catégorie</th>
                    <th style="width: 20%;">Filles</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Taux</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600;">Candidats</td>
                    <td>{{ $rapport->cfee->cfee_candidats_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->cfee->cfee_candidats_total ?? 0 }}</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Admis</td>
                    <td>{{ $rapport->cfee->cfee_admis_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->cfee->cfee_admis_total ?? 0 }}</td>
                    <td>-</td>
                </tr>
                <tr style="background: #d1fae5; font-weight: bold;">
                    <td>Taux de Réussite</td>
                    <td>{{ number_format($rapport->cfee->cfee_taux_reussite_filles ?? 0, 1) }} %</td>
                    <td>{{ number_format($rapport->cfee->cfee_taux_reussite ?? 0, 1) }} %</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
        @endif

        @if($rapport->entreeSixieme)
        <div class="subsection-title">Entrée en Sixième</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Catégorie</th>
                    <th style="width: 20%;">Filles</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Taux</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-weight: 600;">Candidats</td>
                    <td>{{ $rapport->entreeSixieme->sixieme_candidats_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->entreeSixieme->sixieme_candidats_total ?? 0 }}</td>
                    <td>-</td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Admis</td>
                    <td>{{ $rapport->entreeSixieme->sixieme_admis_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->entreeSixieme->sixieme_admis_total ?? 0 }}</td>
                    <td>-</td>
                </tr>
                <tr style="background: #d1fae5; font-weight: bold;">
                    <td>Taux d'Admission</td>
                    <td>{{ number_format($rapport->entreeSixieme->sixieme_taux_admission_filles ?? 0, 1) }} %</td>
                    <td>{{ number_format($rapport->entreeSixieme->sixieme_taux_admission ?? 0, 1) }} %</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
        @endif

        @if($rapport->cmg)
        <div class="subsection-title">Classes Multigrades (CMG)</div>
        <table class="info-table">
            <tr>
                <td style="width: 40%; font-weight: 600;">Nombre de CMG</td>
                <td style="width: 60%;">{{ $rapport->cmg->cmg_nombre ?? 0 }}</td>
            </tr>
            @if($rapport->cmg->cmg_combinaison_1)
            <tr>
                <td style="font-weight: 600;">Combinaison 1</td>
                <td>{{ $rapport->cmg->cmg_combinaison_1 }}</td>
            </tr>
            @endif
            @if($rapport->cmg->cmg_combinaison_2)
            <tr>
                <td style="font-weight: 600;">Combinaison 2</td>
                <td>{{ $rapport->cmg->cmg_combinaison_2 }}</td>
            </tr>
            @endif
            @if($rapport->cmg->cmg_combinaison_3)
            <tr>
                <td style="font-weight: 600;">Combinaison 3</td>
                <td>{{ $rapport->cmg->cmg_combinaison_3 }}</td>
            </tr>
            @endif
            @if($rapport->cmg->cmg_combinaison_autres)
            <tr>
                <td style="font-weight: 600;">Autres Combinaisons</td>
                <td>{{ $rapport->cmg->cmg_combinaison_autres }}</td>
            </tr>
            @endif
        </table>
        @endif

        @if($rapport->recrutementCi)
        <div class="subsection-title">Recrutement CI (Cours d'Initiation)</div>
        <table class="info-table">
            <tr>
                <td style="width: 40%; font-weight: 600;">Nombre de CI</td>
                <td style="width: 60%;">{{ $rapport->recrutementCi->ci_nombre ?? 0 }}</td>
            </tr>
            @if($rapport->recrutementCi->ci_combinaison_1)
            <tr>
                <td style="font-weight: 600;">Combinaison 1</td>
                <td>{{ $rapport->recrutementCi->ci_combinaison_1 }}</td>
            </tr>
            @endif
            @if($rapport->recrutementCi->ci_combinaison_2)
            <tr>
                <td style="font-weight: 600;">Combinaison 2</td>
                <td>{{ $rapport->recrutementCi->ci_combinaison_2 }}</td>
            </tr>
            @endif
            @if($rapport->recrutementCi->ci_combinaison_3)
            <tr>
                <td style="font-weight: 600;">Combinaison 3</td>
                <td>{{ $rapport->recrutementCi->ci_combinaison_3 }}</td>
            </tr>
            @endif
            @if($rapport->recrutementCi->ci_combinaison_autres)
            <tr>
                <td style="font-weight: 600;">Autres Combinaisons</td>
                <td>{{ $rapport->recrutementCi->ci_combinaison_autres }}</td>
            </tr>
            @endif
            @if($rapport->recrutementCi->ci_statut)
            <tr>
                <td style="font-weight: 600;">Statut</td>
                <td>{{ ucfirst($rapport->recrutementCi->ci_statut) }}</td>
            </tr>
            @endif
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Effectifs CI par Période de Recrutement</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Période</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Octobre</td>
                    <td>{{ $rapport->recrutementCi->ci_octobre_garcons ?? 0 }}</td>
                    <td>{{ $rapport->recrutementCi->ci_octobre_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->recrutementCi->ci_octobre_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Janvier</td>
                    <td>{{ $rapport->recrutementCi->ci_janvier_garcons ?? 0 }}</td>
                    <td>{{ $rapport->recrutementCi->ci_janvier_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->recrutementCi->ci_janvier_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Mai</td>
                    <td>{{ $rapport->recrutementCi->ci_mai_garcons ?? 0 }}</td>
                    <td>{{ $rapport->recrutementCi->ci_mai_filles ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->recrutementCi->ci_mai_total ?? 0 }}</td>
                </tr>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL GÉNÉRAL</td>
                    <td>{{ ($rapport->recrutementCi->ci_octobre_garcons ?? 0) + ($rapport->recrutementCi->ci_janvier_garcons ?? 0) + ($rapport->recrutementCi->ci_mai_garcons ?? 0) }}</td>
                    <td>{{ ($rapport->recrutementCi->ci_octobre_filles ?? 0) + ($rapport->recrutementCi->ci_janvier_filles ?? 0) + ($rapport->recrutementCi->ci_mai_filles ?? 0) }}</td>
                    <td>{{ ($rapport->recrutementCi->ci_octobre_total ?? 0) + ($rapport->recrutementCi->ci_janvier_total ?? 0) + ($rapport->recrutementCi->ci_mai_total ?? 0) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        @if($rapport->cmg)
        <div class="subsection-title">CMG (Classes Multigrades)</div>
        <table class="info-table">
            <tr>
                <td>Nombre de CMG</td>
                <td>{{ $rapport->cmg->cmg_nombre ?? 0 }}</td>
            </tr>
            @if($rapport->cmg->cmg_combinaison_1)
            <tr>
                <td>Combinaison 1</td>
                <td>{{ $rapport->cmg->cmg_combinaison_1 }}</td>
            </tr>
            @endif
            @if($rapport->cmg->cmg_combinaison_2)
            <tr>
                <td>Combinaison 2</td>
                <td>{{ $rapport->cmg->cmg_combinaison_2 }}</td>
            </tr>
            @endif
            @if($rapport->cmg->cmg_combinaison_3)
            <tr>
                <td>Combinaison 3</td>
                <td>{{ $rapport->cmg->cmg_combinaison_3 }}</td>
            </tr>
            @endif
            @if($rapport->cmg->cmg_combinaison_autres)
            <tr>
                <td>Autres Combinaisons</td>
                <td>{{ $rapport->cmg->cmg_combinaison_autres }}</td>
            </tr>
            @endif
        </table>
        @endif

        @if($rapport->recrutementCi)
        <div class="subsection-title">Recrutement CI (Cours d'Initiation)</div>
        <table class="info-table">
            <tr>
                <td>Nombre de CI</td>
                <td>{{ $rapport->recrutementCi->ci_nombre ?? 0 }}</td>
            </tr>
            <tr>
                <td>Statut</td>
                <td>{{ $rapport->recrutementCi->ci_statut === 'homologue' ? 'Homologué' : 'Non homologué' }}</td>
            </tr>
            <tr style="background: #f3f4f6;">
                <td colspan="2"><strong>Effectifs Octobre</strong></td>
            </tr>
            <tr>
                <td>Garçons Octobre</td>
                <td>{{ $rapport->recrutementCi->ci_octobre_garcons ?? 0 }}</td>
            </tr>
            <tr>
                <td>Filles Octobre</td>
                <td>{{ $rapport->recrutementCi->ci_octobre_filles ?? 0 }}</td>
            </tr>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td>Total Octobre</td>
                <td>{{ $rapport->recrutementCi->ci_octobre_total ?? 0 }}</td>
            </tr>
            <tr style="background: #f3f4f6;">
                <td colspan="2"><strong>Effectifs Mai</strong></td>
            </tr>
            <tr>
                <td>Garçons Mai</td>
                <td>{{ $rapport->recrutementCi->ci_mai_garcons ?? 0 }}</td>
            </tr>
            <tr>
                <td>Filles Mai</td>
                <td>{{ $rapport->recrutementCi->ci_mai_filles ?? 0 }}</td>
            </tr>
            <tr style="background: #f3f4f6; font-weight: bold;">
                <td>Total Mai</td>
                <td>{{ $rapport->recrutementCi->ci_mai_total ?? 0 }}</td>
            </tr>
        </table>
        @endif

        <!-- SECTION 4 : Personnel Enseignant -->
        <div class="section-title">SECTION 4 : PERSONNEL ENSEIGNANT</div>

        @if($rapport->personnelEnseignant)
        <div class="subsection-title">Répartition par Spécialité</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Spécialité</th>
                    <th style="width: 20%;">Hommes</th>
                    <th style="width: 20%;">Femmes</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Titulaires</td>
                    <td>{{ $rapport->personnelEnseignant->titulaires_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->titulaires_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->titulaires_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Vacataires</td>
                    <td>{{ $rapport->personnelEnseignant->vacataires_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->vacataires_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->vacataires_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Volontaires</td>
                    <td>{{ $rapport->personnelEnseignant->volontaires_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->volontaires_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->volontaires_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Contractuels</td>
                    <td>{{ $rapport->personnelEnseignant->contractuels_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->contractuels_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->contractuels_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Communautaires</td>
                    <td>{{ $rapport->personnelEnseignant->communautaires_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->communautaires_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->communautaires_total ?? 0 }}</td>
                </tr>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL GÉNÉRAL</td>
                    <td>{{ $rapport->personnelEnseignant->total_personnel_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->total_personnel_femmes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->total_personnel ?? 0 }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Répartition par Corps</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Corps</th>
                    <th style="width: 20%;">Hommes</th>
                    <th style="width: 20%;">Femmes</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Instituteurs</td>
                    <td>{{ $rapport->personnelEnseignant->instituteurs_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->instituteurs_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->instituteurs_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Instituteurs Adjoints</td>
                    <td>{{ $rapport->personnelEnseignant->instituteurs_adjoints_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->instituteurs_adjoints_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->instituteurs_adjoints_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Professeurs</td>
                    <td>{{ $rapport->personnelEnseignant->professeurs_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->professeurs_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->professeurs_total ?? 0 }}</td>
                </tr>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL</td>
                    <td>{{ ($rapport->personnelEnseignant->instituteurs_hommes ?? 0) + ($rapport->personnelEnseignant->instituteurs_adjoints_hommes ?? 0) + ($rapport->personnelEnseignant->professeurs_hommes ?? 0) }}</td>
                    <td>{{ ($rapport->personnelEnseignant->instituteurs_femmes ?? 0) + ($rapport->personnelEnseignant->instituteurs_adjoints_femmes ?? 0) + ($rapport->personnelEnseignant->professeurs_femmes ?? 0) }}</td>
                    <td>{{ ($rapport->personnelEnseignant->instituteurs_total ?? 0) + ($rapport->personnelEnseignant->instituteurs_adjoints_total ?? 0) + ($rapport->personnelEnseignant->professeurs_total ?? 0) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Répartition par Diplôme</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Diplôme</th>
                    <th style="width: 20%;">Hommes</th>
                    <th style="width: 20%;">Femmes</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Master</td>
                    <td>{{ $rapport->personnelEnseignant->master_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->master_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->master_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Licence</td>
                    <td>{{ $rapport->personnelEnseignant->licence_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->licence_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->licence_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>BAC</td>
                    <td>{{ $rapport->personnelEnseignant->bac_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->bac_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->bac_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>BFEM</td>
                    <td>{{ $rapport->personnelEnseignant->bfem_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->bfem_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->bfem_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>CFEE</td>
                    <td>{{ $rapport->personnelEnseignant->cfee_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->cfee_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->cfee_total ?? 0 }}</td>
                </tr>
                <tr>
                    <td>Autres Diplômes</td>
                    <td>{{ $rapport->personnelEnseignant->autres_diplomes_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->autres_diplomes_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->autres_diplomes_total ?? 0 }}</td>
                </tr>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL</td>
                    <td>{{ ($rapport->personnelEnseignant->master_hommes ?? 0) + ($rapport->personnelEnseignant->licence_hommes ?? 0) + ($rapport->personnelEnseignant->bac_hommes ?? 0) + ($rapport->personnelEnseignant->bfem_hommes ?? 0) + ($rapport->personnelEnseignant->cfee_hommes ?? 0) + ($rapport->personnelEnseignant->autres_diplomes_hommes ?? 0) }}</td>
                    <td>{{ ($rapport->personnelEnseignant->master_femmes ?? 0) + ($rapport->personnelEnseignant->licence_femmes ?? 0) + ($rapport->personnelEnseignant->bac_femmes ?? 0) + ($rapport->personnelEnseignant->bfem_femmes ?? 0) + ($rapport->personnelEnseignant->cfee_femmes ?? 0) + ($rapport->personnelEnseignant->autres_diplomes_femmes ?? 0) }}</td>
                    <td>{{ ($rapport->personnelEnseignant->master_total ?? 0) + ($rapport->personnelEnseignant->licence_total ?? 0) + ($rapport->personnelEnseignant->bac_total ?? 0) + ($rapport->personnelEnseignant->bfem_total ?? 0) + ($rapport->personnelEnseignant->cfee_total ?? 0) + ($rapport->personnelEnseignant->autres_diplomes_total ?? 0) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Compétences TIC</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Compétence</th>
                    <th style="width: 20%;">Hommes</th>
                    <th style="width: 20%;">Femmes</th>
                    <th style="width: 20%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Enseignants Formés aux TIC</td>
                    <td>{{ $rapport->personnelEnseignant->enseignants_formes_tic_hommes ?? 0 }}</td>
                    <td>{{ $rapport->personnelEnseignant->enseignants_formes_tic_femmes ?? 0 }}</td>
                    <td style="font-weight: bold;">{{ $rapport->personnelEnseignant->enseignants_formes_tic_total ?? 0 }}</td>
                </tr>
            </tbody>
        </table>

        <div class="subsection-title">Statistiques Générales</div>
        <table class="info-table">
            <tr>
                <td style="width: 50%; font-weight: 600;">Total Personnel Enseignant</td>
                <td style="width: 50%;">{{ $rapport->personnelEnseignant->total_personnel ?? 0 }}</td>
            </tr>
            <tr>
                <td style="font-weight: 600;">Taux de Féminisation</td>
                <td style="font-weight: bold; color: #10b981;">{{ number_format($rapport->personnelEnseignant->taux_feminisation ?? 0, 1) }} %</td>
            </tr>
            @if($rapport->personnelEnseignant->ratio_eleves_enseignant)
            <tr>
                <td style="font-weight: 600;">Ratio Élèves/Enseignant</td>
                <td style="font-weight: bold; color: #3b82f6;">{{ number_format($rapport->personnelEnseignant->ratio_eleves_enseignant, 1) }}</td>
            </tr>
            @endif
        </table>
        @endif

        <!-- SECTION 5 : Matériel Pédagogique -->
        <div class="section-title">SECTION 5 : MATÉRIEL PÉDAGOGIQUE</div>

        @if($rapport->manuelsEleves && $rapport->manuelsEleves->count() > 0)
        <div class="subsection-title">Manuels Élèves par Niveau</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Niveau</th>
                    <th style="width: 8%;">LC Français</th>
                    <th style="width: 8%;">Maths</th>
                    <th style="width: 8%;">EDD</th>
                    <th style="width: 8%;">DM</th>
                    <th style="width: 8%;">Manuel Classe</th>
                    <th style="width: 8%;">Livret Maison</th>
                    <th style="width: 8%;">Livret Gradué</th>
                    <th style="width: 8%;">Planche Alpha</th>
                    <th style="width: 8%;">Arabe</th>
                    <th style="width: 8%;">Religion</th>
                    <th style="width: 8%;">Autres</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapport->manuelsEleves as $manuel)
                <tr>
                    <td style="font-weight: bold;">{{ $manuel->niveau }}</td>
                    <td>{{ $manuel->lc_francais ?? 0 }}</td>
                    <td>{{ $manuel->mathematiques ?? 0 }}</td>
                    <td>{{ $manuel->edd ?? 0 }}</td>
                    <td>{{ $manuel->dm ?? 0 }}</td>
                    <td>{{ $manuel->manuel_classe ?? 0 }}</td>
                    <td>{{ $manuel->livret_maison ?? 0 }}</td>
                    <td>{{ $manuel->livret_devoir_gradue ?? 0 }}</td>
                    <td>{{ $manuel->planche_alphabetique ?? 0 }}</td>
                    <td>{{ $manuel->manuel_arabe ?? 0 }}</td>
                    <td>{{ $manuel->manuel_religion ?? 0 }}</td>
                    <td>{{ $manuel->autres_manuels ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($rapport->manuelsMaitre && $rapport->manuelsMaitre->count() > 0)
        <div class="subsection-title">Manuels du Maître par Niveau</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Niveau</th>
                    <th style="width: 11%;">Guide LC Français</th>
                    <th style="width: 11%;">Guide Maths</th>
                    <th style="width: 11%;">Guide EDD</th>
                    <th style="width: 11%;">Guide DM</th>
                    <th style="width: 11%;">Guide Péda</th>
                    <th style="width: 11%;">Guide Arabe/Religion</th>
                    <th style="width: 11%;">Guide Langue Nat.</th>
                    <th style="width: 11%;">Autres</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rapport->manuelsMaitre as $manuel)
                <tr>
                    <td style="font-weight: bold;">{{ $manuel->niveau }}</td>
                    <td>{{ $manuel->guide_lc_francais ?? 0 }}</td>
                    <td>{{ $manuel->guide_mathematiques ?? 0 }}</td>
                    <td>{{ $manuel->guide_edd ?? 0 }}</td>
                    <td>{{ $manuel->guide_dm ?? 0 }}</td>
                    <td>{{ $manuel->guide_pedagogique ?? 0 }}</td>
                    <td>{{ $manuel->guide_arabe_religieux ?? 0 }}</td>
                    <td>{{ $manuel->guide_langue_nationale ?? 0 }}</td>
                    <td>{{ $manuel->autres_manuels_maitre ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($rapport->dictionnaires)
        <div class="subsection-title">Dictionnaires</div>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Type de Dictionnaire</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Dictionnaires Français</td>
                    <td>{{ $rapport->dictionnaires->dico_francais_total ?? 0 }}</td>
                    <td>{{ $rapport->dictionnaires->dico_francais_bon_etat ?? 0 }}</td>
                    <td>
                        @if(($rapport->dictionnaires->dico_francais_total ?? 0) > 0)
                            {{ number_format((($rapport->dictionnaires->dico_francais_bon_etat ?? 0) / $rapport->dictionnaires->dico_francais_total) * 100, 1) }} %
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Dictionnaires Arabe</td>
                    <td>{{ $rapport->dictionnaires->dico_arabe_total ?? 0 }}</td>
                    <td>{{ $rapport->dictionnaires->dico_arabe_bon_etat ?? 0 }}</td>
                    <td>
                        @if(($rapport->dictionnaires->dico_arabe_total ?? 0) > 0)
                            {{ number_format((($rapport->dictionnaires->dico_arabe_bon_etat ?? 0) / $rapport->dictionnaires->dico_arabe_total) * 100, 1) }} %
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Autres Dictionnaires</td>
                    <td>{{ $rapport->dictionnaires->dico_autre_total ?? 0 }}</td>
                    <td>{{ $rapport->dictionnaires->dico_autre_bon_etat ?? 0 }}</td>
                    <td>
                        @if(($rapport->dictionnaires->dico_autre_total ?? 0) > 0)
                            {{ number_format((($rapport->dictionnaires->dico_autre_bon_etat ?? 0) / $rapport->dictionnaires->dico_autre_total) * 100, 1) }} %
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td>TOTAL GÉNÉRAL</td>
                    <td>{{ ($rapport->dictionnaires->dico_francais_total ?? 0) + ($rapport->dictionnaires->dico_arabe_total ?? 0) + ($rapport->dictionnaires->dico_autre_total ?? 0) }}</td>
                    <td>{{ ($rapport->dictionnaires->dico_francais_bon_etat ?? 0) + ($rapport->dictionnaires->dico_arabe_bon_etat ?? 0) + ($rapport->dictionnaires->dico_autre_bon_etat ?? 0) }}</td>
                    <td>
                        @php
                            $totalDico = ($rapport->dictionnaires->dico_francais_total ?? 0) + ($rapport->dictionnaires->dico_arabe_total ?? 0) + ($rapport->dictionnaires->dico_autre_total ?? 0);
                            $totalBonEtat = ($rapport->dictionnaires->dico_francais_bon_etat ?? 0) + ($rapport->dictionnaires->dico_arabe_bon_etat ?? 0) + ($rapport->dictionnaires->dico_autre_bon_etat ?? 0);
                        @endphp
                        {{ $totalDico > 0 ? number_format(($totalBonEtat / $totalDico) * 100, 1) : '0' }} %
                    </td>
                </tr>
            </tbody>
        </table>
        @if($rapport->dictionnaires->besoins_dictionnaires)
        <p style="font-size: 10px; margin-top: 8px;"><strong>Besoins :</strong> {{ $rapport->dictionnaires->besoins_dictionnaires }}</p>
        @endif
        @if($rapport->dictionnaires->budget_estime_dictionnaires)
        <p style="font-size: 10px;"><strong>Budget Estimé :</strong> {{ number_format($rapport->dictionnaires->budget_estime_dictionnaires, 0, ',', ' ') }} FCFA</p>
        @endif
        @if($rapport->dictionnaires->observations_dictionnaires)
        <p style="font-size: 10px;"><strong>Observations :</strong> {{ $rapport->dictionnaires->observations_dictionnaires }}</p>
        @endif
        @endif

        @if($rapport->materielDidactique)
        <div class="subsection-title">Matériel Didactique</div>
        
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Instruments de Géométrie</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Matériel</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Règles Plates</td>
                    <td>{{ $rapport->materielDidactique->regle_plate_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->regle_plate_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->regle_plate_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->regle_plate_bon_etat ?? 0) / $rapport->materielDidactique->regle_plate_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Équerres</td>
                    <td>{{ $rapport->materielDidactique->equerre_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->equerre_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->equerre_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->equerre_bon_etat ?? 0) / $rapport->materielDidactique->equerre_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Compas</td>
                    <td>{{ $rapport->materielDidactique->compas_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->compas_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->compas_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->compas_bon_etat ?? 0) / $rapport->materielDidactique->compas_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Rapporteurs</td>
                    <td>{{ $rapport->materielDidactique->rapporteur_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->rapporteur_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->rapporteur_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->rapporteur_bon_etat ?? 0) / $rapport->materielDidactique->rapporteur_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Décamètres</td>
                    <td>{{ $rapport->materielDidactique->decametre_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->decametre_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->decametre_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->decametre_bon_etat ?? 0) / $rapport->materielDidactique->decametre_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Chaînes d'Arpenteur</td>
                    <td>{{ $rapport->materielDidactique->chaine_arpenteur_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->chaine_arpenteur_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->chaine_arpenteur_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->chaine_arpenteur_bon_etat ?? 0) / $rapport->materielDidactique->chaine_arpenteur_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Instruments Scientifiques</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Matériel</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Boussoles</td>
                    <td>{{ $rapport->materielDidactique->boussole_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->boussole_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->boussole_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->boussole_bon_etat ?? 0) / $rapport->materielDidactique->boussole_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Thermomètres</td>
                    <td>{{ $rapport->materielDidactique->thermometre_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->thermometre_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->thermometre_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->thermometre_bon_etat ?? 0) / $rapport->materielDidactique->thermometre_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Kits de Capacité</td>
                    <td>{{ $rapport->materielDidactique->kit_capacite_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->kit_capacite_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->kit_capacite_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->kit_capacite_bon_etat ?? 0) / $rapport->materielDidactique->kit_capacite_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Balances</td>
                    <td>{{ $rapport->materielDidactique->balance_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->balance_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->balance_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->balance_bon_etat ?? 0) / $rapport->materielDidactique->balance_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Kits Matériel Scientifique</td>
                    <td>{{ $rapport->materielDidactique->kit_materiel_scientifique_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->kit_materiel_scientifique_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->kit_materiel_scientifique_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->kit_materiel_scientifique_bon_etat ?? 0) / $rapport->materielDidactique->kit_materiel_scientifique_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Supports Pédagogiques</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Matériel</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Globes Terrestres</td>
                    <td>{{ $rapport->materielDidactique->globe_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->globe_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->globe_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->globe_bon_etat ?? 0) / $rapport->materielDidactique->globe_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Cartes Murales</td>
                    <td>{{ $rapport->materielDidactique->cartes_murales_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->cartes_murales_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->cartes_murales_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->cartes_murales_bon_etat ?? 0) / $rapport->materielDidactique->cartes_murales_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Planches Illustrées</td>
                    <td>{{ $rapport->materielDidactique->planches_illustrees_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->planches_illustrees_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->planches_illustrees_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->planches_illustrees_bon_etat ?? 0) / $rapport->materielDidactique->planches_illustrees_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Autres Matériels</td>
                    <td>{{ $rapport->materielDidactique->autres_materiel_total ?? 0 }}</td>
                    <td>{{ $rapport->materielDidactique->autres_materiel_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->materielDidactique->autres_materiel_total ?? 0) > 0 ? number_format((($rapport->materielDidactique->autres_materiel_bon_etat ?? 0) / $rapport->materielDidactique->autres_materiel_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- SECTION 6 : Infrastructure & Équipements -->
        <div class="section-title">SECTION 6 : INFRASTRUCTURE & ÉQUIPEMENTS</div>

        @if($rapport->capitalImmobilier)
        <div class="subsection-title">Capital Immobilier</div>
        
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Salles de Classe et Espaces d'Enseignement</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Salles en Dur</td>
                    <td>{{ $rapport->capitalImmobilier->salles_dur_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->salles_dur_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->salles_dur_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->salles_dur_bon_etat ?? 0) / $rapport->capitalImmobilier->salles_dur_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Abris Provisoires</td>
                    <td>{{ $rapport->capitalImmobilier->abris_provisoires_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->abris_provisoires_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->abris_provisoires_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->abris_provisoires_bon_etat ?? 0) / $rapport->capitalImmobilier->abris_provisoires_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Espaces Administratifs et de Service</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Blocs Administratifs</td>
                    <td>{{ $rapport->capitalImmobilier->bloc_admin_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->bloc_admin_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->bloc_admin_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->bloc_admin_bon_etat ?? 0) / $rapport->capitalImmobilier->bloc_admin_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Magasins</td>
                    <td>{{ $rapport->capitalImmobilier->magasin_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->magasin_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->magasin_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->magasin_bon_etat ?? 0) / $rapport->capitalImmobilier->magasin_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Salles Informatiques</td>
                    <td>{{ $rapport->capitalImmobilier->salle_informatique_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->salle_informatique_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->salle_informatique_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->salle_informatique_bon_etat ?? 0) / $rapport->capitalImmobilier->salle_informatique_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Bibliothèques</td>
                    <td>{{ $rapport->capitalImmobilier->salle_bibliotheque_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->salle_bibliotheque_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->salle_bibliotheque_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->salle_bibliotheque_bon_etat ?? 0) / $rapport->capitalImmobilier->salle_bibliotheque_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Restauration</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Cuisines</td>
                    <td>{{ $rapport->capitalImmobilier->cuisine_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->cuisine_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->cuisine_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->cuisine_bon_etat ?? 0) / $rapport->capitalImmobilier->cuisine_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Réfectoires</td>
                    <td>{{ $rapport->capitalImmobilier->refectoire_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->refectoire_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->refectoire_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->refectoire_bon_etat ?? 0) / $rapport->capitalImmobilier->refectoire_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Sanitaires</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Toilettes Enseignants</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_enseignants_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_enseignants_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->toilettes_enseignants_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->toilettes_enseignants_bon_etat ?? 0) / $rapport->capitalImmobilier->toilettes_enseignants_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Toilettes Garçons</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_garcons_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_garcons_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->toilettes_garcons_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->toilettes_garcons_bon_etat ?? 0) / $rapport->capitalImmobilier->toilettes_garcons_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Toilettes Filles</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_filles_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_filles_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->toilettes_filles_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->toilettes_filles_bon_etat ?? 0) / $rapport->capitalImmobilier->toilettes_filles_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Toilettes Mixtes</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_mixtes_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->toilettes_mixtes_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->toilettes_mixtes_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->toilettes_mixtes_bon_etat ?? 0) / $rapport->capitalImmobilier->toilettes_mixtes_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Logements</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Logement Directeur</td>
                    <td>{{ $rapport->capitalImmobilier->logement_directeur_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->logement_directeur_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->logement_directeur_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->logement_directeur_bon_etat ?? 0) / $rapport->capitalImmobilier->logement_directeur_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Logement Gardien</td>
                    <td>{{ $rapport->capitalImmobilier->logement_gardien_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->logement_gardien_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->logement_gardien_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->logement_gardien_bon_etat ?? 0) / $rapport->capitalImmobilier->logement_gardien_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        @if(($rapport->capitalImmobilier->autres_infrastructures_total ?? 0) > 0)
        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Autres Infrastructures</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Infrastructure</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Autres</td>
                    <td>{{ $rapport->capitalImmobilier->autres_infrastructures_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalImmobilier->autres_infrastructures_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalImmobilier->autres_infrastructures_total ?? 0) > 0 ? number_format((($rapport->capitalImmobilier->autres_infrastructures_bon_etat ?? 0) / $rapport->capitalImmobilier->autres_infrastructures_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>
        @endif
        @endif

        @if($rapport->capitalMobilier)
        <div class="subsection-title">Capital Mobilier</div>
        
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Mobilier Élèves</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Type</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tables-Bancs</td>
                    <td>{{ $rapport->capitalMobilier->tables_bancs_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->tables_bancs_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->tables_bancs_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->tables_bancs_bon_etat ?? 0) / $rapport->capitalMobilier->tables_bancs_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Chaises Élèves</td>
                    <td>{{ $rapport->capitalMobilier->chaises_eleves_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->chaises_eleves_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->chaises_eleves_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->chaises_eleves_bon_etat ?? 0) / $rapport->capitalMobilier->chaises_eleves_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Tables Individuelles</td>
                    <td>{{ $rapport->capitalMobilier->tables_individuelles_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->tables_individuelles_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->tables_individuelles_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->tables_individuelles_bon_etat ?? 0) / $rapport->capitalMobilier->tables_individuelles_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Mobilier Enseignants</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Type</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bureaux Maître</td>
                    <td>{{ $rapport->capitalMobilier->bureaux_maitre_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->bureaux_maitre_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->bureaux_maitre_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->bureaux_maitre_bon_etat ?? 0) / $rapport->capitalMobilier->bureaux_maitre_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Chaises Maître</td>
                    <td>{{ $rapport->capitalMobilier->chaises_maitre_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->chaises_maitre_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->chaises_maitre_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->chaises_maitre_bon_etat ?? 0) / $rapport->capitalMobilier->chaises_maitre_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Tableaux</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->tableaux_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->tableaux_bon_etat ?? 0) / $rapport->capitalMobilier->tableaux_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Armoires</td>
                    <td>{{ $rapport->capitalMobilier->armoires_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->armoires_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->armoires_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->armoires_bon_etat ?? 0) / $rapport->capitalMobilier->armoires_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Mobilier Administratif</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Type</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bureaux Administratifs</td>
                    <td>{{ $rapport->capitalMobilier->bureaux_admin_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->bureaux_admin_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->bureaux_admin_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->bureaux_admin_bon_etat ?? 0) / $rapport->capitalMobilier->bureaux_admin_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Chaises Administratives</td>
                    <td>{{ $rapport->capitalMobilier->chaises_admin_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->chaises_admin_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->chaises_admin_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->chaises_admin_bon_etat ?? 0) / $rapport->capitalMobilier->chaises_admin_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        @if(($rapport->capitalMobilier->mat_drapeau_total ?? 0) > 0 || ($rapport->capitalMobilier->drapeau_total ?? 0) > 0)
        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Équipements Divers</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Type</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                @if(($rapport->capitalMobilier->tableaux_mobiles_total ?? 0) > 0)
                <tr>
                    <td>Tableaux Mobiles</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_mobiles_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_mobiles_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->tableaux_mobiles_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->tableaux_mobiles_bon_etat ?? 0) / $rapport->capitalMobilier->tableaux_mobiles_total) * 100, 1) : '0' }} %</td>
                </tr>
                @endif
                @if(($rapport->capitalMobilier->tableaux_interactifs_total ?? 0) > 0)
                <tr>
                    <td>Tableaux Interactifs</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_interactifs_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_interactifs_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->tableaux_interactifs_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->tableaux_interactifs_bon_etat ?? 0) / $rapport->capitalMobilier->tableaux_interactifs_total) * 100, 1) : '0' }} %</td>
                </tr>
                @endif
                @if(($rapport->capitalMobilier->tableaux_muraux_total ?? 0) > 0)
                <tr>
                    <td>Tableaux Muraux</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_muraux_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->tableaux_muraux_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->tableaux_muraux_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->tableaux_muraux_bon_etat ?? 0) / $rapport->capitalMobilier->tableaux_muraux_total) * 100, 1) : '0' }} %</td>
                </tr>
                @endif
                @if(($rapport->capitalMobilier->mat_drapeau_total ?? 0) > 0)
                <tr>
                    <td>Mâts de Drapeau</td>
                    <td>{{ $rapport->capitalMobilier->mat_drapeau_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->mat_drapeau_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->mat_drapeau_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->mat_drapeau_bon_etat ?? 0) / $rapport->capitalMobilier->mat_drapeau_total) * 100, 1) : '0' }} %</td>
                </tr>
                @endif
                @if(($rapport->capitalMobilier->drapeau_total ?? 0) > 0)
                <tr>
                    <td>Drapeaux</td>
                    <td>{{ $rapport->capitalMobilier->drapeau_total ?? 0 }}</td>
                    <td>{{ $rapport->capitalMobilier->drapeau_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->capitalMobilier->drapeau_total ?? 0) > 0 ? number_format((($rapport->capitalMobilier->drapeau_bon_etat ?? 0) / $rapport->capitalMobilier->drapeau_total) * 100, 1) : '0' }} %</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif
        @endif

        @if($rapport->equipementInformatique)
        <div class="subsection-title">Équipement Informatique</div>
        
        <p style="font-size: 10px; font-weight: bold; margin: 8px 0 5px 0;">Matériel Informatique</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Équipement</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Ordinateurs Fixes</td>
                    <td>{{ $rapport->equipementInformatique->ordinateurs_fixes_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->ordinateurs_fixes_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->ordinateurs_fixes_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->ordinateurs_fixes_bon_etat ?? 0) / $rapport->equipementInformatique->ordinateurs_fixes_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Ordinateurs Portables</td>
                    <td>{{ $rapport->equipementInformatique->ordinateurs_portables_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->ordinateurs_portables_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->ordinateurs_portables_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->ordinateurs_portables_bon_etat ?? 0) / $rapport->equipementInformatique->ordinateurs_portables_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Tablettes</td>
                    <td>{{ $rapport->equipementInformatique->tablettes_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->tablettes_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->tablettes_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->tablettes_bon_etat ?? 0) / $rapport->equipementInformatique->tablettes_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Vidéoprojecteurs</td>
                    <td>{{ $rapport->equipementInformatique->videoprojecteurs_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->videoprojecteurs_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->videoprojecteurs_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->videoprojecteurs_bon_etat ?? 0) / $rapport->equipementInformatique->videoprojecteurs_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Matériel d'Impression</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Équipement</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Imprimantes Laser</td>
                    <td>{{ $rapport->equipementInformatique->imprimantes_laser_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->imprimantes_laser_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->imprimantes_laser_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->imprimantes_laser_bon_etat ?? 0) / $rapport->equipementInformatique->imprimantes_laser_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Imprimantes Jet d'Encre</td>
                    <td>{{ $rapport->equipementInformatique->imprimantes_jet_encre_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->imprimantes_jet_encre_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->imprimantes_jet_encre_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->imprimantes_jet_encre_bon_etat ?? 0) / $rapport->equipementInformatique->imprimantes_jet_encre_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Imprimantes Multifonction</td>
                    <td>{{ $rapport->equipementInformatique->imprimantes_multifonction_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->imprimantes_multifonction_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->imprimantes_multifonction_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->imprimantes_multifonction_bon_etat ?? 0) / $rapport->equipementInformatique->imprimantes_multifonction_total) * 100, 1) : '0' }} %</td>
                </tr>
                <tr>
                    <td>Photocopieuses</td>
                    <td>{{ $rapport->equipementInformatique->photocopieuses_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->photocopieuses_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->photocopieuses_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->photocopieuses_bon_etat ?? 0) / $rapport->equipementInformatique->photocopieuses_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>

        @if(($rapport->equipementInformatique->autres_equipements_total ?? 0) > 0)
        <p style="font-size: 10px; font-weight: bold; margin: 15px 0 5px 0;">Autres Équipements</p>
        <table class="info-table">
            <thead>
                <tr>
                    <th style="width: 40%;">Équipement</th>
                    <th style="width: 20%;">Total</th>
                    <th style="width: 20%;">Bon État</th>
                    <th style="width: 20%;">% Bon État</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Autres Équipements</td>
                    <td>{{ $rapport->equipementInformatique->autres_equipements_total ?? 0 }}</td>
                    <td>{{ $rapport->equipementInformatique->autres_equipements_bon_etat ?? 0 }}</td>
                    <td>{{ ($rapport->equipementInformatique->autres_equipements_total ?? 0) > 0 ? number_format((($rapport->equipementInformatique->autres_equipements_bon_etat ?? 0) / $rapport->equipementInformatique->autres_equipements_total) * 100, 1) : '0' }} %</td>
                </tr>
            </tbody>
        </table>
        @endif
        @endif

        <!-- Signatures -->
        <div style="margin-top: 30px; page-break-inside: avoid;">
            <table class="info-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Le Directeur de l'Établissement</th>
                        <th style="width: 50%;">L'Inspecteur IEF</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="height: 60px; vertical-align: top;">
                            <p style="font-size: 9px; margin: 5px 0;"><strong>Nom :</strong> {{ $rapport->infoDirecteur->directeur_nom ?? '' }}</p>
                            <p style="font-size: 9px; margin: 5px 0;"><strong>Date :</strong> {{ $rapport->date_soumission?->format('d/m/Y') ?? now()->format('d/m/Y') }}</p>
                            <p style="font-size: 9px; margin: 30px 0 5px 0;">Signature : ___________________</p>
                        </td>
                        <td style="height: 60px; vertical-align: top;">
                            @if($rapport->statut === 'validé' && $rapport->valide_par)
                            <p style="font-size: 9px; margin: 5px 0;"><strong>Nom :</strong> {{ $rapport->valide_par->name ?? '' }}</p>
                            <p style="font-size: 9px; margin: 5px 0;"><strong>Date :</strong> {{ $rapport->date_validation?->format('d/m/Y') ?? '' }}</p>
                            <p style="font-size: 9px; margin: 30px 0 5px 0;">Signature : ___________________</p>
                            @else
                            <p style="font-size: 9px; color: #6b7280; text-align: center; padding: 20px 0;">En attente de validation</p>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e5e7eb; font-size: 8px; color: #6b7280;">
            <strong style="font-size: 9px; color: #002147;">{{ $rapport->etablissement->etablissement }}@if($rapport->etablissement->code) - Code : {{ $rapport->etablissement->code }}@endif</strong><br>
            Document généré le {{ now()->format('d/m/Y à H:i') }}<br>
            <strong>Inspection de l'Éducation et de la Formation de Louga</strong><br>
            <em>Document officiel - Toute reproduction ou falsification est passible de sanctions</em>
        </div>
    </div>

    <!-- Le contenu du rapport sera ajouté ici -->
    </div>
</div>
PS C:\WINDOWS\system32> cd C:\xampp\mysql
PS C:\xampp\mysql>
PS C:\xampp\mysql> Move-Item -Path "data" -Destination "data_new" -Force
PS C:\xampp\mysql>
PS C:\xampp\mysql> Move-Item -Path "data_old" -Destination "data" -Force
PS C:\xampp\mysql>


<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
