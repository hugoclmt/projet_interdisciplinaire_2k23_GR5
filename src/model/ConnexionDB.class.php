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
        $query = "SELECT mdp FROM employes WHERE identifiant=:identifiant";
        $sql = $this->db->prepare($query);
        $sql->bindValue(':identifiant',$user);
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_OBJ);
        while ($result=$sql->fetch() ) {
            if($result->mdp==hash('sha256',$mdp)){
                return true;
            }
            else{
                return false;
            }
        }
        return null;
    }
    public function verifier_admin($identifiant)
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