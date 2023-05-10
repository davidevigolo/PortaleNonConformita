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
//inserire qui il fatto che cicli le option in base ai permessi della persona
        $a = "SELECT GRADOGESTIONE FROM RUOLO WHERE NOME='$_SESSION[role]'";
        $risultato = mysqli_query($connessione, $a);
        $risultato = mysqli_fetch_assoc($risultato);
        
   echo '<div id="containernc" > 
   
  
  
   <div class="nc">
   
    <form id="filtri" action="./Esegui/fai.php" method="post">
        <input type="radio" name="isAperta" value="APERTA" ><label>Solo aperte</label>
        <input type="radio" name="isAperta" value="CHIUSA"><label>Solo chiuse</label>
        <input type="radio" name="isAperta" value="IN APPROVAZIONE"><label>Solo in approvazione</label>
        <input type="radio" name="isAperta" value="tutte" checked><label>Tutte</label><br>
        <input type="radio" name="filtri" value="mie"><label>Solo mie</label>
        <input type="radio" name="filtri" value="coinvolto"><label>Solo quelle in cui sono coinvolto</label>
        <input type="radio" name="filtri" value="diAltri"><label>Solo quelle altrui</label>
        <input type="radio" name="filtri" value="tutte" checked><label>Tutte</label><br>

        <label>Grado massimo</label>
        <select name="grado" style="width:50px">

        ';
        $grado = $risultato[GRADOGESTIONE];
        for($i=1;$i<$grado;$i++){
            echo '<option>'.$i.'</option>';
        }
        echo '<option selected>'.$grado.'</option>';
        
        echo'
        </select><br>
        <input type="submit" value="Applica">
    </form>
    </div><br><br><br>';
    echo '<div class="nc"><table class="actions" id="tabella">';
    echo '<tr><td><b>ID </b></td><td><b>Autore </b></td><td><b>Stato </b></td><td><b>Data Apertura </b></td><td><b>Data Chiusura </b></td></tr>';
    

    //se l'utente è già andato almeno una volta in fai.php (ovvero ha premuto il bottone sopra) allora non mostrerà questo if, altrimenti mostrerà le query al suo interno
    if($_SESSION['usata']=='no'){
        /*
        $q = "SELECT * FROM ACCOUNT A JOIN RUOLO R ON A.ruolo=R.nome WHERE username='".$_SESSION["username"]."'";
        $grado = mysqli_query($connessione,$q);
        $grado = mysqli_fetch_assoc($grado);
        $grado = $grado['GRADOGESTIONE'];
        
        $q = "SELECT * FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.tipo=N.id WHERE gradoMinimo<='".$grado."' ORDER BY identificatore ASC";

        $dettagliSEGNALAZIONE="SELECT S.ID AS COD, DATACREAZIONE,DATACHIUSURA,AUTORE,STATO FROM SEGNALAZIONE AS S JOIN ACCOUNT AS A ON S.AUTORE=A.IDSEGNALANTE JOIN RUOLO AS R ON A.RUOLO=R.NOME WHERE $grado<=R.GRADOGESTIONE";
*/

        $q="SELECT DISTINCT SE.ID, SE.DATACREAZIONE, IFNULL(SE.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA, SE.AUTORE, SE.STATO FROM SEGNALAZIONE SE JOIN NONCONFORMITA NC ON SE.TIPO=NC.ID WHERE 1=1 AND $grado >= NC.GRADOMINIMO";

        /*Gerva */
        
        $dettagliSEGNALAZIONE = mysqli_query($connessione,$q);

        while($row = mysqli_fetch_assoc($dettagliSEGNALAZIONE)){
        
        //echo '<tr class="tblRows" data="'.$row[identificatore].'" style="cursor:pointer"><td>'.$row[identificatore].'</td><td>'.$row[Autore].'</td><td>'.$row[stato].'</td><td>'.$row[dataCreazione].'</td><td>'.$row[dataChiusura].'</td><td></td></tr>';
        echo "<tr style=cursor:pointer><td>$row[ID]</td><td>$row[AUTORE]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td><form method=\"POST\" action=\"../Utenti/dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"ID\" value=$row[ID]></form></td></tr>";
        }
    }else{
    


        //da modificare questa parte prima di finire


        $c = $_SESSION['c']; //il grado
        $a = $_SESSION['a'];
        $b = $_SESSION['b']; 


        if($b=='tutte'){
            $query = "SELECT DISTINCT SE.ID, SE.DATACREAZIONE, IFNULL(SE.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA, SE.AUTORE, SE.STATO FROM SEGNALAZIONE SE JOIN NONCONFORMITA NC ON SE.TIPO=NC.ID WHERE 1=1";
        }elseif($b=='mie'){
            $query = "SELECT DISTINCT SE.ID, SE.DATACREAZIONE, IFNULL(SE.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA, SE.AUTORE, SE.STATO FROM SEGNALAZIONE SE JOIN NONCONFORMITA NC ON SE.TIPO=NC.ID WHERE SE.AUTORE='{$_SESSION['idsegn']}'";
        }elseif($b=='coinvolto'){
            $query = "SELECT DISTINCT SE.ID, SE.DATACREAZIONE, IFNULL(SE.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA, SE.AUTORE, SE.STATO FROM SEGNALAZIONE SE JOIN GESTIONENC GE ON SE.AUTORE=GE.IDSEGNALANTE AND SE.ID=GE.IDSEGNALAZIONE JOIN ACCOUNT AC ON AC.IDSEGNALANTE=SE.AUTORE JOIN NONCONFORMITA NC ON SE.TIPO=NC.ID WHERE 1=1";
        }elseif($b=='diAltri'){
            $query = "SELECT DISTINCT SE.ID, SE.DATACREAZIONE, IFNULL(SE.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA, SE.AUTORE, SE.STATO FROM SEGNALAZIONE SE JOIN NONCONFORMITA NC ON SE.TIPO=NC.ID WHERE NOT SE.AUTORE='{$_SESSION['idsegn']}'";
        }else{
            //ciao (da scrivere errore come massimo ma non mettere niente qui)
        }


        //Qui il programma applica i filtri richiesti
        if($a=='tutte'){
            //$query="SELECT DISTINCT * FROM SEGNALAZIONE S JOIN GESTIONENC G ON S.ID=G.IDSEGNALAZIONE JOIN ACCOUNT A ON A.IDSEGNALANTE=S.AUTORE JOIN RUOLO R ON R.NOME=A.RUOLO WHERE '1'='1' AND A.USERNAME='{$_SESSION['username']}'";
            $query = $query . "";
        }else{ 
            //$query = "SELECT DISTINCT * FROM SEGNALAZIONE S JOIN GESTIONENC G ON S.ID=G.IDSEGNALAZIONE JOIN ACCOUNT A ON A.IDSEGNALANTE=S.AUTORE JOIN RUOLO R ON R.NOME=A.RUOLO WHERE STATO='{$a}' AND A.USERNAME='{$_SESSION['username']}'";
            $query = $query . " AND SE.STATO='{$a}'";
        }


        //Controlla la seconda parte dei filtri
       

        //controllo grado massimo

        //(SELECT R.GRADOGESTIONE FROM ACCOUNT A JOIN RUOLO R ON A.RUOLO=R.NOME WHERE A.IDSEGNALANTE={$_SESSION['idsegn']})

        $query = $query." AND {$c} <= NC.GRADOMINIMO";

        //controllo grado massimo

        if($a=='tutte'&&$b=='tutte'){
            $query = "SELECT DISTINCT SE.ID, SE.DATACREAZIONE, IFNULL(SE.DATACHIUSURA,'NON SPECIFICATA') AS DATACHIUSURA, SE.AUTORE, SE.STATO FROM SEGNALAZIONE SE JOIN NONCONFORMITA NC ON SE.TIPO=NC.ID WHERE 1=1";
            $query = $query." AND {$c} >= NC.GRADOMINIMO";
        }

        //Qui il programma invece mostra i risultati della megaquery pazzasgrava
        
        $risultato = $connessione ->query($query);

        //da sistemare la visualizzazione della tabella (è stata copiata da quella di prima ma non modificata)
        while($row = mysqli_fetch_assoc($risultato)){
            //echo '<tr class="tblRows" data="'.$row[identificatore].'" style="cursor:pointer"><td>'.$row[identificatore].'</td><td>'.$row[Autore].'</td><td>'.$row[stato].'</td><td>'.$row[dataCreazione].'</td><td>'.$row[dataChiusura].'</td><td></td></tr>';
            echo "<tr style=cursor:pointer><td>$row[ID]</td><td>$row[AUTORE]</td><td>$row[STATO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td><form method=\"POST\" action=\"../Utenti/dettagliNC.php\"><input type=submit value=\"Dettagli\"><input type=\"hidden\" name=\"ID\" value=$row[ID]></form></td></tr>";
        }
    }
    $_SESSION['usata']='no';
    echo '</table></div></div>';

    //Qui vanno messe le cose per far vedere all'utente informazioni (chi se ne sta occupando ecc...)

        mysqli_close($connessione);
    ?>

</body>
</html>