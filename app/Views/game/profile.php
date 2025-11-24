<?php
/**
 * Vue : profil joueur
 * Variables attendues : $player, $scores
 */
?>
<h1><?= htmlspecialchars($title ?? 'Profil') ?></h1>

<h2><?= htmlspecialchars($player['name']) ?></h2>
<p>Inscrit le <?= htmlspecialchars($player['created_at']) ?></p>

<h3>Meilleurs scores</h3>
<?php if (empty($scores)): ?>
  <p>Aucun score pour le moment.</p>
<?php else: ?>
  <table>
    <thead><tr><th>Date</th><th>Pairs</th><th>Temps (s)</th><th>Coups</th></tr></thead>
    <tbody>
      <?php foreach ($scores as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['created_at']) ?></td>
          <td><?= (int)$s['pairs'] ?></td>
          <td><?= (int)$s['time_seconds'] ?></td>
          <td><?= (int)$s['moves'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<p><a href="/game">Rejouer</a> • <a href="/game/leaderboard">Classement</a></p>
