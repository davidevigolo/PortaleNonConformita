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
    <title>Errore 404!</title>
    <style>
    h1{
        animation-name:testo;
        animation-duration:5s;
        animation-delay:-.2s;
        font-size: 100px;

    }
    h2{
        animation-name:testo;
        animation-duration:5s;
        animation-delay:-.2s;
        font-size: 50px;
    }
    a{
        text-decoration: none;
    }
    @keyframes testo{
        from {color:#242424;}
        to  {color:white;}
    }

    body{
        background-image: none;
    }
    </style>
</head>
<body>
<img src="../../img/magnifying-glass.png" style="margin-top: 50px; max-width: 100%; width: auto; height: auto;">
<h2>Sembra che tu ti sia perso (*>﹏<*)</h2>
<a href="https://bicicletta22235id.altervista.org"><h2>Torna al Login</h2></a>
</body>
</html>