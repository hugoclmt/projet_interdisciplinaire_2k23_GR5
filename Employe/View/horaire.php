<?php
require_once '../src/controller/EmployeController.class.php';
$controlleur_employe = new EmployeController();


//Variables par défaut pour la date et la semaine
$date_debut = new DateTime();
$date_debut->modify('monday this week');
$date_fin = new DateTime();
$date_fin->add(new DateInterval('P7D'));
$week = $date_debut->format("W");
$annee = $date_debut->format("Y");

if (isset($_POST['submitconge']))
{
    $str = $_POST['date'];
    $date = new DateTime($str);
    $justification = $_POST['demande'];
    $message = $controlleur_employe->demander_conge($date,$justification,$id);
}
if (isset($_POST['submit_semaine']))
{
    $annee_semaine = explode("-W",$_POST['semaine']); //on recupere l'annee et la semaine
    $week = $annee_semaine[1];
    $annee = $annee_semaine[0];
    $date_debut->setISODate($annee,$week,1);
    $date_fin->setISODate($annee,$week,7); 
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
    echo '<h3>Semaine '.$week.', '.$annee.'</h3>';
?>
<div class="h_general">
<form method="post">
    <label for="semaine">Choisir une semaine</label>
    <input type="week" name="semaine" required>
    <input type="submit" name="submit_semaine" value="Voir cette semaine">
</form>
</div>
<table>
    <?php
    for ($i = 0;$i<$nbre_horaire;$i++)
    {
        if ($horaire[$i]['date'] >= $date_debut->format("Y-m-d") && $horaire[$i]['date'] <= $date_fin->format("Y-m-d"))
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