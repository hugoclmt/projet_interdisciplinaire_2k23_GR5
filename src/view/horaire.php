<?php
if (!isset($_SESSION['id_employe'])){ //Si l'employé n'est pas connecté
    header('Location:index.php?page=login.php'); //On le redirige vers la page de connexion
    exit();
}
require(__DIR__.'/../controller/EmployeController.class.php');
require(__DIR__.'/../model/ParentAbstraite.php');
$employe = new EmployeController();
$db=new DbModel('localhost','projet_gr5','root',''); //Connexion à la base de données
$pdo = $db->get_pdo();
if (isset($_POST['enregistrer'])){ //Si l'employé a envoyé une demande de congé
    $req=$pdo->prepare("INSERT INTO conges(id_employe, date, conge, justification) 
    VALUES (:id,:date,1,:comment)"); //Requête pour mettre à jour la table jour_horaire
    $req->bindValue(':date',$_POST['date']); //Lier la date à la requête
    $req->bindValue(':id',$_SESSION['id_employe']); //Lier l'id à la requête
    $req->bindValue(':comment',$_POST['comment']); //Lier la justification à la requête
    $req->execute();
    $req->closeCursor();
    header('Location:index.php?page=horaire.php'); //On redirige vers la page horaire.php
    exit();
}
if(isset($_POST['submit-semaine'])){
    $annee_semaine=explode('-W',$_POST['semaine']); //$annee_semaine[0] contient l'année et $annee_semaine[1] contient le numéro de la semaine
    $dateajd = new DateTime();
    $dateajd->setISODate($annee_semaine[0],$annee_semaine[1],1); //year , week num , day
    echo $dateajd->format('Y-m-d');
}
else{
    $dateajd = new DateTime();
    $annee_semaine = $dateajd->format("Y-W"); //$annee_semaine[0] contient l'année et $annee_semaine[1] contient le numéro de la semaine
    $annee_semaine=explode('-',$annee_semaine);
    $dateajd->setISODate($annee_semaine[0],$annee_semaine[1],1); //year , week num , day
}
?>
<section>
    <h2>Votre horaire</h2>
<?php

$nbre_heure_semaine= $employe->heures_semaine($_SESSION['id_employe'],$annee_semaine[1]); //On récupère le nombre d'heures de la semaine actuelle
$req=$pdo->prepare('SELECT admin FROM employes WHERE id_employe=:id'); //Requête pour savoir si l'employé est admin
$req->bindValue(':id',$_SESSION['id_employe']); //On récupère l'id de l'employé dans la session
$req->execute();
$req->setFetchMode(PDO::FETCH_OBJ);
$result=$req->fetch();
if($result->admin==1){ //Si l'employé est admin
    echo '<a href="index.php?page=gestion.php">Administration</a>'; //On affiche le lien vers l'administration
}
$req->closeCursor();
?>
<a href="index.php?page=login.php">Deconnexion</a>
<div>
<?php
echo '<h3> Semaine ',$annee_semaine[1],'</h3>'; //On affiche la semaine actuelle
echo '<h4>',$nbre_heure_semaine,' heures cette semaine</h4>'; //On affiche le nombre d'heures de la semaine actuelle

$req=$pdo->prepare("SELECT jour_horaire.id_employe,jour_horaire.date,
TIME_FORMAT(`debut`, '%H:%i') as debut,
TIME_FORMAT(`fin`, '%H:%i') as fin,
TIME_FORMAT(`nbre_heure`, '%H:%i') as nbre_heure,
congeconfirm,conge
FROM jour_horaire
JOIN employes on employes.id_employe = jour_horaire.id_employe
LEFT OUTER JOIN conges ON conges.id_employe = employes.id_employe AND conges.date = jour_horaire.date
WHERE jour_horaire.date>=:d_jour AND jour_horaire.date<=:f_jour AND jour_horaire.id_employe = :id
ORDER BY jour_horaire.date ASC"); //Requête pour récuperer les jours et les horaires
echo $dateajd->format('Y-m-d').' ';
$req->bindValue(':id',$_SESSION['id_employe']); //On récupère l'id de l'employé dans la session
$req->bindValue(':d_jour',$dateajd->format('Y-m-d')); //On récupère la date du jour
$dateajd->modify('+6 day'); //On ajoute 6 jours à la date du jour
echo $dateajd->format('Y-m-d').' '; 
$req->bindValue(':f_jour',$dateajd->format('Y-m-d')); //On récupère la date du jour + 6 jours
$req->execute();
$req->setFetchMode(PDO::FETCH_OBJ);
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE); //Permet de traduire la date en français



echo '<table>';
while ($result=$req->fetch() ) {
    $timestamp= strtotime($result->date); //On convertit la date en timestamp
    echo '<tr>';
    echo '<td>',$formatter->format($timestamp),'</td>'; //On affiche la date en français
    echo '<td';
    if ($result->congeconfirm!=NULL) {echo ' class="conge" ';}
    echo '>De ',$result->debut,' à ',$result->fin,'</td>'; 
    echo '<td';
    if ($result->congeconfirm!=NULL) {echo ' class="conge" ';}
    echo '>',$result->nbre_heure,' heures</td>';
    if($result->conge!=NULL){ //Si aucune demande n'a été envoyée  
        if(!isset($result->congeconfirm)){ //Si la demande n'a pas encore été traitée
            echo '<td>La demande est en attente</td>';
        }
        else if($result->congeconfirm==NULL){ //Si la demande a été refusée
            echo '<td>La demande a été refusée</td>';
        }
        else if($result->congeconfirm!=NULL){ //Si la demande a été acceptée
            echo '<td>La demande a été acceptée</td>';
        }
    }
    echo '</tr>';
}
echo '</table>';

?>
</table>
<p>Voir une autre semaine</p>
<form method="post">
    <label for "semaine">Semaine :</label>
    <input type="week" name="semaine" id="semaine" required>
    <input type="submit" name="submit-semaine" value="Voir">
</form>
</div>
<div>
<h3>Demander un congé</h3>
<!-- Créer un formulaire ou l'on peut rentrer une date, rentrer une justification et envoyer la demande -->
<form method="post">
    <label for "date">Date :</label>
    <input type="date" name="date" id="date" required>
    <label for "comment">Demander un congé :</label>
            <textarea name="comment" rows=1 cols=30></textarea>
    <input type="submit" name="enregistrer" value="Envoyer">
</form>
</div>
</section>