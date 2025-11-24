<?php
require __DIR__ . '/../vendor/autoload.php';

$pdo = Core\Database::getPdo();

$players = "CREATE TABLE IF NOT EXISTS players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

$scores = "CREATE TABLE IF NOT EXISTS scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    player_id INT NOT NULL,
    pairs INT NOT NULL,
    time_seconds INT NOT NULL,
    moves INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $pdo->exec($players);
    echo "Table 'players' ensured.\n";
} catch (PDOException $e) {
    echo "Erreur players: " . $e->getMessage() . PHP_EOL;
}

try {
    $pdo->exec($scores);
    echo "Table 'scores' ensured.\n";
} catch (PDOException $e) {
    echo "Erreur scores: " . $e->getMessage() . PHP_EOL;
}
