<?php
require_once(__DIR__.'/../model/DbModel.class.php');
require(__DIR__.'/../controller/EmployeController.class.php');
$employe = new EmployeController();
$dbg=new DbModel('localhost','projet_gr5','root','');
$pdo = $dbg->get_pdo();

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
if (isset($_POST['selection'])){ //Si l'admin a selectionné des employés pour ajouter une plage horaire
    $employes=$_POST['employes'];
    $date=$_POST['date'];
    $heuredebut=$_POST['heuredebut'];
    $heurefin=$_POST['heurefin'];
    $nbre_heure=$_POST['nbreheure'];
    $type=$_POST['type'];
    $req=$pdo->prepare('INSERT INTO jour_horaire (id_employe,date,debut,fin,nbre_heure) VALUES (:id,:date,:debut,:fin,:nbre_heure)'); //Requête pour mettre à jour la table jour_horaire
    $req->bindValue(':date',$date); 
    $req->bindValue(':debut',$heuredebut); 
    $req->bindValue(':fin',$heurefin); 
    $req->bindValue(':nbre_heure',$nbre_heure); 
    foreach ($employes as $employe){ //Pour chaque employé selectionné
        $req2=$pdo->prepare('SELECT id_employe FROM employes WHERE identifiant=:identifiant'); //Requête pour récuperer l'id de l'employéµ
        $req2->bindValue(':identifiant',$employe); 
        $req2->execute();
        $req2->setFetchMode(PDO::FETCH_OBJ);
        $result=$req2->fetch();
        $req2->closeCursor();
        $req->bindValue(':id',$result->id_employe);
        $req->execute();
    }
    $req->closeCursor();
    header('Location:index.php?page=gestion.php');
    exit();
}
if (isset($_POST['ajouter'])){ //Si l'admin a ajouté un employé
    $identifiant=$_POST['prenom'].'.'.$_POST['nom']; //Identifiant = nom.prenom
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
    if ($_POST['etat']==1){ //Si l'admin a accepté la demande de congé
        $req=$pdo->prepare('UPDATE employes SET nbre_conges=nbre_conges+1 WHERE id_employe=:id'); //Augmenter le nombre de congés de 1
        $req->bindValue(':id',$_POST['id_employe']); //TODO ERREUR NBRE CONGES
        $req->execute();
        $req->closeCursor();
    }
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
<?php
$req=$pdo->prepare('SELECT conge,congeconfirm,conges.date,conges.id_employe,conges.justification,employes.identifiant FROM conges JOIN employes ON employes.id_employe=conges.id_employe WHERE congeconfirm IS NULL AND conge = 1 ORDER BY date ASC');
$req->execute(); //Requête pour récuperer les demandes de congés en liant les employés et les jours des congés
$req->setFetchMode(PDO::FETCH_OBJ);
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE); //Permet de traduire la date en français
if ($req->rowCount()==0){ //Si il n'y a aucune demande de congé
    echo '<p>Aucune demande de congé</p>';
}
else if($req->rowCount()>>0){ //Si il y a une demande de congé) {
    while ($result=$req->fetch() ) {
        $timestamp= strtotime($result->date); //On convertit la date en timestamp
        $nomprenom = explode('.',$result->identifiant); //On sépare le nom et le prénom
        echo '<table>';
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
        echo '</table>';
    }
}
$req->closeCursor();
?>

