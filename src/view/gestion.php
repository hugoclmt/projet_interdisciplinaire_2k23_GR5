<?php
require(__DIR__.'/../model/DbModel.class.php');
$db=new DbModel('localhost','projet_gr5','root','');
$pdo = $db->get_pdo();

$reqadmin=$pdo->prepare('SELECT admin FROM employes WHERE id_employe=:id'); //Requête pour savoir si l'employé est admin
$reqadmin->bindValue(':id',$_SESSION['id_employe']); //On récupère l'id de l'employé dans la session
$reqadmin->execute();
$reqadmin->setFetchMode(PDO::FETCH_OBJ);
$resultadmin=$reqadmin->fetch();
if($resultadmin->admin==0){ //Si l'employé n'est pas admin
    header('Location:index.php?page=horaire.php'); //On le redirige vers la page horaire.php
}
$reqadmin->closeCursor();


if (isset($_POST['submit'])){
    $identifiant=$_POST['nom'].'.'.$_POST['prenom']; //Identifiant = nom.prenom
    $req=$pdo->prepare('INSERT INTO employes (id_type,identifiant,mdp,admin) VALUES (:id_type,:identifiant,:mdp,:admin)');
    $req->bindValue(':id_type',$_POST['type']); //Lier le type à la requête d'insertion
    $req->bindValue(':identifiant',$identifiant); //Lier l'identifiant à la requête d'insertion
    $req->bindValue(':mdp',sha1($_POST['mdp'])); //Lier le mot de passe à la requête d'insertion
    if(isset($_POST['admin'])){ 
        $req->bindValue(':admin',1); //Lier le statut admin à la requête d'insertion
    }
    else{
        $req->bindValue(':admin',0); //idem
    }
    $req->execute();
    $req->closeCursor();
    header('Location:index.php?page=gestion.php');
    exit();
}
if (isset($_POST['etat'])){ //Si l'admin a accepté ou refusé une demande de congé
    $req=$pdo->prepare('UPDATE jour_horaire SET congeconfirm=:etat WHERE id_employe=:id AND date=:date'); //Requête pour mettre à jour la table jour_horaire
    $req->bindValue(':etat',$_POST['etat']);
    $req->bindValue(':id',$_POST['id_employe']);
    $req->bindValue(':date',$_POST['date']);
    $req->execute();
    $req->closeCursor();
    header('Location:index.php?page=gestion.php');
    exit();
}
?>
<section>
<h2>Gestion</h2>
<a href=index.php?page=horaire.php>Retour</a> <!-- Lien vers la page horaire.php -->
<div>
<h3>Ajouter un employé</h3>
<form method="post"> <!-- Formulaire pour ajouter un employé -->
    <label for="nom">Nom</label>
    <input type="text" name="nom" id="nom" required>
    <label for="prenom">Prénom</label>
    <input type="text" name="prenom" id="prenom" required>
    <label for="mdp">Mot de passe</label>
    <input type="password" name="mdp" id="mdp" required>
    <label for="type">Métier</label>
    <select name="type" id="type">
        <?php
        
        $req=$pdo->prepare('SELECT nom_type,id_type FROM type'); //Requête pour récuperer les types
        $req->execute();
        $req->setFetchMode(PDO::FETCH_OBJ);
        while ($result=$req->fetch() ) {
            echo '<option value="',$result->id_type,'">',$result->nom_type,'</option>'; //On affiche les types dans une liste déroulante
        }
        $req->closeCursor();
        ?>
    </select>
    <label for="admin">Admin</label>
    <input type="checkbox" name="admin" id="admin">
    <input type="submit" name="submit" value="Ajouter">
</form>
</div>
<div>
<h3>Demandes de congés</h3>
<table>
<?php
$req=$pdo->prepare('SELECT employes.id_employe,employes.identifiant,jour_horaire.date,jour_horaire.justification FROM jour_horaire LEFT JOIN employes ON jour_horaire.id_employe=employes.id_employe WHERE jour_horaire.conge=1 AND jour_horaire.congeconfirm IS NULL');
$req->execute(); //Requête pour récuperer les demandes de congés en liant les employés et les jours des congés
$req->setFetchMode(PDO::FETCH_OBJ);
while ($result=$req->fetch() ) {
    $nomprenom = explode('.',$result->identifiant); //On sépare le nom et le prénom
    echo '<tr>';
    echo '<td>',ucfirst($nomprenom[0]),' ',ucfirst($nomprenom[1]),'</td>'; //On affiche le prénom et le nom (ucfirst pour mettre la première lettre en majuscule)
    echo '<td>',$result->date,'</td>';
    echo '<td>',$result->justification,'</td>';
    echo '<form method="post">';
    echo '<input type="hidden" name="id_employe" value="'.$result->id_employe.'"></input>';
    echo '<input type="hidden" name="date" value="'.$result->date.'"></input>';
    echo '<td><button name="etat" value="1">Accepter</button></td>';
    echo '<td><button name="etat" value="0">Refuser</button></td>';
    echo '</form>';
    echo '</tr>';
}
$req->closeCursor();
?>
</table>
</div>
</section>