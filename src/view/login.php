<?php
if (isset($_POST['connexion']))
{
    if ($_SESSION['crsf_token'] === $_POST['crsf'])
    {
        if (!empty($_POST['username']) && !empty($_POST['password']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $controller->connexionDB($username,$password);
        }else{
            echo "Le nom d'user ou le mot de passe est vide ";
        }
    }else{
        echo "Erreur dans le token";
    }
}
?>
<form method="post" id='form_connect'>
    <input type="text" name="username" placeholder="Identifiant" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="hidden" name="crsf" value="<?php echo $crsf_token;?>">
    <input type="submit" name ="connexion" value="Connexion">
    <?php if (isset($_SESSION['result']))
        {
            var_dump($_SESSION);
        }?>
</form>