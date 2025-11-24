<?php
/**
 * Vue : plateau de jeu
 * Variables attendues : $deckJson, $pairs, $player
 */
?>
<h1><?= htmlspecialchars($title ?? 'Memory') ?></h1>

<div id="game-info">
  <span>Joueur: <strong id="player-name"><?= htmlspecialchars($player) ?></strong></span>
  <span>Pairs: <strong id="pairs-count"><?= $pairs ?></strong></span>
  <span>Temps: <strong id="timer">0</strong>s</span>
  <span>Coups: <strong id="moves">0</strong></span>
</div>

<div id="memory-board" data-deck='<?= $deckJson ?>'></div>

<p>
  <a href="/game">Retour</a>
</p>

<!-- Inclure styles et script du jeu -->
<link rel="stylesheet" href="/asset/memory.css" />
<script>
  // variables rendues côté serveur
  const DECK = <?= $deckJson ?>;
  const PAIRS = <?= (int)$pairs ?>;
  const PLAYER = <?= json_encode($player) ?>;
</script>
<script src="/asset/memory.js"></script>
