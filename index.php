<?php
session_start();
require __DIR__.'/src/controller/AdministrateurController.class.php';
require __DIR__.'/src/controller/UserController.class.php';
$controller = new UserController()
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Centerpark</title>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="lib/css/style1.css?v=vvv" rel="stylesheet">
    <link rel="icon" href="images/icone.png">
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
    $_SESSION['page'] = "horaire.php"; //on la cree -> donc page d'accueil quand on arrive
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
