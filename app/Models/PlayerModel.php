<?php
namespace App\Models;

use Core\Database;

class PlayerModel
{
    public function findById(int $id): ?array
    {
        $stmt = Database::getPdo()->prepare('SELECT id, name, created_at FROM players WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByName(string $name): ?array
    {
        $stmt = Database::getPdo()->prepare('SELECT id, name, created_at FROM players WHERE name = :name');
        $stmt->execute(['name' => $name]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $name): int
    {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare('INSERT INTO players (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return (int)$pdo->lastInsertId();
    }

    /**
     * Crée un joueur si non existant et retourne son id
     */
    public function createIfNotExists(string $name): int
    {
        $existing = $this->findByName($name);
        if ($existing) {
            return (int)$existing['id'];
        }
        return $this->create($name);
    }

    /**
     * Retourne les meilleurs joueurs (meilleur temps par joueur)
     */
    public function topPlayers(int $limit = 10): array
    {
        $sql = "SELECT p.id, p.name, MIN(s.time_seconds) AS best_time, MIN(s.moves) AS best_moves
                FROM players p
                JOIN scores s ON p.id = s.player_id
                GROUP BY p.id, p.name
                ORDER BY best_time ASC
                LIMIT :limit";

        $stmt = Database::getPdo()->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
