<?php
$horaire = $controlleur_admin->recuperer_all_horaire(); //on recupere tous les horaires
//Variables par défaut pour la date et la semaine
$date_debut = new DateTime();
$date_debut->modify('monday this week');
$date_fin = new DateTime();
$date_fin->modify('sunday this week');
$week = $date_debut->format("W");
$annee = $date_debut->format("Y");

$nbre_tout_horaire =0; //on initialise le nombre d'horaire

$demande_conge = $controlleur_admin->recuperer_demande_conge(); //on recuperer toutes lse demandes de congé et maladie
if (is_array($demande_conge) || $demande_conge instanceof Countable) { //on verifie si on peut compter
    $nbre_demande = count($demande_conge); //on compte le nbre de demande pour la bouvle
}
if (is_array($horaire) || $horaire instanceof Countable)
{
$nbre_tout_horaire = count($horaire);
}else{
$msg = "Erreur";
}
if (isset($_POST['submit_semaine'])) //si on appuie sur le bouton pour voir une semaine
{
    $annee_semaine = explode("-W",$_POST['semaine']); //on recupere l'annee et la semaine
    $week = $annee_semaine[1];
    $annee = $annee_semaine[0];
    $date_debut->setISODate($annee,$week,1);
    $date_fin->setISODate($annee,$week,7); 
}
?>

<h2>Horaire Général</h2>
<div class="h_general">
<form method="post">
    <label for="semaine">Choisir une semaine</label>
    <input type="week" name="semaine" required>
    <input type="submit" name="submit_semaine" value="Voir cette semaine">
</form>
</div>
<div class="h_general">
    <fieldset>
        <legend> 
        <?php
            echo '<h3>Semaine '.$week.', '.$annee.'</h3>';
        ?>
        </legend>
    <table>
        <tr>
            <th>Id employe</th>
            <th>Date</th>
            <th>Horaire</th>
            <th>Nombre d'heure</th>
            <th>Etat</th>
        </tr>
        <?php
        for ($i = 0;$i<$nbre_tout_horaire;$i++)
        {
            if ($horaire[$i]['date'] >= $date_debut->format("Y-m-d") && $horaire[$i]['date'] <= $date_fin->format("Y-m-d"))
            {
            ?>
                <tr>
                    <td><?php echo $horaire[$i]['id_employe'] ?></td>
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
    </fieldset>
</div>
