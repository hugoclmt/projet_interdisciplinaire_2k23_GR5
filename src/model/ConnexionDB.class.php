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
        $query = "SELECT admin FROM employes WHERE identifiant = :user";
        $sql = $this->db->prepare($query);
        $sql->bindValue(':user',$user);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        echo 'eqsdqds '.$result['admin'];
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
    private function verifier_admin($identifiant,$mdp)
    {
        $query = "SELECT admin FROM employes WHERE identifiant=:identifiant";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':identifiant',$identifiant);
        $resultset->execute();
        $result = $resultset->fetch();
        if ($result['admin'] == 1)
        {
            return true;
        }
        else{
            return false;
        }
    }

    private function hash($mdp)
    {
        return hash('sha256',$mdp);
    }

}