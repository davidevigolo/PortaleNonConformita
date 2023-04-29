
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
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
        $deleteq = "DELETE FROM SEGNALANTE WHERE ID=$_POST[id]";
        if($connessione->query($deleteq)){
            setcookie("validinsert","true",time() + 3000);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }
        echo $deleteq;
        header('location: ./modificasegnalante.php');
        exit;
    }

    if(!isset($_POST[email]) || !isset($_POST[tel]) || !isset($_POST[idsegn])){
        setcookie("validinsert","false",time() + 3000);
        header('location: ./modificasegnalante.php');
        exit;
    }

    $email = $_POST[email];
    $tipo = $_POST[tipo];
    $tel = $_POST[tel];

    $updateq = "UPDATE SEGNALANTE SET EMAIL='$email',TIPO='$tipo',TELEFONO='$tel' WHERE ID=$_POST[idsegn]";

    if($connessione->query($updateq)){
        setcookie("validinsert","true",time() + 3000);
    }else{
        setcookie("validinsert","false",time() + 3000);
    }

    header('location: ./modificasegnalante.php');
?>
</body>
</html>
