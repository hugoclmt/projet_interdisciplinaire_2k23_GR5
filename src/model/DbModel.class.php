<?php

class DbModel
{
    private $host;
    private $dbName;
    private $user;
    private $mdp;
    private $pdo;

    public function __construct(){ //appele lors de l'instanciation
        $this->host = "localhost"; //nom de hote
        $this->dbName = "projet_interdisciplinaire"; //nom de la db
        $this->user = "root"; //nom de l'user
        $this->mdp = ""; //mdp
        $this->pdo = null; //pdo

        $this->connect(); //appelle la fonction connect lors de l'initialisation de l'objet
    }

    private function connect(){
        if($this->pdo == null) //si pdo est null
        {
            try{
                $dsn = 'mysql:host=' .$this->host . ';dbname=' .$this->dbName . ';charset=utf8'; //route pour la bd
                $this->pdo = new PDO($dsn,$this->user,$this->mdp); //creation du pdo
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            }catch (PDOException $e){
                echo "Erreur de connexion a la bd";
            }
        }
    }

    public function get_pdo(){ //methode pour recuperer le pdo
        if ($this->pdo == null) //si on souhaite recup le pdo est qu'il est null
        {
            $this->connect(); //on appelle la methode connexion
        }
        return $this->pdo; //on return le pdo
    }

}