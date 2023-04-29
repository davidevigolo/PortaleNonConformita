<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
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
    <div id="container">
        <form action ="login.php" method="POST">
        <div id="subtitle">Accedi</div>
            <label>Username</label>
            <input type="text" name="username" value="" placeholder="Username" required>
            <label>Password</label>
            <input type="password" name="password" value="" placeholder="Password" required>
            <?php
            session_start();
            if(isset($_SESSION['wrongpass'])){
                if($_SESSION['wrongpass'] == true){
                    echo "<footer style='color:red; font-size:80%;'>Password o username errati! Se questo è un errore contatta l'amministratore</footer>";
                }
            }
            ?>
            <input type="submit" action="login.php" style="text-align: center">
        </form>
        <img src="./img/login.png">
    </div>
</body>
</html>