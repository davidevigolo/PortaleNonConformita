<?php
session_start();

if(!isset($_SESSION['valid'])){
    echo "<header style=\"background-color: rgb(199 50 50);\">Sessione scaduta, rieffettuare l'accesso.</header>";
    exit;
}
if(!$_SESSION['valid']){
    echo "<header style=\"background-color: rgb(199 50 50);\">Sessione invalida, rieffettuare l'accesso.</header>";
    exit;
}

setcookie('colormode',$_POST['colormode'],time() + 3600000,'/');
setcookie('ssduration',$_POST['ssduration'],time() + 3600000,'/');

header('location: ./settings.php');
exit();

?>