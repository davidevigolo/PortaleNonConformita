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
    if($_SESSION[role] != "Admin"){
        header('location: bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php');
    }
    
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
    
    $connessione = mysqli_connect($servername,$username,$password,$dbname);
    
    if($connessione->connect_error){
        die("Connessione fallita: " . $conn->connect_error);
    }

    if(!isset($_POST['tipo']) || !isset($_POST['sku']) || !isset($_POST['prezzo']) || !isset($_POST['fornitore'])){
        setcookie("validinsert",false, time() + 3000);
        header('location: ./compilatipoprod.php');
        exit;
    }
    $sku = $_POST[sku];
    $prezzo = $_POST[prezzo];
    $tipo = $_POST[tipo];
    $descrizione = isset($_POST[desc]) ? $_POST[desc] : "";
    $fornitore = $_POST[fornitore];
    
    $insertq = "INSERT INTO TIPOPRODOTTO(SKU,PREZZO,TIPO,DESCRIZIONE) VALUES($sku,$prezzo,'$tipo','$descrizione')";
    echo $insertq;
    
    if($connessione->query($insertq)){
        setcookie("validinsert","true", time() + 3000);
        foreach($fornitore as $f){
            $insertfornq = "INSERT INTO FORNITURE(SKU,IDSEGNALANTE) VALUES($sku,$f)";
            echo $insertfornq;
            if(!$connessione->query($insertfornq)){
                setcookie("validinsert","false", time() + 3000);
                $deleteq = "DELETE FROM TIPOPRODOTTO WHERE SKU=$sku";
                $connessione->query($deleteq);
            }
        }

    }else{
        setcookie("validinsert","false", time() + 3000);
    }

    header('location: ./compilatipoprod.php');
    
    ?>
</body>
</html>