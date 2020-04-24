<?php
require __DIR__ . '/shared.php';
require __DIR__ . '/header.php';
?>

<h1>Liste des CV créés sur le site</h1>

<?php
$stmt = $bdd->query(<<<EOS
    SELECT *, cv.nom AS cv_nom, u.nom AS u_nom
        FROM {$link_gs}_cv AS cv
        INNER JOIN {$link_gs}_users AS u
            ON cv.user_id = u.id
        ORDER BY cv.date_maj DESC
EOS
);
?>

<ul>
    <?php foreach ($stmt as $cv): ?>
        <li><a href="show_cv.php?id=<?= $cv['id'] ?>"><?= htmlspecialchars($cv['cv_nom']) ?></a> de <?= htmlspecialchars($cv['u_nom']) ?> (<?= l($cv['date_maj']) ?>)</li>
    <?php endforeach ?>
</ul>
