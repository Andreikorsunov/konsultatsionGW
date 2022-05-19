<?php
$yhendus=new mysqli("d105616.mysql.zonevs.eu", "d105616_korsunov", "ak47crmpradmir", "d105616_andrei17");
session_start();
$error= $_SESSION["error"] ?? "";
function puhastaAndmed($data){
    $data=trim($data);
    $data=htmlspecialchars($data);
    $data=stripslashes($data);
    return $data;
}
if(isset($_REQUEST["knimi"])&& isset($_REQUEST["psw"])) {
    $login = puhastaAndmed($_REQUEST["knimi"]);
    $pass = puhastaAndmed($_REQUEST["psw"]);
    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);
    $kask = $yhendus->prepare("SELECT id, unimi, psw FROM uuedkasutajad
WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool);
    $kask->execute();
    if ($kask->fetch()) {
        $_SESSION["error"] = "Kasutaja on juba olemas";
        header("Location: $_SERVER[PHP_SELF]");
        $yhendus->close();
        exit();
    } else {
        $_SESSION["error"] = " ";
    }
    $kask = $yhendus->prepare("
INSERT INTO uuedkasutajad(unimi, psw, isadmin) 
VALUES (?,?,?)");
    $kask->bind_param("ssi", $login, $krypt, $_REQUEST["admin"]);
    $kask->execute();
    $_SESSION['unimi'] = $login;
    $_SESSION['admin'] = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registreerimisvorm</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="login.css">
</head>
<body>
<a href="#modal-opened" class="link-1" id="modal-closed">Uue kasutaja vorm</a>
<div class="modal-container" id="modal-opened">
    <div class="modal">
        <div class="modal__details">
            <form class="link-1" action="register.php" method="post">
                <img class="displayed" src="pildid/cr7.png" alt="cr7" width="100" height="100">
                <label for="knimi" class="modal__description">Kasutajanimi</label>
                <input type="text" class="modal__description" placeholder="Sisesta kasutajanimi"
                       name="knimi" id="knimi" required>
                <br>
                <label for="psw" class="modal__description">Parool</label>
                <input type="password" class="modal__description" placeholder="Sisesta parool"
                       name="psw" id="psw" required>
                <br>
                <label for="admin" class="modal__description">Kas teha admin?</label>
                <input type="checkbox" class="modal__description" name="admin" id="admin" value="1">
                <br>
                <input type="submit" class="modal__btn" value="Loo kasutaja">
                <br>
                <a href="#modal-closed" class="link-2"></a>
                <br>
                <button type="button"
                        onclick="window.location.href='temphaldus.php'"
                        class="modal__btn">Loobu</button>
                <strong> <?=$error ?></strong>
            </form>
        </div>
    </div>
</div>
</body>
</html>