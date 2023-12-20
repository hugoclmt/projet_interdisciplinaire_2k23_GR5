<?php
require __DIR__.'/../model/LdapConnexion.class.php';



class UserController
{
    private $activeDirectory;

    public function __construct(){
        $this->activeDirectory = new LdapConnexion("192.168.200.1",389,"dc=SERVEUR_GR5,dc=lan");
    }
    public function connexionAD($name,$mdp)
    {
        $nameNettoye = htmlspecialchars($name); //eviter le XSS
        $mdpNettoye = htmlspecialchars($mdp);
        if(!empty($nameNettoye) && !empty($mdpNettoye)) //si les variables ne sont pas vide
        {
             $result = $this->activeDirectory->authentification($nameNettoye,$mdpNettoye); //on appelle le modele ldapConnexion avec sa methode authentification
             if ($result === true)
             {
                 $_SESSION['username'] = $nameNettoye;
                 $_SESSION['user_logged_admin'] = true;
                 $_SESSION['page'] = 'gestion.php';
                 header("Location: ./Administrateur/index.php");
                 exit();
             }
             elseif ($result === false){
                 $_SESSION['username'] = $nameNettoye;
                 $_SESSION['user_logged_employe'] = true;
                 $_SESSION['page'] = 'horaire.php';
                 header('Location: /Employe/index.php');
                 exit();
                 //TODO Utilisateur connecte en tant qu'employe
             }
             else{
                 header("Location: ../index.php?page=login.php");
                 exit();
                 //TODO Utilisateur inconnu a l'AD
             }
        }
    }
    public function connexion($name,$mdp)
    {
        $nameNettoye = htmlspecialchars($name); //eviter le XSS
        $mdpNettoye = htmlspecialchars($mdp);
        if(!empty($nameNettoye) && !empty($mdpNettoye)) //si les variables ne sont pas vide
        {
             $result = $this->activeDirectory->authentification($nameNettoye,$mdpNettoye); //on appelle le modele ldapConnexion avec sa methode authentification
             if ($result === true)
             {
                 $_SESSION['username'] = $nameNettoye;
                 $_SESSION['user_logged_admin'] = true;
                 $_SESSION['page'] = 'gestion.php';
                 header("Location: __DIR__/../view/gestion.php");
                 exit();
             }
             elseif ($result === false){
                 $_SESSION['username'] = $nameNettoye;
                 $_SESSION['user_logged_employe'] = true;
                 $_SESSION['page'] = 'horaire.php';
                 header('Location: /Employe/index.php');
                 exit();
                 //TODO Utilisateur connecte en tant qu'employe
             }
             else{
                 header("Location: ../index.php?page=login.php");
                 exit();
                 //TODO Utilisateur inconnu a l'AD
             }
        }
    }
}