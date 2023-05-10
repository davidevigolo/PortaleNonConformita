<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portale NC - Esecuzione</title>
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
    if($_SESSION['role'] != "Admin"){
        header("location: ./Pagine/Utenti/dashboard.php");
        exit;
    }
    
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
    
    $connessione = mysqli_connect($servername,$username,$password,$dbname);
    
    if($connessione->connect_error){
        die("Connessione fallita: " . $conn->connect_error);
    }

    if(!isset($_POST[nome])){
        setcookie("validinsert","false", time() + 3000);
        header('location: ./compilareparto.php');
        exit;
    }
    $nome = $_POST[nome];
    
    $insertq = "INSERT INTO REPARTO(NOME) VALUES('$nome')";
    echo $insertq;
    
    if($connessione->query($insertq)){
        setcookie("validinsert","true", time() + 3000);
    }else{
        setcookie("validinsert","false", time() + 3000);
    }

    header('location: ./compilareparto.php');
    
    ?>
</body>
</html>