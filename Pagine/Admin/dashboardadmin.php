<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
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
?>

<div id="title">Dashboard Admin</div>
<div id="flexcontainer" style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;">
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo prodotto</h2><form action="./compilaprodotto.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo tipo di prodotto</h2><form action="./compilatipoprod.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo segnalante</h2><form action="./compilasegnalante.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Inserisci nuovo account</h2><form action="./registeracc.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci account</h2><form action="./modificaAccount.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci tipi di prodotti</h2><form action="./modificatipoprod.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci prodotti</h2><form action="./modificaprodotti.php"><input type="submit" value="Vai"></form></div>
    <div id="container" style="flex-basis: 49% margin: 10px"><h2>Gestisci segnalante</h2><form action="./modificasegnalante.php"><input type="submit" value="Vai"></form></div>
</div>
</body>

</html>