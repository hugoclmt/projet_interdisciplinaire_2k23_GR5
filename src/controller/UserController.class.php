<?php
require_once './src/model/LdapConnexion.class.php';
require_once './src/model/ConnexionDB.class.php';
class UserController
{
    private $activeDirectory;
    private $baseDonnee;

    public function __construct(){
        $this->activeDirectory = new LdapConnexion("192.168.200.1",389,"dc=groupe5,dc=lan");
        $this->baseDonnee = new ConnexionDB();
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
                 header('Location: ./Employe/index.php');
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


    public function connexionDB($user,$mdp){
        $userNettoye = htmlspecialchars($user);
        $mdpNettoye = htmlspecialchars($mdp);
        if(!empty($userNettoye) && !empty($mdpNettoye))
        {
<<<<<<< HEAD
            $result = $this->baseDonnee->connexion($userNettoye,$mdpNettoye);
            $_SESSION['result'] = $result;
            if ($result === true)
            {
                $_SESSION['username'] = $userNettoye;
                $_SESSION['user_logged_admin'] = true;
                $_SESSION['page'] = 'gestion.php';
                header("Location: ./Administrateur/index.php");
                exit();
            }
            elseif ($result === false){
                $_SESSION['username'] = $userNettoye;
                $_SESSION['user_logged_employe'] = true;
                $_SESSION['page'] = 'horaire.php';
                header('Location: ./Employe/index.php');
                exit();
                //TODO Utilisateur connecte en tant qu'employe
            }
            else{
                header("Location: ../index.php?page=login.php");
                exit();
                //TODO Utilisateur inconnu a l'AD
            }
=======
            $_SESSION['username'] = $usernettoye;
            $_SESSION['user_logged_admin'] = true;
            $_SESSION['page'] = 'gestion.php';
            header("Location: ./Administrateur/index.php");
            exit();
        }
        elseif ($result === false){
            $_SESSION['username'] = $usernettoye;
            $_SESSION['user_logged_employe'] = true;
            $_SESSION['page'] = 'horaire.php';
            header('Location: ./Employe/index.php');
            exit();
        }else{
            header("Location: ../index.php?page=login.php");
            exit();
>>>>>>> ecd35c251e7541b9ce9f722ac6df9f53916132cd
        }
    }

}