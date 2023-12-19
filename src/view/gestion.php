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
if (isset($_POST['voiremployes'])){
header('Location:index.php?page=gestion.php'); //On redirige vers la page gestion.php
exit();
}

if (isset($_POST['ajouter'])){ //Si l'admin a ajouté un employé
    $identifiant=$_POST['nom'].'.'.$_POST['prenom']; //Identifiant = nom.prenom
    $req=$pdo->prepare('INSERT INTO employes (id_type,identifiant,mdp,admin) VALUES (:id_type,:identifiant,:mdp,:admin)');
    $req->bindValue(':id_type',$_POST['type']); //Lier le type à la requête d'insertion
    $req->bindValue(':identifiant',$identifiant); //Lier l'identifiant à la requête d'insertion
    $req->bindValue(':mdp',hash('sha256',$_POST['mdp'])); //Lier le mot de passe à la requête d'insertion
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
    $req=$pdo->prepare('UPDATE conges SET congeconfirm=:etat WHERE id_employe=:id AND date=:date'); //Requête pour mettre à jour la table jour_horaire
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
<div id="ligne1">
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
    <input type="submit" name="ajouter" value="Ajouter">
</form>
</div>
<div>
<h3>Rappel des employés</h3>
<form method="post"> <!-- Formulaire pour selectionner la plage horaire et le type d'employés -->
    <label for="date">Date</label>
    <input type="date" name="date" id="date" required>
    <label for="heuredebut">De </label>
    <input type="time" name="heuredebut" id="heuredebut" required>
    <label for="heurefin"> à </label>
    <input type="time" name="heurefin" id="heurefin" required>
    <label for="nbre">Nombre d'employés</label>
    <input type="number" name="nbre" id="nbre" min="1" required>
    <label for="type">Métier</label>
    <select name="type" id="type" required>
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
    <input type="submit" name="voir_employes" value="Voir les employés disponibles">
</form>
</div>
</div>
<div>
<h3>Demandes de congés</h3>
<table>
<?php
$req=$pdo->prepare('SELECT conge,congeconfirm,conges.date,conges.id_employe,conges.justification,employes.identifiant FROM conges JOIN employes ON employes.id_employe=conges.id_employe WHERE congeconfirm IS NULL AND conge = 1 ORDER BY date ASC');
$req->execute(); //Requête pour récuperer les demandes de congés en liant les employés et les jours des congés
$req->setFetchMode(PDO::FETCH_OBJ);
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE); //Permet de traduire la date en français
while ($result=$req->fetch() ) {
    $timestamp= strtotime($result->date); //On convertit la date en timestamp
    $nomprenom = explode('.',$result->identifiant); //On sépare le nom et le prénom
    echo '<tr>';
    echo '<td>',ucfirst($nomprenom[0]),' ',ucfirst($nomprenom[1]),'</td>'; //On affiche le prénom et le nom (ucfirst pour mettre la première lettre en majuscule)
    echo '<td>',$formatter->format($timestamp),'</td>';
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
<?php
if (isset($_POST['voir_employes'])){
    echo '<div id=employesdispos>';
    echo '<h3 id=employes>Employés disponibles</h3>';
    $nbre=$_POST['nbre'];
    //Ecrit une requête SQL pour récuperer les employés disponibles dans la plage horaire selectionnée ou ceux qui ne sont pas JOIN dans la table jour_horaire
    $req=$pdo->prepare('SELECT employes.id_employe,identifiant,jour_horaire.nbre_heure FROM employes 
    LEFT OUTER JOIN jour_horaire ON employes.id_employe=jour_horaire.id_employe
    WHERE id_type=:type 
    AND jour_horaire.date=:date
    AND (employes.id_employe IN (
        SELECT employes.id_employe FROM jour_horaire
        WHERE date=:date AND ((debut<=:h_debut AND fin<=:h_debut) OR (debut>=:h_fin AND fin>=:h_fin
                                                                    )
                            )
                                )
        OR jour_horaire.id_employe is NULL
    )');

    $req->bindValue(':type',$_POST['type']);
    $req->bindValue(':date',$_POST['date']);
    $req->bindValue(':h_debut',$_POST['heuredebut'].':00');
    $req->bindValue(':h_fin',$_POST['heurefin'].':00');
    $req->execute();
    $req->setFetchMode(PDO::FETCH_OBJ);
    $nbreemployes=0;
    #Form qui contient tout les employés de la requête précédente dans des checkbox, on ne peut pas cocher plus de $nbre checkbox tout en affichant tout les employés
    echo '<form method="post">';
    while ($result=$req->fetch() ) {
        $nomprenom = explode('.',$result->identifiant); //On sépare le nom et le prénom
        echo '<div class=employedispo><input type="checkbox" name="employes[]" value="'
        .$result->identifiant.'">
        '.ucfirst($nomprenom[0]).' '.ucfirst($nomprenom[1]).' ' //On affiche le prénom et le nom (ucfirst pour mettre la première lettre en majuscule)
        .$result->nbre_heure.
        ' heures ce jour là</input></div><br>';
        $nbreemployes++;
    }
    echo '<input type="submit" name="selection" value="Valider"></input>';
    echo '</form>';
    $req->closeCursor();
}

?>
</section>