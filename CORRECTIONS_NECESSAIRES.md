# Corrections Nécessaires - Ressources Financières

## Problème Identifié

La vue `etape1-ressources-financieres.blade.php` utilise des noms de champs qui ne correspondent PAS à la migration/base de données.

### Migration/Database (CORRECT):
- `subvention_etat_existe` / `subvention_etat_montant`
- `subvention_partenaires_existe` / `subvention_partenaires_montant`
- `subvention_collectivites_existe` / `subvention_collectivites_montant`
- `subvention_communaute_existe` / `subvention_communaute_montant`
- `ressources_generees_existe` / `ressources_generees_montant`

### Vue Actuelle (INCORRECT):
- `dotation_etat_existe` / `dotation_etat_montant` ❌
- `subvention_collectivite_existe` / `subvention_collectivite_montant` ❌ (singulier)
- `contribution_cge_existe` / `contribution_cge_montant` ❌
- `contribution_ape_existe` / `contribution_ape_montant` ❌
- `contribution_partenaires_existe` / `contribution_partenaires_montant` ❌

## Solution

Modifier la vue pour utiliser les bons noms de champs.

## Labels Corrigés

1. "Subvention de l'État" → `subvention_etat_...`
2. "Subvention Partenaires/ONG" → `subvention_partenaires_...`
3. "Subvention Collectivités Locales" → `subvention_collectivites_...`
4. "Contribution Communauté (CGE/APE)" → `subvention_communaute_...`
5. "Ressources Générées (AGR)" → `ressources_generees_...`
