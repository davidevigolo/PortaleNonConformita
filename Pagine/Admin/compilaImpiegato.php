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
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="../../script/compilasegnalante.js"></script>
    <title>Compilazione impiegato</title>
    </head>
    <body>
        <?php
            session_start();        
            require_once('../header.php');
            $header = new Header();
            $header->render($_SESSION[role],$_SESSION[username]);
            header("Cache-Control: no-cache, must-revalidate");
            
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
            
            $servername = "localhost";
            $username = "";
            $password = "";
            $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
            
            $connessione = mysqli_connect($servername,$username,$password,$dbname);
            
            if($connessione->connect_error){
                die("Connessione fallita: " . $conn->connect_error);
            }

            $tipo = $_POST[tipo];
            $email = $_POST[email];
            $tel = $_POST[phone];

            $ddn = $_POST[ddn];
            $cognome = $_POST[cognome];
            $nome = $_POST[nome];
            $cf = $_POST[cf];
            $dassunzione = $_POST[dassunzione];
            $dlicenziamento = $_POST[dlicenziamento];
            $reparto = $_POST[reparto];
/*
            if($tipo<>'I'){
                header("Location: ./addsegnalante.php");
            }
*/          
            echo '<div style="display: flex; flex-wrap: wrap; width: 90%; margin: auto;"><form><select>';

            $q = "SELECT NOME FROM REPARTO";
            echo $q;
            $risultato = $connessione -> query($q);
            while($row = mysqli_fetch_assoc($risulato)){
                echo "<option value='{$row[NOME]}'>{$row[NOME]}</option>";
            }

            echo '</select></form><div style="flex-basis: 49% margin: 10px"><h2>Seleziona Reparto</h2><form action="./addsegnalante.php"><input type="submit" value="Conferma"></form></div></div>';



        ?>
    </body>
</html>