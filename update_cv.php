<?php
session_start();

require __DIR__ . '/shared.php';

if (!isset($_GET['id'])) {
    http_not_found();
    exit;
}

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $bdd->prepare('SELECT * FROM ' . $link_gs . '_cv WHERE id = ?');
$stmt->execute([$_GET['id']]);
if (!($cv = $stmt->fetch())) {
    http_not_found();
} else if ($cv['user_id'] != $_SESSION['id']) {
    http_forbidden();
}

if (isset($_POST['submit'])) {
    $bdd->beginTransaction();
    $stmt = $bdd->prepare('UPDATE ' . $link_gs . '_cv SET nom = :nom, date_maj = NOW() WHERE id = :id');
    $stmt->execute([
        'id' => $_GET['id'],
        'nom' => $_POST['cv']['nom'],
    ]);
    if (is_array($_POST['cv']['experiences'])) { # fait avant ?
        $bdd->query('DELETE FROM ' . $link_gs . '_cv_experiences WHERE cv_id = ' . intval($_GET['id']) . ' AND experience_id NOT IN(' . implode(',', array_map('intval', $_POST['cv']['experiences'])) . ')');
    }
    $stmt = $bdd->prepare('INSERT IGNORE INTO ' . $link_gs . '_cv_experiences(cv_id, experience_id) VALUES(:cv_id, :experience_id)');
    $stmt->bindValue('cv_id', $_GET['id'], PDO::PARAM_INT);
    $stmt->bindParam('experience_id', $exp, PDO::PARAM_INT);
    foreach ($_POST['cv']['experiences'] as $exp) {
        $stmt->execute();
    }
    $bdd->commit();

    header('Location: .');
    exit;
}

# TODO: DRY avec create_cv.php

require __DIR__ . '/header.php';

$stmt = $bdd->prepare(<<<EOS
    SELECT *
        FROM {$link_gs}_experiences e
        LEFT JOIN {$link_gs}_cv_experiences se
            ON e.id = se.experience_id AND cv_id = ?
        WHERE user_id = ?
        ORDER BY df_employeur IS NOT NULL, df_employeur
EOS
);
$stmt->execute([$_GET['id'], $_SESSION['id']]);
?>

<form method="POST">
    <p>
        Nom : <input type="text" name="cv[nom]" value="<?= htmlspecialchars($_POST['cv']['nom'] ?? $cv['nom']) ?>">
    </p>
    <?php foreach ($stmt as $exp): ?>
        <p>
            <input type="checkbox" name="cv[experiences][]" value="<?= $exp['id'] ?>" <?= !is_null($exp['experience_id'])/*isset($_POST['cv']['experiences']) && is_array($_POST['cv']['experiences']) && in_array($exp['id'], $_POST['cv']['experiences'])*/ ? 'checked' : '' ?> id="experience_<?= $exp['id'] ?>">
            <label for="experience_<?= $exp['id'] ?>">
                <?= htmlspecialchars($exp['dd_employeur']) ?> - <?= htmlspecialchars($exp['df_employeur'] ?? 'maintenant') ?> : <?= htmlspecialchars($exp['employeur']) ?>
            </label>
        </p>
    <?php endforeach ?>
    <input type="submit" name="submit" value="Modifier">
</form>
