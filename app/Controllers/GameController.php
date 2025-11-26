<?php
namespace App\Controllers;

use Core\BaseController;
use App\Models\PlayerModel;
use App\Models\ScoreModel;

class GameController extends BaseController
{
    public function index(): void
    {
        $this->render('game/index', ['title' => 'Memory - Nouveau jeu']);
    }

    public function play(): void
    {
        $pairs = (int)($_GET['pairs'] ?? 3);
        $pairs = max(3, min(12, $pairs));

        $playerName = trim((string)($_GET['player'] ?? 'Guest')) ?: 'Guest';

        // Génération des valeurs de cartes : utiliser des émojis uniques par paire
        $symbols = [
            '🐶','🐱','🐭','🐹','🐰','🦊','🐻','🐼','🐨','🐯','🦁','🐮',
            '🐷','🐸','🐵','🐔','🐧','🦄','🐢','🐙','🦋','🌼','🍎','🍓'
        ];
        shuffle($symbols);
        $selected = array_slice($symbols, 0, $pairs);

        // Dupliquer et mélanger
        $deck = [];
        foreach ($selected as $s) {
            $deck[] = $s;
            $deck[] = $s;
        }
        shuffle($deck);

        // Convert to JSON for the frontend
        $deckJson = json_encode(array_values($deck));

        $this->render('game/play', [
            'title' => 'Jouer',
            'pairs' => $pairs,
            'player' => $playerName,
            'deckJson' => $deckJson
        ]);
    }

    public function save(): void
    {
        $player = trim((string)($_GET['player'] ?? 'Guest')) ?: 'Guest';
        $pairs = (int)($_GET['pairs'] ?? 3);
        $time = (int)($_GET['time'] ?? 0);
        $moves = (int)($_GET['moves'] ?? 0);

        $playerModel = new PlayerModel();
        $scoreModel = new ScoreModel();

        $playerId = $playerModel->createIfNotExists($player);
        $scoreModel->save($playerId, $pairs, $time, $moves);

        // Après sauvegarde, on redirige vers le profil du joueur
        header('Location: /game/profile?id=' . $playerId);
        exit;
    }

    public function leaderboard(): void
    {
        $scoreModel = new ScoreModel();
        $top = $scoreModel->top10();
        $this->render('game/leaderboard', [
            'title' => 'Classement',
            'top' => $top
        ]);
    }

    public function profile(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $playerModel = new PlayerModel();
        $scoreModel = new ScoreModel();

        $player = $playerModel->findById($id);
        if (!$player) {
            echo "Joueur introuvable";
            return;
        }

        $scores = $scoreModel->byPlayer($id);

        $this->render('game/profile', [
            'title' => 'Profil - ' . $player['name'],
            'player' => $player,
            'scores' => $scores
        ]);
    }
}
