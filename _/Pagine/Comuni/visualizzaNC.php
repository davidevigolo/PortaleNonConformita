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
    
        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);

    echo '<div id="title">Lista N.C.</div>';
   // da rimuovere il fatto che si veda tutto in corsivo

   echo '<div id="containernc" > 
   
   <div class="nc">
   
    <form id="filtri" action="./Esegui/fai.php" method="post">
        <input type="radio" name="isAperta" value="APERTA" ><label>Solo aperte</label>
        <input type="radio" name="isAperta" value="CHIUSA"><label>Solo chiuse</label>
        <input type="radio" name="isAperta" value="IN APPROVAZIONE"><label>Solo approvate</label>
        <input type="radio" name="isAperta" value="tutte" checked><label>Tutte</label><br>
        <input type="radio" name="filtri" value="mie"><label>Solo mie</label>
        <input type="radio" name="filtri" value="coinvolto"><label>Solo quelle in cui sono coinvolto</label>
        <input type="radio" name="filtri" value="diAltri"><label>Solo quelle altrui</label>
        <input type="radio" name="filtri" value="tutte" checked><label>Tutte</label><br>

        <label>Grado minimo</label>
        <select name="grado" style="width:50px">
        <option>1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>6</option>
        <option>7</option>
        <option>8</option>
        <option>9</option>
        <option>10</option>
        </select><br>
        <input type="submit" value="Applica">
    </form>
    </div><br><br><br>';
    echo '<div class="nc"><table class="actions" id="tabella">';
    echo '<tr><td><b>ID </b></td><td><b>Autore </b></td><td><b>Stato </b></td><td><b>Data Apertura </b></td><td><b>Data Chiusura </b></td></tr>';
    

    //se l'utente è già andato almeno una volta in fai.php (ovvero ha premuto il bottone sopra) allora non mostrerà questo if, altrimenti mostrerà le query al suo interno
    if($_SESSION['usata']=='no'){

        $q = "SELECT * FROM ACCOUNT A JOIN RUOLO R ON A.ruolo=R.nome WHERE username='".$_SESSION["username"]."'";
        $grado = mysqli_query($connessione,$q);
        $grado = mysqli_fetch_assoc($grado);
        $grado = $grado['GRADOGESTIONE'];
    
        $q = "SELECT * FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.tipo=N.id WHERE gradoMinimo<='".$grado."' ORDER BY identificatore ASC";
        $risultato = mysqli_query($connessione, $q);
        
        /*Gerva */
        $dettagliSEGNALAZIONE="SELECT S.ID AS COD, DATACREAZIONE,DATACHIUSURA,AUTORE,STATO FROM SEGNALAZIONE AS S JOIN ACCOUNT AS A ON S.AUTORE=A.IDSEGNALANTE JOIN RUOLO AS R ON A.RUOLO=R.NOME WHERE $grado<=R.GRADOGESTIONE";
        $dettagliSEGNALAZIONE = mysqli_query($connessione,$dettagliSEGNALAZIONE);
    

        while($row = mysqli_fetch_assoc($dettagliSEGNALAZIONE)){
        
        //echo '<tr class="tblRows" data="'.$row[identificatore].'" style="cursor:pointer"><td>'.$row[identificatore].'</td><td>'.$row[Autore].'</td><td>'.$row[stato].'</td><td>'.$row[dataCreazione].'</td><td>'.$row[dataChiusura].'</td><td></td></tr>';
        echo "<tr style=cursor:pointer><td>$row[COD]</td><td>$row[AUTORE]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td><form method=\"GET\" action=\"../Utenti/dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"COD\" value=$row[COD]></form></td></tr>";
        }
    }else{
    
        $c = $_SESSION['c']; //il grado
        $a = $_SESSION['a'];
        //Qui il programma applica i filtri richiesti
        if($a=='tutte'){
            $query="SELECT DISTINCT * FROM SEGNALAZIONE S JOIN GESTIONENC G ON S.ID=G.IDSEGNALAZIONE JOIN ACCOUNT A ON A.IDSEGNALANTE=S.AUTORE JOIN RUOLO R ON R.NOME=A.RUOLO WHERE '1'='1' AND A.USERNAME='{$_SESSION['username']}'";
        }else{ 
            $query = "SELECT DISTINCT * FROM SEGNALAZIONE S JOIN GESTIONENC G ON S.ID=G.IDSEGNALAZIONE JOIN ACCOUNT A ON A.IDSEGNALANTE=S.AUTORE JOIN RUOLO R ON R.NOME=A.RUOLO WHERE STATO='{$a}' AND A.USERNAME='{$_SESSION['username']}'";
        }


        //Controlla la seconda parte dei filtri
        if($_SESSION['b']=='tutte'){
            
        }elseif($_SESSION['b']=='mie'){
            $query = $query." AND S.AUTORE='{$_SESSION['idsegn']}'";
        }elseif($_SESSION['b']=='coinvolto'){
            $query = $query." AND G.IDSEGNALANTE='{$_SESSION['idsegn']}'";
        }elseif($_SESSION['b']=='diAltri'){
            
        }else{
            //ciao (da scrivere errore come massimo ma non mettere niente qui)
        }

        //controllo grado minimo
        //$query = $query."AND R.GRADOGESTIONE>'{$c}'";

        echo $query;

        //Qui il programma invece mostra i risultati della megaquery pazzasgrava
        
        $risultato = $connessione ->query($query);

        //da sistemare la visualizzazione della tabella (è stata copiata da quella di prima ma non modificata)
        while($row = mysqli_fetch_assoc($risultato)){
            //echo '<tr class="tblRows" data="'.$row[identificatore].'" style="cursor:pointer"><td>'.$row[identificatore].'</td><td>'.$row[Autore].'</td><td>'.$row[stato].'</td><td>'.$row[dataCreazione].'</td><td>'.$row[dataChiusura].'</td><td></td></tr>';
            echo "<tr style=cursor:pointer><td>$row[COD]</td><td>$row[AUTORE]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td><form method=\"GET\" action=\"../Utenti/dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"COD\" value=$row[COD]></form></td></tr>";
        }

    }
    $_SESSION['usata']='no';
    echo '</table></div></div>';

    //Qui vanno messe le cose per far vedere all'utente informazioni (chi se ne sta occupando ecc...)

        mysqli_close($connessione);
    ?>

</body>
</html>