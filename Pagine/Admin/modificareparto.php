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
        <div id="title">Modifica reparto</div>
        <div class="flexcontainer" style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;">
            <?php
            if($connessione->connect_error){
                 die("Connessione fallita: " . $conn->connect_error);
            }
           
            $repartiq = "SELECT NOME FROM REPARTO ORDER BY NOME ASC";
            $reparti = $connessione->query($repartiq);
            while($row = mysqli_fetch_assoc($reparti)){
                if(isset($_POST[repselect]) && $row[NOME] == $_POST[repselect]){
                    echo "<div id=\"container\" style=\"flex: 48%; margin: 10px; text-align: left;\">
                    <form action=\"updatereparto.php\" method=\"POST\">
                    <label>Nome ($row[NOME])</label><br>
                    <input type=\"text\" value=\"$row[NOME]\" name=\"rep\">
                    <input type=\"hidden\" value=\"$_POST[repselect]\" name=\"repselect\">
                    <input type=\"submit\" value=\"Salva modifiche\">
                    </form>
                </div>";
                }else{
                echo "<div id=\"container\" style=\"flex: 48%; margin: 10px; text-align: left;\">
                    <label>Nome: $row[NOME]</label><br>
                    <form action=\"modificareparto.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"repselect\" value=\"$row[NOME]\">
                    <input type=\"submit\" value=\"Modifica\">
                    </form>
                    <form action=\"updatereparto.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"rep\" value=\"$row[NOME]\">
                    <input type=\"hidden\" name=\"delete\"  value=\"true\">
                    <input type=\"submit\" value=\"Elimina\" style=\"background-color: #eb4034; box-shadow: 0px 5px 0px rgb(150, 37, 20);\">
                    </form>
                </div>";
                }
            }
            ?>
        </div>
</body>
</html>