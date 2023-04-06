
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
        $deleteq = "DELETE FROM PRODOTTO WHERE ID=$_POST[id]";
        if($connessione->query($deleteq)){
            setcookie("validinsert","true",time() + 3000);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }
        echo $deleteq;
        header('location: ./modificaprodotti.php');
        exit;
    }

    if(!isset($_POST[id]) || !isset($_POST[tipo]) || !isset($_POST[lotto])){
        setcookie("validinsert","false",time() + 3000);
        header('location: ./modificaprodotti.php');
        exit;
    }

    $id = $_POST[id];
    $tipo = $_POST[tipo];
    $lotto = $_POST[lotto];

    $updateq = "UPDATE PRODOTTO SET ID=$id,TIPO=$tipo,LOTTO=$lotto WHERE ID=$id";

    if($connessione->query($updateq)){
        setcookie("validinsert","true",time() + 3000);
    }else{
        setcookie("validinsert","false",time() + 3000);
    }

    header('location: ./modificaprodotti.php');
?>
</body>
</html>
