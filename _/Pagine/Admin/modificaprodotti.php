<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>PortaleNC - Modifica tipo prodotto</title>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
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

        if($connessione->connect_error){
            die("Connessione fallita: " . $conn->connect_error);
       }
       
        ?>
        <div id="title">Modifica prodotto</div>
        <div class="flexcontainer" style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;">
        <div id="container" style="width: 100%">
            <form action="modificaprodotti.php" method="POST">
                <label>Seleziona un lotto</label>
                <select class="selector" name="selectlotto">
                <?php
                $lottiq = "SELECT DISTINCT LOTTO FROM PRODOTTO";
                $lotti = $connessione->query($lottiq);
                while($row = mysqli_fetch_assoc($lotti)){
                    echo "<option value=\"$row[LOTTO]\""; if($row[LOTTO]==$_POST[selectlotto]) echo "selected"; echo">$row[LOTTO]</option>";
                }
                ?>
                <option value="all">Tutti</option>
                </select>
            </form>
        </div>
            <?php

            if(isset($_POST[selectlotto]) && $_POST[selectlotto] == "all")
                $segnalantiq = "SELECT P.ID,P.LOTTO,TP.TIPO as TIPONOME,TP.SKU AS SKU FROM PRODOTTO P JOIN TIPOPRODOTTO TP ON P.TIPO=TP.SKU";
            else
                $segnalantiq = "SELECT P.ID,P.LOTTO,TP.TIPO as TIPONOME,TP.SKU AS SKU FROM PRODOTTO P JOIN TIPOPRODOTTO TP ON P.TIPO=TP.SKU WHERE LOTTO=$_POST[selectlotto]";
            
            $segnalanti = $connessione->query($segnalantiq);
            $tipiq = "SELECT * FROM TIPOPRODOTTO";
            $tipi = $connessione->query($tipiq);
            while($row = mysqli_fetch_assoc($segnalanti)){
                $tipo = getTipoSegnalante($row[TIPO]);
                if(isset($_POST[idselect]) && $row[ID] == $_POST[idselect]){
                    echo "<div id=\"container\" style=\"flex: 18%; margin: 10px; text-align: left;\">
                    <form action=\"updateprodotti.php\" method=\"POST\">
                    <label>ID ($row[ID])</label><br>
                    <input type=\"text\" value=\"$row[ID]\" name=\"id\" style=\"pointer-events: none;\">
                    <label>Lotto</label>
                    <input type=\"number\" name=\"lotto\" value=\"$row[LOTTO]\" placeholder=\"Numero lotto\" required><br>
                    <label>Tipo</label>
                    <select class=\"selector\" name=\"tipo\">";
                    while($row2 = mysqli_fetch_assoc($tipi)){
                        echo "<option value=\"$row2[SKU]\""; if($row[SKU] == $row2[SKU]) echo "selected"; echo ">SKU: $row2[SKU] Tipo: $row2[TIPO] Prezzo: $row2[PREZZO]</option>";
                    }
                    echo "
                    </select>
                    <input type=\"submit\" value=\"Salva modifiche\">
                    </form>
                </div>";
                }else{
                echo "<div id=\"container\" style=\"flex: 18%; margin: 10px; text-align: left;\">
                    <label>ID: $row[ID]</label><br>
                    <label>Tipo: $row[TIPONOME]</label><br>
                    <label>Lotto: $row[LOTTO]</label><br>
                    <form action=\"modificaprodotti.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"idselect\" value=\"$row[ID]\">
                    <input type=\"hidden\" name=\"selectlotto\" value=\"$_POST[selectlotto]\">
                    <input type=\"submit\" value=\"Modifica\">
                    </form>
                    <form action=\"updateprodotti.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"id\" value=\"$row[ID]\">
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