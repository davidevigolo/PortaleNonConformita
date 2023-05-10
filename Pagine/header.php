<?php

class Header{

    public function __construct(){

    }

    public function render($role,$username){
        session_start();
        echo '<header>';
        if($role == "Admin"){ 
                echo "<ul>
                    <div class=\"dropdown\">
                        Amministrazione
                        <div class=\"dropdown-content\">
                            <a href=\"../Admin/compilaprodotto.php\">Inserisci prodotto</a>
                            <a href=\"../Admin/compilatipoprod.php\">Inserisci tipo prodotto</a>
                            <a href=\"../Admin/compilareparto.php\">Inserisci reparto</a>
                            <a href=\"../Admin/compilasegnalante.php\">Inserisci segnalante</a>
                            <a href=\"../Admin/registeracc.php\">Inserisci account</a>
                            <a href=\"../Admin/modificaprodotti.php\">Gestisci prodotti</a>
                            <a href=\"../Admin/modificatipoprod.php\">Gestisci tipi prodotti</a>
                            <a href=\"../Admin/modificareparto.php\">Gestisci reparti</a>
                            <a href=\"../Admin/modificasegnalante.php\">Gestisci segnalanti</a>
                            <a href=\"../Admin/modificaAccount.php\">Gestisci accounts</a>
                        </div>
                    </div>";
        }

        echo "
        <li style=\"float:left;\"><a href=\"../Comuni/visualizzaNC.php\">Visualizza N.C.</a></li>
        ";
        if(!($_SESSION[tipo] != I && $role != "Caporeparto"))
        echo "<li style=\"float:left;\"><a href=\"../Comuni/risolviNC.php\">Risolvi N.C.</a></li>";
        echo "<li style=\"float:left;\"><a href=\"../Utenti/compilanc.php\">Segnala N.C.</a></li>
        <li style=\"float:left;\"><a href=\"";
         if($role == 'Admin') {echo "../Admin/dashboardadmin.php";
        } else if($role == 'Dirigente'){
             echo "../Dirigenti/dashboarddirigenti.php";
        } else{
                 echo "../Utenti/dashboard.php";
        } echo "\">Dashboard</a></li>
        <li style=\"float:left;\"><a href=\"../Dirigenti/creareport.php\">Crea report PDF</a></li>
        <li style=\"float:right;\">{$username}</li>  
        <li style=\"float: right;\"><a href=\"../Disconnessione/disconnetti.php\">Disconnettiti</a></li>
        </ul>";
        
         echo "</header>";
    }
}


?>
