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
            width:50%;
        } 
        #container{
            border-radius: 10px;
            display: flex;
            flex-wrap: wrap;
            align-items:baseline;
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

        #input{
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
    echo '<header>';
    if($_SESSION['role']=='Admin'){ 
        
            echo "<ul>
                <li style=\"float:left;\"><a href=\"../Admin/registeracc.php\">Registra Account</a></li>
                <li style=\"float:left;\"><a href=\"../Admin/modificaAccount.php\">Gestisci Account</a></li>
                <li style=\"float:left;\"><a href=\"../Admin/registersegnalante.php\">Registra segnalante</a></li>";
    }

    echo "
    <li style=\"float:left;\"><a href=\"../Comuni/risolviNC.php\">Risolvi N.C.</a></li>
    <li style=\"float:left;\"><a href=\"../Comuni/visualizzaNC.php\">Visualziza N.C.</a></li>
    <li style=\"float:left;\"><a href=\""; if($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Dirigente') echo "../Utenti/dashboard.php"; else echo "../Dirigenti/dashboarddirigenti.php"; echo "\">Dashboard</a></li>
    <li style=\"float:right;\">{$_SESSION['username']}</li>  
    <li style=\"float: right;\"><a href=\"../Disconnessione/disconnetti.php\">Disconnettiti</a></li>
    </ul>";
    
     echo "</header>";

    /*Query:*/

    $dettagliACCOUNT = "SELECT * FROM ACCOUNT AS A JOIN SEGNALANTE AS S ON A.IDSEGNALANTE=S.ID WHERE A.IDSEGNALANTE=$_POST[idselect]";
    $dettagliACCOUNT = mysqli_query($connessione, $dettagliACCOUNT);
    $dettagliACCOUNT = mysqli_fetch_assoc($dettagliACCOUNT);

    echo '<div id="title">Modifica Account</div>';
        echo "<div id='container'>";
                
                

        echo"</div>";
    ?>
</boby>
</html>