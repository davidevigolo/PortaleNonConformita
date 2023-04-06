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
        #container{
            border-radius: 10px;
            display: flex;
            flex-wrap: wrap;
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

    $dettagliACCOUNT = "SELECT * FROM ACCOUNT AS A JOIN SEGNALANTE AS S ON A.IDSEGNALANTE=S.ID";
    $dettagliACCOUNT = mysqli_query($connessione, $dettagliACCOUNT);

    echo '<div id="title">Modifica Account</div>';

    echo '<div id="acoount" style="width: 80%; margin: auto;">';

        echo "<div id='container'>";
                while($row = mysqli_fetch_assoc($dettagliACCOUNT)){
                    if(isset($_POST[idselect])){
                        echo "<div id=info> 
                            <form method=POST action=modificaAccount.php>
                                <label>Id: <input type=text name=ID value=$dettagliACCOUNT[ID]></label> <br>
                                <label>Username: <input type=text name=USERNAME value=$dettagliACCOUNT[USERNAME]></label> <br>
                                <label>Email: <input type=email pattern=^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$ size=30 name=EMAIL value=$dettagliACCOUNT[EMAIL]></label><br>
                                <label>Ruolo: <input type=text name=RUOLO value=$dettagliACCOUNT[RUOLO]></label> <br>
                                <label>Telefono: <input type=text name=TELEFONO value=$dettagliACCOUNT[TELEFONO]></label> <br>
                            </form>
                        </div> ";
                    }else{  
                        echo "<div id='info' >
                            <label>Id: </label> <br>
                            <label>Username: </label> <br>
                            <label>Email: </label> <br>
                            <label>Ruolo: </label> <br>
                            <label>Telefono: </label> <br>
                        </div>
                        <div id='dati'>
                            <label>$row[ID]</label> <br>
                            <label>$row[USERNAME]</label> <br>
                            <label>$row[EMAIL]</label> <br>
                            <label>$row[RUOLO]</label> <br>
                            <label>$row[TELEFONO]</label> <br>
                        </div>
                        <div style='flex:100%;'>
                            <form action=\"modificaAccount2.php\" method=\"POST\">
                                <input type=\"hidden\" name=\"idselect\" value=\"$row[ID]\">
                                <input type=\"submit\" value=\"Modifica\">
                            </form>
                        </div>";
                    }

                    $id=$_POST[ID];
                    $user=$_POST[USERNAME];
                    $email=$_POST[EMAIL];
                    $ruolo=$_POST[RUOLO];
                    $tel=$_POST[TELEFONO];

                    $update="UPDATE ACCOUNT, SEGNALANTE SET ACCOUNT.IDSEGNALANTE=$id, ACCOUNT.USERNAME=$user, SEGNALANTE.EMAIL=$email, SEGNALANTE.TELEFONO=$tel FROM ACCOUNT AS A, SEGNALANTE AS S WHERE A.IDSEGNALANTE=S.ID AND A.IDSEGNALANTE=$_POST[idselect]";
                    if($connessione->query($update)){
                        setcookie("validinsert","true",time() + 3000);
                    }else{
                        setcookie("validinsert","false",time() + 3000);
                    }
                }
        echo"</div>";
    echo"</div>";
    ?>
</boby>
</html>