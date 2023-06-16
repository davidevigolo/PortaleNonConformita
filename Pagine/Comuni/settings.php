<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PortaleNC - Settings</title>
    <?php
    if($_COOKIE['colormode'] == 'b'){
        echo "<link rel=\"stylesheet\" href=\"../../style/dashboardb.css\">";
    }else{
        echo "<link rel=\"stylesheet\" href=\"../../style/dashboard.css\">";
    }
    ?>
</head>
<body>
<?php
    header("Cache-Control: no-cache, must-revalidate");
    session_start();

    if(!isset($_SESSION['valid'])){
        echo "<header style=\"background-color: rgb(199 50 50);\">Sessione scaduta, rieffettuare l'accesso.</header>";
        exit;
    }
    if(!$_SESSION['valid']){
        echo "<header style=\"background-color: rgb(199 50 50);\">Sessione invalida, rieffettuare l'accesso.</header>";
        exit;
    }

    require_once('../header.php');
    $header = new Header();
    $header->render($_SESSION[role],$_SESSION[username]);
?>
<div id="title">Settings</div>
<div id="container">
    <form action="./savesettings.php" method="POST">
        <div id="username" style="text-align: center; width: 100%; margin-bottom: 30px;">
            <label>Account: <?php echo $_SESSION['username']; ?></label>
            </div>
        <div id="colormode" style="text-align: left; width: 100%">
        <label>Modalità colori</label>
            <select class="selector" style="width: 100px; margin-left: 20px" name="colormode">
            <?php
            if($_COOKIE['colormode'] == 'd'){
                echo "<option value=\"d\" selected>Scura</option>";
                echo "<option value=\"b\">Chiara</option>";
            }else{
                echo "<option value=\"d\">Scura</option>";
                echo "<option value=\"b\" selected>Chiara</option>";
            }
            ?>
            </select>
        </div>
        <div id="colormode" style="text-align: left; width: 100%">
        <label>Durata sessione (minuti)</label>
            <input type="number" value=<?php echo $_COOKIE['ssduration'];?> placeholder="Durata in minuti" style="width: 50px; margin-left: 20px;" name="ssduration" placeholder=60 required>
        </div>
        <input type="submit" value="Salva modifiche">
    </form>
</div>