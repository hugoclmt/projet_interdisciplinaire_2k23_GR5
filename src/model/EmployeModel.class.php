<?php

class EmployeModel 
{
    private $db;

    public function __construct()
    {
        $database = new DbModel();
        $this->db = $database->get_pdo();
    }

    public function demander_conge($date_conge,$justificatif,$id_employe) //fct pour employe qui demande conge. date debut conge et date fin conge
    {
        $statut = "En attente"; //on declare la variable statut en attente ->confirmation de l'administateur
        try{
            $query = "INSERT INTO conge (date_conge,congeconfirm,justification,id_employe) VALUES (:date_conge,:congeconfirm,:justification,:id_employe)"; //rqt prepare pour vesqui injection sql
            $resultset = $this->db->prepare($query); //on prep
            $resultset->bindValue(':date_conge',$date_conge);
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
    public function heures_semaine($id_employe,$num_semaine)
    {
        $query = "SELECT nbre_heure FROM jour_horaire WHERE id_employe=:id_employe AND WEEK(date)=:num_semaine";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':num_semaine',$num_semaine);
        $resultset->execute();
        $somme_heure = $resultset->fetchall(PDO::FETCH_ASSOC);
        $heures_semaines='00:00:00';
        $heures_semaine = date_create($heures_semaines);
        foreach ($somme_heure as $heure) {
            $heure = date_create($heure['nbre_heure']);
        }
        $heures_semaine=array_sum($somme_heure);
        echo get_debug_type($somme_heure[0]['nbre_heure']);
        echo $heures_semaine;


        if (!empty($somme_heure))
        {
            return $somme_heure;
        }
        else{
            return null;
        }
    }
}