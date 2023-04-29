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
    <header>
        <ul>
        <a href ="../Admin/dashboardadmin.php"><li style="float: left;">Pagina Admin</li></a>
        <a href ="../Comuni/risolviNC.php"><li style="float: left;">Risolvi N.C.</li></a>
        <a href ="../Comuni/visualizzaNC.php"><li style="float: left;">Visualizza N.C.</li></a>
            <a href ="../Utenti/dashboard.php"><li style="float: right;">Home</li></a>
        </ul>    
    </header>
    <div id="container">
        <form action ="addacc.php" method="POST">
        <div id="subtitle">Registra un account</div>
            <label>Creare username</label>
            <input type="text" name="username" value="" placeholder="Username">
            <label>Creare password</label>
            <input type="password" name="password" value="" placeholder="Password">
            
            <?php 
            header("Cache-Control: no-cache, must-revalidate");
            session_start();

            $servername = "localhost";
            $username = "";
            $password = "";
            $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
        
            // controlla connessione
            $connessione = mysqli_connect($servername, $username, $password, $dbname);
                
                if ($connessione->connect_error) {
                    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                }
            $nomeutente= $_SESSION['username'];  //qui ci deve finire il nome utente della persona in qualche modo, va quindi cambiato davide vigggggggggggggggolo
            //se la persona è admin fa vedere la pagina degli admin, altrimenti no
            $sql = "SELECT * FROM ACCOUNT WHERE username='{$nomeutente}'"; 
            $risultato = $connessione -> query($sql);
            $row = mysqli_fetch_assoc($risultato);
            /*Se la persona è admin allora fa vedere la pagina con tutte le non conformità, 
            altrimenti fa vedere solo quelle che riguardano a quel determinato utente secondo il power del suo ruolo*/
            if($row['ruolo'] != "Admin"){
                header("location: ./Pagine/Utenti/dashboard.php");
                die();
            }


            /*DA TOGLIERE QUESTA SOPRA ASSOLUTAMENTE PRIMA DI FINIRE TUTTO ALTRIMENTI I NON ADMIN NON POSSONO REGISTRARSI*/
            
            /*Questa porzione di programma crea una connessione con il database 
            per prendere tutti i ruoli presenti nella tabella ruoli del db per poi metterli nella select*/
                
            //variabili        
            $userpdo_q = "SELECT * FROM RUOLO";
            $userpdo = $connessione->query($userpdo_q);
            
            echo '<select name="ruolo" class="selector">';
            echo '<option value="" disabled selected>Seleziona un ruolo</option>';
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row[nome].'">'.$row[nome].'</option>';
            }
            echo '</select>';
            /*
            Qui va messo un qualcosa che fa vedere gli id di tutti i segnalanti con magari nome e altre cazzate
            
            *//*Questo aggiunge alla select i FORNITORI*/
            $userpdo_q = "SELECT * FROM SEGNALANTE S JOIN FORNITORE F ON S.id=F.id WHERE fonte='F'";
            $userpdo = $connessione ->query($userpdo_q);
        
            echo '<br><br><select name="segnalante" class="selector">';
            echo "<option value='' disabled selected> Seleziona il segnalante</option>";
            echo "<option value='' disabled> Forntiori:</option>";
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row["id"].'">'.$row["id"].' - '.$row["denominazione"].'</option>';
            }
            echo "<option value='' disabled> Clienti:</option>";
            //Questo aggiunge alla select i CLIENTI
            $userpdo_q = "SELECT * FROM SEGNALANTE S JOIN CLIENTE C ON S.id=C.id WHERE fonte='C'";
            $userpdo = $connessione ->query($userpdo_q);
            
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row["id"].'">'.$row["id"].' - '.$row["nome"].' '.$row["cognome"].'</option>';
            }
            echo "<option value='' disabled> Impiegati:</option>";
               //Questo aggiunge alla select gli IMPIEGATI
            $userpdo_q = "SELECT * FROM SEGNALANTE S JOIN IMPIEGATO I ON S.id=I.id WHERE fonte='I'";
            $userpdo = $connessione ->query($userpdo_q);
            
            while($row= mysqli_fetch_assoc($userpdo)){
                echo '<option value="'.$row["id"].'">'.$row["id"].' - '.$row["nome"].' '.$row["cognome"].' </option>';
            }
            echo '</select>';
            ?>
            



            <input type="submit" action="login.php" style="text-align: center" value="Registra">
        </form>
    </div>
</body>
</html>