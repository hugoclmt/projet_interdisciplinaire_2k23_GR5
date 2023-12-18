<?php
require(__DIR__.'/../model/DbModel.class.php');
if (isset($_POST['submit'])){
    $db=new DbModel('localhost','projet_gr5','root','');
    $pdo = $db->get_pdo();
    $identifiant=$_POST['nom'].'.'.$_POST['prenom'];
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
?>
<section>
<h2>Gestion</h2>
<div>
<h3>Ajouter un employé</h3>
<form method="post">
    <label for="nom">Nom</label>
    <input type="text" name="nom" id="nom" required>
    <label for="prenom">Prénom</label>
    <input type="text" name="prenom" id="prenom" required>
    <label for="mdp">Mot de passe</label>
    <input type="password" name="mdp" id="mdp" required>
    <label for="type">Métier</label>
    <select name="type" id="type">
        <?php
        $db=new DbModel('localhost','projet_gr5','root','');
        $pdo = $db->get_pdo();
        $req=$pdo->prepare('SELECT nom_type,id_type FROM type');
        $req->execute();
        $req->setFetchMode(PDO::FETCH_OBJ);
        while ($result=$req->fetch() ) {
            echo '<option value="',$result->id_type,'">',$result->nom_type,'</option>';
        }
        $req->closeCursor();
        ?>
    </select>
    <label for="admin">Admin</label>
    <input type="checkbox" name="admin" id="admin" required>
    <input type="submit" name="submit" value="Ajouter">
</div>
</section>