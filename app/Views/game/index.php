<?php
/**
 * Vue : choix du nombre de paires et nom du joueur
 */
?>
<h1><?= htmlspecialchars($title ?? 'Memory') ?></h1>

<form action="/game/play" method="get">
  <label for="player">Nom du joueur</label>
  <input id="player" name="player" type="text" placeholder="Votre nom" />

  <label for="pairs">Nombre de paires</label>
  <select id="pairs" name="pairs">
    <?php for ($i = 3; $i <= 12; $i++): ?>
      <option value="<?= $i ?>"><?= $i ?> paires</option>
    <?php endfor; ?>
  </select>

  <button type="submit">Jouer</button>
</form>

<p>
  <a href="/game/leaderboard">Voir le classement</a>
</p>
