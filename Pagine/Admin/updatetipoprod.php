
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
        $deleteq = "DELETE FROM TIPOPRODOTTO WHERE SKU=$_POST[sku]";
        if($connessione->query($deleteq)){
            setcookie("validinsert","true",time() + 3000);
            $deleteq = "DELETE FROM FORNITURE WHERE SKU=$_POST[sku]";
            $connessione->query($deleteq);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }
        echo $deleteq;
        header('location: ./modificatipoprod.php');
        exit;
    }

    if(!isset($_POST[sku]) || !isset($_POST[tipo]) || !isset($_POST[desc]) || !isset($_POST[prezzo]) || !isset($_POST[fornitore])){
        setcookie("validinsert","false",time() + 3000);
        header('location: ./modificatipoprod.php');
        exit;
    }

    $sku = $_POST[sku];
    $oldsku = $_POST[oldsku];
    $tipo = $_POST[tipo];
    $desc = $_POST[desc];
    $prezzo = $_POST[prezzo];
    $fornitori = $_POST[fornitore];

    $updateq = "UPDATE TIPOPRODOTTO SET SKU=$sku,TIPO='$tipo',DESCRIZIONE='$desc',PREZZO=$prezzo WHERE SKU=$sku";

    if($connessione->query($updateq)){
        setcookie("validinsert","true",time() + 3000);
        $updatefornitureq = "DELETE FROM FORNITURE WHERE SKU = $oldsku";
        if(!$connessione->query($updatefornitureq)){
            setcookie("validinsert","false",time() + 3000);
        }else{
            setcookie("validinsert","true",time() + 3000); //SE TUTTE LE INSERT VANNO A BUON FINE NON VIENE CAMBIATO
            foreach($fornitori as $f){
                $insertfornitureq = "INSERT INTO FORNITURE(SKU,IDSEGNALANTE) VALUES($sku,$f)";
                if(!$connessione->query($insertfornitureq)){
                    setcookie("validinsert","false",time() + 3000);
                }
            }
        }

    }else{
        setcookie("validinsert","false",time() + 3000);
    }

    header('location: ./modificatipoprod.php');
?>
</body>
</html>
