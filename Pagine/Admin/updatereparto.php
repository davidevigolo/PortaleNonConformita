
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if($_COOKIE['colormode'] == 'b'){
        echo "<link rel=\"stylesheet\" href=\"../../style/dashboardb.css\">";
    }else{
        echo "<link rel=\"stylesheet\" href=\"../../style/dashboard.css\">";
    }
    ?>
    <title>PortaleNC - Esecuzione</title>
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
    if($_SESSION[role] != "Admin"){
        header('location: bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php');
    }

    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

    $connessione = new mysqli($servername,$username,$password,$dbname);

    if(isset($_POST[delete]) && $_POST[delete] == true){
        $deleteq = "DELETE FROM REPARTO WHERE NOME='$_POST[rep]'";
        if($connessione->query($deleteq)){
            setcookie("validinsert","true",time() + 3000);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }
        header('location: ./modificareparto.php');
        exit;
    }

    if(!isset($_POST[rep])){
        setcookie("validinsert","false",time() + 3000);
        header('location: ./modificareparto.php');
        exit;
    }

    $updateq = "UPDATE REPARTO SET NOME='$_POST[rep]' WHERE NOME='$_POST[repselect]'";

    if($connessione->query($updateq)){
        setcookie("validinsert","true",time() + 3000);
    }else{
        setcookie("validinsert","false",time() + 3000);
    }

    header('location: ./modificareparto.php');
?>
</body>
</html>
