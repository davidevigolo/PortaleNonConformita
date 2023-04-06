<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

    if(!isset($_POST['tipo'])){
        setcookie("validinsert",false, time() + 3000);
        exit;
    }
    $tipo = $_POST[tipo];
    $email = $_POST[email];
    $tel = $_POST[phone];
    
    
    $insertq = "INSERT INTO SEGNALANTE (TIPO,EMAIL,TELEFONO) VALUES('$tipo','$email','$tel')";
    
    if($connessione->query($insertq)){
        setcookie("validinsert","true", time() + 3000);
    }else{
        setcookie("validinsert","false", time() + 3000);
    }
    $idsegn = mysqli_insert_id($connessione);

    switch($tipo){
        case "I":
            Impiegato($connessione,$idsegn);
            break;
        case "C":
            Cliente($connessione,$idsegn);
            break;
        case "F":
            Fornitore($connessione,$idsegn);
            break;
    }

    header('location: ./compilasegnalante.php');
    

    function Impiegato($connessione,$idsegn){
        if(!isset($_POST[ddn]) || !isset($_POST[cognome]) || !isset($_POST[nome]) || !isset($_POST[cf]) || !isset($_POST[dassunzione]) || !isset($_POST[dlicenziamento])){
            setcookie("validinsert","false", time() + 3000);
            return;
        }
        $ddn = $_POST[ddn];
        $cognome = $_POST[cognome];
        $nome = $_POST[nome];
        $cf = $_POST[cf];
        $dassunzione = $_POST[dassunzione];
        $dlicenziamento = $_POST[dlicenziamento];
        $reparto = $_POST[reparto];

        $insertq = "INSERT INTO IMPIEGATO(IDSEGNALANTE,TIPO,COGNOME,NOME,DATAN,DATASSUNZIONE,DATALICENZIAMENTO,REPARTO) VALUES($idsegn,'$tipo','$cognome','$nome','$ddn','$dassunzione','$dlicenziamento','$reparto')";

        if($connessione->query($insertq)){
            setcookie("validinsert","true", time() + 3000);
        }else{
            setcookie("validinsert","false", time() + 3000);
        }
        
        return;
    }

    function Cliente($connessione,$idsegn){
        if(!isset($_POST[ddn]) || !isset($_POST[cognome]) || !isset($_POST[nome]) || !isset($_POST[cf])){
            setcookie("validinsert","false", time() + 3000);
            return;
        }
        $ddn = $_POST[ddn];
        $cognome = $_POST[cognome];
        $nome = $_POST[nome];
        $cf = $_POST[cf];

        $insertq = "INSERT INTO CLIENTE(DATAN,COGNOME,NOME,CODF,IDSEGNALANTE) VALUES('$ddn','$cognome','$nome','$cf',$idsegn)";

        if($connessione->query($insertq)){
            setcookie("validinsert","true", time() + 3000);
        }else{
            setcookie("validinsert","false", time() + 3000);
        }

        echo $insertq;
        return;
    }

    function Fornitore($connessione,$idsegn){
        if(!isset($_POST[piva]) || !isset($_POST[cap]) || !isset($_POST[denominazione]) || !isset($_POST[via])){
            setcookie("validinsert","false", time() + 3000);
            return;
        }
        $piva = $_POST[piva];
        $cap = $_POST[cap];
        $denominazione = $_POST[denominazione];
        $via = $_POST[via];

        $insertq = "INSERT INTO FORNITORE(PIVA,CAP,DENOMINAZIONE,VIA,IDSEGNALANTE) VALUES('$piva','$cap','$denominazione','$via',$idsegn)";

        if($connessione->query($insertq)){
            setcookie("validinsert","true", time() + 3000);
        }else{
            setcookie("validinsert","false", time() + 3000);
        }

        echo $insertq;
        return;
    }
    ?>
</body>
</html>