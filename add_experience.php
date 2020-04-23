<?php
session_start();

/* <TEMPORARY> */
$link_gs = '';
$_SESSION['id'] = 1; # simule une connexion en tant qu'utilisateur "foo"
$bdd = new PDO("mysql:host=localhost;dbname={$_SERVER['DB_NAME']};charset=utf8", $_SERVER['DB_LOGIN'], $_SERVER['DB_PASSWORD'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
/* </TEMPORARY> */

if (!isset($_SESSION['id'])) {
    header('Location: /');
    exit;
}

if(isset($_POST['submit'])) {
    $stmt = $bdd->prepare('INSERT INTO ' . $link_gs . '_experiences(user_id, dd_employeur, df_employeur, employeur_ville, poste, employeur) VALUES(:user_id, :dd_employeur, :df_employeur, :employeur_ville, :poste, :employeur)');
    $stmt->bindValue('user_id', $_SESSION['id']);
    foreach ($_POST['experience'] as $subarray) {
        if (empty($subarray['dd_employeur']) && empty($subarray['employeur_ville']) && empty($subarray['poste']) && empty($subarray['employeur'])) {
            continue;
        }
        $stmt->bindValue('dd_employeur', $subarray['dd_employeur']);
        $stmt->bindValue('df_employeur', empty($subarray['df_employeur']) ? NULL : $subarray['df_employeur']);
        $stmt->bindValue('poste', $subarray['poste']);
        $stmt->bindValue('employeur_ville', $subarray['employeur_ville']);
        $stmt->bindValue('employeur', $subarray['employeur']);
        $stmt->execute();
    }
}
?>

<form method="POST">
    <?php for($i = 0; $i < 10; $i++): ?>
        <div class="form-group">
            <div class="form-row">
                <div class="col-lg-12">
                    Début : <input type="date" class="form-control" name="experience[<?= $i ?>][dd_employeur]" value="<?= htmlspecialchars($_POST['experience'][$i]['dd_employeur'] ?? '') ?>">
                    Fin : <input type="date" class="form-control" name="experience[<?= $i ?>][df_employeur]" value="<?= htmlspecialchars($_POST['experience'][$i]['df_employeur'] ?? '') ?>">
                    Poste : <input type="text" class="form-control" name="experience[<?= $i ?>][poste]" value="<?= htmlspecialchars($_POST['experience'][$i]['poste'] ?? '') ?>">
                    Lieu : <input type="text" class="form-control" name="experience[<?= $i ?>][employeur_ville]" value="<?= htmlspecialchars($_POST['experience'][$i]['employeur_ville'] ?? '') ?>">
                    Employeur : <input type="text" class="form-control" name="experience[<?= $i ?>][employeur]" value="<?= htmlspecialchars($_POST['experience'][$i]['employeur'] ?? '') ?>">
                </div>
            </div>
        </div>
    <?php endfor ?>
    <input type="submit" name="submit" class="btn btn-success btn-sm" value="Ajouter">
</form>
