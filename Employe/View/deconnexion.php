<?php
$_SESSION = array(); //on vide la session
session_destroy(); //on la detruit
header("Location: ../index.php"); //on redirige vers la page d'accueil
exit();