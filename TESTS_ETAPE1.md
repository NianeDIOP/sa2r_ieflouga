# Tests de Validation - Étape 1 du Rapport de Rentrée

## ✅ Configuration Validée

### Base de données
- ✅ Toutes les 26 migrations appliquées
- ✅ Tables créées correctement avec relations

### Serveur
- ✅ Laravel server démarré sur http://127.0.0.1:8000
- ✅ Pas d'erreurs PHP fatales

## 🧪 Tests à Effectuer Manuellement

### 1. Test de Chargement Initial
**URL:** `http://127.0.0.1:8000/etablissement/rapport-rentree`

**Vérifications:**
- [ ] Page charge sans erreur 500
- [ ] Sidebar visible avec 5 sous-sections
- [ ] Titre "Rapport de Rentrée 2024-2025" centré sur fond bleu foncé
- [ ] Badge "BROUILLON" affiché
- [ ] Formulaire vide (première visite)

### 2. Test Auto-Save - Section Info Directeur

**Actions:**
1. Remplir le champ "Nom du Directeur"
2. Attendre 1 seconde
3. Observer l'alerte de confirmation verte

**Vérifications:**
- [ ] Alerte "Informations directeur sauvegardées" apparaît
- [ ] Actualiser la page (F5)
- [ ] Le nom reste affiché (données persistées)
- [ ] ✅ Checkmark vert apparaît dans la sidebar à côté de "Info Directeur"

**Données à tester:**
```
Nom: Jean DIOP
Contact 1: +221 77 123 45 67
Contact 2: +221 70 987 65 43
Email: jean.diop@education.sn
Distance: 5
```

### 3. Test Checkbox avec Champs Dépendants - Infrastructures

**Test CPE:**
1. Cocher "Case Tout-Petits (CPE)"
2. Vérifier que le champ "Nombre de cases" apparaît
3. Entrer "2" dans le champ nombre
4. Attendre 1 seconde (auto-save)
5. Décocher "Case Tout-Petits"
6. Vérifier que le champ nombre disparaît
7. Actualiser la page
8. Vérifier que la checkbox est décochée et le nombre est null

**Test Clôture:**
1. Cocher "Clôture"
2. Sélectionner "Dur" dans le menu déroulant
3. Attendre 1 seconde
4. Actualiser la page
5. Vérifier que "Clôture" est cochée et "Dur" est sélectionné

**Vérifications Database:**
```sql
SELECT * FROM rapport_infrastructures_base WHERE rapport_id = 1;
```
- [ ] `cpe_existe` = 0 ou 1 selon checkbox
- [ ] `cpe_nombre` = NULL si checkbox décochée
- [ ] `cloture_existe` = 1
- [ ] `cloture_type` = 'dur'

### 4. Test Structures Communautaires

**Test CGE:**
1. Cocher "CGE Existe"
2. Champs CGE deviennent visibles
3. Remplir:
   - Hommes: 8
   - Femmes: 12
   - Président: Moussa FALL
   - Contact Président: +221 77 111 22 33
   - Trésorier: Fatou SARR
   - Contact Trésorier: +221 76 444 55 66
4. Attendre 1 seconde
5. Actualiser la page
6. Vérifier persistance

**Répéter pour:** G.Scol, APE, AME

### 5. Test Langues & Projets

**Test Langue Nationale:**
1. Sélectionner "Wolof" dans le dropdown
2. Attendre 1 seconde
3. Actualiser - vérifier que "Wolof" est toujours sélectionné

**Test Projets Informatiques:**
1. Cocher "Projets Informatiques"
2. Le textarea apparaît
3. Entrer: "Projet PAQUEB - 25 ordinateurs"
4. Attendre 1 seconde
5. Décocher la checkbox
6. Le textarea disparaît et se vide
7. Actualiser - vérifier que checkbox décochée et textarea vide

### 6. Test Ressources Financières

**Test Multiple Checkboxes:**
1. Cocher "Dotation de l'État"
2. Entrer montant: 500000
3. Cocher "Contribution CGE"
4. Entrer montant: 150000
5. Attendre 1 seconde
6. Vérifier le total calculé: 650000 FCFA
7. Actualiser la page
8. Vérifier que les 2 checkboxes sont cochées avec leurs montants

### 7. Test Scroll Spy

**Actions:**
1. Scroller lentement vers le bas du formulaire
2. Observer la sidebar pendant le scroll

**Vérifications:**
- [ ] "Info Directeur" surligné en vert quand sa section est visible
- [ ] Transition vers "Infrastructures" quand on scrolle
- [ ] "Infrastructures" devient vert, "Info Directeur" redevient gris
- [ ] Continue pour toutes les sections

### 8. Test Completion Indicators

