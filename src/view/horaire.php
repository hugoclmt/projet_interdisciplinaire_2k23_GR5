<?php
require(__DIR__.'/../model/DbModel.class.php');
?>
<section>
    <h2>Votre horaire</h2>
<?php
if (!isset($_SESSION['id_employe'])){ //Si l'employé n'est pas connecté
    header('Location:index.php?page=login.php'); //On le redirige vers la page de connexion
    exit();
}
$db=new DbModel('localhost','projet_gr5','root',''); //Connexion à la base de données
$pdo = $db->get_pdo();
if (isset($_POST['enregistrer'])){ //Si l'employé a envoyé une demande de congé
    $req=$pdo->prepare('UPDATE jour_horaire SET conge=1,justification=:comment WHERE date=:date AND id_employe=:id'); //Requête pour mettre à jour la table jour_horaire
    $req->bindValue(':date',$_POST['date']); //Lier la date à la requête
    $req->bindValue(':id',$_SESSION['id_employe']); //Lier l'id à la requête
    $req->bindValue(':comment',$_POST['comment']); //Lier la justification à la requête
    $req->execute();
    $req->closeCursor();
    header('Location:index.php?page=horaire.php'); //On redirige vers la page horaire.php
    exit();
}
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
<table>
    <?php
    $db=new DbModel('localhost','projet_gr5','root',''); //Connexion à la base de données
    $pdo = $db->get_pdo();
    $req=$pdo->prepare("SELECT id_employe,date,
    TIME_FORMAT(`debut`, '%H:%i') as debut,
    TIME_FORMAT(`fin`, '%H:%i') as fin,
    TIME_FORMAT(`nbre_heure`, '%H:%i') as nbre_heure,conge,congeconfirm FROM jour_horaire 
    WHERE date>=CURRENT_DATE AND id_employe =:id
    ORDER BY date ASC"); //Requête pour récuperer les jours et les horaires

    $req->bindValue(':id',$_SESSION['id_employe']); //On récupère l'id de l'employé dans la session
    $req->execute();
    $req->setFetchMode(PDO::FETCH_OBJ);
    while ($result=$req->fetch() ) {
        $timestamp= strtotime($result->date); //On convertit la date en timestamp
        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE); //Permet de traduire la date en français
        echo '<tr>';
        echo '<td>',$formatter->format($timestamp),' ','</td>'; //On affiche la date en français
        echo '<td';
        if ($result->congeconfirm==1) {echo ' class="conge" ';}
        echo '>De ',$result->debut,' à ',$result->fin,'</td>'; 
        echo '<td';
        if ($result->congeconfirm==1) {echo ' class="conge" ';}
        echo '>',$result->nbre_heure,' heures</td>';
        if($result->conge==1){ //Si aucune demande n'a été envoyée  
            if(!isset($result->congeconfirm)){ //Si la demande n'a pas encore été traitée
                echo '<td>La demande est en attente</td>';
            }
            else if($result->congeconfirm==0){ //Si la demande a été refusée
                echo '<td>La demande a été refusée</td>';
            }
            else if($result->congeconfirm==1){ //Si la demande a été acceptée
                echo '<td>La demande a été acceptée</td>';
            }
        }
        echo '</tr>';
    }
    ?>
</table>
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