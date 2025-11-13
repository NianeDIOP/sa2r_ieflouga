<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== DEBUG MANUELS ÉLÈVES ===\n";

// 1. Vérifier si le modèle existe
echo "1. Modèle RapportManuelsEleves existe: " . (class_exists('App\Models\RapportManuelsEleves') ? 'OUI' : 'NON') . "\n";

// 2. Vérifier la table
try {
    $count = App\Models\RapportManuelsEleves::count();
    echo "2. Nombre d'enregistrements dans la table: $count\n";
} catch (Exception $e) {
    echo "2. Erreur table: " . $e->getMessage() . "\n";
}

// 3. Vérifier les données d'un rapport spécifique
try {
    $rapport = App\Models\Rapport::find(1);
    if ($rapport) {
        echo "3. Rapport ID 1 existe: OUI\n";
        $manuels = $rapport->manuelsEleves;
        echo "4. Nombre de manuels liés: " . $manuels->count() . "\n";
        
        if ($manuels->count() > 0) {
            echo "5. Détail des manuels:\n";
            foreach ($manuels as $manuel) {
                echo "   - Niveau: {$manuel->niveau}, Français: {$manuel->lc_francais}, Maths: {$manuel->mathematiques}\n";
            }
        } else {
            echo "5. Aucun manuel trouvé pour ce rapport\n";
        }
    } else {
        echo "3. Rapport ID 1 n'existe pas\n";
    }
} catch (Exception $e) {
    echo "3. Erreur rapport: " . $e->getMessage() . "\n";
}

// 4. Tester la relation
try {
    echo "6. Test de la relation hasMany:\n";
    $rapports = App\Models\Rapport::with('manuelsEleves')->get();
    foreach ($rapports as $r) {
        echo "   - Rapport {$r->id}: " . $r->manuelsEleves->count() . " manuels\n";
    }
} catch (Exception $e) {
    echo "6. Erreur relation: " . $e->getMessage() . "\n";
}