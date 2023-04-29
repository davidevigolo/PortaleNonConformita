
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>PortaleNC - ModificaNC</title>
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

    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

    $connessione = new mysqli($servername,$username,$password,$dbname);

    if(isset($_POST[delprod])){
        $deleteq = "DELETE FROM SEGNALAZIONEPROD WHERE IDSEGNALAZIONE = $_POST[idnc] AND IDPROD = $_POST[idprod]";
        if($connessione->query($deleteq)){
            setcookie("validinsert","true",time() + 3000);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }
        header('location: ./risolviNC.php');
        exit();
    }

    if(isset($_POST[chgcoinv]) && isset($_POST[coinvolti])){
        $deleteq = "DELETE FROM GESTIONENC WHERE IDSEGNALAZIONE=$_POST[id]";
        $connessione->query($deleteq);
        foreach($_POST[coinvolti] as $cv){
            $insertq = "INSERT INTO GESTIONENC(IDSEGNALANTE,IDSEGNALAZIONE) VALUES($cv,$_POST[id])";
            setcookie("validinsert","true",time() + 3000);
            if(!$connessione->query($insertq)){
                setcookie("validinsert","false",time() + 3000);
                header('location: ./risolviNC.php');
                exit();
            }
        }
        header('location: ./risolviNC.php');
        exit();
    }

    if(isset($_POST[chgcoinv]) xor isset($_POST[coinvolti])){
        setcookie("validinsert","false",time() + 3000);
        header('location: ./risolviNC.php');
        exit();
    }

    $coinvoltoq = "SELECT count(*) AS C FROM GESTIONENC WHERE IDSEGNALANTE = {$_SESSION['idsegn']} AND IDSEGNALAZIONE = {$_POST['id']} }";
    $coinvolto = mysqli_fetch_assoc($connessione->query($coinvoltoq));
    $gradogestionencq = "SELECT GRADOMINIMO FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.TIPO=N.ID WHERE S.ID = $_POST[id]";
    $gradogestionenc = mysqli_fetch_assoc($connessione->query($gradogestionencq));
    if($coinvolto[C] <= 0 && $gradogestionenc[GRADOMINIMO] > $_SESSION[gradominimo]){
        header('location: http://bicicletta22235id.altervista.org/Pagine/Comuni/risolviNC.php');
        exit();
    }
    
    $tiponc = $_POST['tiponc'] != "" ? $_POST['tiponc'] : "NULL";

    $datachiusura = $_POST['df'] != "" ? $_POST['df'] : "NULL";
    $datacreazione = $_POST['dc'];

    $originefornitore = $_POST['orgforn'] != "" ? $_POST['orgforn'] : "NULL";
    $originereparto = $_POST['orgrep'] != "" ? $_POST['orgrep'] : "NULL";

    $stato= $_POST['stato'];
    $note = $_POST['note'];

    if($datachiusura == "NULL"){
        $updateq = "UPDATE SEGNALAZIONE SET TIPO={$tiponc},DATACHIUSURA={$datachiusura},DATACREAZIONE='{$datacreazione}',"; 
        if ($originereparto == "NULL"){
            $updateq .= "NCREPARTO=NULL";
        }
        else{
            $updateq .= "NCREPARTO='{$originereparto}'"; 
        } 
        $updateq .= ",NCFORNITORE={$originefornitore},NOTE='{$note}', STATO='{$stato}' WHERE ID={$_POST['id']}";
    }else{
        $updateq = "UPDATE SEGNALAZIONE SET TIPO={$tiponc},DATACHIUSURA='{$datachiusura}',DATACREAZIONE='{$datacreazione}',"; 
        if ($originereparto == "NULL"){
            $updateq .= "NCREPARTO=NULL";
        }else{
            $updateq.= "NCREPARTO='{$originereparto}'";   
        }
         $updateq .=",NCFORNITORE={$originefornitore},NOTE='{$note}',STATO='CHIUSA' WHERE ID={$_POST['id']}";
    }
    if($connessione->query($updateq)){
        echo "<div id=\"container\">Segnalazione aggiornata con successo!</br> <a href=\"https://bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php\">Torna alla dashboard</a></div>";
        setcookie("validinsert","true",time() + 3000);
        header('location: ./risolviNC.php');
        exit();
    }else{
        echo "<div id=\"container\">Si è verificato un errore durante l'aggiornamento, controllare i dati immessi.</br> <a href=\"https://bicicletta22235id.altervista.org/Pagine/Comuni/risolviNC.php\">Torna alla pagina di risoluzione delle non conformità</a></div>";
        setcookie("validinsert","false",time() + 3000);
        header('location: ./risolviNC.php');
        exit();
    }


    
?>
</body>
</html>
