<?php
if (isset($_SESSION['username'])) {
    $name = htmlspecialchars($_SESSION['username']);
    $id = $controllerEmploye->get_id($name);
}else{
    $_SESSION['page'] = "login.php";
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['submitconge']))
{
    $str = $_POST['date'];
    $date = new DateTime($str);
    $justification = $_POST['demande'];
    $message = $controllerEmploye->demander_conge($date,$justification,$id);
}
$horaire = $controllerEmploye->recuperer_horaire($id);
$nbre_horaire =0;
if (is_array($horaire) || $horaire instanceof Countable)
{
    $nbre_horaire = count($horaire);
}else{
    $msg = "Erreur";
}?>

<h2>Votre horaire</h2>

<h3>Semaine 51</h3>
<table>
    <?php
    for ($i = 0;$i<$nbre_horaire;$i++)
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