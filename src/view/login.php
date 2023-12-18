<?php
if (isset($_POST['submitted']))
{
    if (!empty($_POST['username']) && !empty($_POST['password']))
    {
        $controller->connexion($_POST['username'],$_POST['password']);
    }
}
?>
<form method="post">
    <input type="text" name="username" placeholder="Identifiant">
    <input type="password" name="password" placeholder="Mot de passe">
    <input type="submit" name="submitted" value="Connexion">
</form>