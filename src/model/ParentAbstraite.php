<?php
include "DbModel.class.php";

//classe abstraite qui permet a tout les modeles de charger la db
abstract class ParentAbstraite
{
    protected $db;
    public function __construct()
    {
        $database = new DbModel(); //on cree l'objet database
        $this->db = $database->get_pdo(); //on recup le pdo
    }
}