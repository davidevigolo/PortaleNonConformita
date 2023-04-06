<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style/dashboard.css">
    <title>Visualizza NC</title>
    <style>
        div#container{
            border-radius: 10px;
        }
    </style>

</head>

<body>
    <?php
    header("Cache-Control: no-cache, must-revalidate");
    session_start();
    echo '<header>';
    if (!isset($_SESSION['valid'])) {
        echo "Sessione scaduta, rieffettuare l'accesso.";
        exit;
    }
    if (!$_SESSION['valid']) {
        echo "Sessione invalida, rieffettuare l'accesso.";
        exit;
    }
    echo "<ul>
                    <a href=\"../../Admin/dashboardadmin.php\"><li style=\"float:left;\">Pagina Admin</li></a>
                    <a href=\"../../Admin/registeracc.php\"><li style=\"float:left;\">Registra Account</li></a>
                    <a href=\"../../Utenti/dashboard.php\"><li style=\"float:left;\">Dashboard</li></a>
                    <li style=\"float:right; \">{$_SESSION['username']}</li>
                    <a href=\"../../Disconnessione/disconnetti.php\"><li style=\"float: right;\">Disconnettiti</li></a>
                </ul>";

    echo '</header>';
    $colonna = $_COOKIE["colonna"];
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id"; //va cambiato il nome del db secondo il nome usato
    
    $connessione = mysqli_connect($servername, $username, $password, $dbname);

    if ($connessione->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    $q = "SELECT * FROM ACCOUNT A JOIN RUOLO R ON A.ruolo=R.nome WHERE username='" . $_SESSION["username"] . "'";
    $grado = mysqli_query($connessione, $q);
    $grado = mysqli_fetch_assoc($grado);
    $grado = $grado[gradoGestione];
    $q = "SELECT * FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.tipo=N.id WHERE gradoMinimo<='" . $grado . "' ORDER BY identificatore ASC";
    $risultato = mysqli_query($connessione, $q);


    echo '<div id="title">Informazioni</div>';
    // da rimuovere il fatto che si veda tutto in corsivo
    
    echo '<div id="container">';
    $i = 0;
    $row = mysqli_fetch_assoc($risultato);
    while ($row != NULL && $i != $colonna) {
        $i++;
        if ($i == $colonna) {
            echo 'Identificatore NC:'.$row[identificatore].'<br>'.
    'Autore: '.$row[Autore].'<br>'.
    'Stato: '.$row[stato].'<br>'.
    'Data Creazione: '.$row[dataCreazione].'<br>'.
    'Data Chiusura: '.$row[dataChiusura].'<br><br>'
    
    ;
        }
        $row = mysqli_fetch_assoc($risultato);
        
    }

    echo 'Le persone che ci stanno lavorando:';
    $q = "SELECT * FROM SEGNALAZIONE S JOIN NONCONFORMITA N ON S.tipo=N.id JOIN GESTIONENC ON identificatore=idSegnalazione JOIN IMPIEGATO ON IMPIEGATO.id=idImpiegato WHERE gradoMinimo<='" . $grado . "' AND identificatore='".$colonna."'";
    $risultato = mysqli_query($connessione, $q);
    $row = mysqli_fetch_assoc($risultato);

    $i = 1;

    while($row!=NULL){
            echo '<br>';
        echo '<br>ID impiegato: '.$i.
        '<br>Nome: '.$row[nome].
        '<br>Cognome: '.$row[cognome];
        $i++;
        $row = mysqli_fetch_assoc($risultato);
    }
    






    $_COOKIE["colonna"]=0;
    echo '</div id="container">';
   
    mysqli_close($connessione);
    ?>

</body>

</html>