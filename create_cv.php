<?php
session_start();

require __DIR__ . '/shared.php';

if (!isset($_SESSION['id'])) {
    header('Location: /');
    exit;
}

if (isset($_POST['submit'])) {
    $bdd->beginTransaction();
    $stmt = $bdd->prepare('INSERT INTO ' . $link_gs . '_cv(nom) VALUES(:nom)');
    $stmt->execute([
        'nom' => $_POST['cv']['nom'],
    ]);
    $stmt = $bdd->prepare('INSERT INTO ' . $link_gs . '_cv_experiences(cv_id, experience_id) VALUES(:cv_id, :experience_id)');
    $stmt->bindValue('cv_id', $bdd->lastInsertId(), PDO::PARAM_INT);
    $stmt->bindParam('experience_id', $exp, PDO::PARAM_INT);
    foreach ($_POST['cv']['experiences'] as $exp) {
        $stmt->execute();
    }
    $bdd->commit();
}

$stmt = $bdd->prepare('SELECT * FROM ' . $link_gs . '_experiences WHERE user_id = ? ORDER BY df_employeur IS NOT NULL, df_employeur');
$stmt->execute([$_SESSION['id']]);
?>

<form method="POST">
    <p>
        Nom : <input type="text" name="cv[nom]" value="<?= htmlspecialchars($_POST['cv']['nom'] ?? '') ?>">
    </p>
    <?php foreach ($stmt as $exp): ?>
        <p>
            <input type="checkbox" name="cv[experiences][]" value="<?= $exp['id'] ?>" <?= isset($_POST['cv']['experiences']) && is_array($_POST['cv']['experiences']) && in_array($exp['id'], $_POST['cv']['experiences']) ? 'checked' : '' ?> id="experience_<?= $exp['id'] ?>">
            <label for="experience_<?= $exp['id'] ?>">
                <?= htmlspecialchars($exp['dd_employeur']) ?> - <?= htmlspecialchars($exp['df_employeur'] ?? 'maintenant') ?> : <?= htmlspecialchars($exp['employeur']) ?>
            </label>
        </p>
    <?php endforeach ?>
    <input type="submit" name="submit" value="Créer">
</form>
