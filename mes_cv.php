<?php
session_start();

require __DIR__ . '/shared.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/header.php';
?>

<h1>Liste de mes CV</h1>

<?php
$stmt = $bdd->prepare(<<<EOS
    SELECT *
        FROM {$link_gs}_cv
        WHERE user_id = ?
        ORDER BY date_maj DESC
EOS
);
$stmt->execute([$_SESSION['id']]);
?>

<ul>
    <?php foreach ($stmt as $cv): ?>
        <li><a href="show_cv.php?id=<?= $cv['id'] ?>"><?= htmlspecialchars($cv['nom']) ?></a> (<?= l($cv['date_maj']) ?>) [ <a href="update_cv.php?id=<?= $cv['id'] ?>">Modifier</a> ]</li>
    <?php endforeach ?>
</ul>
