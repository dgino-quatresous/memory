<?php
/**
 * Vue : classement
 * Variable attendue : $top
 */
?>
<h1><?= htmlspecialchars($title ?? 'Classement') ?></h1>

<?php if (empty($top)): ?>
  <p>Aucun score enregistré pour le moment.</p>
<?php else: ?>
  <ol>
    <?php foreach ($top as $row): ?>
      <li>
        <?= htmlspecialchars($row['name']) ?> — <?= (int)$row['time_seconds'] ?>s — <?= (int)$row['moves'] ?> coups — <?= (int)$row['pairs'] ?> paires
      </li>
    <?php endforeach; ?>
  </ol>
<?php endif; ?>

<p><a href="/game">Jouer</a></p>
