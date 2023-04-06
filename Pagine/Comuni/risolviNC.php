<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>Risolvi NC</title>
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
        div.ac{
            flex-basis:24%;
            border: 2px solid green;
            border-radius: 5px;
            margin: 3px;
            box-shadow: 0px 0px 50px 20px rgb(40,40,40) inset; 
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
    <script>
        $(document).ready(() => {
            $('select.selector[name="selectlotto"]').change(function() {
                $(this).parent().submit();
            });
        })
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

        if($_COOKIE['validinsert'] == "true"){
            echo "<header style=\"background-color: rgb(90, 242, 102);\">Aggiornamento riuscito!.</header>";
            setcookie('validinsert',"",time() - 3600);
        }elseif ($_COOKIE['validinsert'] == "false"){
            echo "<header style=\"background-color: rgb(199, 50, 50);\">Aggiornamento fallito, controllare i dati immessi.</header>";
            setcookie('validinsert',"",time() - 3600);
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

    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

    $connessione = mysqli_connect($servername, $username, $password, $dbname);
    
    if ($connessione->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }
    ?>

    <div id="container" style="width:80%; display:flex; flex-wrap: wrap;">
        <label>SelezionaNC</label>
        <form method="POST" action="risolviNC.php" style="flex:100%;">
            <select name="idnc" class="selector" style="width:100%">
                <?php
                if($_SESSION['role']=='Dirigente'||$_SESSION['role']=='Caporeparto'||$_SESSION['role']=='Admin'){
                    $nonconformitaq = "SELECT S.ID,S.TIPO,S.NCREPARTO,S.NCFORNITORE,S.AUTORE FROM SEGNALAZIONE S JOIN NONCONFORMITA NC ON S.TIPO=NC.ID WHERE NC.GRADOMINIMO <= $_SESSION[gradominimo] AND STATO<>'CHIUSA'";
                }else{
                    $nonconformitaq = "SELECT S.ID,S.TIPO,S.NCREPARTO,S.NCFORNITORE,S.AUTORE FROM SEGNALAZIONE S JOIN NONCONFORMITA NC ON S.TIPO=NC.ID WHERE NC.GRADOMINIMO <= $_SESSION[gradominimo] AND STATO='APERTA'";
                }
                
                echo $nonconformitaq;
                $nonconformita = $connessione->query($nonconformitaq);
                echo $nonconformita->num_rows;
                while($row = mysqli_fetch_assoc($nonconformita)){
                    $idsegnalante = $row['AUTORE'];
                    $autoreq = "SELECT USERNAME FROM ACCOUNT WHERE IDSEGNALANTE={$idsegnalante}";
                    $res = mysqli_fetch_assoc($connessione->query($autoreq));
                    echo "<option value=\"{$row['ID']}\""; if($row[ID] == $_POST[idnc]) echo "selected";  echo ">{$row['ID']} - {$row['TIPO']} - {$res['USERNAME']} - {$row['NCREPARTO']} - {$row['NCFORNITORE']}</option>";
                }

                ?>
            </select>
            <input type="submit" value="Seleziona">
        </form>
        <?php
        
        if(!isset($_POST['idnc'])){
            exit();
        }
        $idNC = $_POST['idnc'];
        
        ?>
        <div id="info" style="text-align: left; flex: 30%;">
            <h2>Modifica le informazioni</h2></br>
            <?php
            $ncquery = "SELECT * FROM SEGNALAZIONE WHERE ID = $idNC";
            $ncresult = mysqli_fetch_assoc($connessione->query($ncquery));
            $id = $ncresult['ID'];
            $dataCreazione = $ncresult['DATACREAZIONE'];
            $dataFine = isset($ncresult['DATACHIUSURA']) ? $ncresult['DATACHIUSURA'] : "Non ancora terminata";
            $stato = $ncresult['STATO'];

            $idsegnalante = $ncresult['AUTORE'];
            $autoreq = "SELECT * FROM ACCOUNT WHERE IDSEGNALANTE={$idsegnalante}";
            $res = mysqli_fetch_assoc($connessione->query($autoreq));
            $autore = $res['USERNAME'];
            $originerep = $ncresult['NCREPARTO'] == "" ? null : $ncresult['NCREPARTO'];
            $origineforn = $ncresult['NCFORNITORE'] == "" ? null : $ncresult['NCFORNITORE'];
            $stato = $ncresult['STATO'] ==  "" ? null : $ncresult['STATO'];

            $coinvoltiq = "SELECT USERNAME FROM ACCOUNT AS A JOIN GESTIONENC AS G ON A.IDSEGNALANTE=G.IDSEGNALANTE WHERE IDSEGNALAZIONE = {$idNC}";
            $coinvolti = "";
            $coinvoltires = $connessione->query($coinvoltiq);

            while($row = mysqli_fetch_assoc($coinvoltires)){
                $coinvolti += $row['USERNAME'] + ",";
            }
            $note = $ncresult['NOTE'];

            /*VALORE ATTUALE TIPO NC */
            $tipoq = "SELECT * FROM NONCONFORMITA WHERE ID={$ncresult['TIPO']}";
            $tipo = mysqli_fetch_assoc($connessione->query($tipoq)); 

            /*LISTA VALORI NC DISPONIBILI (PER SELECT) */
            $tipiq = "SELECT * FROM NONCONFORMITA";
            $tipi = $connessione->query($tipiq);

            ?>
            <form class="chginfo" action="updateNC.php" method="POST">
            <?php
            if(isset($_POST['chginfo']) && $_POST['chginfo'] == true){
                /*Id Segnalazione*/
                echo "<label>ID: ({$id})</label></br>";
                echo "<input type=\"text\" placeholder=\"ID: {$id}\" readonly></br>";
                echo "<input type=\"hidden\" name=\"id\" value=\"{$id}\">";
                /*Tipo NC*/
                echo "<label>Tipo non conformità: ({$tipo['NOME']})</label></br>";
                echo "<select name=\"tiponc\" class=\"selector\">";
                if($tipo == null){
                echo "<option disable selected value>Non ancora impostato</option>";
                }

            
                while($row = mysqli_fetch_assoc($tipi)){
                    echo "<option value=\"{$row['ID']}\""; if($tipo['ID'] == $row['ID']) echo "selected"; echo ">{$row['ID']} - {$row['NOME']}</option>";
                }
                echo "</select></br>";

                echo "<label>Stato non conformità: ({$stato})</label><select class='selector' name='stato'>
                <option value='APERTA'";if($stato=="APERTA"){echo "selected";} echo ">Aperta</option>
                <option value='IN APPROVAZIONE'";if($stato=="IN APPROVAZIONE"){echo "selected";} echo ">In approvazione</option>";
                /*Data creazione*/
                echo "<label>Data creazione: ({$dataCreazione})</label></br>";
                echo "<input type=\"date\" name=\"dc\" placeholder=\"Data creazione: {$dataCreazione}\" value=\"{$dataCreazione}\"></br>";
                /*Data chiusura*/
                echo "<label>Data fine: ({$dataFine})</label></br>";
                echo "<input type=\"date\" name=\"df\" placeholder=\"Data fine: {$dataFine}\""; if($dataFine != null) echo "value=\"{$dataFine}\""; echo "></br>";
                /*Id Segnalante*/
                echo "<label>ID Segnalante: ({$idsegnalante})</label></br>";
                echo "<input type=\"text\" name=\"idsegn\" placeholder=\"ID Segnalante: {$idsegnalante}\" readonly></br>";
                /*Id Autore*/
                echo "<label>Autore: ({$autore})</label></br>";
                echo "<input type=\"text\" name=\"autore\" placeholder=\"Autore: {$autore}\" readonly></br>";

                /*Fornitore origine */
                echo "<label>Fornitore di origine: ({$origineforn})</label></br>";
                echo "<select name=\"orgforn\""; if($origineforn != null) echo "value=\"Origine: {$origineforn}\""; echo"class=\"selector\"></br>";
                if($origineforn == null){
                    echo "<option disable selected value> Non ancora impostata</option>";
                }

                $originiq = "SELECT IDSEGNALANTE,PIVA,DENOMINAZIONE FROM FORNITORE";
                $origini = $connessione->query($originiq);
                while($row = mysqli_fetch_assoc($origini)){
                    echo "<option value=\"{$row['IDSEGNALANTE']}\""; if($origineforn == $row['IDSEGNALANTE']) echo "selected"; echo ">{$row['IDSEGNALANTE']} - {$row['DENOMINAZIONE']} - {$row['PIVA']}</option>";
                }

                echo "</select></br>";

                /*Reparto origine */
                echo "<label>Reparto di origine: ({$originerep})</label></br>";
                echo "<select name=\"orgrep\"";  if($originerep != null)echo "value=\"Origine: {$originerep}\""; echo "class=\"selector\"></br>";
                if($originerep == null){
                    echo "<option disable selected value> Non ancora impostata</option>";
                }

                $originiq = "SELECT NOME FROM REPARTO";
                $origini = $connessione->query($originiq);
                while($row = mysqli_fetch_assoc($origini)){
                    echo "<option value=\"{$row['NOME']}\""; if($originerep == $row['NOME']) echo "selected"; echo ">{$row['NOME']}</option>";
                } 

                echo "</select></br>";
                /*Fine OrigineNC */
                echo "<label>Note: {$coinvolti}</label></br>";
                echo "<textarea style=\"resize:none; width: 100%; height: 100px; color: black;\" name=\"note\" placeholder=\"Note\">{$note}</textarea></br>";
                echo "<input type=\"submit\" value=\"Salva modifiche\">";
            }else{
                echo "<label>ID: {$id}</label></br>";
                echo "<label>Stato: {$stato}</label></br>";
                echo "<label>Data creazione: {$dataCreazione}</label></br>";
                echo "<label>Data fine: {$dataFine}</label></br>";
                echo "<label>ID Segnalante: {$idsegnalante}</label></br>";
                echo "<label>Autore: {$autore}</label></br>";
                echo "<label>Reparto di origine: {$originerep}</label></br>";
                echo "<label>Fornitore di origine: {$origineforn}</label></br>";
                echo "<label>Coinvolti: {$coinvolti}</label></br>";
                echo "<label>Note: {$note}</label></br>";
            }
            ?>
            </form>
            <form action="risolviNC.php" method="POST">
                <?php
                echo "<input type=\"hidden\" name=\"idnc\" value=\"{$idNC}\">";
                ?>
                <input type="hidden" name="chginfo" value="true">
                <?php
                if(!isset($_POST['chginfo']) || $_POST['chginfo'] != true){
                    echo "<input type=\"submit\" value=\"Modifica\" style=\"width: 100%\">";
                }
                ?>
            </form>
        </div>

        <div id="aggac" style="flex: 70%; padding-left: 30px; padding-right: 30px;">
        <form class="aggiungiac" action="aggac.php" method="POST" style="">
                <h2>Aggiungi un'azione correttiva:</h2></br>
                <?php

                if(isset($_POST[numac])){
                    $acq = "SELECT * FROM AZIONECORRETTIVA WHERE IDSEGNALAZIONE=$idNC AND NUMERO=$_POST[numac]";
                    $acq = $connessione->query($acq);
                    $acq = mysqli_fetch_assoc($acq);
                }
                echo "<input type=\"hidden\" name= \"idNC\" value=$idNC>";
                echo "<input type=\"hidden\" name=\"eseguente\" value=$_SESSION[idsegn]>";
                echo "<label>Data inizio:</label>";
                echo "<input type=\"date\" name=\"di\""; if(isset($_POST[numac])) echo "value=$acq[DATAINIZIO]"; echo "></br>";
                echo "<label>Data fine:</label>";
                echo "<input type=\"date\" name=\"df\""; if(isset($_POST[numac])) echo "value=$acq[DATAFINE]"; echo"></br>";
                if(isset($_POST[numac])) echo "<input type=\"hidden\" value=\"$_POST[numac]\" name=\"numac\">";
                echo "<textarea style=\"resize:none; width: 100%; height: 100px; color: black;\" name=\"desc\" placeholder=\"Descrizione\">"; if(isset($_POST[numac])) echo "$acq[DESCRIZIONE]"; echo"</textarea></br>";
                if(isset($_POST[numac])){
                    echo "<input type=\"submit\" value=\"Modifica\">";
                }else{
                    echo "<input type=\"submit\" value=\"Aggiungi nuova\">";
                }
                
                ?>
        </form>
        <div id="accontainer" style="display: flex; flex-wrap: wrap;">
            <?php
            $acq = "SELECT * FROM AZIONECORRETTIVA AS AC LEFT JOIN IMPIEGATO AS I ON AC.ESEGUENTE=I.IDSEGNALANTE WHERE AC.IDSEGNALAZIONE = {$idNC}";
            $acq = $connessione->query($acq);

            while($ac = mysqli_fetch_assoc($acq)){
                echo "
                <div class=\"ac\""; if($ac[DATAFINE] != "") echo "style=\"border: 2px solid gray\""; echo ">
                    <label>Numero: $ac[NUMERO]</label><br>
                    <label>Data inizio: $ac[DATAINIZIO]</label><br>
                    <label>Data fine: $ac[DATAFINE]</label><br>
                    <label>Addetto: $ac[NOME] $ac[COGNOME]</label><br>
                    <form action=\"$_SERVER[PHP_SELF]\" method=\"POST\">
                    <input type=\"hidden\" value=\"$idNC\" name=\"idnc\">
                    <input type=\"hidden\" value=\"$ac[NUMERO]\" name=\"numac\"> 
                    <input type=\"submit\" value=\"Modifica\">
                    </form>
                </div>";
            }

            ?>
        </div>
        <h2>Aggiungi prodotti relativi</h2></br>
        <div id="prodcontainer" style="display: flex; flex-wrap: wrap;">

                <div id="container" style="flex-basis: 100%;">
                    <form action="risolviNC.php" method="POST">
                        <label>Seleziona un lotto</label>
                        <select class="selector" name="selectlotto">
                        <?php
                        $lottiq = "SELECT DISTINCT LOTTO FROM PRODOTTO";
                        $lotti = $connessione->query($lottiq);
                        while($row = mysqli_fetch_assoc($lotti)){
                            echo "<option value=\"$row[LOTTO]\""; if($row[LOTTO]==$_POST[selectlotto]) echo "selected"; echo">$row[LOTTO]</option>";
                        }
                        ?>
                        <option value="all">Tutti</option>
                        </select>
                        <?php
                        if(isset($_POST[numac]))echo "<input type=\"hidden\" name=\"numac\" value=\"$_POST[numac]\">";
                        if(isset($_POST[chginfo]))echo "<input type=\"hidden\" name=\"chginfo\" value=\"$_POST[chginfo]\">";
                        if(isset($_POST[idnc]))echo "<input type=\"hidden\" name=\"idnc\" value=\"$_POST[idnc]\">";
                        ?>
                    </form>
                </div>
                    <?php

                    if(isset($_POST[selectlotto]) && $_POST[selectlotto] == "all")
                        $segnalantiq = "SELECT P.ID,P.LOTTO,TP.TIPO as TIPONOME,TP.SKU AS SKU FROM PRODOTTO P JOIN TIPOPRODOTTO TP ON P.TIPO=TP.SKU";
                    else
                        $segnalantiq = "SELECT P.ID,P.LOTTO,TP.TIPO as TIPONOME,TP.SKU AS SKU FROM PRODOTTO P JOIN TIPOPRODOTTO TP ON P.TIPO=TP.SKU WHERE LOTTO=$_POST[selectlotto]";
                    
                    $segnalanti = $connessione->query($segnalantiq);
                    $tipiq = "SELECT * FROM TIPOPRODOTTO";
                    $tipi = $connessione->query($tipiq);
                    while($row = mysqli_fetch_assoc($segnalanti)){
                        echo "<div id=\"container\" style=\"flex: 18%; margin: 10px; text-align: left;\">
                            <label>ID: $row[ID]</label><br>
                            <label>Tipo: $row[TIPONOME]</label><br>
                            <label>Lotto: $row[LOTTO]</label><br>
                            <form action=\"modificaprodotti.php\" method=\"POST\">
                            <input type=\"hidden\" name=\"idselect\" value=\"$row[ID]\">
                            <input type=\"hidden\" name=\"selectlotto\" value=\"$_POST[selectlotto]\">
                            <input type=\"submit\" value=\"Aggiungi\">
                            </form>
                        </div>";
                        }
                    ?>

                </div>
            </div>
    </div>


    <!--
    $q = "SELECT * FROM ACCOUNT A JOIN RUOLO R ON A.ruolo=R.nome WHERE username='".$_SESSION["username"]."'";
    $grado = mysqli_query($connessione,$q);
    $grado = mysqli_fetch_assoc($grado);
    $grado = $grado[gradoGestione];
    $q = "SELECT * FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.tipo=N.id WHERE gradoMinimo<='".$grado."' ORDER BY identificatore ASC";
    $risultato = mysqli_query($connessione, $q);
    */



    $q = "SELECT * FROM ";
        mysqli_close($connessione);
    ?>-->

</body>
</html>