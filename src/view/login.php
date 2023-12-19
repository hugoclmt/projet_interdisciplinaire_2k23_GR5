<?php
require(__DIR__.'/../model/DbModel.class.php');

$db=new DbModel('localhost','projet_gr5','root','');
$pdo = $db->get_pdo();
if(isset($_POST['connexion'])){ //Vérification de la connexion
    $req=$pdo->prepare('SELECT identifiant,mdp,id_employe FROM employes'); //Requête pour savoir récuperer l'identifiant, le mdp et l'id
    $req->execute();
    $req->setFetchMode(PDO::FETCH_OBJ);
    while ($result=$req->fetch() ) {
        if($result->identifiant==$_POST['username'] && $result->mdp==hash('sha256',($_POST['password']))){ //Si l'identifiant et le mdp sont corrects
            $_SESSION['id_employe']=$result->id_employe; //On enregistre l'id dans la session 
            header('Location:index.php?page=horaire.php');
            exit();
        }
    }
    echo "Identifiant ou mot de passe incorrect"; //Si l'identifiant ou le mdp sont incorrects
    $req->closeCursor();
}

?>
<form method="post">
    <input type="text" name="username" placeholder="Identifiant">
    <input type="password" name="password" placeholder="Mot de passe">
    <input type="submit" name ="connexion" value="Connexion">
</form>