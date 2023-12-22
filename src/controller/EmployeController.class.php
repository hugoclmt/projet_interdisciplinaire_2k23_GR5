<?php
//controlleur de la classe EmployeModel

require_once '../src/model/EmployeModel.class.php';

class EmployeController
{
    private $modelemploye;

    public function __construct(){
        $this->modelemploye = new EmployeModel();
    }

    public function demander_conge($date,$justificatif,$id_employe){ //methode pour demander un conge
        $justificatifNettoye = htmlspecialchars($justificatif); //nettoyage des données
        if (!empty($id_employe) && !empty($date) && !empty($justificatifNettoye))
        {
            $result = $this->modelemploye->demander_conge($date,$justificatifNettoye,$id_employe); //appel de la methode demander_conge du modeleemploye
            if ($result)
            {
                return "Demande de congé envoyée";
            }
            else{
                return "Erreur lors de l'envoi de la demande";
            }
        }
    }
    public function prevenir_maladie($date,$justificatif,$id_employe)
    {
        $justificatif = htmlspecialchars($justificatif); //nettoyage des données
        if (!empty($id_employe) && !empty($date) && !empty($justificatif))
        {
            $result = $this->modelemploye->prevenir_maladie($date,$justificatif,$id_employe); //appel de la methode prevenir_maladie du modeleemploye
            if ($result)
            {
                return "jour de maladie envoyé";
            }
            else{
                return "Erreur lors de l'envoi de la demande";
            }
        }
    }
    public function voir_confirm_conge($id_employe,$date){ //methode pour voir si le conge a été accepté ou refusé
        $id = htmlspecialchars($id_employe); //nettoyage des données
        $dateNettoye = htmlspecialchars($date); //nettoyage des données
        $result = $this->modelemploye->voir_confirm_conge($id,$dateNettoye); //appel de la methode voir_confirm_conge du modeleemploye
        if ($result!=null) //si le resultat n'est pas null
        {
            return $result; //on retourne le resultat
        }
        else{
            return null; //sinon on retourne null
        }
    }

    public function recuperer_horaire($id,$week) //methode pour recuperer les horaires
    {
        $horaire = $this->modelemploye->recuperer_horaire($id,$week); //appel de la methode recuperer_horaire du modeleemploye
        if ($horaire!=null){
            return $horaire;
        }
        else{
            return null;
        }
    }

    public function get_id($username) //methode pour recuperer l'id de l'employe avec son username
    {
        $name = htmlspecialchars($username); //nettoyage des données
        $result = $this->modelemploye->get_id($name); //appel de la methode get_id du modeleemploye
        if ($result == null)
        {
            return "Aucun utilisateur trouvé";
        }
        else{
            return $result;
        }
    }

    public function recuperer_all_heures($id,$date_debut,$date_fin) //methode pour recuperer toutes les heures de l'employe
    {
        $heures = $this->modelemploye->get_all_hours($id,$date_debut,$date_fin); //appel de la methode get_all_hours du modeleemploye
        if ($heures!=null){
            return $heures;
        }
        else{
            return null;
        }
    }
}