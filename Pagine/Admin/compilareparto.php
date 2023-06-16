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
    <script src="../Utenti/coinvolti.js"></script>
    <title>PortaleNC - Registra Tipo Prodotto</title>
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
    ?>
    <div id="title">Registra un tipo di prodotto</div>
    <div id="container">
        <form action ="addreparto.php" method="POST">
            <label>Nome</label>
            <input type="text" maxlength=50 minlength=0 name="nome" value="" placeholder="Nome reparto" required><br>
            <input type="submit" style="text-align: center" value="Registra">
        </form>
    </div>
</body>
</html>