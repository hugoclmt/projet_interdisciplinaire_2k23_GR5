<nav>
<div id="logo">
<img src="/../../../img/logoisimscalendar.png" alt="logo">    
<h1>Application Horaires</h1>
</div>
    <?php
        if (isset($_SESSION['user_logged_admin']) || isset($_SESSION['user_logged_employe'])) {
            echo '<ul>';
            // Utilisateur connecté, affiche les boutons
            echo '<li><a href="index.php?page=horaire.php">Horaire</a></li>';
            echo '<li><a href="index.php?page=deconnexion.php">Deconnexion</a></li>';
            echo '</ul>';
        }
    ?>
</nav>
