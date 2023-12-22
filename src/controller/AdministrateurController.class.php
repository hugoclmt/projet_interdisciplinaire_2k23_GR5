<?php
require_once '../src/model/AdministrateurModel.class.php';

//Classe du controlleur pour l'administrateur
class AdministrateurController
{
    private $admin;

    public function __construct(){
        $this->admin = new AdministrateurModel(); //on instancie l'objet admin
    }

    public function recuperer_demande_conge() //methode pour recupere les demandes de conge de tout les employes
    {
        $liste_conge = $this->admin->get_all_conge(); //on appelle la methode get_all_conge du modele admin
        if ($liste_conge!=null)
        {
            return $liste_conge;
        }else{
            return null;
        }
    }
    public function recuperer_identifiant($id) //methode pour recuperer l'identifiant de l'employe
    {
        $nom = $this->admin->recuperer_identifiant($id); //on appelle la methode recuperer_identifiant du modele admin
        if ($nom!=null)
        {
            return $nom;
        }else{
            return null;
        }
    }

    public function accepter_conge($id_employe,$date) //methode pour accepter le conge de l'employe
    {
        $id = htmlspecialchars($id_employe); //nettoyage des données
        $this->admin->accepter_conge($id_employe,$date); //on appelle la methode accepter_conge du modele admin
    }

    public function refuser_conge($id_employe,$date){ //methode pour refuser le conge de l'employe
        $id =htmlspecialchars($id_employe); //nettoyage des données
        $this->admin->refuser_conge($id,$date); //on appelle la methode refuser_conge du modele admin
    }

    public function recuper_type() //methode pour recuperer les types d'employes
    {
        $types = $this->admin->recuperer_type(); //on appelle la methode recuperer_type du modele admin
        if ($types!=null)
        {
            return $types;
        }else{
            return null;
        }
    }

    public function recuper_personnes_partype($id_type) //methode pour recuperer les personnes d'un type d'employe
    {
        $id = htmlspecialchars($id_type);
        $users = $this->admin->recuperer_employe_type($id); //on appelle la methode recuperer_employe_type du modele admin
        if ($users != null)
        {
            return $users;
        }
        else{
            return null;
        }
    }

    public function rappeler_employe($id_employe,$date,$debut,$fin) //methode pour rappeler un employe
    {   
        $result = $this->admin->rappeller_employe($id_employe,$date,$debut,$fin); //on appelle la methode rappeller_employe du modele admin
        if ($result)
        {
            return "l'utilisateur a ete rapelle";
        }else{
            return "Erreur";
        }
    }

    public function creer_horaire($id_employe,$date,$debut,$fin) //methode pour creer un horaire
    {
        $result = $this->admin->creer_horaire($id_employe,$date,$debut,$fin); //on appelle la methode creer_horaire du modele admin
        if ($result)
        {
            return "l'horaire a ete cree";
        }elseif($result === null){
            return "Erreur 38h deja atteint";
        }else{
            return "Erreur";
        }
    }

    public function recuperer_all_horaire() //methode pour recuperer tout les horaires
    {
        $result = $this->admin->get_all_horaire(); //on appelle la methode get_all_horaire du modele admin
        if ($result)
        {
            return $result;
        }else{
            return null;
        }
    }

    public function recuperer_all_heure($id)
    {
        $nbre = $this->admin->recuperer_nbre_heure($id);
        if (!empty($nbre))
        {
            return $nbre;
        }
        else{
            return null;
        }
    }

}