<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <link rel="icon" type="image/x-icon" href="./img/favicon.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="../../script/index.js"></script>
    <title>PortaleNC - Login</title>
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
    <div id="title">Registra account</div>
    <div id="container">
        <form action ="addacc.php" method="POST">
        <div id="subtitle">Registra un account</div>
            <label>Creare username</label>
            <input type="text" name="username" value="" placeholder="Username">
            <label>Creare password</label>
            <input type="password" name="password" value="" placeholder="Password">
            
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
            /*Se la persona è admin allora fa vedere la pagina con tutte le non conformità, 
            altrimenti fa vedere solo quelle che riguardano a quel determinato utente secondo il power del suo ruolo*/
            if($_SESSION['role'] != "Admin"){
                header("location: ./Pagine/Utenti/dashboard.php");
                die();
            }
                
            //variabili        
            $userpdo_q = "SELECT * FROM RUOLO";
            $userpdo = $connessione->query($userpdo_q);
            
            echo '<label>Selezionare ruolo</label>';
            echo '<select name="ruolo" class="selector">';
            echo '<option value="" disabled selected>Seleziona un ruolo</option>';
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row[NOME].'">'.$row[NOME].'</option>';
            }
            echo '</select>';
            /*
            Qui va messo un qualcosa che fa vedere gli id di tutti i segnalanti con magari nome e altre cazzate
            
            *//*Questo aggiunge alla select i FORNITORI*/
            $userpdo_q = "SELECT * FROM SEGNALANTE S JOIN FORNITORE F ON IDSEGNALANTE=ID WHERE S.TIPO='F'";
            $userpdo = $connessione ->query($userpdo_q);
        
            echo '<label>Selezionare segnalante</label>';
            echo '<select name="segnalante" class="selector">';
            echo "<option value='' disabled selected> Seleziona il segnalante</option>";
            echo "<option value='' disabled> Forntiori:</option>";
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row["ID"].'">'.$row["ID"].' - '.$row["DENOMINAZIONE"].'</option>';
            }
            echo "<option value='' disabled> Clienti:</option>";
            //Questo aggiunge alla select i CLIENTI
            $userpdo_q = "SELECT * FROM SEGNALANTE S JOIN CLIENTE C ON IDSEGNALANTE=ID WHERE S.TIPO='C'";
            $userpdo = $connessione ->query($userpdo_q);
            
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row["ID"].'">'.$row["ID"].' - '.$row["NOME"].' '.$row["COGNOME"].'</option>';
            }
            echo "<option value='' disabled> Impiegati:</option>";
               //Questo aggiunge alla select gli IMPIEGATI
            $userpdo_q = "SELECT * FROM SEGNALANTE S JOIN IMPIEGATO I ON IDSEGNALANTE=ID WHERE S.TIPO='I'";
            $userpdo = $connessione ->query($userpdo_q);
            
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row["ID"].'">'.$row["ID"].' - '.$row["NOME"].' '.$row["COGNOME"].' </option>';
            }
            echo '</select>';
            ?>
            



            <input type="submit" style="text-align: center" value="Registra">
        </form>
    </div>
</body>
</html>