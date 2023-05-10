<?php
session_start();
if($_SESSION[tipo] != I && $_SESSION[role] != "Caporeparto"){
    header('location: ../Utenti/dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="../Utenti/coinvolti.js"></script>
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
            box-shadow: 0px 0px 90px 20px rgb(40,40,40) inset; 
            padding: 10px;
        }
        select.selector#first{
            border: 2px solid rgb(1, 144, 201);
        }
        table{
            border: 1px solid black;
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

        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);

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
        <label>Seleziona non conformità</label>
        <form method="POST" action="risolviNC.php" style="flex:100%;">
            <select name="idnc" class="selector" style="width:100%">
                <?php
                if($_SESSION['role']=='Dirigente'||$_SESSION['role']=='Caporeparto'||$_SESSION['role']=='Admin'){
                    $nonconformitaq = "SELECT DISTINCT S.ID,NC.NOME,S.NCREPARTO,S.NCFORNITORE,S.AUTORE FROM SEGNALAZIONE S JOIN NONCONFORMITA NC ON S.TIPO=NC.ID JOIN GESTIONENC G ON G.IDSEGNALAZIONE=S.ID WHERE NC.GRADOMINIMO <= $_SESSION[gradominimo] AND S.AUTORE IS NOT NULL OR G.IDSEGNALANTE=$_SESSION[idsegn] OR S.NCREPARTO=(SELECT REPARTO FROM IMPIEGATO WHERE IDSEGNALANTE=$_SESSION[idsegn]) ORDER BY S.ID ASC";
                }else{
                    $nonconformitaq = "SELECT DISTINCT S.ID,NC.NOME,S.NCREPARTO,S.NCFORNITORE,S.AUTORE FROM SEGNALAZIONE S JOIN NONCONFORMITA NC ON S.TIPO=NC.ID JOIN GESTIONENC G ON G.IDSEGNALAZIONE=S.ID WHERE NC.GRADOMINIMO <= $_SESSION[gradominimo] AND STATO='APERTA' OR G.IDSEGNALANTE=$_SESSION[idsegn] ORDER BY S.ID ASC";
                }
                
                echo $nonconformitaq;
                $nonconformita = $connessione->query($nonconformitaq);
                echo $nonconformita->num_rows;
                while($row = mysqli_fetch_assoc($nonconformita)){
                    $idsegnalante = $row['AUTORE'];
                    $autoreq = "SELECT USERNAME FROM ACCOUNT WHERE IDSEGNALANTE={$idsegnalante}";
                    $res = mysqli_fetch_assoc($connessione->query($autoreq));
                    echo "<option value=\"{$row['ID']}\""; if($row[ID] == $_POST[idnc]) echo "selected";  echo ">ID: {$row['ID']} Tipo: {$row['NOME']} Autore: {$res['USERNAME']} Reparto di origine: {$row['NCREPARTO']} Fornitore di origine: {$row['NCFORNITORE']}</option>";
                }

                ?>
            </select>
            <input type="submit" value="Seleziona">
        </form>
        <?php
        echo "<div style=\"display: inline-block; flex: 100%;\">";
        if(!isset($_POST[idnc])){
            echo "<label>Le più recenti:</label><br>";
            echo "<table style=\"width: 100%;\" id=\"tabella\" class=\"actions\">";
            echo "<tr><th>ID</th><th>Tipo</th><th>Stato</th><th>Autore</th><th>Data creazione</th><th>Data chiusura</th><th>Modifica</th></tr>";
            $recentiq = 
            "SELECT S.ID AS ID,N.NOME AS TIPO,S.DATACREAZIONE AS DCR,S.DATACHIUSURA AS DCS,A.USERNAME AS USER,S.STATO AS STATO
            FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.TIPO=N.ID JOIN ACCOUNT A ON A.IDSEGNALANTE=S.AUTORE
            WHERE STATO='APERTA'";
            $recenti = $connessione->query($recentiq);
            while($row = mysqli_fetch_assoc($recenti)){
                echo "<tr><td>$row[ID]</td><td>$row[TIPO]</td><td>$row[DCR]</td><td>$row[DCS]</td><td>$row[USER]</td><td>$row[STATO]</td>";
                echo "<td>
                <form method=\"POST\" action=\"risolviNC.php\">
                <input type=\"hidden\" name=\"idnc\" value=\"$row[ID]\">
                <input type=\"submit\" value=\"modifica\">
                </form>
                </td>";
                echo "</tr>";
            }
            echo "</table>";

        }
        echo "</div>";

        
        ?>
        <form method="POST" action="risolviNC.php" style="flex:100%;">
        <?php
            if ( ($_SESSION['role']=='Dirigente'||$_SESSION['role']=='Caporeparto'||$_SESSION['role']=='Admin') && isset($_POST[idnc]) && !isset($_POST[chgcoinv])){
                echo "<input type=\"hidden\" name=\"chgcoinv\" value=\"true\">";
                echo "<input type=\"submit\" value=\"Modifica coinvolti\">";
                echo "<input type=\"hidden\" name=\"idnc\" value=\"$_POST[idnc]\">";
                if(isset($_POST[chginfo])){
                    echo "<input type=\"hidden\" name=\"chginfo\" value=\"$_POST[idnc]\">";
                }
            }
        ?>
        </form>
        <form method="POST" action="updateNC.php" style="flex:100%;">
        <input type="hidden" name="chgcoinv" value="true">
        <?php
            if ( ($_SESSION['role']=='Dirigente'||$_SESSION['role']=='Caporeparto'||$_SESSION['role']=='Admin') && isset($_POST[chgcoinv]) && isset($_POST[idnc])){
                echo "<select class=\"selector\" tag=\"coinvolti[]\" id=\"first\">";
                $impq = "SELECT * FROM IMPIEGATO";
                $fornq = "SELECT * FROM FORNITORE";
                $cliq = "SELECT * FROM CLIENTE";
                $impr = $connessione->query($impq);
                $fornr = $connessione->query($fornq);
                $clir = $connessione->query($cliq);
                echo "<option value='' disabled selected> Impiegati</option>";
                while($row = mysqli_fetch_assoc($impr)){
                    echo "<option value=\"{$row['IDSEGNALANTE']}\">Impiegato: {$row['NOME']} {$row['COGNOME']}</option>";
                } 
                echo "<option value='' disabled selected> Fornitori</option>";
                while($row = mysqli_fetch_assoc($fornr)){
                    echo "<option value=\"{$row['IDSEGNALANTE']}\">{$row['DENOMINAZIONE']} PIVA: {$row['PIVA']}</option>";
                } 
                echo "<option value='' disabled selected> Clienti</option>";
                while($row = mysqli_fetch_assoc($clir)){
                    echo "<option value=\"{$row['IDSEGNALANTE']}\">Cliente: {$row['NOME']} {$row['COGNOME']}</option>";
                }     
                echo "</select>";


                $coinvoltiq = "SELECT I.IDSEGNALANTE FROM GESTIONENC G JOIN IMPIEGATO I ON G.IDSEGNALANTE=I.IDSEGNALANTE WHERE G.IDSEGNALAZIONE=$_POST[idnc]";
                $coinvolti = $connessione->query($coinvoltiq);
                while($row = mysqli_fetch_assoc($coinvolti)){
                    echo "<select class=\"selector\" name=\"coinvolti[]\">";
                    printCoinvolgibili($connessione,$row[IDSEGNALANTE]);
                    echo "</select>";
                }
                $coinvoltiq = "SELECT F.IDSEGNALANTE FROM GESTIONENC G JOIN FORNITORE F ON G.IDSEGNALANTE=F.IDSEGNALANTE WHERE G.IDSEGNALAZIONE=$_POST[idnc]";
                $coinvolti = $connessione->query($coinvoltiq);
                while($row = mysqli_fetch_assoc($coinvolti)){
                    echo "<select class=\"selector\" name=\"coinvolti[]\">";
                    printCoinvolgibili($connessione,$row[IDSEGNALANTE]);
                    echo "</select>";
                }
                $coinvoltiq = "SELECT C.IDSEGNALANTE FROM GESTIONENC G JOIN CLIENTE C ON G.IDSEGNALANTE=C.IDSEGNALANTE WHERE G.IDSEGNALAZIONE=$_POST[idnc]";
                $coinvolti = $connessione->query($coinvoltiq);
                while($row = mysqli_fetch_assoc($coinvolti)){
                    echo "<select class=\"selector\" name=\"coinvolti[]\">";
                    printCoinvolgibili($connessione,$row[IDSEGNALANTE]);
                    echo "</select>";
                }
                echo "<input type=\"hidden\" name=\"id\" value=\"$_POST[idnc]\">";
                echo "<input type=\"submit\" value=\"Conferma\">";
            }
        ?>
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
                
                if($_SESSION[role] == "Caporeparto" || $_SESSION[role] == "Dirigente" || $_SESSION[role] == "Admin"){
                echo "<label>Stato non conformità: ({$stato})</label><select class='selector' name='stato'>
                <option value='APERTA'";if($stato=="APERTA"){echo "selected";} echo ">Aperta</option>
                <option value='IN APPROVAZIONE'";if($stato=="IN APPROVAZIONE"){echo "selected";} echo ">In approvazione</option>";
                }
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

                $dataq = "SELECT DATACREAZIONE FROM SEGNALAZIONE WHERE ID = $_POST[idnc]";
                $data = mysqli_fetch_assoc($connessione->query($dataq));
                echo "<input type=\"hidden\" name= \"idnc\" value=$idNC>";
                echo "<input type=\"hidden\" name=\"eseguente\" value=$_SESSION[idsegn]>";
                echo "<label>Data inizio:</label>";
                echo "<input type=\"date\" name=\"di\" min=\"$data[DATACREAZIONE]\""; if(isset($_POST[numac])) echo "value=$acq[DATAINIZIO]"; echo "></br>";
                echo "<label>Data fine:</label>";
                echo "<input type=\"date\" name=\"df\" min=\"$data[DATACREAZIONE]\""; if(isset($_POST[numac])) echo "value=$acq[DATAFINE]"; echo"></br>";
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
        <h2>Prodotti relativi</h2></br>
        <div id="segnprodcontainer" style="display: flex; flex-wrap: wrap;">
            <?php
            $acq = "SELECT S.IDPROD AS ID,T.TIPO AS TIPO,P.LOTTO AS LOTTO FROM SEGNALAZIONEPROD S JOIN PRODOTTO P ON S.IDPROD=P.ID JOIN TIPOPRODOTTO T ON P.TIPO=T.SKU WHERE S.IDSEGNALAZIONE=$_POST[idnc]";
            $acq = $connessione->query($acq);

            while($ac = mysqli_fetch_assoc($acq)){
                echo "
                <div class=\"ac\" style=\"border: 1px solid black\">
                    <label>ID: $ac[ID]</label><br>
                    <label>Tipo: $ac[TIPO]</label><br>
                    <label>Lotto: $ac[LOTTO]</label><br>
                    <form action=\"updateNC.php#segnprodcontainer\" method=\"POST\">
                    <input type=\"hidden\" value=\"$idNC\" name=\"idnc\">
                    <input type=\"hidden\" value=\"$ac[ID]\" name=\"idprod\"> 
                    <input type=\"hidden\" value=\"true\" name=\"delprod\">
                    <input type=\"submit\" value=\"Elimina\">
                    </form>
                </div>";
            }

            ?>
        </div>
        <div id="prodcontainer" style="display: flex; flex-wrap: wrap;">

                <div id="container" style="flex-basis: 100%;">
                    <form action="risolviNC.php#prodcontainer" method="POST">
                        <label>Seleziona un lotto</label>
                        <select class="selector" name="selectlotto">
                        <?php
                        $lottiq = "SELECT DISTINCT LOTTO FROM PRODOTTO P WHERE P.ID NOT IN (SELECT IDPROD FROM SEGNALAZIONEPROD WHERE IDSEGNALAZIONE = $_POST[idnc])";
                        $lotti = $connessione->query($lottiq);
                        echo "<option disabled selected>Scegli un lotto</option>";
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
                        $prodq = "SELECT P.ID,P.LOTTO,TP.TIPO as TIPONOME,TP.SKU AS SKU FROM PRODOTTO P JOIN TIPOPRODOTTO TP ON P.TIPO=TP.SKU WHERE P.ID NOT IN (SELECT IDPROD FROM SEGNALAZIONEPROD WHERE IDSEGNALAZIONE = $_POST[idnc])";
                    else
                        $prodq = "SELECT P.ID,P.LOTTO,TP.TIPO as TIPONOME,TP.SKU AS SKU FROM PRODOTTO P JOIN TIPOPRODOTTO TP ON P.TIPO=TP.SKU WHERE P.ID NOT IN (SELECT IDPROD FROM SEGNALAZIONEPROD WHERE IDSEGNALAZIONE = $_POST[idnc]) AND LOTTO=$_POST[selectlotto]";
                    
                    $segnalanti = $connessione->query($prodq);
                    $tipiq = "SELECT * FROM TIPOPRODOTTO";
                    $tipi = $connessione->query($tipiq);
                    while($row = mysqli_fetch_assoc($segnalanti)){
                        echo "<div class=\"ac\" style=\"flex: 18%; margin: 10px; text-align: left; border: 1px solid black;\">
                            <label>ID: $row[ID]</label><br>
                            <label>Tipo: $row[TIPONOME]</label><br>
                            <label>Lotto: $row[LOTTO]</label><br>
                            <form action=\"modificaprodotti.php\" method=\"POST\">
                            <input type=\"hidden\" name=\"idselect\" value=\"$row[ID]\">
                            <input type=\"hidden\" name=\"selectlotto\" value=\"$_POST[selectlotto]\">
                            <input type=\"hidden\" name=\"idnc\" value=\"$idNC\">
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


    <?php
    function printCoinvolgibili($connessione,$selected){
        echo $selected;
        $impq = "SELECT * FROM IMPIEGATO";
        $fornq = "SELECT * FROM FORNITORE";
        $cliq = "SELECT * FROM CLIENTE";
        $impr = $connessione->query($impq);
        $fornr = $connessione->query($fornq);
        $clir = $connessione->query($cliq);
        echo "<option value='' disabled> Impiegati</option>";
        while($row = mysqli_fetch_assoc($impr)){
            echo "<option value=\"{$row['IDSEGNALANTE']}\""; if($row[IDSEGNALANTE] == $selected) echo "selected"; echo">Impiegato: {$row['NOME']} {$row['COGNOME']}</option>";
        } 
        echo "<option value='' disabled> Fornitori</option>";
        while($row = mysqli_fetch_assoc($fornr)){
            echo "<option value=\"{$row['IDSEGNALANTE']}\""; if($row[IDSEGNALANTE] == $selected) echo "selected"; echo ">{$row['DENOMINAZIONE']} PIVA: {$row['PIVA']}</option>";
        } 
        echo "<option value='' disabled> Clienti</option>";
        while($row = mysqli_fetch_assoc($clir)){
            echo "<option value=\"{$row['IDSEGNALANTE']}\""; if($row[IDSEGNALANTE] == $selected) echo "selected"; echo">Cliente: {$row['NOME']} {$row['COGNOME']}</option>";
        }
        echo "<option>Elimina</option>";
    }
    
    ?>

</body>
</html>