<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>Visualizza NC</title>
    <style>
        #container{
            border-radius: 10px;
            overflow-wrap: break-word;
            display: flex;
            flex-wrap:wrap; 
            align-items:baseline;
        }

        /* #container{  CSS
            display: flex;
            flex-direction:row;
            justify-content: space-evenly;
            width: 80%;
            border: 1px solid black;
            border-radius: 10px;
            background-color: white;
            box-shadow: 2px 2px 5px black;
            padding: 30px;
            margin: auto;
            backdrop-filter: blur(10px);
            background-color: transparent;
            overflow: hidden;
        } */

        @media screen and (max-width: 990px){
            #container{
                flex-direction: column;
            }
        }

        #info{
            border-radius: 3px;
            background-color: rgb(49,49,49);
            text-align: left;
            padding: 7px;
            padding-right: 15px;
            flex:20%;
        }

        #dati{
            border-radius: 3px;
            padding: 7px;
            flex:50%;
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

    $dettagliSEGNALAZIONE = "SELECT * FROM SEGNALAZIONE WHERE ID = {$_GET['COD']}";
    $dettagliSEGNALAZIONE = mysqli_query($connessione, $dettagliSEGNALAZIONE);
    $dettagliSEGNALAZIONE = mysqli_fetch_assoc($dettagliSEGNALAZIONE);
    $TipoNC = $dettagliSEGNALAZIONE['TIPO'];

    $dettagliNONCONFORMITA = "SELECT ID,GRADOMINIMO,DESCRIZIONE,NOME FROM NONCONFORMITA WHERE ID=$TipoNC";
    $dettagliNONCONFORMITA = mysqli_query($connessione, $dettagliNONCONFORMITA);
    $dettagliNONCONFORMITA = mysqli_fetch_assoc($dettagliNONCONFORMITA);

    $dettagliAZIONECORRETTIVA = "SELECT * FROM AZIONECORRETTIVA AS AC JOIN SEGNALAZIONE AS S ON AC.IDSEGNALAZIONE=S.ID WHERE IDSEGNALAZIONE={$_GET['COD']}";
    $dettagliAZIONECORRETTIVA = mysqli_query($connessione, $dettagliAZIONECORRETTIVA);

    echo '<div id="title">Dettagli</div>';

    echo '<div id="dettagli" style="display: flex; width: 80%; margin: auto;">';
        
        echo "<div id='container'>
                <div id='title' style='flex:100%'>Segnalazione</div>
                    <div id='info' >
                        <label>Codice: </label> <br>
                        <label>Data Creazione: </label> <br>
                        <label>Data Chiusura: </label> <br>
                        <label>Creatore: </label> <br>
                        <label>Stato: </label> <br>
                        <label>Reparto: </label> <br>
                        <label>Fornitore: </label> <br>
                        <label>Note: </label> <br> 
                    </div>
                    <div id='dati' style='flex:50%; padding: 7px;'>
                        <label>$dettagliSEGNALAZIONE[ID]</label> <br>
                        <label>$dettagliSEGNALAZIONE[DATACREAZIONE]</label> <br>
                        <label>$dettagliSEGNALAZIONE[DATACHIUSURA]</label> <br>
                        <label>$dettagliSEGNALAZIONE[AUTORE]</label> <br> 
                        <label>$dettagliSEGNALAZIONE[STATO]</label> <br>
                        <label>$dettagliSEGNALAZIONE[NCREPARTO]</label> <br>
                        <label>$dettagliSEGNALAZIONE[NCFORNITORE]</label> <br>
                        <label>$dettagliSEGNALAZIONE[NOTE]</label> <br> 
                    </div>
            </div>";

        echo "<div id='container'>
                <div id='title'>Non Conformità</div>
                    <div id='info' >
                            <label>Codice: </label> <br>
                            <label>Grado: </label> <br>
                            <label>Nome: </label> <br>
                            <label>Note: </label> <br>
                    </div>
                    <div id='dati' style='flex:45%; padding: 7px;'>
                        <label>$dettagliNONCONFORMITA[ID]</label> <br>
                        <label>$dettagliNONCONFORMITA[GRADOMINIMO]</label> <br>
                        <label>$dettagliNONCONFORMITA[NOME]</label> <br>
                        <label>$dettagliNONCONFORMITA[DESCRIZIONE]</label> <br> 
                    </div>
        </div>";

        echo "<div id='container'>
            <div id='title'>Azione Corretiva</div>";
                while($row = mysqli_fetch_assoc($dettagliAZIONECORRETTIVA)){
                    echo "<div id='info' >
                        <label>Codice: </label> <br>
                        <label>Data Inizio: </label> <br>
                        <label>Data Fine: </label> <br>
                        <label>Eseguente: </label> <br>
                        <label>Note: </label> <br>
                    </div>
                    <div id='dati'>
                        <label>$row[NUMERO]</label> <br>
                        <label>$row[DATAINIZIO]</label> <br>
                        <label>$row[DATAFINE]</label> <br>
                        <label>$row[ESEGUENTE]</label> <br> 
                        <label>$row[DESCRIZIONE]</label> <br>
                    </div>";
                }
        echo"</div>";
    echo"</div>";
    ?>
    <!--Attributi:
        SEGNALAZIONE:
                ID as Codice
                DATACREAZIONE
                DATACHIUSURA
                AUTORE as Creatore
                STATO
                NCREPARTO 
                NCFORNITORE 
                NOTE
        NONCONFORMITA:
                ID as Codice 
                GRADOMINIMO as Grado Minimo
                DESCRIZIONE NC as Note
                NOME 
        AZIONECORETTIVA:
                NUMERO  as Codice
                DATAINIZIO
                DATAFINE
                DESCRIZIONE
                ESEGUENTE
    -->
</boby>
</html>