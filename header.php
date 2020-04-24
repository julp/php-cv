<nav>
    <ul>
        <li><a href="list_cv.php">Tous les CV publiés</a></li>
        <li><a href="login.php">Changer d'utilisateur</a></li>
        <?php if (isset($_SESSION['id'])): ?>
            <li><a href="mes_cv.php">Mes CV</a></li>
            <li><a href="add_experience.php">Définir mon parcours professionnel</a></li>
            <li><a href="create_cv.php">Créer un nouveau CV</a></li>
        <?php endif ?>
    </ul>
    <?php if (isset($_SESSION['id'])): ?>
        <span><?= htmlspecialchars($_SESSION['nom']) ?></span>
    <?php endif ?>
</nav>