</div>
<?php
if (isset($_POST['voir_employes'])){ //Si l'admin a selectionné une plage horaire et un type d'employés
    echo '<div id=employesdispos>';
    echo '<h3 id=employes>Employés disponibles</h3>';
    $t1 = new DateTime($_POST['heuredebut'].':00'); //On convertit l'heure de début en DateTime
    $t2 = new DateTime($_POST['heurefin'].':00'); //On convertit l'heure de fin en DateTime
    $nbreheure = $t2->diff($t1)->format('%H:%I:%S'); //On calcule la différence entre les deux heures

    $ddate = date($_POST['date']); //On récupère la date
    $date = new DateTime($ddate);
    $week = $date->format("W"); //On récupère le numéro de la semaine de la date fournie pour calculer la somme des heures de la semaine des employés

    $nbre=$_POST['nbre'];
    // requête SQL pour récuperer les employés disponibles dans la plage horaire selectionnée
    // OU ceux qui ne travaillent pas pendant la plage horaire sélectionnée malgré le fait qu'ils aient plusieurs enregistrements dans leurs horaire (XOR)
    // OU ceux qui n'ont pas d'horaire ce jour là (Pas d'enregistrement dans le join)
    $req=$pdo->prepare('SELECT employes.id_employe,identifiant,SUM(jour_horaire.nbre_heure) as nbre_heure FROM employes 
    LEFT OUTER JOIN jour_horaire ON employes.id_employe=jour_horaire.id_employe
    WHERE jour_horaire.date=:date
    AND (employes.id_employe NOT IN (
        SELECT jour_horaire.id_employe FROM jour_horaire
        WHERE date=:date AND ((debut <= :h_debut AND ( fin >=:h_debut AND fin <=:h_fin))
        OR ((debut >= :h_debut AND debut <= :h_fin) AND (fin >= :h_debut AND fin <= :h_fin))
        OR (fin>=:h_fin AND (debut>=:h_debut AND debut<=:h_fin))
        OR (debut<=:h_debut AND fin>=:h_fin)
                                    )) OR (debut IS NULL OR fin IS NULL))
        OR (employes.id_employe NOT IN (
        SELECT employes.id_employe FROM employes
        LEFT OUTER JOIN jour_horaire ON employes.id_employe=jour_horaire.id_employe
        WHERE date = :date
        GROUP BY employes.id_employe)
        AND id_type=:type)
        GROUP BY employes.id_employe');
    $req->bindValue(':type',$_POST['type']);
    $req->bindValue(':date',$_POST['date']);
    //$req->bindValue(':nbreheure',$nbreheure);
    $req->bindValue(':h_debut',$_POST['heuredebut'].':00');
    $req->bindValue(':h_fin',$_POST['heurefin'].':00');
    $req->execute();
    $req->setFetchMode(PDO::FETCH_OBJ);
    $nbreemployes=0;
    echo '<form method="post">';
    echo '<input type="hidden" name="date" value="'.$_POST['date'].'"></input>';
    echo '<input type="hidden" name="heuredebut" value="'.$_POST['heuredebut'].':00"></input>';
    echo '<input type="hidden" name="heurefin" value="'.$_POST['heurefin'].':00"></input>';
    echo '<input type="hidden" name="type" value="'.$_POST['type'].'"></input>';
    echo '<input type="hidden" name="nbreheure" value="'.$nbreheure.'"></input>';
    
    while ($result=$req->fetch() ) {
        if ($employe->heures_semaine($result->id_employe,$week) == NULL){
            $nomprenom = explode('.',$result->identifiant); //On sépare le nom et le prénom
            echo '<div class=employedispo><input type="checkbox" name="employes[]" value="' 
            .$result->identifiant.'">
            '.ucfirst($nomprenom[0]).' '.ucfirst($nomprenom[1]).', '; //On affiche le prénom et le nom (ucfirst pour mettre la première lettre en majuscule)
            echo ' n\'a pas d\'horaire ce jour là</input></div>';
        }
        else{
            $nbre_heure_semaine= $employe->heures_semaine($result->id_employe,$week); //On récupère le nombre d'heures de la semaine actuelle
            $temps=explode(':',$nbre_heure_semaine); //On sépare les heures, les minutes et les secondes
            $temps = DateInterval::createFromDateString($temps[0].' hours '.$temps[1].' minutes '.$temps[2].' seconds'); //On convertit le string en DateInterval
            $tempsmax= DateInterval::createFromDateString('38 hours 0 minutes 0 seconds'); //Pour tester si le temps est plus grand que 38 heures
            $date = new DateTime('00:00:00'); //On créer une date à 00:00:00 pour pouvoir comparer (car on ne sait pas comparer des DateInterval)
            
            if (date_add($date,$temps)>date_add($date,$tempsmax)){ 
                $nomprenom = explode('.',$result->identifiant); //On sépare le nom et le prénom
                echo '<div class=employedispo><input type="checkbox" name="employes[]" value="' 
                .$result->identifiant.'">
                '.ucfirst($nomprenom[0]).' '.ucfirst($nomprenom[1]).', '; //On affiche le prénom et le nom (ucfirst pour mettre la première lettre en majuscule)
                if ($result->nbre_heure==NULL){
                    echo ' n\'a pas d\'horaire ce jour là</input></div>';
                }
                else{
                    $nbre_heure_jour = $result->nbre_heure;
                    $nbre_heure_jour = substr_replace($nbre_heure_jour,':',-2,0); //On place un ':' entre les heures et les minutes
                    $nbre_heure_jour = substr_replace($nbre_heure_jour,':',-5,0); //On place un ':' entre les heures et les minutes
                    echo $nbre_heure_jour.' heures ce jour là</input></div>';  //TODO AJOUTER LA LIMITE DE PERSONNEL
                }
                $nbreemployes++;
            }
        }
    }
    echo '<input type="submit" name="selection" value="Valider"></input>';
    echo '</form>';
    $req->closeCursor();
}
?>
</section>