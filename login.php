<?php
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
                    $_SESSION['valid'] = true;
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
                    echo "wrong";
                    header("location: ./index.php");
                    $_SESSION['wrongpass'] = true;
                }
            } else {
                echo "wrong,";
                header("location: ./index.php");
                $_SESSION['wrongpass'] = true;
            }
        }
?>