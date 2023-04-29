<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>Modifica Account</title>
</head> 
<boby>
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
    $dbname = "my_bicicletta22235id";

    $connessione = new mysqli($servername,$username,$password,$dbname);

    if(!isset($_POST[ids]) || !isset($_POST[username]) || !isset($_POST[email]) || !isset($_POST[ruolo]) || !isset($_POST[telefono])){
        setcookie("validinsert","false",time() + 3000);
        header('location: ./modificaAccount.php');
        exit;
    }

    $id=$_POST[ids];
    $user=$_POST[username];
    $email=$_POST[email];
    $ruolo=$_POST[ruolo];
    $tel=$_POST[telefono];

    //$updateQ="UPDATE SEGNALANTE SET EMAIL='$email', TELEFONO='$tel' WHERE ID=$id; UPDATE ACCOUNT SET USERNAME='$user', RUOLO='$ruolo' WHERE IDSEGNALANTE=$id;";
    $updateQ="UPDATE 
                SEGNALANTE, ACCOUNT
            SET
                SEGNALANTE.EMAIL='$email', SEGNALANTE.TELEFONO='$tel', ACCOUNT.USERNAME='$user', ACCOUNT.RUOLO='$ruolo'
            WHERE
                SEGNALANTE.ID=ACCOUNT.IDSEGNALANTE AND SEGNALANTE.ID=$id AND ACCOUNT.USERNAME='$user';";
    //echo $updateQ;
    if($connessione->query($updateQ)){
        setcookie("validinsert","true",time() + 3000);
    }else{
        setcookie("validinsert","false",time() + 3000);
    }

    header('location: ./modificaAccount.php');
    
    ?>
</boby>
</html>

