<?php
if (isset($_SESSION['username'])) { //si l'utilisateur est connecte
    $name = htmlspecialchars($_SESSION['username']); //on recupere son nom
    $id = $controlleur_employe->get_id($name); //on recupere son id
}else{
    $_SESSION['page'] = "login.php"; //sinon on le redirige vers la page de connexion
    header("Location: ../index.php"); //on redirige vers la page d'accueil
    exit();
}
//Variables par défaut pour la date et la semaine
$date_debut = new DateTime();
$date_debut->modify('monday this week');
$date_fin = new DateTime();
$date_fin->add(new DateInterval('P7D'));
$week = $date_debut->format("W");
$annee = $date_debut->format("Y");
if (isset($_POST['submitconge'])) //si on appuie sur le bouton pour dmd ses conge
{
    $str = $_POST['date']; //on recupere la date
    $date = new DateTime($str);
    $justification = $_POST['demande'];
    $message = $controlleur_employe->demander_conge($date,$justification,$id); //on appelle la methode demander_conge du controller
}
$horaire = $controlleur_employe->recuperer_horaire($id); //on recupere l'horaire de l'employe
$nbre_horaire =0;
if (is_array($horaire) || $horaire instanceof Countable)
{
    $nbre_horaire = count($horaire);
}else{
    $msg = "Erreur";
}

$types = $controlleur_admin->recuper_type(); //on recupere les types
$nbre_type = 0;
if (is_array($types) || $types instanceof Countable) {
    $nbre_type = count($types);
}
$vu = false;
$message ="";
if (isset($_POST['submittype'])) //si on appuie sur le bouton pour voir les employes d'un type
{
    $vu = true; //on affiche les employes
    $id_type = $_POST['type']; //on recupere l'id du type
    $users = $controlleur_admin->recuper_personnes_partype($id_type); //on recupere les employes du type
    $nbre_users = 0;
    if(!empty($users)){ //si il y a des employes
        $nbre_users = count($users);
    }
}
if (isset($_POST['submit_horaire'])) //si on appuie sur le bouton pour creer un horaire
{
    $id_employe = $_POST['employe']; //on recupere l'id de l'employe
    $date = $_POST['date']; //on recupere la date
    $debut = $_POST['debut']; //on recupere l'heure de debut
    $fin = $_POST['fin']; //on recupere l'heure de fin
    $message = $controlleur_admin->creer_horaire($id_employe,$date,$debut,$fin); //on appelle la methode creer_horaire du controller
    $_POST = array();
    $id_employe = null;
    $date = null;
    $debut = null;
}
if (isset($_POST['submit_semaine'])) //si on appuie sur le bouton pour voir une semaine
{
    $annee_semaine = explode("-W",$_POST['semaine']); //on recupere l'annee et la semaine
    $week = $annee_semaine[1];
    $annee = $annee_semaine[0];
    $date_debut->setISODate($annee,$week,1);
    $date_fin->setISODate($annee,$week,7); 
}

$heure_total = $controlleur_employe->recuperer_all_heures($id,$week); //on recupere le nombre d'heure total de l'employe

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
            $heure_total = substr_replace($heure_total,':',-2,0);
            $heure_total = substr_replace($heure_total,':',-5,0);
            echo $heure_total?></p>
        </div>
<table>
        <tr>
            <th>Date</th>
            <th>Horaire</th>
            <th>Nombre d'heure</th>
            <th>Etat congé</th>
        </tr>
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
</fieldset>
</div>
<div>
<form method="post">
    <fieldset>
        <legend>Demander un congé</legend>
        <label for="date">Date :</label>
        <input type="date" id="date" name="date" required><br>

        <label for="demande">Demander un congé :</label>
        <textarea id="demande" name="demande" required></textarea><br>

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
            <select id="employe" name="employe" required>
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
            <input type="date" id="date" name="date" required><br>
            <label for="debut">Debut d'horaire :</label>
            <input type="time" id="debut" name="debut" required><br>
            <label for="fin">Fin d'horaire :</label>
            <input type="time" id="fin" name="fin" required><br>
            <input type="submit" name="submit_horaire">
        </fieldset>
    </form>
    </div>
<?php
    echo $message;
}?>
