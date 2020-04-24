<?php
session_start();

require __DIR__ . '/shared.php';

if (!isset($_GET['id'])) {
    # paramètre attendu
    http_not_found();
}

$stmt = $bdd->prepare('SELECT * FROM ' . $link_gs . '_cv WHERE id = ?');
$stmt->execute([$_GET['id']]);
if (!($cv = $stmt->fetch())) {
    http_not_found();
}
$stmt = $bdd->prepare(<<<EOS
    SELECT *
      FROM {$link_gs}_experiences AS e
      INNER JOIN {$link_gs}_cv_experiences AS se -- Selected Experiences
          ON se.experience_id = e.id
      WHERE se.cv_id = ?
      ORDER BY e.df_employeur IS NOT NULL, e.df_employeur
EOS
);
$stmt->execute([$_GET['id']]);

require __DIR__ . '/header.php';
?>

<h1>CV <?= htmlspecialchars($cv['nom']) ?></h1>

<h2>Compétences</h2>

<p>TODO</p>

<h2>Expériences professionnelles</h2>

<ul>
    <?php foreach ($stmt as $exp): ?>
        <li>De <?= htmlspecialchars($exp['dd_employeur']) ?> à <?= htmlspecialchars($exp['df_employeur'] ?? 'maintenant') ?> : <?= htmlspecialchars($exp['employeur']) ?> (<?= htmlspecialchars($exp['employeur_ville']) ?>)</li>
    <?php endforeach ?>
</ul>

<h2>Formations</h2>

<p>TODO</p>
