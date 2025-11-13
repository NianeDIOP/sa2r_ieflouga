# SA2R - IEF LOUGA
## Système d'Analyse des Rapports de Rentrée

### 🚀 Installation et Configuration

#### 1. Installation des dépendances
```bash
composer install
npm install
```

#### 2. Configuration de l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

#### 3. Configuration de la base de données
Modifiez le fichier `.env` avec vos paramètres de base de données :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sa2r_ieflouga
DB_USERNAME=root
DB_PASSWORD=
```

#### 4. Création de la base de données et migration
```bash
# Créer la base de données (si elle n'existe pas)
mysql -u root -p -e "CREATE DATABASE sa2r_ieflouga CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Exécuter les migrations
php artisan migrate

# Remplir avec les données de test
php artisan db:seed
```

#### 5. Démarrer le serveur
```bash
php artisan serve
```

Le site sera accessible à : `http://localhost:8000`

---

### 👥 Comptes de Test

#### **Login Unifié**
- **URL Unique** : `http://localhost:8000/login`

Le système détecte automatiquement le type d'utilisateur :

#### **Connexion Admin**
- **Identifiant** : `admin` (nom d'utilisateur)
- **Mot de passe** : `admin123`

ou

- **Identifiant** : `admin_ief`
- **Mot de passe** : `ief2024`

#### **Connexion Établissement**
- **Identifiant** : `1234567890` (code à 10 chiffres)
- **Mot de passe** : `SA2R2024`

ou

- **Identifiant** : `0987654321`
- **Mot de passe** : `SA2R2024`

---

### 📁 Structure du Projet

```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/
│       │   └── LoginController.php (UNIFIÉ)
│       ├── Admin/
│       │   └── DashboardController.php
│       └── Etablissement/
│           └── DashboardController.php
├── Models/
│   ├── Admin.php
│   └── Etablissement.php

resources/
└── views/
    ├── auth/
    │   └── login.blade.php (PAGE UNIQUE)
    └── ... (autres vues à créer)

database/
├── migrations/
│   ├── 2024_01_01_000001_create_admins_table.php
│   └── 2024_01_01_000002_create_etablissements_table.php
└── seeders/
    ├── AdminSeeder.php
    └── EtablissementSeeder.php

routes/
└── web.php (routes simplifiées avec login unifié)

config/
└── auth.php (guards admin et etablissement configurés)
```

---

### 🔐 Système d'Authentification

Le projet utilise **un système de login unifié professionnel** avec deux guards distincts :

#### **Login Unique** (`/login`)
- Détection automatique du type d'utilisateur
- Un seul formulaire pour tous
- Si identifiant = 10 chiffres ➔ Établissement
- Sinon ➔ Admin

#### **Guards Laravel**

1. **Guard Admin** (`auth:admin`)
   - Authentification par username/password
   - Accès aux fonctionnalités d'administration
   - Redirection : `/admin/dashboard`

2. **Guard Établissement** (`auth:etablissement`)
   - Authentification par code (10 chiffres)/password
   - Accès aux fonctionnalités propres à l'établissement
   - Redirection : `/etablissement/dashboard`

---

### 🛣️ Routes Disponibles

#### Routes Authentification
- `GET /login` - Page de connexion unique
- `POST /login` - Traitement connexion (détection auto)
- `POST /logout` - Déconnexion (détection auto du guard actif)

#### Routes Admin (protégées par `auth:admin`)
- `GET /admin/dashboard` - Dashboard admin

#### Routes Établissement (protégées par `auth:etablissement`)
- `GET /etablissement/dashboard` - Dashboard établissement

---

### 📊 Base de Données

#### Table `admins`
- `id` - Identifiant unique
- `username` - Nom d'utilisateur (unique)
- `email` - Email (unique)
- `password` - Mot de passe hashé
- `nom_complet` - Nom complet
- `role` - super_admin | admin
- `is_active` - Statut actif/inactif

#### Table `etablissements`
- `id` - Identifiant unique
- `code` - Code à 10 chiffres (unique)
- `nom` - Nom de l'établissement
- `arrondissement` - Arrondissement
- `commune` - Commune
- `zone` - Zone (Urbaine/Rurale)
- `statut` - Public | Privé
- `type_statut` - Type d'établissement
- `password` - Mot de passe hashé
- `is_active` - Statut actif/inactif

---

### ✅ Test de la Configuration

Pour vérifier que tout fonctionne :

1. **Accéder à la page de login unique** :
   ```
   http://localhost:8000/login
   ```

2. **Tester la connexion Admin** :
   - Identifiant : `admin`
   - Mot de passe : `admin123`
   - ➔ Redirige vers `/admin/dashboard`

3. **Tester la connexion Établissement** :
   - Identifiant : `1234567890`
   - Mot de passe : `SA2R2024`
   - ➔ Redirige vers `/etablissement/dashboard`

---

### 🔧 Commandes Utiles

```bash
# Réinitialiser la base de données
php artisan migrate:fresh --seed

# Créer un nouvel admin
php artisan tinker
>>> App\Models\Admin::create(['username' => 'nouvel_admin', 'email' => 'admin@test.sn', 'password' => bcrypt('password'), 'nom_complet' => 'Nom Admin', 'role' => 'admin']);

# Créer un nouvel établissement
>>> App\Models\Etablissement::create(['code' => '1111111111', 'nom' => 'École Test', 'password' => bcrypt('SA2R2024')]);

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

### 📝 Prochaines Étapes

- [ ] Créer les vues dashboard pour admin et établissement
- [ ] Implémenter les 8 sections du formulaire établissement
- [ ] Créer les contrôleurs pour la gestion des données
- [ ] Ajouter les statistiques au dashboard admin
- [ ] Implémenter la gestion des années scolaires
- [ ] Créer les fonctionnalités d'import Excel

---

### 🆘 Support

En cas de problème, vérifiez :
1. Les permissions sur le dossier `storage/` et `bootstrap/cache/`
2. La configuration de votre `.env`
3. Que la base de données existe et est accessible
4. Que les migrations ont été exécutées

```bash
# Permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Windows : Propriétés > Sécurité > Modifier les permissions
```
