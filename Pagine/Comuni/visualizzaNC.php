<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>Visualizza NC</title>
    <style> 
        #filtri{
            float:left;
            width:auto;
            display: inline-block;
        }

        form#filtri input{
            width: auto;
            height: auto;
        }
        b{
            padding:3px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script>
        $( document ).ready(function() {
            console.log( "ready!" );

            $( ".tblRows" ).click(function() {
                var row_data = $(this).attr("data");

                document.cookie = "colonna="+row_data;

                window.location="./Esegui/fai.php";
            });
        });
    </script>



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

    $connessione = mysqli_connect($servername, $username, $password, $dbname);
        
        if ($connessione->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }
    
        echo '<header>';
        if($_SESSION['role']=='Admin'){ 
            
                echo "<ul>
                    <li style=\"float:left;\"><a href=\"../Admin/registeracc.php\">Registra Account</a></li>
                    <li style=\"float:left;\"><a href=\"../Admin/modificaAccount.php\">Gestisci Account</a></li>
                    <li style=\"float:left;\"><a href=\"../Admin/registersegnalante.php\">Registra segnalante</a></li>
                    ";
        }
    
        echo "
        <li style=\"float:left;\"><a href=\"../Comuni/risolviNC.php\">Risolvi N.C.</a></li>
        <li style=\"float:left;\"><a href=\"../Comuni/visualizzaNC.php\">Visualziza N.C.</a></li>
        <li style=\"float:left;\"><a href=\""; if($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Dirigente') echo "../Utenti/dashboard.php"; else echo "../Dirigenti/dashboarddirigenti.php"; echo "\">Dashboard</a></li>
        <li style=\"float:right;\">{$_SESSION['username']}</li>  
        <li style=\"float: right;\"><a href=\"../Disconnessione/disconnetti.php\">Disconnettiti</a></li>
        </ul>";
        
         echo "</header>";

    $q = "SELECT * FROM ACCOUNT A JOIN RUOLO R ON A.ruolo=R.nome WHERE username='".$_SESSION["username"]."'";
    $grado = mysqli_query($connessione,$q);
    $grado = mysqli_fetch_assoc($grado);
    $grado = $grado['GRADOGESTIONE'];

    $q = "SELECT * FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.tipo=N.id WHERE gradoMinimo<='".$grado."' ORDER BY identificatore ASC";
    $risultato = mysqli_query($connessione, $q);
    
    /*Gerva */
    $dettagliSEGNALAZIONE="SELECT S.ID AS COD, DATACREAZIONE,DATACHIUSURA,AUTORE,STATO FROM SEGNALAZIONE AS S JOIN ACCOUNT AS A ON S.AUTORE=A.IDSEGNALANTE JOIN RUOLO AS R ON A.RUOLO=R.NOME WHERE $grado<=R.GRADOGESTIONE";
    $dettagliSEGNALAZIONE = mysqli_query($connessione,$dettagliSEGNALAZIONE);

    echo '<div id="title">Lista N.C.</div>';
   // da rimuovere il fatto che si veda tutto in corsivo

   echo '<div id="containernc" > 
   
   <div class="nc">
   
    <form id="filtri" action="./Esegui/fai.php" method="post">
        <input type="radio" name="isAperta" value="dataFineNotNull" ><label>Solo aperte</label>
        <input type="radio" name="isAperta" value="dataFineNull"><label>Solo chiuse</label><br>
        <input type="checkbox" name="filtri[]" value="soloMie"><label>Solo mie</label><br>
        <input type="submit" value="Applica">
    </form>
    </div><br><br><br>';
    echo '<div class="nc"><table class="actions" id="tabella">';
    echo '<tr><td><b>ID </b></td><td><b>Autore </b></td><td><b>Stato </b></td><td><b>Data Apertura </b></td><td><b>Data Chiusura </b></td></tr>';
    while($row = mysqli_fetch_assoc($dettagliSEGNALAZIONE)){
        
        //echo '<tr class="tblRows" data="'.$row[identificatore].'" style="cursor:pointer"><td>'.$row[identificatore].'</td><td>'.$row[Autore].'</td><td>'.$row[stato].'</td><td>'.$row[dataCreazione].'</td><td>'.$row[dataChiusura].'</td><td></td></tr>';
        echo "<tr style=cursor:pointer><td>$row[COD]</td><td>$row[AUTORE]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td><form method=\"GET\" action=\"../Utenti/dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"COD\" value=$row[COD]></form></td></tr>";
    }
    echo '</table></div></div>';

    //Qui vanno messe le cose per far vedere all'utente informazioni (chi se ne sta occupando ecc...)

        mysqli_close($connessione);
    ?>

</body>
</html>