<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AnneeScolaireController;
use App\Http\Controllers\Admin\EtablissementController;
use App\Http\Controllers\Etablissement\DashboardController as EtablissementDashboardController;

// ============================================
// AUTHENTIFICATION UNIFIÉE
// ============================================

// Page d'accueil - Redirection vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login unifié pour tous les utilisateurs
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ============================================
// ROUTES ADMIN
// ============================================

Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Années Scolaires
    Route::resource('annees-scolaires', AnneeScolaireController::class);
    Route::patch('annees-scolaires/{anneesScolaire}/activate', [AnneeScolaireController::class, 'activate'])
        ->name('annees-scolaires.activate');
    
    // Établissements (Import Données de Base)
    Route::resource('etablissements', EtablissementController::class);
    Route::post('etablissements/import', [EtablissementController::class, 'import'])
        ->name('etablissements.import');
    Route::get('etablissements/template/download', [EtablissementController::class, 'downloadTemplate'])
        ->name('etablissements.template');
    Route::post('etablissements/{etablissement}/toggle-status', [EtablissementController::class, 'toggleStatus'])
        ->name('etablissements.toggle-status');
    Route::post('etablissements/{etablissement}/reset-password', [EtablissementController::class, 'resetPassword'])
        ->name('etablissements.reset-password');
    
    // Autres routes admin à ajouter ici
    // Route::get('import', [AdminImportController::class, 'index'])->name('import');
    // etc...
});

// ============================================
// ROUTES ETABLISSEMENT
// ============================================

Route::prefix('etablissement')->name('etablissement.')->middleware('auth:etablissement')->group(function () {
    Route::get('dashboard', [EtablissementDashboardController::class, 'index'])->name('dashboard');
    
    // Rapport de Rentrée
    Route::get('rapport-rentree', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'index'])
        ->name('rapport-rentree.index');
    
    // ÉTAPE 1 - Sauvegarde AJAX
    Route::post('rapport-rentree/{rapport}/info-directeur', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveInfoDirecteur'])
        ->name('rapport-rentree.save-info-directeur');
    Route::post('rapport-rentree/{rapport}/infrastructures', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveInfrastructures'])
        ->name('rapport-rentree.save-infrastructures');
    Route::post('rapport-rentree/{rapport}/structures-communautaires', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveStructuresCommunautaires'])
        ->name('rapport-rentree.save-structures-communautaires');
    Route::post('rapport-rentree/{rapport}/langues-projets', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveLanguesProjets'])
        ->name('rapport-rentree.save-langues-projets');
    Route::post('rapport-rentree/{rapport}/ressources-financieres', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveRessourcesFinancieres'])
        ->name('rapport-rentree.save-ressources-financieres');
    
    // ÉTAPE 2 - Effectifs
    Route::post('rapport-rentree/{rapport}/effectifs', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveEffectifs'])
        ->name('rapport-rentree.save-effectifs');

    // ÉTAPE 3 - Examens
    Route::post('rapport-rentree/{rapport}/recrutement-ci', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveRecrutementCi'])
        ->name('rapport-rentree.save-recrutement-ci');
    Route::post('rapport-rentree/{rapport}/cmg', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveCmg'])
        ->name('rapport-rentree.save-cmg');
    Route::post('rapport-rentree/{rapport}/cfee', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveCfee'])
        ->name('rapport-rentree.save-cfee');
    Route::post('rapport-rentree/{rapport}/entree-sixieme', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveEntreeSixieme'])
        ->name('rapport-rentree.save-entree-sixieme');

    // ÉTAPE 4 - Personnel Enseignant
    Route::post('rapport-rentree/{rapport}/personnel', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'savePersonnel'])
        ->name('rapport-rentree.save-personnel');

    // ÉTAPE 5 - Matériel Pédagogique
    Route::post('rapport-rentree/{rapport}/materiel', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveMateriel'])
        ->name('rapport-rentree.save-materiel');
    Route::post('rapport-rentree/{rapport}/materiel-didactique', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveMaterielDidactique'])
        ->name('rapport-rentree.save-materiel-didactique');
    Route::post('rapport-rentree/{rapport}/manuels-eleves', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveManuelsEleves'])
        ->name('rapport-rentree.save-manuels-eleves');
    Route::post('rapport-rentree/{rapport}/manuels-maitre', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveManuelsMaitre'])
        ->name('rapport-rentree.save-manuels-maitre');
    Route::post('rapport-rentree/{rapport}/dictionnaires', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveDictionnaires'])
        ->name('rapport-rentree.save-dictionnaires');
    Route::post('rapport-rentree/{rapport}/geometrie', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveGeometrie'])
        ->name('rapport-rentree.save-geometrie');
    Route::post('rapport-rentree/{rapport}/mesure', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveMesure'])
        ->name('rapport-rentree.save-mesure');

    // ÉTAPE 6 - Infrastructure & Équipements
    Route::post('rapport-rentree/{rapport}/capital-immobilier', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveCapitalImmobilier'])
        ->name('rapport-rentree.save-capital-immobilier');
    Route::post('rapport-rentree/{rapport}/capital-mobilier', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveCapitalMobilier'])
        ->name('rapport-rentree.save-capital-mobilier');
    Route::post('rapport-rentree/{rapport}/equipements-informatiques', [\App\Http\Controllers\Etablissement\RapportRentreeController::class, 'saveEquipementsInformatiques'])
        ->name('rapport-rentree.save-equipements-informatiques');
});
