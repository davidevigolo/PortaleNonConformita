<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>Modifica Account</title>
    <style>
        div#container {
            width:70%;
        }

        @media screen and (max-width: 990px){
            #container{
                
            }
        }

        #info{
            text-align: left;
            padding: 7px;
            flex:30%;
        }

        #dati{
            border-radius: 3px;
            padding: 7px;
            flex:70%;
        }
    </style>
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

    if($_COOKIE['validinsert'] == "true"){
        echo "<header style=\"background-color: rgb(90, 242, 102);\">Aggiornamento riuscito!.</header>";
        setcookie('validinsert',"",time() - 3600);
    }elseif ($_COOKIE['validinsert'] == "false"){
        echo "<header style=\"background-color: rgb(199, 50, 50);\">Aggiornamento fallito, controllare i dati immessi.</header>";
        setcookie('validinsert',"",time() - 3600);
    }
    
    $indirizzo = "localhost";
    $user = "";
    $password = "";
    $db = "my_bicicletta22235id"; 



    $connessione = new mysqli($indirizzo, $user, $password, $db);
    // controlla connessione

    if ($connessione->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }
    $nomeutente= $_SESSION['username'];
    require_once('../header.php');
    $header = new Header();
    $header->render($_SESSION[role],$_SESSION[username]);

    /*Query:*/

    $dettagliACCOUNT = "SELECT * FROM ACCOUNT AS A JOIN SEGNALANTE AS S ON A.IDSEGNALANTE=S.ID";
    $dettagliACCOUNT = $connessione->query($dettagliACCOUNT);

    echo '<div id="title">Modifica Account</div>';

    echo '<div id="acoount" style="width: 80%; margin: auto;">';
                while($row = mysqli_fetch_assoc($dettagliACCOUNT)){
                    if(isset($_POST[idselect])&& $row[ID] == $_POST[idselect] && $row[USERNAME] == $_POST[userselect]){
                        echo "<div id=container style=flex: 48%; margin: 10px; text-align: left;> 
                            <form method=POST action=modificaAccount2.php>
                                <label>Id: </label><input type=text name=ids value=$row[ID]> <br>
                                <label>Username: </label><input type=text name=username value=$row[USERNAME]> <br>
                                <label>Email: </label><input type=email name=email pattern=^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ size=30 value=$row[EMAIL]><br>
                                <label>Ruolo: </label><input type=text name=ruolo value=$row[RUOLO]> <br>
                                <label>Telefono: </label><input type=tel name=telefono value=$row[TELEFONO] minlength=12 maxlength=12> <br>
                                <input type=\"submit\" value=\"Salva modifiche\">
                            </form>
                        </div> ";
                    }else{  
                        echo "<div id=container style=flex: 48%; margin: 10px; text-align: left;>
                            <label>ID: $row[ID]</label><br>
                            <label>Username: $row[USERNAME]</label><br>
                            <label>Email: $row[EMAIL]</label><br>
                            <label>Ruolo: $row[RUOLO]</label><br>
                            <label>Telefono: $row[TELEFONO]</label><br>
                            <form action=\"modificaAccount.php\" method=\"POST\">
                                <input type=\"hidden\" name=\"idselect\" value=\"$row[ID]\">
                                <input type=\"hidden\" name=\"userselect\" value=\"$row[USERNAME]\">
                                <input type=\"submit\" value=\"Modifica\">
                            </form>
                        </div>";
                    }
                }
    echo"</div>";
    ?>
</boby>
</html>