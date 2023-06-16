<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    if($_COOKIE['colormode'] == 'b'){
        echo "<link rel=\"stylesheet\" href=\"../../style/dashboardb.css\">";
    }else{
        echo "<link rel=\"stylesheet\" href=\"../../style/dashboard.css\">";
    }
    ?>
    <style>
       
    div#container{
        padding: 3px;
    }


    </style>
    <title>Dashboard admin</title>
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
        if($_SESSION[role] != "Admin"){
            header('location: bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php');
        }
        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);
?>

<div id="title">Dashboard Admin</div>
<div id="flexcontainer" style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;">
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo prodotto</h2><form action="./compilaprodotto.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo tipo di prodotto</h2><form action="./compilatipoprod.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo segnalante</h2><form action="./compilasegnalante.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo account</h2><form action="./registeracc.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci reparto</h2><form action="./compilareparto.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci account</h2><form action="./modificaAccount.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci tipi di prodotti</h2><form action="./modificatipoprod.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci prodotti</h2><form action="./modificaprodotti.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci segnalante</h2><form action="./modificasegnalante.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci reparto</h2><form action="./modificasegnalante.php"><input type="submit" value="Vai"></form></div>
</div>
</body>

</html>