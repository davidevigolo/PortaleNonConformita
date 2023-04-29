<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <title>PortaleNC - Modifica segnalante</title>
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
        <div id="title">Modifica segnalante</div>
        <div class="flexcontainer" style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;">
            <?php
            if($connessione->connect_error){
                 die("Connessione fallita: " . $conn->connect_error);
            }
           
            $segnalantiq = "SELECT * FROM SEGNALANTE";
            $segnalanti = $connessione->query($segnalantiq);
            while($row = mysqli_fetch_assoc($segnalanti)){
                $tipo = getTipoSegnalante($row[TIPO]);
                if(isset($_POST[idselect]) && $row[ID] == $_POST[idselect]){
                    echo "<div id=\"container\" style=\"flex: 48%; margin: 10px; text-align: left;\">
                    <form action=\"updatesegnalante.php\" method=\"POST\">
                    <label>ID ($row[ID])</label><br>
                    <input type=\"text\" value=\"$row[ID]\" name=\"idsegn\" style=\"pointer-events: none;\">
                    <label>Email</label><br>
                    <input type=\"email\" pattern=\"^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$\" size=\"30\" name=\"email\" value=\"$row[EMAIL]\" required>
                    <label>Telefono (formato: XXX-XXX-XXXX)</label>
                    <input type=\"tel\" minlength=12 maxlength=12 name=\"tel\" value=\"$row[TELEFONO]\" pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\" required>
                    <input type=\"submit\" value=\"Salva modifiche\">
                    </form>
                </div>";
                }else{
                echo "<div id=\"container\" style=\"flex: 48%; margin: 10px; text-align: left;\">
                    <label>ID: $row[ID]</label><br>
                    <label>Email: $row[EMAIL]</label><br>
                    <label>Tipo: $tipo</label><br>
                    <label>Telefono: $row[TELEFONO]</label><br>
                    <form action=\"modificasegnalante.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"idselect\" value=\"$row[ID]\">
                    <input type=\"submit\" value=\"Modifica\">
                    </form>
                    <form action=\"updatesegnalante.php\" method=\"POST\">
                    <input type=\"hidden\" name=\"delete\" value=\"true\">
                    <input type=\"hidden\" name=\"id\" value=\"$row[ID]\">
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

            /*
                    <select name=\"tipo\" class=\"selector\" required>
                        <label>Tipo segnalante ($tipo)</label>
                        <option value=\"F\""; if($row[TIPO] == 'F') echo "selected"; echo ">Fornitore</option>
                        <option value=\"C\""; if($row[TIPO] == 'C') echo "selected"; echo ">Cliente</option>
                        <option value=\"I\""; if($row[TIPO] == 'I') echo "selected"; echo ">Impiegato</option>
                    </select>
            */
            ?>
        </div>
</body>
</html>