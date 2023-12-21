<?php
include_once 'EmployeModel.class.php';

//classe administrateur qui herite de la classe employemodel
class AdministrateurModel extends EmployeModel
{
    public function __construct()
    {
        parent::__construct();
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

    public function accepter_conge($id_employe,$date) //methode pour accepter le conge de l'employe
    {
        $nbre = $this->recuperer_nbre_conge($id_employe);
        if ($nbre<=30) {
            $statut = "Accepté";
            $this->ajouter_conge();
            $this->modifier_statut_conge($id_employe, $statut,$date);

        }
        else{
            $this->refuser_conge($id_employe,$date);
        }
    }

    private function ajouter_conge()
    {
        $query = "UPDATE employes SET nbre_conges = nbre_conges + 1";
        $resultset = $this->db->prepare($query);
        $resultset->execute();
    }


    public function refuser_conge($id_employe,$date) //methode pour refuser le conge de l'employe
    {
        $statut = "Refusé";
        $this->modifier_statut_conge($id_employe,$statut,$date);
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

    private function modifier_statut_conge($id_employe,$statut,$date){ //fct pour changer le statut d'un employe
        try{
                $query = "UPDATE conge SET congeconfirm=:statut WHERE id_employe=:id AND date_conge=:date";
                $resultset = $this->db->prepare($query);
                $resultset->bindValue(':statut',$statut);
                $resultset->bindValue(':id',$id_employe);
                $resultset->bindValue(':date',$date);
                $resultset->execute();
        }catch(PDOException $e){

        }
    }


    public function rappeller_employe($id_employe,$date,$heure_debut,$heure_fin)
    {
        $r =$this->verifier_utilisateur_work($id_employe,$date);
        if ($r) {
            $diff_heure = $this->calculerDifferenceHeures($heure_debut, $heure_fin);
            $query = "INSERT INTO jour_horaire (id_employe,date,debut,fin,nbre_heure) VALUES (:id_employe,:date,:heure_debut,:heure_fin,:difference)";
            $resultset = $this->db->prepare($query);
            $resultset->bindValue(':id_employe', $id_employe);
            $resultset->bindValue(':date', $date);
            $resultset->bindValue(':heure_debut', $heure_debut);
            $resultset->bindValue(':heure_fin', $heure_fin);
            $resultset->bindValue(':difference', $diff_heure);
            return $resultset->execute();
        }else{
            return false;
            }
    }



    private function calculerDifferenceHeures($heure1, $heure2) {
        // Convertir les heures en objets DateTime
        $datetime1 = new DateTime($heure1);
        $datetime2 = new DateTime($heure2);

        $interval = $datetime1->diff($datetime2);

        return $interval->format('%H:%I:%S');;
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
            $query = "SELECT * FROM employes WHERE id_type=:id_type";
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

    public function recuperer_nbre_heure($id_employe) //erreur a fixer
    {
        //calculer le nombre d'heure de l'employe
        $query = "SELECT SUM(nbre_heure) FROM jour_horaire WHERE id_employe=:id_employe";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->execute();
        $result = $resultset->fetchall(PDO::FETCH_ASSOC);
        if (!empty($result)){
            return $result;
        }
        else{
            return null;
        }
    }

    private function verifier_utilisateur_work($id_employe,$date)
    {
        $query = "SELECT * FROM jour_horaire WHERE id_employe=:id_employe AND date=:date";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date',$date);
        $resultset->execute();
        $result = $resultset->fetchall(PDO::FETCH_ASSOC);
        if (!empty($result)){
            return true;
        }
        else{
            return false;
        }
    }

    public function creer_horaire($id_employe,$date,$debut,$fin)
    {
        $h_totale = 38;
        $nbre_heure = floatval($this->calculerDifferenceHeures($debut,$fin)); //calculer la difference entre l'heure de debut et l'heure de fin
        $nbre_heure_totale = $this->recuperer_nbre_heure($id_employe); //recuperer le nombre d'heure de l'employe
        $nbre_heure_totale = floatval($nbre_heure_totale[0]['SUM(nbre_heure)']); //recuperer le nombre d'heure de l'employe
        if ($nbre_heure_totale + $nbre_heure<=$h_totale)
        {
            $query = "INSERT INTO jour_horaire (id_employe,date,debut,fin,nbre_heure) VALUES (:id_employe,:date,:debut,:fin,:nbre_heure)";
            $resultset = $this->db->prepare($query);
            $resultset->bindValue(':id_employe',$id_employe);
            $resultset->bindValue(':date',$date);
            $resultset->bindValue(':debut',$debut);
            $resultset->bindValue(':fin',$fin);
            $resultset->bindValue(':nbre_heure',$nbre_heure);
            $result=  $resultset->execute();
            if ($result)
            {
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return null;
        }

    }
    public function get_all_horaire(){
        $query = "SELECT * FROM jour_horaire";
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


}

