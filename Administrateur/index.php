<?php
session_start();
require_once '../src/controller/AdministrateurController.class.php';
require_once '../src/controller/EmployeController.class.php';
if (!isset($_SESSION['user_logged_admin']))
{
    $_SESSION['page'] = "login.php";
    header("Location: ../index.php");
    exit();
}
if(isset($_SESSION['user_logged_employe']))
{
    $_SESSION['page'] = "horaire.php";
    header('Location: ../Employe/index.php');
}
if (empty($_SESSION['page']))
{
    $_SESSION['page'] = 'gestion.php';
}
$controlleur_admin = new AdministrateurController();
$controlleur_employe = new EmployeController();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<link href="../lib/css/style1.css?v=vvvv" rel="stylesheet">
</head>
<header>
    <?php
    if (file_exists('./lib/php/menu_nav.php'))
    {
        include "./lib/php/menu_nav.php";
    }
    ?>
</header>
<body>
<?php
if(!isset($_SESSION['page'])){ //si la variable sess de page n'a pas été définie
    $_SESSION['page'] = "gestion.php";
}
if (isset($_GET['page'])){ // si une variable page est recupere dans l'url
    $_SESSION['page'] = $_GET['page']; //On la stocke dans $_Session
}
$path = './view/'.$_SESSION['page']; //On cree le chemin vers la page stocké dans ./view
if (file_exists($path)) //Si le fichier existe
{
    include $path; //On l'inclut
}else{
    include ('./view/404.php'); //Si pas on inclut l'erreur
}

?>
</body>
<footer>
    <?php
    if (file_exists('../lib/php/footer/footer.php')){//si footer existe on l'inclut
        include ('../lib/php/footer/footer.php');
    }
    ?>
</footer>
</html>

