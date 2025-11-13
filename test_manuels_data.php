<?php

// Test simple pour voir les données manuels élèves
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->boot();

try {
    echo "=== TEST MANUELS ÉLÈVES ===\n\n";
    
    // Récupérer le rapport avec les relations
    $rapport = App\Models\Rapport::with('manuelsEleves')->find(1);
    
    if ($rapport) {
        echo "✅ Rapport trouvé (ID: {$rapport->id})\n";
        echo "📊 Nombre de manuels: " . $rapport->manuelsEleves->count() . "\n\n";
        
        if ($rapport->manuelsEleves->count() > 0) {
            echo "📋 DONNÉES EXISTANTES:\n";
            echo "---------------------\n";
            foreach ($rapport->manuelsEleves as $manuel) {
                echo "🎯 Niveau {$manuel->niveau}:\n";
                echo "   - Français: {$manuel->lc_francais}\n";
                echo "   - Maths: {$manuel->mathematiques}\n";
                echo "   - EDD: {$manuel->edd}\n";
                echo "   - DM: {$manuel->dm}\n";
                echo "   - Total: " . ($manuel->lc_francais + $manuel->mathematiques + $manuel->edd + $manuel->dm) . "\n\n";
            }
        } else {
            echo "❌ AUCUNE DONNÉE trouvée\n";
            echo "➡️ Essayons de créer des données test...\n\n";
            
            // Créer des données test
            $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
            foreach ($niveaux as $niveau) {
                $rapport->manuelsEleves()->updateOrCreate(
                    ['niveau' => $niveau],
                    [
                        'lc_francais' => rand(5, 50),
                        'mathematiques' => rand(5, 50),
                        'edd' => rand(0, 20),
                        'dm' => rand(0, 15)
                    ]
                );
                echo "✅ Créé données test pour {$niveau}\n";
            }
            
            echo "\n🔄 Rechargement des données...\n";
            $rapport->refresh();
            $rapport->load('manuelsEleves');
            echo "📊 Nouveau nombre de manuels: " . $rapport->manuelsEleves->count() . "\n";
        }
        
    } else {
        echo "❌ Aucun rapport trouvé\n";
    }
    
} catch (Exception $e) {
    echo "💥 ERREUR: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
    echo "📂 Fichier: " . $e->getFile() . "\n";
}