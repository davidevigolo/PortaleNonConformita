
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

    $coinvoltoq = "SELECT count(*) AS C FROM GESTIONENC WHERE IDSEGNALANTE = {$_SESSION['idsegn']} AND IDSEGNALAZIONE = {$_POST['id']} }";
    $coinvolto = mysqli_fetch_assoc($connessione->query($coinvoltoq));
    $gradogestionencq = "SELECT GRADOMINIMO FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.TIPO=N.ID WHERE S.ID = $_POST[id]";
    $gradogestionenc = mysqli_fetch_assoc($connessione->query($gradogestionencq));
    if($coinvolto[C] <= 0 && $gradogestionenc[GRADOMINIMO] > $_SESSION[gradominimo]){
        header('location: http://bicicletta22235id.altervista.org/Pagine/Comuni/risolviNC.php');
        exit();
    }
    
    $idselect = $_POST[idselect];
    $selectlotto = $_POST[selectlotto];
    $idnc = $_POST[idnc];

    $aggiungiq = "INSERT INTO SEGNALAZIONEPROD(IDSEGNALAZIONE,IDPROD) VALUES($idnc,$idselect)";
    if($connessione->query($aggiungiq)){
        setcookie("validinsert","true",time() + 3000);
    }else{
        setcookie("validinsert","false",time() + 3000);
    }

    header('location: ./risolviNC.php');

    
?>
</body>
</html>
