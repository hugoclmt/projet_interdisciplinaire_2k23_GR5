<?php
require_once (__DIR__.'/../model/AdministrateurModel.class.php');

//Classe du controlleur pour l'administrateur
class AdministrateurController
{
    private $admin;

    public function __construct(){
        $this->admin = new AdministrateurModel(); //on instancie l'objet admin
    }

    public function recuperer_demande_conge()
    {
        $liste_conge = $this->admin->get_all_conge();
        if ($liste_conge!=null)
        {
            return $liste_conge;
        }else{
            return null;
        }
    }

    public function accepter_conge($id_employe)
    {
        $id = htmlspecialchars($id_employe);
        $this->admin->accepter_conge($id_employe);
    }

    public function refuser_conge($id_employe){
        $id =htmlspecialchars($id_employe);
        $this->admin->refuser_conge($id);
    }

    public function recuper_type()
    {
        $types = $this->admin->recuperer_type();
        if ($types!=null)
        {
            return $types;
        }else{
            return null;
        }
    }

    public function recuper_personnes_partype($id_type)
    {
        $id = htmlspecialchars($id_type);
        $users = $this->admin->recuperer_employe_type($id);
        if ($users != null)
        {
            return $users;
        }
        else{
            return null;
        }
    }

    public function rappeler_employe($id_employe,$date,$debut,$fin)
    {
        $result = $this->admin->rappeller_employe($id_employe,$date,$debut,$fin);
        if ($result)
        {
            return "l'utilisateur a ete rapelle";
        }else{
            return "Erreur";
        }
    }
}