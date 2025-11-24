<?php
// Petit script CLI pour créer la base/tables requises par l'application
require __DIR__ . '/../vendor/autoload.php';

use Core\Database;

echo "Exécution du script SQL 'mini-mvc.sql'...\n";

try {
    $pdo = Database::getPdo();
    $sqlFile = __DIR__ . '/../mini-mvc.sql';
    if (!file_exists($sqlFile)) {
        throw new RuntimeException("Fichier mini-mvc.sql introuvable: $sqlFile");
    }

    $content = file_get_contents($sqlFile);
    // Essayer d'exécuter le fichier SQL en une seule fois (plus fiable pour les déclarations USE/FOREIGN KEY)
    try {
        $pdo->exec($content);
    } catch (PDOException $e) {
        // Si l'exécution en bloc échoue (certaines versions/paramètres), retomber sur le découpage simple
        echo "Échec de l'exécution en une seule fois, tentative de découpage: " . $e->getMessage() . "\n";
        $statements = array_filter(array_map('trim', explode(';', $content)));

        foreach ($statements as $stmt) {
            if ($stmt === '') continue;
            // Ignore les lignes de commentaires seules
            if (strpos($stmt, '--') === 0 || strpos($stmt, '/*') === 0) continue;
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                // Affiche l'erreur mais continue (utile si DB existe déjà)
                echo "Erreur pour une instruction SQL: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "Import SQL terminé. Vérifiez la base 'mvc' et les tables 'players' et 'scores'.\n";
} catch (Exception $e) {
    echo 'Erreur: ' . $e->getMessage() . "\n";
    exit(1);
}
