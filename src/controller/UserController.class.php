<?php
require_once './src/model/LdapConnexion.class.php';
require_once './src/model/ConnexionDB.class.php';
class UserController
{
    private $activeDirectory;
    private $baseDonnee;

    public function __construct(){
        $this->activeDirectory = new LdapConnexion("192.168.200.1",389,"dc=groupe5,dc=lan"); //on instancie la classe ldapConnexion
        $this->baseDonnee = new ConnexionDB(); //on instancie la classe ConnexionDB
    }

    public function connexionAD($name,$mdp) //methode pour la connexion a l'active directory
    {
        $nameNettoye = htmlspecialchars($name); //eviter le XSS
        $mdpNettoye = htmlspecialchars($mdp);
        if(!empty($nameNettoye) && !empty($mdpNettoye)) //si les variables ne sont pas vide
        {
            $result = $this->activeDirectory->authentification($nameNettoye,$mdpNettoye); //on appelle le modele ldapConnexion avec sa methode authentification
            if ($result) //si l'user existe
            {
                $result_admin = $this->baseDonnee->verifier_admin($nameNettoye); //on appelle le modele ConnexionDB avec sa methode verifier_admin
                if ($result_admin) //si l'user est admin
                {
                    $_SESSION['username'] = $nameNettoye;
                    $_SESSION['user_logged_admin'] = true;
                    $_SESSION['page'] = 'gestion.php';
                    header("Location: ./Administrateur/index.php");
                    exit();
                }
                elseif (!$result_admin){
                    $_SESSION['username'] = $nameNettoye;
                    $_SESSION['user_logged_employe'] = true;
                    $_SESSION['page'] = 'horaire.php';
                    header('Location: ./Employe/index.php');
                    exit();

                }
                else{
                    header("Location: ./index.php?page=login.php");
                    exit();
                }

            }
            else{
                $this->connexionDB($nameNettoye,$mdpNettoye);
            }
        }
    }


    public function connexionDB($user,$mdp){
        $userNettoye = htmlspecialchars($user);
        $mdpNettoye = htmlspecialchars($mdp);
        if(!empty($userNettoye) && !empty($mdpNettoye)){
            if ($this->baseDonnee->connexion($userNettoye,$mdpNettoye)){
                if ($this->baseDonnee->verifier_admin($userNettoye))
                {
                    $_SESSION['username'] = $userNettoye;
                    $_SESSION['user_logged_admin'] = true;
                    $_SESSION['page'] = 'gestion.php';
                    header("Location: ./Administrateur/index.php");
                    exit();
                }
                elseif (!$this->baseDonnee->verifier_admin($userNettoye)){
                    $_SESSION['username'] = $userNettoye;
                    $_SESSION['user_logged_employe'] = true;
                    $_SESSION['page'] = 'horaire.php';
                    header('Location: ./Employe/index.php');
                    exit();

                }
            }
            else{
                header("Location: ./index.php?page=login.php");
                exit();

            }
        }

    }

}