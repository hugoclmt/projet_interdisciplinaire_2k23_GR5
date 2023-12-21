<?php
$horaire = $controlleur_admin->recuperer_all_horaire();
$nbre_tout_horaire =0;
if (is_array($horaire) || $horaire instanceof Countable)
{
$nbre_tout_horaire = count($horaire);
}else{
$msg = "Erreur";
}
?>

<h2>Horaire Generale</h2>

<h3>Semaine X</h3>
<table>
    <?php
    for ($i = 0;$i<$nbre_tout_horaire;$i++)
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
    ?>

</table>