**Scénario:**
1. Remplir complètement la section "Info Directeur"
2. Vérifier ✅ vert apparaît dans sidebar
3. Remplir partiellement "Infrastructures" (juste 1 checkbox)
4. Vérifier ✅ vert apparaît aussi
5. Actualiser la page
6. Les 2 ✅ doivent réapparaître au chargement

### 9. Test Validation Database

**Requêtes SQL à exécuter:**

```sql
-- Vérifier le rapport créé
SELECT * FROM rapports WHERE annee_scolaire = '2024-2025';

-- Vérifier info directeur
SELECT * FROM rapport_info_directeur WHERE rapport_id = 1;

-- Vérifier infrastructures
SELECT * FROM rapport_infrastructures_base WHERE rapport_id = 1;

-- Vérifier structures
SELECT * FROM rapport_structures_communautaires WHERE rapport_id = 1;

-- Vérifier langues
SELECT * FROM rapport_langues_projets WHERE rapport_id = 1;

-- Vérifier ressources
SELECT * FROM rapport_ressources_financieres WHERE rapport_id = 1;
```

**Vérifications:**
- [ ] Chaque table a UN SEUL enregistrement pour ce rapport
- [ ] Les valeurs correspondent exactement à ce qui a été saisi
- [ ] Les checkboxes décochées = 0 (false)
- [ ] Les champs dépendants sont NULL quand checkbox décochée
- [ ] Les timestamps `created_at` et `updated_at` sont corrects

### 10. Test Comportement après Actualisation

**Actions:**
1. Remplir TOUTES les sections partiellement
2. Actualiser la page plusieurs fois (F5, Ctrl+R, bouton refresh)

**Vérifications:**
- [ ] AUCUNE donnée perdue
- [ ] Checkboxes conservent leur état (cochée/décochée)
- [ ] Champs dépendants visibles/cachés selon checkbox
- [ ] Scroll spy fonctionne immédiatement
- [ ] Completion indicators présents dès le chargement
- [ ] Pas de réinitialisation intempestive

## 📊 Résultats Attendus

### ✅ Succès si:
- Toutes les données persistent après actualisation
- Auto-save fonctionne avec délai de 1 seconde
- Checkboxes décochées enregistrent `false` (0)
- Champs dépendants se vident quand checkbox décochée
- Scroll spy change la couleur de la section active
- Completion indicators apparaissent/disparaissent correctement
- Pas d'erreurs JavaScript dans la console
- Pas d'erreurs PHP/Laravel dans les logs

### ❌ Échec si:
- Données perdues après F5
- Checkboxes toujours cochées après décochage + refresh
- Champs dépendants restent remplis malgré checkbox décochée
- Scroll spy ne réagit pas
- Completion indicators ne s'affichent pas
- Erreurs 500 lors de la sauvegarde
- Erreurs JavaScript dans console

## 🐛 Debugging

### Si auto-save ne fonctionne pas:
1. Ouvrir Console Développeur (F12)
2. Observer l'onglet "Network" pendant la saisie
3. Vérifier requêtes POST vers `/etablissement/rapport-rentree/{id}/{section}`
4. Vérifier Status Code = 200
5. Vérifier Response JSON = `{"success": true, ...}`

### Si données ne persistent pas:
```bash
# Vérifier les logs Laravel
tail -f storage/logs/laravel.log

# Vérifier connexion DB
php artisan db:show

# Test direct en Tinker
php artisan tinker
>>> App\Models\Rapport::with(['infoDirecteur'])->first()
```

### Si checkboxes posent problème:
1. Inspecter l'élément dans DevTools
2. Vérifier que `name="xxx_existe"` et `value="1"`
3. Vérifier dans Network tab que checkbox envoyée = "1" ou absente
4. Vérifier le contrôleur fait `array_merge([...], $validated)`

## 📝 Notes Importantes

### Comportement Checkbox HTML
- ✅ Cochée → envoyée avec `value="1"` → Laravel voit `true`
- ✅ Décochée → PAS envoyée → Laravel ne voit rien → array_merge met `false`

### UpdateOrCreate
- Premier save: INSERT nouveau record
- Saves suivants: UPDATE même record
- Clé unique: `rapport_id`

### Auto-Save Debounce
- Délai = 1000ms (1 seconde)
- Timer reset à chaque changement
- Évite spam de requêtes AJAX

### Scroll Spy Detection
- Zone de détection: top ≤ 200px && bottom ≥ 100px
- Met à jour toutes les 100ms pendant scroll
- Utilise `getBoundingClientRect()`

### Completion Check
- Compte inputs remplis (non vides)
- Ignore inputs disabled
- Checkboxes comptées séparément
- Affiche ✅ si au moins 1 champ rempli
