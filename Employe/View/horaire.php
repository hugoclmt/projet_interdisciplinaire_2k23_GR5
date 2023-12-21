<?php
require_once '../src/controller/EmployeController.class.php';
$controlleur_employe = new EmployeController();


$week = date("W");

if (isset($_POST['submitconge']))
{
    $str = $_POST['date'];
    $date = new DateTime($str);
    $justification = $_POST['demande'];
    $message = $controlleur_employe->demander_conge($date,$justification,$id);
}
$id = $controlleur_employe->get_id($_SESSION['username']);
$horaire = $controlleur_employe->recuperer_horaire($id);
$nbre_horaire =0;
if (is_array($horaire) || $horaire instanceof Countable)
{
    $nbre_horaire = count($horaire);
}else{
    $msg = "Erreur";
}


$vu = false;
$message ="";
?>
<h2>Votre horaire cette semaine</h2>
<div>
<?php
    echo '<h3>Semaine '.$week.'</h3>';
?>
<table>
    <?php
    for ($i = 0;$i<$nbre_horaire;$i++)
    {
    ?>
        <tr>
            <td><?php echo $horaire[$i]['date'] ?></td>
            <td><?php echo $horaire[$i]['debut'] ?> jusque <?php echo $horaire[$i]['fin']?></td>
            <td><?php echo $horaire[$i]['nbre_heure']?></td>
            <?php 
                        if ($controlleur_employe->voir_confirm_conge($horaire[$i]['id_employe'],$horaire[$i]['date']) == "Accepté")
                        {
                            echo '<td>Congé accepté</td>';
                        }
                        else if ($controlleur_employe->voir_confirm_conge($horaire[$i]['id_employe'],$horaire[$i]['date']) == "Refusé"){
                            echo '<td>Congé refusé</td>';
                        }
                        else if($controlleur_employe->voir_confirm_conge($horaire[$i]['id_employe'],$horaire[$i]['date']) == "En attente"){
                            echo '<td>En attente</td>';
                        }
                    ?>
        </tr>
        <?php
    }
        ?>

</table>
</div>
<div>
<form method="post">
    <fieldset>
        <legend>Demander un congé</legend>
        <label for="date">Date :</label>
        <input type="date" id="date" name="date"><br>

        <label for="demande">Demander un congé :</label>
        <textarea id="demande" name="demande"></textarea><br>

        <input type="submit" name="submitconge">
    </fieldset>
</form>
<?php
if (isset($message))
{
    echo $message;
}
?>
</div>