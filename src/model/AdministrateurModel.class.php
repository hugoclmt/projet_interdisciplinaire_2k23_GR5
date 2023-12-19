<?php

class AdministrateurModel extends ParentAbstraite
{


    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_conge(){ //methode pour afficher tout les congé demandé par les employé
        try{
            $query = "SELECT * FROM conge";
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
        $statut = "Accepté";
        $query = "";



        $this->modifier_statut_conge($id_employe,$statut);
    }


    public function refuser_conge($id_employe) //methode pour refuser le conge de l'employe
    {
        $statut = "Refusé";
        $query = "";


        $this->modifier_statut_conge($id_employe,$statut);
    }


    private function modifier_etat_user($id,$statut)
    {
        //TODO
    }

    private function modifier_statut_conge($id_employe,$statut){ //fct pour changer le statut d'un employe
        try{
                $query = "UPDATE conge SET statut=:statut WHERE id_employe=:id";
                $resultset = $this->db->prepare($query);
                $resultset->bindValue(':statut',$statut);
                $resultset->bindValue(':id',$id_employe);
                return $resultset->execute();
        }catch(PDOException $e){

        }
    }


    public function rappeller_employe()
    {

    }

    public function recuper_employe_type($id_poste)
    {
        try{
            $query = "SELECT * FROM employe WHERE id_poste=:id_poste AND actif = true";
            $resultset = $this->db->prepare($query);
            $resultset->bindValue(':id_poste',$id_poste);
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

    public function creer_horaire()
    {

    }
}

