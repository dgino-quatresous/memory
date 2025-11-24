<?php
require __DIR__ . '/../vendor/autoload.php';

$pdo = Core\Database::getPdo();
$stmt = $pdo->query('SHOW TABLES');
$rows = $stmt->fetchAll(PDO::FETCH_NUM);
if (!$rows) {
    echo "Aucune table trouvée\n";
    exit;
}
foreach ($rows as $r) {
    echo implode(' | ', $r) . PHP_EOL;
}
