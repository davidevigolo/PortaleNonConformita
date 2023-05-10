<?php
session_start();

if(!isset($_SESSION['valid'])){
    echo "<header style=\"background-color: rgb(199 50 50);\">Sessione scaduta, rieffettuare l'accesso.</header>";
    exit;
}
if(!$_SESSION['valid']){
    echo "<header style=\"background-color: rgb(199 50 50);\">Sessione invalida, rieffettuare l'accesso.</header>";
    exit;
}

if($_SESSION[role] != "Dirigente" && $_SESSION[role] != "Admin"){
    //header('location: bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PortaleNC - Report</title>
    <link rel="stylesheet" href="../../style/dashboard.css">
    <style>
        table{
            background-color: white;
            color: black;
        }
        table tr:nth-child(2n + 1){
            background-color: gray;
        }
        table tr td{
            color: black;
        }
    </style>
</head>
<body>
    <?php
        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);
    ?>
    
    <div id="title">Crea un report PDF</div>
    <div id="container" style="width:90%;">
        <form style="text-align: center;" action="report.php" method="POST">
            <div style="display: inline-block; width: 30%;">
            <label>Data minima</label><br>
            <input type="date" value="" style="width: 100%;" name="dmin">
            </div>
            <div style="display: inline-block; width: 30%;">
            <label>Data massima</label><br>
            <input type="date" value="" style="width: 100%;" name="dmax">
            </div>
            <select class="selector" style="width: 25%;" name="stato">
                <option selected>APERTA</option>
                <option >CHIUSA</option>
                <option >IN APRROVAZIONE</option>
            </select><br>
            <div style="display: inline-block; width: 85%;">
            <label>Descrizione:</label><br>
            <textarea name="descrizione" placeholder="Inserisci la descrizione del report qui" style="resize: none; width: 100%; height: 60px; color: black; text-align: left;">

            </textarea>
            </div>
            <input type="submit" value="Crea">
        </form>
    </div>
    <!-- <div id="report" style="width: 90%; margin: auto; background-color: white; color: black;">
        <div id="title" style="color: inherit;">Report odierno</div>
        <table style="width: 90%; margin: auto; color: black;">
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Data creazione</th>
                <th>Data chiusura</th>
                <th>Autore</th>
                <th>Stato</th>
                <th>Reparto di origine</th>
                <th>Fornitore di origine</th>
                <th>Note</th>
            </tr>
        <?php

        /*if(!isset($_POST[dmin]) || !isset($_POST[dmax])){
            exit();
        }

        $reportq = "SELECT ID,TIPO,DATACREAZIONE,DATACHIUSURA,AUTORE,STATO,NCREPARTO,NCFORNITORE,NOTE FROM SEGNALAZIONE WHERE DATACREAZIONE <= '$_POST[dmax]' AND DATACREAZIONE >= '$_POST[dmin]'";
        $report = $connessione->query($reportq);
        while($row = mysqli_fetch_assoc($report)){
            echo "<tr><td>$row[ID]</td><td>$row[TIPO]</td><td>$row[DATACREAZIONE]</td><td>$row[DATACHIUSURA]</td><td>$row[AUTORE]</td><td>$row[STATO]</td><td>$row[NCREPARTO]</td><td>$row[NCFORNITORE]</td><td>$row[NOTE]</td></tr>";
        }*/

        ?>
        </table>
    </div> -->
</body>
</html>