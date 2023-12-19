<?php
//controlleur de la classe EmployeModel

require_once __DIR__.'/../model/EmployeModel.class.php';

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
    public function heures_semaine($id_employe,$num_semaine)
    {
        $somme_heure = $this->modelemploye->heures_semaine($id_employe,$num_semaine);
        if ($somme_heure!=null){
            return $somme_heure;
        }
        else{
            return null;
        }
    }
}