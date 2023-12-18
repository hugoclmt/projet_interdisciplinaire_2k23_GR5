<?php
require(__DIR__.'/../model/DbModel.class.php');
?>
<section>
    <h2>Votre horaire</h2>
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
        echo '<td>De ',$result->debut,' à ',$result->fin,'</td>'; 
        echo '<td>',$result->nbre_heure,' heures</td>';
        echo '<td>';
        if($result->conge==0){ //Si aucune demande n'a été envoyée
            echo '<a href="">Demander un congé</a>';
            //TODO Ajouter un controlleur pour la demande de congé
        }
        else if($result->conge==1){
            echo '<a href="">Annuler le congé</a>';
            
            if ($result->congeconfirm==0){
                echo 'La demande a été refusée';
            }
            else if ($result->congeconfirm==1){
                echo 'La demande a été acceptée';
            }
        }  
        echo '</td>';
        echo '</tr>';
    }
    ?>
</table>
</section>