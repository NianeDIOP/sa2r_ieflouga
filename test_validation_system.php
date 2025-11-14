<?php
/**
 * SCRIPT TEST VALIDATION RAPPORT
 * Vérifie que l'établissement 3711747581 est bien nettoyé et prêt
 */

require_once 'vendor/autoload.php';

// Configuration
$pdo = new PDO('mysql:host=127.0.0.1;dbname=sa2r_ieflouga', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
]);

$etablissementCode = '3711747581';

echo "🧪 TEST VALIDATION SYSTÈME\n";
echo "========================\n\n";

// 1. Vérifier établissement
$stmt = $pdo->prepare("SELECT etablissement, commune, zone FROM etablissements WHERE code = ?");
$stmt->execute([$etablissementCode]);
$etab = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$etab) {
    echo "❌ Établissement $etablissementCode non trouvé\n";
    exit(1);
}

echo "🏫 Établissement: {$etab['etablissement']}\n";
echo "📍 Localisation: {$etab['commune']} - {$etab['zone']}\n\n";

// 2. Vérifier rapports nettoyés  
$stmt = $pdo->prepare("SELECT id FROM etablissements WHERE code = ?");
$stmt->execute([$etablissementCode]);
$etablissementId = $stmt->fetchColumn();

$stmt = $pdo->prepare("
    SELECT r.id, r.statut, r.annee_scolaire
    FROM rapports r 
    WHERE r.etablissement_id = ?
");
$stmt->execute([$etablissementId]);
$rapports = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "📊 STATUT RAPPORTS:\n";
foreach ($rapports as $rapport) {
    $color = $rapport['statut'] === 'brouillon' ? '✅' : '⚠️';
    echo "   {$color} Rapport {$rapport['id']} ({$rapport['annee_scolaire']}): {$rapport['statut']}\n";
}
echo "\n";

// 3. Vérifier données nettoyées
$tables = [
    'rapport_info_directeur' => 'directeur_nom',
    'rapport_effectifs_classe' => 'effectif_total',
    'rapport_infrastructures_base' => 'cpe_nombre',
    'rapport_personnel_enseignant' => 'nombre_titulaires'
];

$totalRecords = 0;
echo "🧹 VÉRIFICATION NETTOYAGE:\n";

foreach ($tables as $table => $sampleColumn) {
    $stmt = $pdo->prepare("
        SELECT COUNT(*) as count 
        FROM $table rt 
        JOIN rapports r ON rt.rapport_id = r.id 
        WHERE r.etablissement_id = ?
    ");
    $stmt->execute([$etablissementId]);
    $count = $stmt->fetchColumn();
    
    $status = $count == 0 ? '✅' : '⚠️';
    echo "   $status $table: $count enregistrements\n";
    $totalRecords += $count;
}

echo "\n";

// 4. Résultat final
if ($totalRecords == 0) {
    echo "🎉 SUCCÈS: Environnement propre pour test validation!\n";
    echo "\n📋 ÉTAPES DE TEST:\n";
    echo "1. Ouvrir: http://127.0.0.1:8000/etablissement/rapport-rentree\n";
    echo "2. Tester champ vide 'Nom directeur' → Erreur attendue\n";
    echo "3. Tester téléphone '12345' → Format invalide\n";
    echo "4. Tester effectifs incohérents → Validation mathématique\n";
    echo "5. Vérifier modale d'erreur s'affiche correctement\n\n";
    
    echo "🔗 Documentation: VALIDATION_SYSTEM.md\n";
} else {
    echo "⚠️ ATTENTION: $totalRecords enregistrements restants\n";
    echo "Relancer: php clean_test_data.php\n";
}

echo "\n🚀 Test system ready!\n";
?>