<?php
namespace App\Models;

use Core\Database;

class ScoreModel
{
    public function save(int $playerId, int $pairs, int $timeSeconds, int $moves): int
    {
        $pdo = Database::getPdo();
        $stmt = $pdo->prepare('INSERT INTO scores (player_id, pairs, time_seconds, moves) VALUES (:player_id, :pairs, :time_seconds, :moves)');
        $stmt->execute([
            'player_id' => $playerId,
            'pairs' => $pairs,
            'time_seconds' => $timeSeconds,
            'moves' => $moves
        ]);
        return (int)$pdo->lastInsertId();
    }

    public function top10(): array
    {
        $sql = "SELECT p.name, s.pairs, s.time_seconds, s.moves, s.created_at
                FROM scores s
                JOIN players p ON p.id = s.player_id
                ORDER BY s.time_seconds ASC, s.moves ASC
                LIMIT 10";

        $stmt = Database::getPdo()->query($sql);
        return $stmt->fetchAll();
    }

    public function byPlayer(int $playerId): array
    {
        $stmt = Database::getPdo()->prepare('SELECT id, pairs, time_seconds, moves, created_at FROM scores WHERE player_id = :player_id ORDER BY created_at DESC');
        $stmt->execute(['player_id' => $playerId]);
        return $stmt->fetchAll();
    }
}
