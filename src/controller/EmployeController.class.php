<?php
//controlleur de la classe EmployeModel

require '../model/EmployeModel.class.php';

class EmployeController
{
    private $modelemploye;

    public function __construct(){
        $this->modelemploye = new EmployeModel();
    }

    public function demander_conge($date,$justificatif,$id_employe){
        $dateNettoye = htmlspecialchars($date);
        $justificatifNettoye = htmlspecialchars($justificatif);
        if (!empty($id_employe) && !empty($dateNettoye) && !isset($justificatifNettoye))
        {
            $result = $this->modelemploye->demander_conge($dateNettoye,$justificatifNettoye,$id_employe);
            if ($result)
            {
                $_SESSION['page'] = "tableaux.php";
                header("Location: ../../index.php");
            }

        }
    }

    public function recuperer_horaire()
    {
        $horaire = $this->modelemploye->recuperer_horaire();
        if ($horaire!=null){
            return $horaire;
        }
        else{
            return null;
        }
    }

}