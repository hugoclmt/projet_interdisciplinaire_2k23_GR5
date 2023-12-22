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
            $this->ajouter_conge($id_employe);
            $this->modifier_statut_conge($id_employe, $statut,$date);

        }
        else{
            $this->refuser_conge($id_employe,$date);
        }
    }

    private function ajouter_conge($id_employe) //methode pour ajouter un conge
    {
        $query = "UPDATE employes SET nbre_conges = nbre_conges + 1 WHERE id_employe=:id_employe";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->execute();
    }


    public function refuser_conge($id_employe,$date) //methode pour refuser le conge de l'employe
    {
        $statut = "Refusé";
        $this->modifier_statut_conge($id_employe,$statut,$date);
    }


    private function modifier_statut_conge($id_employe,$statut,$date){ //methode pour changer le statut d'un employe
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


    public function rappeller_employe($id_employe,$date,$heure_debut,$heure_fin) //methode pour rappeller n employe
    {
        $r =$this->verifier_utilisateur_work($id_employe,$date);
        echo 'test1 ';
        if ($r) {
            echo 'test';
            $diff_heure = $this->calculerDifferenceHeures($heure_debut, $heure_fin);
            $query = "INSERT INTO jour_horaire (id_employe,date,debut,fin,nbre_heure) VALUES (:id_employe,:date,:heure_debut,:heure_fin,:difference)";
            $resultset = $this->db->prepare($query);
            echo $id_employe;
            $resultset->bindValue(':id_employe', (int) $id_employe);
            $resultset->bindValue(':date', $date);
            $resultset->bindValue(':heure_debut', $heure_debut);
            $resultset->bindValue(':heure_fin', $heure_fin);
            $d1 = new DateTime();
            $d2 = new DateTime();
            $h1 = explode(':', $heure_debut.':00');
            $h2 = explode(':', $heure_fin.':00');
            $interval1 = new DateInterval(sprintf('PT%dH%dM%dS', $h1[0], $h1[1], $h1[2]));
            $interval2 = new DateInterval(sprintf('PT%dH%dM%dS', $h2[0], $h2[1], $h2[2]));
            $d1 = date_add($d1, $interval1);
            $d2 = date_add($d2, $interval2);
            $intervale = $d2->diff($d1);
            $resultset->bindValue(':difference', $intervale->format('%H:%I:%S'));
            return $resultset->execute();
        }else{
            echo 'test132 ';
            return false;
            }
    }



    private function calculerDifferenceHeures($heure1, $heure2) {  //methode pour calculer la difference entre deux heures
        // Convertir les heures en objets DateTime
        $t1 = new DateTime($heure1.':00');
        $t2 = new DateTime($heure2.':00');
        $interval = $t2->diff($t1); //On calcule la différence entre les deux heures
        return $interval;
    }



    public function remplacer_employe($ancien_id,$nouveau_id) //methode pour remplacer un employe
    {
        $query = "UPDATE jour_horaire SET id_employe=:nv_id WHERE id_employe=:ancien_id";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':nv_id',$nouveau_id);
        $resultset->bindValue(':ancien_id',$ancien_id);
        return $resultset->execute();
    }

    public function recuperer_employe_type($id_type) //methode pour recuperer les employe d'un type
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

    public function recuperer_type() //methode pour recuperer les types
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

    public function recuperer_nbre_heure($id_employe,$date) //methode pour recuperer le nombre d'heure d'un employe mais erreur
    {
        //calculer le nombre d'heure de l'employe
        $query = "SELECT SUM(nbre_heure) FROM jour_horaire WHERE id_employe=:id_employe AND WEEK(date)-1=:date";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date',$date);
        $resultset->execute();
        $result = $resultset->fetchall(PDO::FETCH_ASSOC);
        if (empty($result[0]['SUM(nbre_heure'])){
            $heure_total = '000000';
        }
        else{
            $heure_total = $result[0]['SUM(nbre_heure)'];
        }
        if (!empty($result)){
            $heure_total = substr_replace($heure_total,':',-2,0);
            $heure_total = substr_replace($heure_total,':',-5,0);
            $heure_minutes_secondes = explode(':', $heure_total);
            $nbre_heure = new DateInterval(sprintf('PT%dH%dM%dS', $heure_minutes_secondes[0], $heure_minutes_secondes[1], $heure_minutes_secondes[2]));
            return $nbre_heure;
        }
        else{
            return null;
        }
    }

    private function verifier_utilisateur_work($id_employe,$date) //methode pour verifier si l'employe est deja  entrain travailler
    {
        $query = "SELECT * FROM jour_horaire WHERE id_employe=:id_employe AND date=:date";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id_employe',$id_employe);
        $resultset->bindValue(':date',$date);
        $resultset->execute();
        $result = $resultset->fetchall(PDO::FETCH_ASSOC);
        if (empty($result)){
            return true;
        }
        else{
            return false;
        }
    }

    public function creer_horaire($id_employe,$date,$debut,$fin) //methode pour creer un horaire
    {
        $nbre_heure = $this->calculerDifferenceHeures($debut,$fin); //calculer la difference entre l'heure de debut et l'heure de fin
        $dateW = date('W',strtotime($date)); //recuperer le numero de la semaine
        $nbre_heure_totale = $this->recuperer_nbre_heure($id_employe,$dateW); //recuperer le nombre d'heure de l'employe
        $h_totale = new DateInterval('PT38H');

        $d1 = new DateTime();
        $d2 = new DateTime();
        $d1 = date_add($d1,$nbre_heure_totale);
        $d1 = date_add($d1,$nbre_heure);

        $d2 = date_add($d2,$h_totale);
        if ($d1 <= $d2)
        {
            $query = "INSERT INTO jour_horaire (id_employe,date,debut,fin,nbre_heure) VALUES (:id_employe,:date,:debut,:fin,:nbre_heure)";
            $resultset = $this->db->prepare($query);
            $resultset->bindValue(':id_employe',$id_employe);
            $resultset->bindValue(':date',$date);
            $resultset->bindValue(':debut',$debut);
            $resultset->bindValue(':fin',$fin);
            $resultset->bindValue(':nbre_heure',$nbre_heure->format('%H:%I:%S'));
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
    public function get_all_horaire(){ //methode pour recuperer tous les horaires
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
    public function recuperer_identifiant($id){ //methode pour recuperer l'identifiant d'un employe
        $query = "SELECT identifiant FROM employes WHERE id_employe=:id";
        $resultset = $this->db->prepare($query);
        $resultset->bindValue(':id',$id);
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

