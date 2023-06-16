<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require('./Mail/Exception.php');
    require('./Mail/PHPMailer.php');
    require('./Mail/SMTP.php');

    session_start();
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

    // controlla connessione
    $connessione = mysqli_connect($servername, $username, $password, $dbname);
        
        if ($connessione->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }
        
        //variabili
        $username = $_POST['username'];
        $password = "";
        if ($stmt = $connessione->prepare("SELECT PASSWORD FROM ACCOUNT WHERE USERNAME = ?")) {
            // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
            $stmt->bind_param('s', $_POST['username']);
            $stmt->execute();
            // Store the result so we can check if the account exists in the database.
            $stmt->store_result();
            if ($stmt->num_rows() > 0) {
                $stmt->bind_result($password);
                $stmt->fetch();
                // Account exists, now we verify the password.
                // Note: remember to use password_hash in your registration file to store the hashed passwords.
                if (password_verify($_POST['password'], $password)) {
                    // Verification success! User has logged-in!
                    // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
                    session_regenerate_id();
                    $_SESSION['expires'] = time() + (3600);
                    $_SESSION['username'] = $username;

                    $q = "SELECT A.RUOLO AS RUOLO,A.IDSEGNALANTE AS IDSEGN,R.GRADOGESTIONE AS GRADOGEST, S.TIPO AS TIPO FROM ACCOUNT A JOIN RUOLO R ON A.RUOLO=R.NOME JOIN SEGNALANTE S ON S.ID=A.IDSEGNALANTE WHERE A.USERNAME = '$_SESSION[username]'";
                    
                    $ruolo = mysqli_query($connessione,$q);
                    $ruolo = mysqli_fetch_assoc($ruolo);
                    $role = $ruolo[RUOLO];
                    $_SESSION['role'] = $role;
                    $_SESSION['tipo'] = $ruolo[TIPO];
                    $_SESSION['idsegn'] = $ruolo[IDSEGN];
                    $_SESSION['wrongpass'] = false;
                    $_SESSION['gradominimo'] = $ruolo[GRADOGEST];

                    $emailq = "SELECT EMAIL FROM SEGNALANTE WHERE ID = $_SESSION[idsegn]";

                    $email = mysqli_fetch_assoc($connessione->query($emailq))[EMAIL];
                    $mail = new PHPMailer();
                    try{

                    $otp = rand(100000,999999);

                    /*$mail->isSMTP();
                    $mail->SMTPDebug = true;
                    $mail->Mailer = 'smtp';
                    $mail->Debugoutput = function($str, $level) {echo "$str\n";};
                    $mail->Host = 'smtp.gmail.com';
                    $mail->Username = 'davide.vigolo.04@gmail.com';
                    $mail->Password = 'jmrrwqumnfglyfpy';
                    $mail->Port = 587;
                    $mail->SMTPSecure = "tls";
                    $mail->SMTPAuth = true;
                    $mail->From = 'davide.vigolo.04@gmail.com';
                    $mail->FromName = 'PortaleNC';
                    $mail->AddAddress('vidavid04@gmail.com','name1');
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Encoding = 'base64';
                    $mail->Body = "Il tuo codice di validazione è: $otp";
                    $mail->Subject = "Codice di verifica 2FA";
                    $mail->send();*/

                    $headers = 'From: "PortaleNC" <bicicletta22235id.altervista.org>' . "\r\n" .'Content-Type: text/html; charset=utf-8' . "\r\n";

                    $_SESSION['twofauth'] = $otp;
                    }catch(phpmailerException $e){
                        echo $e->errorMessage();
                    }
                    if(!isset($_SESSION['rememberme']) || $_SESSION['rememberme'] != $_COOKIE['rememberme']){
                        $_SESSION['valid'] = false;
                        if(isset($_POST['rememberme'])){
                            $rememberme = rand(100000,999999);
                            $_SESSION['rememberme'] = $rememberme;
                        }
                        if(mail("vidavid04@gmail.com",
                        'PortaleNC: Verifica a 2 Passaggi (2FA)',"
    
                        <html lang=\"en\">
                            <head>
                                <meta charset=\"UTF-8\">
                                <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
                            </head>
                            <body style=\"color: white; font-family: quicksand; margin: 0; box-sizing: border-box;\">
                                <header style=\"margin: 0; background-color: rgb(39, 39, 39); font-family: Arial, Helvetica, sans-serif; width: 100%; padding: 10px 10px; text-align: center; vertical-align: middle; line-height: 20px; height: 20px;\">PortaleNC - Password OTP</header>
                            <div style=\"margin: 0; background-color: rgb(1, 144, 201);; width: 100%; height: 400px; color: black; text-align: center; vertical-align: middle; line-height: 300px; font-family: Arial, Helvetica, sans-serif;\">
                                <p style=\"font-size: 200%; margin: 0; font-weight: 700; \">Il tuo codice OTP: $otp</p>
                            </div>
        
                        </body>
                        </html>
                        
                        ",$headers))
                        {
                        }else{
                            echo '<div id="container">Invio mail fallito.</div>';
                            exit();
                        }
                        
                        header('location: ./2fa.php');
                        exit();
                    }

                    $connessione->close();


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
                } else {
                    header("location: ./index.php");
                    $_SESSION['wrongpass'] = true;
                }
            } else {
                header("location: ./index.php");
                $_SESSION['wrongpass'] = true;
            }
        }
?>