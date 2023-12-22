<?php
require_once '../src/controller/EmployeController.class.php';

//Variables par défaut pour la date et la semaine
$date_debut = new DateTime();
$date_debut->modify('monday this week');
$date_fin = new DateTime();
$date_fin->add(new DateInterval('P7D'));
$week = $date_debut->format("W");
$annee = $date_debut->format("Y");
$id = $controllerEmploye->get_id($_SESSION['username']); //on recupere l'id de l'employe
if (isset($_POST['submitconge'])) //si on appuie sur le bouton pour dmd ses conge
{
    $str = $_POST['date']; //on recupere la date
    $date = new DateTime($str); //on la met dans un objet DateTime
    $justification = $_POST['demande']; //on recupere la justification
    $message = $controllerEmploye->demander_conge($date,$justification,$id); //on appelle la methode demander_conge du controller
}
if (isset($_POST['submit_semaine'])) //si on appuie sur le bouton pour voir une semaine
{
    $annee_semaine = explode("-W",$_POST['semaine']); //on recupere l'annee et la semaine
    $week = $annee_semaine[1];
    $annee = $annee_semaine[0];
    $date_debut->setISODate($annee,$week,1);
    $date_fin->setISODate($annee,$week,7); 
}

$horaire = $controllerEmploye->recuperer_horaire($id); //on recupere l'horaire de l'employe
$nbre_horaire =0; //on initialise le nombre d'horaire
if (is_array($horaire) || $horaire instanceof Countable) //si l'horaire est un tableau
{
    $nbre_horaire = count($horaire); //on compte le nombre d'horaire
}else{
    $msg = "Erreur";
}
$vu = false;
$message ="";

$heure_total = $controllerEmploye->recuperer_all_heures($id); //on recupere le nombre d'heures total de l'employee

?>
<div>
<h2>Votre horaire cette semaine</h2>
    <fieldset>
        <legend>
        <?php
            echo '<h3>Semaine '.$week.', '.$annee.'</h3>';
        ?>
        </legend>

<div class="h_general">
<form method="post">
    <label for="semaine">Choisir une semaine</label>
    <input type="week" name="semaine" required>
    <input type="submit" name="submit_semaine" value="Voir cette semaine">
</form>
</div>
        <div>
        <p><?php 
        if(!empty($heure_total)){
            $heure_total = substr_replace($heure_total,':',-2,0);
            $heure_total = substr_replace($heure_total,':',-5,0);
            echo $heure_total;
        }
        ?></p>
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
                        if ($controllerEmploye->voir_confirm_conge($horaire[$i]['id_employe'],$horaire[$i]['date']) == "Accepté")
                        {
                            echo '<td>Congé accepté</td>';
                        }
                        else if ($controllerEmploye->voir_confirm_conge($horaire[$i]['id_employe'],$horaire[$i]['date']) == "Refusé"){
                            echo '<td>Congé refusé</td>';
                        }
                        else if($controllerEmploye->voir_confirm_conge($horaire[$i]['id_employe'],$horaire[$i]['date']) == "En attente"){
                            echo '<td>En attente</td>';
                        }
                    ?>
        </tr>
        <?php
    }
}
        ?>

</table>
</fieldset>
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