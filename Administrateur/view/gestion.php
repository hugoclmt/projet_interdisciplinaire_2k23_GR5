<?php
$demande_conge = $controlleur_admin->recuperer_demande_conge(); //on recuperer toutes lse demandes de congé et maladie
if (is_array($demande_conge) || $demande_conge instanceof Countable) { //on verifie si on peut compter
$nbre_demande = count($demande_conge); //on compte le nbre de demande pour la bouvle
}

$types = $controlleur_admin->recuper_type(); //on recupere tout les types de metier qui existent
if (is_array($types) || $types instanceof Countable) { //de nv si on peut le compter
$nbre_types = count($types); //on compte
}

if (isset($_POST['accepter'])) { //si il appuie sur l'input accepter
    $id_conge = $_POST['id_conge']; // on stock l'id du conge
    $_SESSION['clicked_' . $id_conge] = 'accepter'; //on stock en session pour dire qu'on a appuie sur le bouton accepter
    $id = $_POST['id_user']; //on stock l'id de l'user
    $date = $_POST['date']; //on stock la date
    $controlleur_admin->accepter_conge($id,$date); //on appelle le controleur pour accepter la demande de conge
}

if (isset($_POST['refuser'])) {
    $id_conge = $_POST['id_conge'];
    $_SESSION['clicked_' . $id_conge] = 'refuser';
    $id = $_POST['id_user'];
    $date = $_POST['date']; //on stock la date
    $controlleur_admin->refuser_conge($id,$date);
}

$nbre_users = 0;
$afficherFormulaireEmployes = false; // Contrôle l'affichage du formulaire des employés
$msg = ""; // Message d'erreur

// Gestion de la soumission du premier formulaire
if (isset($_POST['submit'])) {
    // Récupération et validation des données du formulaire
    $date = $_POST['date'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $nbre = $_POST['nombre'];
    $id_type = $_POST['metierRappel'];

    // Récupération des employés selon le type sélectionné
    $users = $controlleur_admin->recuper_personnes_partype($id_type);
    if (!empty($users)) {
        $nbre_users = count($users);
        $afficherFormulaireEmployes = true;
    } else {
        $msg = "Aucun employé trouvé pour ce type de métier.";
    }
}

// Gestion de la soumission du second formulaire
if (isset($_POST['submitt'])) {
    $dateo = $_POST['date'];
    $debuto = $_POST['debut'];
    $fino = $_POST['fin'];
    if (!empty($_POST['id_use'])) {
        foreach ($_POST['id_use'] as $id_user) {
            $resultat = $controlleur_admin->rappeler_employe($id_user, $dateo, $debuto, $fino);
            // Vous pouvez ajouter ici un traitement pour `$resultat` si nécessaire
        }
        $msg = "Employés rappelés avec succès.";
    } else {
        $msg = "Aucun employé sélectionné.";
    }
}
?>

<div>
    <?php if (!$afficherFormulaireEmployes):?>
        <form method="post">
            <fieldset>
                <legend>Rappel des employés</legend>
                <?php if ($msg): ?><p><?php echo $msg; ?></p><?php endif; ?>
                <label for="date">Date</label>
                <input type="date" id="date" name="date"><br>

                <label for="debut">De</label>
                <input type="time" id="debut" name="debut"><br>

                <label for="fin">à</label>
                <input type="time" id="fin" name="fin"><br>

                <label for="nombre">Nombre d'employés</label>
                <input type="number" id="nombre" name="nombre"><br>

                <label for="metierRappel">Métier</label>
                <select id="metierRappel" name="metierRappel">
                <?php
                for ($j =0;$j<$nbre_types;$j++)
                {
                ?>
                    <option value="<?php echo $types[$j]['id_type'];?>"><?php echo $types[$j]['nom_type']?></option>
                    <?php
                }
                    ?>
                </select><br>
                <input type="submit" name="submit" value="Voir les employés disponibles">
            </fieldset>
        </form>
    <?php else: ?>
        <form method="post">
            <?php if ($msg): ?><p><?php echo $msg; ?></p><?php endif; ?>
            <?php for ($n = 0; $n < $nbre_users; $n++): ?>
                <label for="option<?php echo $n; ?>"><?php echo $users[$n]['identifiant']; ?></label>
                <input type="checkbox" id="option<?php echo $n; ?>" name="id_use[]" value="<?php echo $users[$n]['id_employe']; ?>">
            <?php endfor; ?>
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="debut" value="<?php echo $debut; ?>">
            <input type="hidden" name="fin" value="<?php echo $fin; ?>">
            <input type="submit" name="submitt" value="Confirmer">
        </form>
    <?php endif; ?>
</div>

<div>
    <fieldset>
        <legend>Demandes de congés</legend>
        <div>
            <table>
                <tr>
                    <th>Employé</th>
                    <th>Id employe</th>
                    <th>Date</th>
                    <th>Justification</th>
                    <th>Accepter/Refuser</th>
                </tr>
            <?php
            for ($i = 0; $i < $nbre_demande; $i++) {
                if ($demande_conge[$i]['congeconfirm'] == 'En attente'){
                    $id_conge = $demande_conge[$i]['id_conge'];
                    $id_employe = $demande_conge[$i]['id_employe'];
                    ?>
                    <tr>
                        <td>
                        <?php
                        $nom = $controlleur_admin->recuperer_nom($id_employe);
                        echo $nom[0]['nom'];
                        ?>
                        </td>
                        <td>
                        <?php echo $id_employe; ?>
                        </td>
                        <td>
                        <?php echo $demande_conge[$i]['date_conge']; ?>
                        </td>
                        <td>
                        <?php echo $demande_conge[$i]['justification']; ?>
                        </td>
                        <td>
                    <?php
                    if (!isset($_SESSION['clicked_' . $id_conge])) {
                        ?>
                        <form action="index.php" method="post" id='gestion_conge'>
                            <input type="hidden" name="id_user" value="<?php echo $id_employe; ?>">
                            <input type="hidden" name="id_conge" value="<?php echo $id_conge; ?>">
                            <input type="hidden" name="date" value="<?php echo $demande_conge[$i]['date_conge']; ?>"> 
                            <input type="submit" name="accepter" value="Accepter">
                            <input type="submit" name="refuser" value="Refuser">
                        </form>
                        <?php
                    } else {
                        "<br>"; echo " Décision déjà prise : " . $_SESSION['clicked_' . $id_conge];"<br>";
                    }
                    echo '</td></tr>';
                }
                }
            ?>
            </table>
        </div>
    </fieldset>
</div>