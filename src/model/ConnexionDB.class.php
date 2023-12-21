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
<<<<<<< HEAD


=======
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
>>>>>>> ecd35c251e7541b9ce9f722ac6df9f53916132cd

    private function hash($mdp)
    {
        return hash('sha256',$mdp);
    }

}