<?php
session_start();

# Ce script simule une authentification en permettant de choisir sous quel utilisateur être reconnu et de switcher de l'un à l'autre

require __DIR__ . '/shared.php';

if (isset($_POST['user'])) {
    $stmt = $bdd->prepare('SELECT * FROM ' . $link_gs . '_users WHERE id = ?');
    $stmt->execute([$_POST['user']]);
    if ($user = $stmt->fetch()) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        header('Location: .');
    } else {
        echo "Le compte sélectionné n'existe pas ou plus";
    }
}

$stmt = $bdd->query('SELECT * FROM ' . $link_gs . '_users ORDER BY nom');
?>

<form method="POST">
    <select name="user">
        <?php foreach ($stmt as $user): ?>
            <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['nom']) ?></option>
        <?php endforeach ?>
    </select>
    <input type="submit" name="submit" value="Utiliser">
</form>
