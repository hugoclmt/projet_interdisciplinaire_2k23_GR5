<nav>
    <h1>Applications Horaires</h1>
    <?php
        if (isset($_SESSION['user_logged_admin']) || isset($_SESSION['user_logged_employe'])) {
            echo '<ul>';
            // Utilisateur connecté, affiche les boutons
            echo '<li><a href="index.php?page=horaire.php">Horaire</a></li>';
            echo '<li><a href="index.php?page=gestion.php">Gestion</a></li>';
            echo '<li><a href="index.php?page=horaire_general.php">Horaire Générale</a></li>';
            echo '<li><a href="index.php?page=deconnexion.php">Deconnexion</a></li>';
            echo '</ul>';
        }
    ?>
</nav>

