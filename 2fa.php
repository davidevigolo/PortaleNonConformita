<?php
session_start();
if(isset($_POST[otp])){
    if($_SESSION['twofauth'] == $_POST[otp]){
        unset($_SESSION['twofatuh']);
        $_SESSION['valid'] = true;
        setcookie('rememberme',$_SESSION['rememberme'], time() + 3600000);
        if($role == "Dirigente"){
            header("location: ./Pagine/Dirigenti/dashboarddirigenti.php");
            exit();
        }
        elseif($role == "Admin"){
            header("location: ./Pagine/Admin/dashboardadmin.php");
            exit();
        }else{
            header("location: ./Pagine/Utenti/dashboard.php");
            exit();
        }
    }else{
        echo "<header style=\"background-color: rgb(199 50 50);\">Codice OTP invalido</header>";
    }
}
?>

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
    <link rel="icon" type="image/x-icon" href="./img/favicon.jpg">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="./script/index.js"></script>
    <title>PortaleNC - Login</title>
    <style>
        header{
            background-color: transparent;
        }
    </style>
</head>
<body>
    <header>
        <ul>
            <!--<li float="left"><a href="./Pagine/Utenti/registrati.php">Registrati</a></li>-->
            <li id="title">PortaleNC</li>
        </ul>    
    </header>
    <div id="container" style="text-align: center;">
        <form action ="2fa.php" method="POST" style="margin: auto;">
        <div id="subtitle" style="margin-bottom: 20px; text-align: center;">Verifica la tua identità: </div>
        <?php
            echo"$_SESSION[twofauth]";
        ?>
            <label>OTP</label>
            <input type="text" name="otp" value="" placeholder="XXXXXX" required>
            <input type="submit" value="Verifica">
        </form>
    </div>
</body>
</html>