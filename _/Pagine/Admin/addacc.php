<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="./script/index.js"></script>
    <title>PortaleNC - Login</title>
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

    // controlla connessione
    $connessione = mysqli_connect($servername, $username, $password, $dbname);
        
        if ($connessione->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }
        
        
        //variabili
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $ruolo = $_POST['ruolo'];
        $id = $_POST['segnalante'];

        $userpdo_q = "INSERT INTO `ACCOUNT`(`USERNAME`, `PASSWORD`, `RUOLO`, `IDSEGNALANTE`) values('{$username}','{$password}','{$ruolo}',{$id}) ";
        $userpdo = $connessione->query($userpdo_q);
        if($userpdo){
            echo "Registrazione avvenuta con successo!";
        }else{
            echo "Registrazione fallita, utente già esistente.";
        }

    
?>
    
</body>
</html>