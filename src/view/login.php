<?php
if (isset($_POST['connexion'])) // Si on appuie sur se co
{
    if ($_SESSION['crsf_token'] === $_POST['crsf']) // Si le token est bon
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) // Si les champs ne sont pas vides
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $controller->connexionDB($username,$password); // On appelle la fonction connexionDB du controllerUser
        }else{
            echo "Le nom d'user ou le mot de passe est vide ";
        }
    }else{
        echo "Erreur dans le token";
    }
}
?>
<form method="post" id='form_connect'>
    <h1>Connexion</h1>
    <input type="text" name="username" class="test" placeholder="Identifiant" required>
    <input type="password" name="password" class ="test" placeholder="Mot de passe" required>
    <input type="hidden" name="crsf" value="<?php echo $crsf_token;?>">
    <input type="submit" id="bouton_connexion" name ="connexion" value="Se connecter">
</form>