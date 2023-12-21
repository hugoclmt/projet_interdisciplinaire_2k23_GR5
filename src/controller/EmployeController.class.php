<?php
//controlleur de la classe EmployeModel

require_once '../src/model/EmployeModel.class.php';

class EmployeController
{
    private $modelemploye;

    public function __construct(){
        $this->modelemploye = new EmployeModel();
    }

    public function demander_conge($date,$justificatif,$id_employe){
        $justificatifNettoye = htmlspecialchars($justificatif);
        if (!empty($id_employe) && !empty($date) && !empty($justificatifNettoye))
        {
            $result = $this->modelemploye->demander_conge($date,$justificatifNettoye,$id_employe);
            if ($result)
            {
                return "OK";
            }
            else{
                return "NONOK";
            }
        }
    }

    public function recuperer_horaire($id)
    {
        $horaire = $this->modelemploye->recuperer_horaire($id);
        if ($horaire!=null){
            return $horaire;
        }
        else{
            return null;
        }
    }

    public function get_id($username)
    {
        $name = htmlspecialchars($username);
        $result = $this->modelemploye->get_id($name);
        if ($result == null)
        {
            return "Aucun utilisateur trouvé";
        }
        else{
            return $result;
        }
    }

    public function recuperer_all_heures($id)
    {
        $heures = $this->modelemploye->get_all_hours($id);
        if ($heures!=null){
            return $heures;
        }
        else{
            return null;
        }
    }
}