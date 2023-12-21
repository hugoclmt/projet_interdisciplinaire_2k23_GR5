<?php
$horaire = $controlleur_admin->recuperer_all_horaire();
//Variables par défaut pour la date et la semaine
$date_debut = new DateTime();
$date_debut->modify('monday this week');
$date_fin = new DateTime();
$date_fin->add(new DateInterval('P7D'));
$week = $date_debut->format("W");
$annee = $date_debut->format("Y");
$nbre_tout_horaire =0;
if (is_array($horaire) || $horaire instanceof Countable)
{
$nbre_tout_horaire = count($horaire);
}else{
$msg = "Erreur";
}
if (isset($_POST['submit_semaine']))
{
    $annee_semaine = explode("-W",$_POST['semaine']); //on recupere l'annee et la semaine
    $week = $annee_semaine[1];
    $annee = $annee_semaine[0];
    $date_debut->setISODate($annee,$week,1);
    $date_fin->setISODate($annee,$week,7); 
}
?>

<h2>Horaire Generale</h2>
<div>
<form method="post">
    <label for="semaine">Choisir une semaine</label>
    <input type="week" name="semaine" required>
    <input type="submit" name="submit_semaine" value="Voir cette semaine">
</form>
</div>
<div>
    <?php
        echo '<h3>Semaine '.$week.', '.$annee.'</h3>';
    ?>
    <table>
        <?php
        for ($i = 0;$i<$nbre_tout_horaire;$i++)
        {
            if ($horaire[$i]['date'] >= $date_debut->format("Y-m-d") && $horaire[$i]['date'] <= $date_fin->format("Y-m-d"))
            {
            ?>
                <tr>
                    <td><?php echo $horaire[$i]['date'] ?></td>
                    <td><?php echo $horaire[$i]['debut'] ?> jusque <?php echo $horaire[$i]['fin']?></td>
                    <td><?php echo $horaire[$i]['nbre_heure']?></td>
                    <td>si dmd accepte</td>
                </tr>
            <?php
            }
        }
        ?>
    </table>
</div>
