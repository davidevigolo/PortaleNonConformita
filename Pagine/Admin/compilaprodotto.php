<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>PortaleNC - Registra Prodotto</title>
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
            echo "<header style=\"background-color: rgb(90, 242, 102);\">Inserimento riuscito!.</header>";
            setcookie('validinsert',"",time() - 3600);
        }elseif ($_COOKIE['validinsert'] == "false"){
            echo "<header style=\"background-color: rgb(199, 50, 50);\">Inserimento fallito, controllare i dati immessi.</header>";
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
     
         // controlla connessione
         $connessione = mysqli_connect($servername, $username, $password, $dbname);
             
         if ($connessione->connect_error) {
             header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
         }
    ?>
    <div id="title">Registra un tipo di prodotto</div>
    <div id="container">
        <form action ="addprod.php" method="POST">
            <label>Numero lotto:</label>
            <input type="number" min=0 max=2147483647 placeholder="Numero lotto" name="lotto"><br>
            <select class="selector" name="sku">
                <?php
                $skuq = "SELECT SKU,TIPO,PREZZO FROM TIPOPRODOTTO";
                $sku = $connessione->query($skuq);
                while($row = mysqli_fetch_assoc($sku)){
                    echo "<option value=\"$row[SKU]\">SKU: $row[SKU] Tipo: $row[TIPO] Prezzo: $row[PREZZO]</option>";
                }
                ?>
            </select>
            <input type="submit" style="text-align: center" value="Registra">
        </form>
    </div>
</body>
</html>