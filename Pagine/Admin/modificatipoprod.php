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
    <title>PortaleNC - Modifica tipo prodotto</title>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="../Utenti/coinvolti.js"></script>
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

        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);

        $servername = "localhost";
        $username = "";
        $password = "";
        $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
         
        $connessione = mysqli_connect($servername,$username,$password,$dbname);
        ?>
        <div id="title">Modifica tipo prodotto</div>
        <div class="flexcontainer" style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;">
            <?php
            if($connessione->connect_error){
                 die("Connessione fallita: " . $conn->connect_error);
            }
           
            $tipiprodq = "SELECT * FROM TIPOPRODOTTO";
            $tipiprod = $connessione->query($tipiprodq);
            while($row = mysqli_fetch_assoc($tipiprod)){

                $fornitoriattualiq = "SELECT F.DENOMINAZIONE,F.IDSEGNALANTE,F.PIVA FROM TIPOPRODOTTO T JOIN FORNITURE FE ON T.SKU=FE.SKU JOIN FORNITORE F ON FE.IDSEGNALANTE=F.IDSEGNALANTE WHERE T.SKU=$row[SKU]";
                $fornitoriattuali = $connessione->query($fornitoriattualiq);
                $tipo = getTipoSegnalante($row[TIPO]);
                if(isset($_POST[skuselect]) && $row[SKU] == $_POST[skuselect]){
                    echo "<div id=\"container\" style=\"flex: 48%; margin: 10px; text-align: left;\">
                    <form action=\"updatetipoprod.php\" method=\"POST\">
                    <label>SKU ($row[SKU])</label><br>
                    <input type=\"text\" value=\"$row[SKU]\" name=\"sku\">
                    <label>Tipo</label>
                    <input type=\"text\" name=\"tipo\" value=\"$row[TIPO]\" placeholder=\"Chiave inglese, smerigliatrice, ecc...\" required><br>
                    <label>Descrizione</label>
                    <textarea name=\"desc\" cols=70 rows=10 placeholder=\"Scrivi qui...\" style=\"text-align:left; color: black; width: 100%\" >$row[DESCRIZIONE]</textarea>
                    <label>Prezzo (€)</label>
                    <input type=\"number\" min=\"1\" step=\"any\" value=$row[PREZZO] name=\"prezzo\" required/>
                    <label>Fornitore</label>
                    <select class=\"selector\" tag=\"fornitore[]\" id=\"first\">";
                    $fornitoriq = "SELECT DENOMINAZIONE,IDSEGNALANTE,PIVA FROM FORNITORE";
                    $fornitori = $connessione->query($fornitoriq);
                    while($row2 = mysqli_fetch_assoc($fornitori)){ //TUTTI I FORNITORI
                        echo "<option value=\"$row2[IDSEGNALANTE]\"> PIVA: $row2[PIVA] - $row2[DENOMINAZIONE]</option>";
                        echo "<option selected disabled> Seleziona un'opzione </option>";
                        $fornitoriprod[] = $row2; //LISTA DI TUTTI I FORNITORI DA USARE NELLE SELECT POI
                    }
                    echo "</select>";
                    while($row2 = mysqli_fetch_assoc($fornitoriattuali)){
                        echo "<select class=\"selector\" name=\"fornitore[]\">";
                        foreach($fornitoriprod as $row3){
                            echo "<option value=\"$row3[IDSEGNALANTE]\""; if($row3[IDSEGNALANTE] == $row2[IDSEGNALANTE]) echo "selected"; echo "> PIVA: $row3[PIVA] - $row3[DENOMINAZIONE]</option>";
                        }
                        echo"<option>Elimina</option> 
                        </select>";
                    }
                    echo"
                    <input type=\"hidden\" value=\"$row[SKU]\" name=\"oldsku\">
                    <input type=\"submit\" value=\"Salva modifiche\">
                    </form>
                </div>";
                }else{
                echo "<div id=\"container\" style=\"flex: 48%; margin: 10px; text-align: left;\">
                    <label>SKU: $row[SKU]</label><br>
                    <label>Tipo: $row[TIPO]</label><br>
                    <label>Descrizione: $row[DESCRIZIONE]</label><br>
                    <label>Prezzo: $row[PREZZO]€</label><br>
                    <label>Fornitore/i:";
                    while($row2 = mysqli_fetch_assoc($fornitoriattuali)){
                        echo "$row2[DENOMINAZIONE] ";
                    }
                    echo "</label>
                    <form action=\"modificatipoprod.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"skuselect\" value=\"$row[SKU]\">
                    <input type=\"submit\" value=\"Modifica\">
                    </form>
                    <form action=\"updatetipoprod.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"sku\" value=\"$row[SKU]\">
                    <input type=\"hidden\" name=\"delete\"  value=\"true\">
                    <input type=\"submit\" value=\"Elimina\" style=\"background-color: #eb4034; box-shadow: 0px 5px 0px rgb(150, 37, 20);\">
                    </form>
                </div>";
                }
            }


            function getTipoSegnalante($c){
                switch($c){
                    case 'C':
                        return 'Cliente';
                        break;
                    case 'I':
                        return 'Impiegato';
                        break;
                    case 'F':
                        return 'Fornitore';
                        break;
                }
            }
            ?>
        </div>
</body>
</html>