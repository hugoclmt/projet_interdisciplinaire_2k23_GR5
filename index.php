<?php
session_start(); //demarrer une session
require './src/controller/UserController.class.php';
$controller = new UserController(); //appel du controller
if (isset($_SESSION['user_logged_admin'])) //si l'utilisateur est connecte en tant qu'admin
{
    header('Location: ./Administrateur/index.php'); //on le redirige vers la page admin

}elseif (isset($_SESSION['user_logged_employe'])) //si l'utilisateur est connecte en tant qu'employe
{
    header("Location: ./Employe/index.php"); //on le redirige vers la page employe
}

if(empty($_SESSION['crsf_token'])) //si la session crsf_token est vide
{
    $_SESSION['crsf_token'] = bin2hex(random_bytes(32)); //on cree un token aleatoire
}
$crsf_token = $_SESSION['crsf_token']; //on stock le token dans une variable

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Centerpark</title>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="lib/css/style1.css?v=vvvv" rel="stylesheet">
</head>
<header>
    <?php
    if (file_exists('./lib/php/header/menu_nav.php')) //si le fichier existe dans l'arbo
    {
        include ('./lib/php/header/menu_nav.php'); //on l'inclut
    }
    ?>
</header>
<body>
<?php
if(!isset($_SESSION['page'])){ //si aucune session page n'existe
    $_SESSION['page'] = "login.php"; //on la cree -> donc page d'accueil quand on arrive
}
if (isset($_GET['page'])){ //si dans l'url on a un parametre page on le recuoere
    $_SESSION['page'] = $_GET['page']; //on le stock dans la session page
}
$path = './src/view/'.$_SESSION['page']; //on initialise la variable path avec le chemin
if (file_exists($path)){
    include $path; //on inclut la page si elle existe
}else{
    include ('./src/view/404.php'); //sinon page 404 error
}

?>
</body>
<footer>
    <?php
    if (file_exists('./lib/php/footer/footer.php')){//si footer existe on l'inclut
        include ('./lib/php/footer/footer.php');
    }
    ?>
</footer>
</html>
