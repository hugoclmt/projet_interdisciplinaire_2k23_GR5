<?php
include 'EmployeModel.class.php';

//classe administrateur qui herite de la classe employemodel
class AdministrateurModel extends EmployeModel
{


    public function __construct()
    {
        parent::__construct();
        $database = new DbModel();
        $this->db = $database->get_pdo();
    }

    public function get_all_conge(){ //methode pour afficher tout les congé demandé par les employé
        try{
            $query = "SELECT * from conge";
            $resultset = $this->db->prepare($query);
            $resultset->execute();

            $conges = $resultset->fetchall(PDO::FETCH_ASSOC);
            if (!empty($conges))
            {
                return $conges;
            }
            else{
                return null;
            }


        }catch(PDOException $e){
            return null;
        }
    }

    public function accepter_conge($id_employe) //methode pour accepter le conge de l'employe
    {
        $nbre = $this->recuperer_nbre_conge($id_employe);
        if ($nbre<=30) {
            $statut = "Accepté";
            $this->ajouter_conge();
            $this->modifier_statut_conge($id_employe, $statut);

        }
        else{
            $this->refuser_conge($id_employe);
        }
    }

    private function ajouter_conge()
    {
        $query = "UPDATE employes SET nbre_conges = nbre_conges + 1";
        $resultset = $this->db->prepare($query);
        $resultset->execute();
    }


    public function refuser_conge($id_employe) //methode pour refuser le conge de l'employe
    {
        $statut = "Refusé";
        $this->modifier_statut_conge($id_employe,$statut);
    }

// finis la methode en dessous
    private function modifier_etat_user($id)
    {
        $query = "";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue();
        $resultset->bindValue();
        $resultset->execute();

    }

    private function modifier_statut_conge($id_employe,$statut){ //fct pour changer le statut d'un employe
        try{
                $query = "UPDATE conge SET congeconfirm=:statut WHERE id_employe=:id";
                $resultset = $this->db->prepare($query);
                $resultset->bindValue(':statut',$statut);
                $resultset->bindValue(':id',$id_employe);
                $resultset->execute();
        }catch(PDOException $e){

        }
    }


    public function rappeller_employe($id_employe,$date,$heure_debut,$heure_fin)
    {
        $query = "INSERT INTO jour_horaire (id_employe,date,debut,fin) VALUES (:id_employe,:date,:heure_debut,:heure_fin)";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date',$date);
        $resultset->bindValue(':heure_debut',$heure_debut);
        $resultset->bindValue(':heure_fin',$heure_fin);
        return $resultset->execute();

    }

    public function remplacer_employe($ancien_id,$nouveau_id)
    {
        $query = "UPDATE jour_horaire SET id_employe=:nv_id WHERE id_employe=:ancien_id";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':nv_id',$nouveau_id);
        $resultset->bindValue(':ancien_id',$ancien_id);
        return $resultset->execute();
    }

    public function recuperer_employe_type($id_type)
    {
        try{
            $query = "SELECT * FROM employe WHERE id_type=:id_type";
            $resultset = $this->db->prepare($query);
            $resultset->bindValue(':id_type',$id_type);
            $resultset->execute();
            $employe_type = $resultset->fetchall(PDO::FETCH_ASSOC);
            if (!empty($employe_type))
            {
                return $employe_type;
            }
            else{
                return null;
            }
        }catch (PDOException $e)
        {

        }
    }

    public function recuperer_type()
    {
        $query = "SELECT * FROM type";
        $resultset = $this->db->prepare($query);
        $resultset->execute();
        $result = $resultset->fetchall(PDO::FETCH_ASSOC);
        if (!empty($result)){
            return $result;
        }
        else{
            return null;
        }
    }

    public function creer_horaire($id_employe,$date,$debut,$fin,$nbre_heure)
    {
        $query = "INSERT INTO jour_horaire (id_employe,date,debut,fin,nbre_heure) VALUES (:id_employe,:date,:debut,:fin,:nbre_heure)";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date',$date);
        $resultset->bindValue(':debut',$debut);
        $resultset->bindValue(':fin',$fin);
        $resultset->bindValue(':nbre_heure',$nbre_heure);
        return $resultset->execute();
    }
}