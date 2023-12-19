<?php

class EmployeModel extends ParentAbstraite
{


    public function __construct()
    {
        parent::__construct();
    }

    public function demander_conge($date_conge) //fct pour employe qui demande conge. date debut conge et date fin conge
    {
        $statut = "En attente"; //on declare la variable statut en attente ->confirmation de l'administateur
        try{
            $query = "INSERT INTO conge (date_conge,statut) VALUES (:date_conge,:statut)"; //rqt prepare pour vesqui injection sql
            $resultset = $this->db->prepare($query); //on prep
            $resultset->bindValue(':date_conge',$date_conge); //on remplace les données
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

    public function recuperer_horaire($id_employe)
    {
        $query = "SELECT * FROM horaire WHERE id_employe=:id_employe";
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

}