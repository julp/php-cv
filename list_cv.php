<?php
require __DIR__ . '/shared.php';
?>

<h1>Liste des CV créés sur le site</h1>

<?php
$stmt = $bdd->query('SELECT * FROM ' . $link_gs . '_cv ORDER BY nom'); # TODO: ORDER BY date DESC
?>

<ul>
    <?php foreach ($stmt as $cv): ?>
        <li><a href="show_cv.php?id=<?= $cv['id'] ?>"><?= $cv['nom'] ?></a></li>
    <?php endforeach ?>
</ul>
