<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>Dashboard</title>
    <style>
        label{
            font-size: 20px;
        }
        </style>
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

        $indirizzo = "localhost";
        $user = "";
        $password = "";
        $db = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
        
        
        
        $connessione = new mysqli($indirizzo, $user, $password, $db);
        // controlla connessione

        if ($connessione->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }
        //se la persona è admin fa vedere la pagina degli admin, altrimenti no
        /*Se la persona è admin allora fa vedere la pagina con tutte le non conformità, 
        altrimenti fa vedere solo quelle che riguardano a quel determinato utente secondo il power del suo ruolo*/
        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);



        $q = "SELECT * FROM ACCOUNT A JOIN RUOLO R ON A.RUOLO=R.NOME WHERE USERNAME='".$_SESSION['username']."'";
        $grado = mysqli_query($connessione,$q);
        $grado = mysqli_fetch_assoc($grado);
        
        $ID = $grado['IDSEGNALANTE'];
        $grado = $grado['GRADOGESTIONE'];

        /*Trova le non coformità che sono state aperte da quell'account*/
        $numNCAperte = "SELECT COUNT(*) AS aperte FROM SEGNALAZIONE WHERE AUTORE={$_SESSION['idsegn']}";  
        $numNCAperte  = mysqli_query($connessione, $numNCAperte);
        $numNCAperte = mysqli_fetch_assoc($numNCAperte);
        $numNCAperte = isset($numNCAperte['aperte']) ? $numNCAperte['aperte'] : 0;

        /*Trova le non coformità che sono in fase di approvazione da quell'account*/
        $numNCApprov = "SELECT COUNT(*) AS approv FROM SEGNALAZIONE WHERE AUTORE={$_SESSION['idsegn']} AND STATO = 'IN APPROVAZIONE'";  
        $numNCApprov  = mysqli_query($connessione, $numNCAperte);
        $numNCApprov = mysqli_fetch_assoc($numNCAperte);
        $numNCApprov = isset($numNCApprov['approv']) ? $numNCApprov['approv'] : 0;
        

        /*Trova le non conformità che sono state chiuse, di quell'account */
        $numNCChiuse = "SELECT COUNT(*) AS chiuse FROM SEGNALAZIONE WHERE AUTORE={$_SESSION['idsegn']} AND DATACHIUSURA IS NOT NULL AND STATO <> 'IN APPROVAZIONE' ";
        $numNCChiuse  = mysqli_query($connessione, $numNCChiuse);
        $numNCChiuse = mysqli_fetch_assoc($numNCChiuse);
        $numNCChiuse = isset($numNCChiuse['chiuse']) ? $numNCChiuse['chiuse'] : 0;


        /*Trova le non conformità che sono ancora in corso */
        $numNCInCorso = "SELECT COUNT(*) AS InCorso FROM SEGNALAZIONE WHERE AUTORE={$_SESSION['idsegn']} AND DATACHIUSURA IS NULL ";
        $numNCInCorso  = mysqli_query($connessione, $numNCInCorso);
        $numNCInCorso = mysqli_fetch_assoc($numNCInCorso);
        $numNCInCorso = isset($numNCInCorso['InCorso']) ? $numNCInCorso['InCorso'] : 0;

        
        $q = "SELECT DISTINCT S.ID as ID,S.STATO,S.DATACREAZIONE,S.DATACHIUSURA,A.USERNAME AS AUTORE 
                FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.TIPO=N.ID JOIN GESTIONENC G ON G.IDSEGNALAZIONE=S.ID JOIN ACCOUNT A ON A.IDSEGNALANTE=$_SESSION[idsegn]
                WHERE S.AUTORE={$_SESSION['idsegn']} OR G.IDSEGNALANTE=$_SESSION[idsegn]
                ORDER BY S.ID";
        $risultato = mysqli_query($connessione, $q);
        echo '<div id="title">Le proprie non conformità</div>';
    
        echo '<div id="containernc">';
        echo '<div class="nc"><table class="actions">
            <div class="head">
                <div>Proprio ID: '.$ID.'</div>
                <div>Nome: '.$_SESSION["username"].'</div>
                <div>Numero NC Totali: '.$numNCAperte.'</div>
                <div>Numero NC Chiuse: '.$numNCChiuse.'</div>
                <div>Numero NC In Corso: '.$numNCInCorso.'</div>
                <div>Numero NC In Approvazione: '.$numNCApprov.' </diV>
                <div>Proprio Grado: '.$_SESSION['role'].' ('.$grado.')</div>
            </div>';
        
            echo '<tr><td><b>ID</b></td><td><b>Stato</b></td><td><b>Data Apertura</b></td><td><b>Data Chiusura</b></td><td><b>Autore</b></td></tr>';
            while($row = mysqli_fetch_assoc($risultato)){
                //echo '<tr><form method="POST" action="dettagliNC.php"><td>'.$row[ID].'</td><td>'.$row[STATO].'</td><td>'.$row[DATACREAZIONE].'</td><td>'.$row[DATACHIUSURA].'</td><td>'.$row[USERNAME].'</td><td><input type=submit value="Dettagli"><input type="hidden" name="ID"></td></form></tr>';
                echo "<tr><td>$row[ID]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td>$row[AUTORE]</td><td><form method=\"POST\" action=\"dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"ID\" value=$row[ID]></td></form></tr>";
            }
            //questa parte servirà a mostrare le N.C. in cui si è coinvolti ma che non si sono necessariamente create
            if($_SESSION[role]=="Caporeparto"){
                $q = "SELECT G.IDSEGNALAZIONE AS ID,S1.DATACREAZIONE,IFNULL(S1.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA,A.USERNAME,S1.ID, S1.STATO FROM GESTIONENC G JOIN SEGNALAZIONE S1 ON G.IDSEGNALAZIONE=S1.ID JOIN ACCOUNT A ON S1.AUTORE=A.IDSEGNALANTE JOIN IMPIEGATO I ON S1.NCREPARTO=I.REPARTO AND I.IDSEGNALANTE=$_SESSION[idsegn] WHERE S1.AUTORE <> $_SESSION[idsegn]";
                $risultato = mysqli_query($connessione, $q);
                
            }else{
                $q = "SELECT G.IDSEGNALAZIONE AS ID,S1.DATACREAZIONE,IFNULL(S1.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA,A.USERNAME,S1.ID, S1.STATO FROM GESTIONENC G JOIN SEGNALAZIONE S1 ON G.IDSEGNALAZIONE=S1.ID JOIN ACCOUNT A ON S1.AUTORE=A.IDSEGNALANTE  WHERE G.IDSEGNALANTE=$_SESSION[idsegn] AND S1.AUTORE <> $_SESSION[idsegn]";
                $risultato = mysqli_query($connessione, $q);
            }
            
            
            /*Nella parte dell'else va aggiunto un colore diverso per la tabella dove vengono mostrate le N.C. dove l'utente è solo coinvolto */
            if(empty($risultato)){
                
            }else{
                echo '<tr><td colspan="6">A seguire le N.C. all\'interno delle quali si è coinvolti, ma non si è gli autori</td></tr>';
                while($row = mysqli_fetch_assoc($risultato)){
                    //echo '<tr><form method="POST" action="dettagliNC.php"><td>'.$row[ID].'</td><td>'.$row[STATO].'</td><td>'.$row[DATACREAZIONE].'</td><td>'.$row[DATACHIUSURA].'</td><td>'.$row[USERNAME].'</td><td><input type=submit value="Dettagli"><input type="hidden" name="ID"></td></form></tr>';
                    echo "<tr><td>$row[ID]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td>$row[USERNAME]</td><td><form method=\"POST\" action=\"dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"ID\" value=$row[ID]></td></form></tr>";
                }  
            }
        echo '</table></div></div>';

        mysqli_close($connessione);
    ?>





        <form action="compilanc.php" method="GET">
            <input type="submit" value="Segnala una non conformità" class="fullpage">
        </form>

    <!--
   

    <div class="title">Le tue non conformità</div>
    <div class="containernc">
        <div class="nc">
            <div class="head">
                <div>ID: 1231aaa4</div>
                <div>Tipo: Pisello verde</div>
                <div>Autore: StaMariaRossi</div>
                <div>Coinvolti: Tutti i plebei</div>
                <div>Stato: Eterno</div>
                <div>Grado minimo: Ascensore di ascensori</div>
            </div>
            <table class="actions">
                <tr>
                    <th>Descrizione</th>
                    <th>Data inizio</th>
                    <th>Data fine</th>
                    <th>Operante</th>
                </tr>
                <tr>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                </tr>
                <tr>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                </tr>
                <tr>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                </tr>
            </table>
        </div>

        <div class="nc">
            <div class="head">
                <div>ID: 1231aaa4</div>
                <div>Tipo: Pisello verde</div>
                <div>Autore: StaMariaRossi</div>
                <div>Coinvolti: Tutti i plebei</div>
                <div>Stato: Eterno</div>
                <div>Grado minimo: Ascensore di ascensori</div>
            </div>
            <table class="actions">
                <tr>
                    <th>Descrizione</th>
                    <th>Data inizio</th>
                    <th>Data fine</th>
                    <th>Operante</th>
                </tr>
                <tr>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                </tr>
                <tr>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                </tr>
                <tr>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                    <td>Prof</td>
                </tr>
            </table>
        </div>
    </div>
    -->
</body>
</html>