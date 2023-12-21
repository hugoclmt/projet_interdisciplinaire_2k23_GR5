<?php
include 'ParentAbstraite.php';
class EmployeModel extends ParentAbstraite
{
    public function __construct()
    {
        parent::__construct();
    }

    public function demander_conge($date_conge,$justificatif,$id_employe) //fct pour employe qui demande conge. date debut conge et date fin conge
    {
        try{
            $statut = "En attente"; //on declare la variable statut en attente ->confirmation de l'administateur
            $query = "INSERT INTO conge (date_conge,congeconfirm,justification,id_employe) VALUES (:date_conge,:congeconfirm,:justification,:id_employe)"; //rqt prepare pour vesqui injection sql
            $resultset = $this->db->prepare($query); //on prep
            $resultset->bindValue(':date_conge',$date_conge->format('Y-m-d'));
            $resultset->bindValue(':congeconfirm',$statut);
            $resultset->bindValue(':justification',$justificatif);
            $resultset->bindValue(':id_employe',$id_employe);

            return $resultset->execute(); //on retourne le resultat de la requete si elle a eye bien realisé au pas
        }catch(PDOException $e)
        {

        }
    }


    public function recuperer_horaire($id_employe) //methode pour recuperer les horaires
    {
        $query = "SELECT * FROM jour_horaire WHERE id_employe=:id_employe";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->execute();
        $horaire = $resultset->fetchall(PDO::FETCH_ASSOC);
        if (!empty($horaire))
        {
            return $horaire;
        }
        else{
            return null;
        }
    }

    protected function recuperer_nbre_conge($id_employe)
    {
        $query="SELECT nbre_conges FROM employes WHERE id_employe=:id_employe";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->execute();
        $result = $resultset->fetch(PDO::FETCH_ASSOC);
        if ($result)
        {
            return (int) $result['nbre_conges'];
        }
        else{
            return 0;
        }
    }

    public function get_id($username)
    {
        $query = "SELECT id_employe FROM employes WHERE identifiant=:username";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':username',$username);
        $resultset->execute();
        $result = $resultset->fetch(PDO::FETCH_ASSOC);
        if (!empty($result) && isset($result['id_employe']))
        {
            return $result['id_employe'];
        }
        else{
            return null;
        }
    }

    public function get_all_hours($id_employe)
    {
        $query = "SELECT SUM(nbre_heure) FROM jour_horaire WHERE id_employe=:id_employe";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->execute();
        $result = $resultset->fetch(PDO::FETCH_ASSOC);
        if (!empty($result) && isset($result['SUM(nbre_heure)']))
        {
            return $result['SUM(nbre_heure)'];
        }
        else{
            return null;
        }
    }
}