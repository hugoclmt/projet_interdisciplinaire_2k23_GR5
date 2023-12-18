<?php

class AdministrateurModel
{
    private $db;

    public function __construct()
    {
        $database = new DbModel();
        $this->db = $database->get_pdo();
    }

    public function get_all_conge(){ //methode pour afficher tout les congé demandé par les employé
        
    }

    public function accepter_conge() //methode pour accepter le conge de l'employe
    {

    }
    public function refuser_conge() //methode pour refuser le conge de l'employe
    {

    }


}