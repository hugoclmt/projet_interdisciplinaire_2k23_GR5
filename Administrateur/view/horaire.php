<?php
if (isset($_SESSION['username'])) {
    $name = htmlspecialchars($_SESSION['username']);
    $id = $controlleur_employe->get_id($name);
}else{
    $_SESSION['page'] = "login.php";
    header("Location: ../index.php");
    exit();
}
$week = date("W");

if (isset($_POST['submitconge']))
{
    $str = $_POST['date'];
    $date = new DateTime($str);
    $justification = $_POST['demande'];
    $message = $controlleur_employe->demander_conge($date,$justification,$id);
}
$horaire = $controlleur_employe->recuperer_horaire($id);
$nbre_horaire =0;
if (is_array($horaire) || $horaire instanceof Countable)
{
    $nbre_horaire = count($horaire);
}else{
    $msg = "Erreur";
}

$types = $controlleur_admin->recuper_type();
$nbre_type = 0;
if (is_array($types) || $types instanceof Countable) {
    $nbre_type = count($types);
}
$vu = false;
$message ="";
if (isset($_POST['submittype']))
{
    $vu = true;
    $id_type = $_POST['type'];
    $users = $controlleur_admin->recuper_personnes_partype($id_type);
    $nbre_users = 0;
    if(!empty($users)){
        $nbre_users = count($users);
    }
}
if (isset($_POST['submit_horaire']))
{
    $id_employe = $_POST['employe'];
    $date = $_POST['date'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $message = $controlleur_admin->creer_horaire($id_employe,$date,$debut,$fin);
}

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
            <td>si dmd accepte</td>
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

<?php if(!$vu)
{ ?>
    <div>
    <form method="post">
        <fieldset>
            <legend>Creation horaire</legend>

            <label for="type">Type :</label>
            <select id="type" name="type">
                <?php
                for ($i = 0;$i<$nbre_type;$i++)
                {
                    ?>
                    <option value="<?php echo $types[$i]['id_type']?>"><?php echo $types[$i]['nom_type']?></option>
                    <?php
                }
                ?>
            </select>
            <input type="submit" name="submittype">
        </fieldset>
    </form>
    </div>
<?php
}else{
?>  
    <div>
    <form method="post">
        <fieldset>
            <label for="employe">Employe :</label>
            <select id="employe" name="employe">
                <?php
                for ($i = 0;$i<$nbre_users;$i++)
                {
                    ?>
                    <option value="<?php echo $users[$i]['id_employe']?>"><?php echo $users[$i]['identifiant']?></option>
                    <?php
                }
                ?>
            </select>
            <label for="date">Date :</label>
            <input type="date" id="date" name="date"><br>
            <label for="debut">Debut d'horaire :</label>
            <input type="time" id="debut" name="debut"><br>
            <label for="fin">Fin d'horaire :</label>
            <input type="time" id="fin" name="fin"><br>
            <input type="submit" name="submit_horaire">
        </fieldset>
    </form>
    </div>
<?php
    echo $message;
}?>
