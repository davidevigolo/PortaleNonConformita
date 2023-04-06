<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="../../style/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="../../script/compilasegnalante.js"></script>
    <title>PortaleNC - Registra Segnalante</title>
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
    ?>
    <div id="title">Registra segnalante</div>
    <div id="container">
        <!--addsegnalante.php è stato cambiato in compilaimpiegato-->
        <form action ="compilaImpiegato.php" method="POST">
            <label>Email</label>
            <input type="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" size="30" name="email" value="" placeholder="Email" required>
            <label>Telefono (formato: XXX-XXX-XXXX)</label>
            <input type="tel" minlength=12 maxlength=12 name="phone" value="" placeholder="Numero di telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
            <label>Tipo segnalante</label>
            <select name="tipo" class="selector" id="tipo" required>
                <option value="" disabled selected>Seleziona un opzione</option>
                <option value="F">Fornitore</option>
                <option value="C">Cliente</option>
                <option value="I">Impiegato</option>
            </select>
            <div id="campi">

            </div>
            <?php 

            $servername = "localhost";
            $username = "";
            $password = "";
            $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
        
            // controlla connessione
            $connessione = mysqli_connect($servername, $username, $password, $dbname);
                
            if ($connessione->connect_error) {
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
            }
            ?>



            <input type="submit" style="text-align: center" value="Registra">
        </form>
    </div>
</body>
</html>