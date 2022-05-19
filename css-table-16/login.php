<?php
session_start();
if(isset($_SESSION["tuvastamine"])){
    header("Location: index.html");
    exit();
}
if(!empty($_POST["login"]) && !empty($_POST["pass"])){
    $login=$_POST["login"];
    $pass=$_POST["pass"];
    $sool="vagavagatekst";
    $krypt=crypt($pass, $sool);
    require("conf.php");
    global $yhendus;
    $kask=$yhendus->prepare("SELECT nimi, koduleht FROM chels WHERE nimi=? AND parool=?");
    $kask->bind_param("ss", $login, $krypt);
    $kask->bind_result($nimi, $koduleht);
    $kask->execute();
    if($kask->fetch()){
        $_SESSION["tuvastamine"]="niilihtne";
        $_SESSION["kasutaja"]=$nimi;
        if(isset($koduleht)){
            header("Location: $koduleht");
        } else{
            header("Location: kontakt.php");
            exit();
        }
    } else{
        echo "username $login or password $krypt is wrong";
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" alt="css">
</head>
<body>
<h1>Login</h1>
<form action="" method="post">
    Login:
    <input type="text" name="login" placeholder="your login">
    <br>
    Password:
    <input type="password" name="pass">
    <br>
    <input type="submit" value="Log-in">
</form>
</body>
</html>