<?php
$email = "vidavid04@gmail.com";
                    try{

                    $otp = rand(100000,999999);

                    $headers = 'From: "PortaleNC" <bicicletta22235id@altervista.org>' . "\r\n" .'Content-Type: text/html; charset=utf-8' . "\r\n";

                    $_SESSION['twofauth'] = $otp;
                    }catch(phpmailerException $e){
                        echo $e->errorMessage();
                    }
                    if(!isset($_SESSION['rememberme']) || $_SESSION['rememberme'] != $_COOKIE['rememberme']){
                        $_SESSION['valid'] = false;
                        if(mail($email,
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
                            echo $email;
                            echo "OK";
                            exit();
                        }else{
                            echo '<div id="container">Invio mail fallito.</div>';
                            exit();
                        }
                        
                        header('location: ./2fa.php');
                        exit();
                    }
?>
