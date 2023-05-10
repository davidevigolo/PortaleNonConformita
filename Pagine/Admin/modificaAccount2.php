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

    //Update password

    if(isset($_POST[newPass]) && $_POST[pass]==true && isset($_POST[user])){
        $password = password_hash($_POST[newPass], PASSWORD_BCRYPT);
        $updateP = "UPDATE ACCOUNT 
                    SET ACCOUNT.PASSWORD='$password' 
                    WHERE USERNAME = '$_POST[user]';";
        if($connessione->query($updateP)){
            setcookie("validinsert","true",time() + 3000);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }
        header('location: ./modificaAccount.php');
    }

    //Update dati account

    if(isset($_POST[ids]) && isset($_POST[username])){
        $oldid=$_POST[idselect];
        $olduser=$_POST[userselect];
        $newid=$_POST[ids];
        $newuser=$_POST[username];
        $email=$_POST[email];
        $ruolo=$_POST[ruolo];
        $tel=$_POST[telefono];

        $updateQ="UPDATE 
                    SEGNALANTE, ACCOUNT
                SET
                    SEGNALANTE.EMAIL='$email', SEGNALANTE.TELEFONO='$tel', SEGNALANTE.ID=$newid, ACCOUNT.USERNAME='$newuser', ACCOUNT.RUOLO='$ruolo'
                WHERE
                    SEGNALANTE.ID=$oldid AND ACCOUNT.USERNAME='$olduser';";
        //echo $updateQ;
        if($connessione->query($updateQ)){
            setcookie("validinsert","true",time() + 3000);
        }else{
            setcookie("validinsert","false",time() + 3000);
        }

        header('location: ./modificaAccount.php');
    }
    
    
    ?>
</boby>
</html>

