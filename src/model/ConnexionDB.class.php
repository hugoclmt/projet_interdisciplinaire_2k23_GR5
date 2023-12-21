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
        if (!empty($user)&&!empty($mdp))
        {
            $query = "SELECT * FROM employes WHERE identifiant=:identifiant AND mdp=:password";
            $resultset = $this->db->prepare($query);
            $mdphashe = $this->hash($mdp);
            $resultset->bindValue(':identifiant',$user);
            $resultset->bindValue(':password',$mdphashe);
            $result = $resultset->execute();
            if ($result)
            {
                $success = $this->verifier_admin($user,$mdphashe);
                if ($success)
                {
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return null;
            }

        }
    }
    private function verifier_admin($identifiant,$mdp)
    {
        $query = "SELECT * FROM employes WHERE identifiant=:identifiant AND mdp=:mdp AND admin=:nbre";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':identifiant',$identifiant);
        $resultset->bindValue(':mdp',$mdp);
        $resultset->bindValue(':nbre',1);
        return $resultset->execute();
    }

    private function hash($mdp)
    {
        return hash('sha256',$mdp);
    }

}