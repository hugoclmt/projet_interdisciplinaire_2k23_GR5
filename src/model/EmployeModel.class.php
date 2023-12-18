<?php

class EmployeModel
{
    private $db;

    public function __construct()
    {
        $database = new DbModel();
        $this->db = $database->get_pdo();
    }

    public function demander_conge($date_debut,$date_fin) //fct pour employe qui demande conge
    {
        $statut = "En attente"; //on declare la variable statut en attente ->confirmation de l'administateur
        try{
            $query = "INSERT INTO conge (date_debut,date_fin,statut) VALUES (:date_debut,:date_fin,:statut)"; //rqt prepare pour vesqui injection sql
            $resultset = $this->db->prepare($query); //on prep
            $resultset->bindValue(':date_debut',$date_debut); //on remplace les données
            $resultset->bindValue(':date_fin',$date_fin);
            $resultset->bindValue(':statut',$statut);
            return $resultset->execute(); //on retourne le resultat de la requete si elle a eye bien realisé au pas
        }catch(PDOException $e)
        {

        }
    }

    public function prevenir_maladie($id,$date,$justificatif)
    {
        try{
            $query = "INSERT INTO maladie (jour,justificatif,id_employe) VALUES (:jour,:justificatif,:id_employe)"; //on prep la rqt
            $resultset = $this->db->prepare($query); //preparation de la rqt
            $resultset->bindValue(':jour',$date); //on remplace
            $resultset->bindValue(':justificatif',$justificatif);
            $resultset->bindValue(':id_employe',$id);
            return $resultset->execute(); //on retourne le resultat de la requete

        }catch(PDOException $e){

        }
    }
}