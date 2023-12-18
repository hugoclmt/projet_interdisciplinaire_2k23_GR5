<?php
require(__DIR__.'/../model/DbModel.class.php');
?>
<h1>Horaire</h1>
<a href=index.php?page=login.php>Connexion</a> <!-- Lien vers la page de connexion -->
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
        echo '<tr>';
        echo '<td>',$result->date,'</td>';
        echo '<td>De ',$result->debut,' à ',$result->fin,'</td>';
        echo '<td>',$result->nbre_heure,'</td>';
        echo '<td>Congé</td>';
        echo '</tr>';
    }
    ?>
</table>