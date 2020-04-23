<?php
/* <TEMPORARY> */
$link_gs = '';
$bdd = new PDO("mysql:host=localhost;dbname={$_SERVER['DB_NAME']};charset=utf8", $_SERVER['DB_LOGIN'], $_SERVER['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
/* </TEMPORARY> */
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
