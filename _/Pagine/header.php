<?php

class Header{

    public function __construct(){

    }

    public function render($role,$username){
        echo '<header>';
        if($role == "Admin"){ 
                echo "<ul>
                    <div class=\"dropdown\">
                        Amministrazione
                        <div class=\"dropdown-content\">
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/compilaprodotto.php\">Inserisci prodotto</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/compilatipoprod.php\">Inserisci tipo prodotto</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/compilareparto.php\">Inserisci reparto</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/compilasegnalante.php\">Inserisci segnalante</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/registeracc.php\">Inserisci account</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/modificaprodotti.php\">Gestisci prodotti</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/modificatipoprod.php\">Gestisci tipi prodotti</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/modificareparto.php\">Gestisci reparti</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/modificasegnalante.php\">Gestisci segnalanti</a>
                            <a href=\"https://bicicletta22235id.altervista.org/Pagine/Admin/modificaAccount.php\">Gestisci accounts</a>
                        </div>
                    </div>";
        }
    
        echo "
        <li style=\"float:left;\"><a href=\"../Comuni/risolviNC.php\">Risolvi N.C.</a></li>
        <li style=\"float:left;\"><a href=\"../Comuni/visualizzaNC.php\">Visualziza N.C.</a></li>
        <li style=\"float:left;\"><a href=\""; if($role != 'Admin' && $role != 'Dirigente') echo "../Utenti/dashboard.php"; else echo "../Dirigenti/dashboarddirigenti.php"; echo "\">Dashboard</a></li>
        <li style=\"float:right;\">{$username}</li>  
        <li style=\"float: right;\"><a href=\"../Disconnessione/disconnetti.php\">Disconnettiti</a></li>
        </ul>";
        
         echo "</header>";
    }
}


?>