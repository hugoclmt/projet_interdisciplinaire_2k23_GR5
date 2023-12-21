<?php
require_once 'ParentAbstraite.php';

class ConnexionDB extends ParentAbstraite
{
    public function __construct()
    {
        ParentAbstraite::__construct();
    }

    public function connexion($user,$mdp)
    {
        $query = "SELECT admin FROM employes WHERE identifiant = :user AND mdp = :mdp";
        $sql = $this->db->prepare($query);
        $sql->bindValue(':user',$user);
        $sql->bindValue(':mdp',$mdp);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if ($result['admin'] == 1)
        {
            return true;
        }
        elseif ($result['admin'] == 0)
        {
            return false;
        }
        else{
            return null;
        }

    }



    private function hash($mdp)
    {
        return hash('sha256',$mdp);
    }

}