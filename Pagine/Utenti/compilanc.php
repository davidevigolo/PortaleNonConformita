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
    <script src="coinvolti.js"></script>
    <title>Segnala</title>
    <style type="text/css">
        select.selector#first{
            border: 2px solid rgb(1, 144, 201);
        }
    </style>
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

        $indirizzo = "localhost";
        $user = "";
        $password = "";
        $db = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato
        
        
        
        $connessione = new mysqli($indirizzo, $user, $password, $db);
        // controlla connessione

        if ($connessione->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }
        $nomeutente= $_SESSION['username'];  //qui ci deve finire il nome utente della persona in qualche modo, va quindi cambiato davide vigggggggggggggggolo
        //se la persona è admin fa vedere la pagina degli admin, altrimenti no
        $sql = "SELECT * FROM ACCOUNT WHERE username='".$nomeutente."'"; 
        $risultato = $connessione -> query($sql);
        $row = mysqli_fetch_assoc($risultato);
        /*Se la persona è admin allora fa vedere la pagina con tutte le non conformità, 
        altrimenti fa vedere solo quelle che riguardano a quel determinato utente secondo il power del suo ruolo*/
        require_once('../header.php');
        $header = new Header();
        $header->render($_SESSION[role],$_SESSION[username]);
?>
    <div id="container">
        <form action="./aggiunginc.php" method="POST">
        <div id="subtitle">Compila la non conformità</div>
        <div class="tab" id="0">
            <label>Autore</label>
            <?php
            session_start();
            echo "<input type=\"text\" name=\"username\" value=\"{$_SESSION['username']}\" style=\"pointer-events: none;\">";
            ?>
            <label>Tipo</label>
            <select class="selector" name="tipo">
            <?php
                $tipoq = "SELECT * FROM NONCONFORMITA";
                $tipor = $connessione->query($tipoq);
                while($row = mysqli_fetch_assoc($tipor)){
                    echo "<option value=\"{$row['ID']}\">{$row['NOME']} - G.M. = {$row['GRADOMINIMO']}</option>";
                }
            ?>
            </select>
            <label>Rilevante</label>
            <select class="selector" name="rilevante">
                <?php
                    $impq = "SELECT * FROM IMPIEGATO";
                    $fornq = "SELECT * FROM FORNITORE";
                    $cliq = "SELECT * FROM CLIENTE";
                    $impr = $connessione->query($impq);
                    $fornr = $connessione->query($fornq);
                    $clir = $connessione->query($cliq);
                    echo "<option value='' disabled selected> Impiegati</option>";
                    while($row = mysqli_fetch_assoc($impr)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">Impiegato: {$row['NOME']} {$row['COGNOME']}</option>";
                    } 
                    echo "<option value='' disabled selected> Fornitori</option>";
                    while($row = mysqli_fetch_assoc($fornr)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">{$row['DENOMINAZIONE']} PIVA: {$row['PIVA']}</option>";
                    } 
                    echo "<option value='' disabled selected> Clienti</option>";
                    while($row = mysqli_fetch_assoc($clir)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">Cliente: {$row['NOME']} {$row['COGNOME']}</option>";
                    }                    
                ?>
            </select>
            <label>Reparto di origine della non conformità</label>
            <select class="selector" name="orgrep">
                <?php
                    $repq = "SELECT * FROM REPARTO";  
                    $repr = $connessione->query($repq);
                    echo "<option value='' disabled selected>Reparti</option>";
                    while($row = mysqli_fetch_assoc($repr)){
                        echo "<option value=\"{$row['NOME']}\">Reparto: {$row['NOME']}</option>";
                    }                    
                ?>
            </select>
            <label>Fornitore di origine della non conformità</label>
            <select class="selector" name="orgforn">
                <?php
                    $fornq = "SELECT * FROM FORNITORE";
                    $fornr = $connessione->query($fornq);
                    echo "<option value='' disabled selected>Fornitori</option>";
                    while($row = mysqli_fetch_assoc($fornr)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">{$row['DENOMINAZIONE']} PIVA: {$row['PIVA']}</option>";
                    }                   
                ?>
            </select>
        </div>
        <div class="tab" id="1">
        <label>Coinvolti</label>
            <select class="selector" tag="coinvolti[]" id="first">
                <?php
                    $impq = "SELECT * FROM IMPIEGATO";
                    $fornq = "SELECT * FROM FORNITORE";
                    $cliq = "SELECT * FROM CLIENTE";
                    $impr = $connessione->query($impq);
                    $fornr = $connessione->query($fornq);
                    $clir = $connessione->query($cliq);
                    echo "<option value='' disabled selected> Impiegati</option>";
                    while($row = mysqli_fetch_assoc($impr)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">Impiegato: {$row['NOME']} {$row['COGNOME']}</option>";
                    } 
                    echo "<option value='' disabled selected> Fornitori</option>";
                    while($row = mysqli_fetch_assoc($fornr)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">{$row['DENOMINAZIONE']} PIVA: {$row['PIVA']}</option>";
                    } 
                    echo "<option value='' disabled selected> Clienti</option>";
                    while($row = mysqli_fetch_assoc($clir)){
                        echo "<option value=\"{$row['IDSEGNALANTE']}\">Cliente: {$row['NOME']} {$row['COGNOME']}</option>";
                    }                    
                ?>
            </select>
        </div>
        <div class="tab" id="2">
        <label>Prodotti</label>
            <select class="selector" tag="prod[]" id="first">
                <?php
                    $prodq = "SELECT * FROM PRODOTTO P JOIN TIPOPRODOTTO T ON P.TIPO=T.SKU";
                    $prod = $connessione->query($prodq);
                    foreach($prod as $p){
                        $lotti[$p[LOTTO]][] = $p;
                    }
                    foreach($lotti as $l){
                        $nlotto = $l[0][LOTTO];
                        echo "<option value='' disabled selected> Lotto: $nlotto</option>";
                        foreach($l as $prod){
                            echo "<option value=\"{$prod[ID]}\">Prodotto: $prod[TIPO] N. $prod[ID]</option>";
                        }
                    }                   
                ?>
            </select>
        </div>
        <div class="tab" id="3">
            <textarea name="note" cols=70 rows=10 placeholder="Scrivi qui..." style="text-align:left; color: black; width: 100%" ></textarea>
        </div>
        <div class="tab" id="4">
        <input type="submit" style="width:100%" style="text-align: center" value="Invia">
        </div>
        <ul id="formnav">
            <li id="n0">Info generali</li>
            <li id="n1">Coinvolti</li>
            <li id="n2">Prodotti</li>
            <li id="n3">Note</li>
            <li id="n4">Invia</li>
        </ul>
        </form>
    </div>
</body>
</html>


<?php
    mysqli_close($connessione);
?>