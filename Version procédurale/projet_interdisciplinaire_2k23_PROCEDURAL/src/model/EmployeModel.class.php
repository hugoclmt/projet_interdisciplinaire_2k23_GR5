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
        $query = "SELECT jour_horaire.id_employe,SUM(jour_horaire.nbre_heure) AS somme FROM jour_horaire
        JOIN employes ON employes.id_employe = jour_horaire.id_employe
        LEFT OUTER JOIN conges ON conges.date = jour_horaire.date
        WHERE (conges.congeconfirm IS NULL OR conges.congeconfirm=0) AND jour_horaire.id_employe=:id_employe AND WEEK(jour_horaire.date)=:num_semaine
        GROUP BY jour_horaire.id_employe"; //Requete pour recuperer les heures de la semaine sans compter les congés
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':num_semaine',$num_semaine);
        $resultset->execute();
        $tableau_resultat = $resultset->fetch(PDO::FETCH_ASSOC); 
        if ($tableau_resultat == null)
        {
            return null;
        }
        $tableau_resultat['somme'] = substr_replace($tableau_resultat['somme'],':',-2,0); //On place un ':' entre les minutes et les secondes
        $tableau_resultat['somme'] = substr_replace($tableau_resultat['somme'],':',-5,0); //On place un ':' entre les heures et les minutes
        $somme_heure = $tableau_resultat['somme'];
        
        if (!empty($somme_heure))
        {
            return $somme_heure;
        }
        else{
            return null;
        }
    }
    
}