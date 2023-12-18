<?php
require __DIR__.'../model/LdapConnexion.class.php';



class UserController
{
    private $activeDirectory;

    public function __construct(){
        $this->activeDirectory = new LdapConnexion("192.168.200.1",389,"dc=SERVEUR_GR5,dc=lan");
    }

    public function connexion($name,$mdp)
    {
        $nameNettoye = htmlspecialchars($name); //eviter le XSS
        $mdpNettoye = htmlspecialchars($mdp);
        if(!empty($nameNettoye) && !empty($mdpNettoye)) //si les variables ne sont pas vide
        {
             $result = $this->activeDirectory->authentification($nameNettoye,$mdpNettoye);
             if ($result === true)
             {
                 //TODO utilisateur connecte en tant que admin
             }
             elseif ($result === false){
                 //TODO Utilisateur connecte en tant qu'employe
             }
             else{
                 //TODO Utilisateur inconnu a l'AD
             }
        }
    }
}