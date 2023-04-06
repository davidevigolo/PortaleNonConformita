<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../style/dashboard.css">
    <title>PortaleNC - ModificaNC</title>
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

    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

    $connessione = new mysqli($servername,$username,$password,$dbname);


    $datainizio = $_POST[di];
    $datafine = $_POST[df];
    $descrizione = $_POST[desc];
    $stato = $datafine == "" ? "aperta" : "chiusa";
    $idNC = $_POST[idNC];
    $eseguente = $_POST[eseguente];
    $numac = $_POST[numac];
    $datafine = ""."'$datafine'";
    if($datafine=="''"){
        $datafine="null";
    }
    //VERIFICA CHE NON SIA UPDATE

    if(isset($numac)){ //SE ESISTONO AZIONI CORRETTIVE



        if(isset($datainizio)){
                //fa update
                $q = "UPDATE AZIONECORRETTIVA SET DATAINIZIO='".$datainizio."', DATAFINE=".$datafine.", DESCRIZIONE='".$descrizione."', STATO='".$stato."' WHERE NUMERO=".$numac;
                echo $q;
                if(mysqli_query($connessione, $q)){
                setcookie("validinsert","true",time() + 3000);  
                }else{
                    /*echo $numac;
                    echo "la query non è stata fatta";*/
                    setcookie("validinsert","false",time() + 3000);
                }
            }
    
        
    }
    else{
            $q = "INSERT INTO AZIONECORRETTIVA(DATAINIZIO, DATAFINE, DESCRIZIONE, IDSEGNALAZIONE, STATO, ESEGUENTE) VALUES('".$datainizio."',".$datafine.",'".$descrizione."',".$idNC.",'".$stato."',".$eseguente.")";
            if(mysqli_query($connessione, $q)){
                setcookie("validinsert","true",time() + 3000);  
            }else{
                /*echo $numac;
                echo "la query non è stata fatta";*/
                setcookie("validinsert","false",time() + 3000);
            }
        //fa insert
        
    }

    //AUTORE E' CHI INVIA LA AZIONE CORRETTIVA, EVITANDO CHE QUALCUNO ASSEGNI TANTE A CARICO DI ALTRI
/*
    if($datafine != ""){
        $insq = "INSERT INTO AZIONECORRETTIVA(DATAINIZIO,DATAFINE,DESCRIZIONE,IDSEGNALAZIONE,STATO,ESEGUENTE) VALUES('$datainizio','$datafine','$descrizione',$idNC,'$stato',$eseguente)";
    }else{
        $insq = "INSERT INTO AZIONECORRETTIVA(DATAINIZIO,DESCRIZIONE,IDSEGNALAZIONE,STATO,ESEGUENTE) VALUES('$datainizio','$descrizione',$idNC,'$stato',$eseguente)";
    }
    */
    /*if($connessione->query($insq)){
        setcookie("validinsert","true",time() + 3000);
    }else{
        setcookie("validinsert","false",time() + 3000);
    }*/
    header('location: https://bicicletta22235id.altervista.org/Pagine/Comuni/risolviNC.php');
    ?>
</body>
</html>