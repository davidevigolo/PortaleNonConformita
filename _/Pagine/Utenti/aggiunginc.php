
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>PortaleNC - Aggiungi N.C.</title>
</head>
<body>
    
</body>
</html>
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

        if(!isset($_POST[rilevante]) || (!isset($_POST[orgrep]) && !isset($_POST[orgforn])) || !isset($_POST[tipo])){
            echo "<div id=\"container\"> Operazione non riuscita, parametri invalidi </br> <a href=\"./dashboard.php\">Torna alla dashboard</a></div>";
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
        $nomeutente = $_SESSION['username'];
        $idutente_q = "SELECT IDSEGNALANTE FROM ACCOUNT WHERE USERNAME = '{$nomeutente}'";
        $idutente = $connessione->query($idutente_q);
        $risultati = mysqli_fetch_assoc($idutente);

        $author = $risultati['IDSEGNALANTE'];
        $tipo = $_POST['tipo'];
        $opening = date('y-m-d h:i:s');
        $stato = "IN APPROVAZIONE";
        $coinvolti = $_POST['coinvolti'];
        $rilevante = $_POST['rilevante'];
        $note = htmlspecialchars($_POST['note']);
        $prodotti = $_POST[prod];

        $originefornitore = $_POST['orgforn'] != "" ? $_POST['orgforn'] : "NULL";
        $originereparto = $_POST['orgrep'] != "" ? $_POST['orgrep'] : "NULL";

        $insertnc_q = "INSERT INTO `SEGNALAZIONE`(`STATO`, `DATACREAZIONE`, `DATACHIUSURA`, `AUTORE`, `TIPO`,`NOTE`,`NCREPARTO`,`NCFORNITORE`) VALUES ('{$stato}','{$opening}',NULL,{$author},{$tipo},'{$note}','$originereparto','$originefornitore')";
        echo $insertnc_q;
        $result = $connessione->query($insertnc_q);
        $idsegn = mysqli_insert_id($connessione);
        foreach($coinvolti as $c){
            $insertcq = "INSERT INTO GESTIONENC(IDSEGNALANTE,IDSEGNALAZIONE) VALUES($c,$idsegn)";
            echo $insertcq;
            if(!$connessione->query($insertcq)){
                echo "<div id=\"container\"> Operazione non riuscita, parametri invalidi </br> <a href=\"./dashboard.php\">Torna alla dashboard</a></div>";
                foreach($coinvolti as $c){ // NON SAPPIAMO ANCORA FARE LE TRANSAZIONI ACID 
                    $deleteq = "DELETE FROM GESTIONENC WHERE IDSEGNALANTE=$c AND IDSEGNALAZIONE=$idsegn; DELETE FROM SEGNALAZIONE WHERE ID=$idsegn";
                    $connessione->query($delteq);
                }
                exit();
            }
        }
        foreach($prodotti as $p){
            $insprodsegnq = "INSERT INTO SEGNALAZIONEPROD(IDSEGNALAZIONE,IDPROD) VALUES($idsegn,$p)";
            if(!$connessione->query($insprodsegnq)){
                echo "<div id=\"container\"> Operazione non riuscita, parametri invalidi </br> <a href=\"./dashboard.php\">Torna alla dashboard</a></div>";
                foreach($coinvolti as $c){ // NON SAPPIAMO ANCORA FARE LE TRANSAZIONI ACID 
                    $deleteq = "DELETE FROM SEGNALAZIONEPROD WHERE IDSEGNALAZIONE=$idsegn; DELETE FROM SEGNALAZIONE WHERE ID=$idsegn";
                    $connessione->query($delteq);
                }
                exit();
            }
        }
        header('location: http://bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php');

?>