<?php
include 'DbModel.class.php';
class HoraireModel
{
    private $db;

    public function __construct(){
        $database = new DbModel();
        $this->db = $database->get_pdo();
    }


}