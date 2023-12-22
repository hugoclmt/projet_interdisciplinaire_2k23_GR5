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
    public function prevenir_maladie($id_employe,$date,$justificatif){
        $statut = "Accepté";
        $query = "INSERT INTO conge (id_employe,date_conge,justification,congeconfirm) VALUES (:id_employe,:date,:justification,'Accepté')";
        $resultset = $this->db->prepare($query);
        echo $id_employe;
        echo $date;
        echo $justificatif;
        $resultset->bindValue(':id_employe',(int)$id_employe);
        $resultset->bindValue(':date',$date);
        $resultset->bindValue(':justification',$justificatif);
        return $resultset->execute();
    }
    public function voir_confirm_conge($id_employe,$date_conge) //methode pour voir si le conge a ete accepter ou refuser
    {
        $query = "SELECT congeconfirm FROM conge WHERE id_employe=:id_employe AND date_conge=:date_conge";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date_conge',$date_conge);
        $resultset->execute();
        $result = $resultset->fetch(PDO::FETCH_ASSOC);
        if (!empty($result) && isset($result['congeconfirm']))
        {
            return $result['congeconfirm'];
        }
        else{
            return null;
        }
    }

    public function recuperer_horaire($id_employe) //methode pour recuperer les horaires
    {
        $query = "SELECT * FROM jour_horaire WHERE id_employe=:id_employe ORDER BY date ASC";
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

    protected function recuperer_nbre_conge($id_employe) //methode pour recuperer le nombre de conge
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

    public function get_id($username) //methode pour recuperer l'id de l'employe avec son username
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

    public function get_all_hours($id_employe,$date_debut,$date_fin) //methode pour recuperer le nombre d'heure total de l'employe
    {
        $query = "SELECT SUM(nbre_heure) FROM jour_horaire WHERE id_employe=:id_employe AND date BETWEEN :date_debut AND :date_fin";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date_debut',$date_debut);
        $resultset->bindValue(':date_fin',$date_fin);
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