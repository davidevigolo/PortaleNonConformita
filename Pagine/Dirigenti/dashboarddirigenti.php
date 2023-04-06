<?php
session_start();

if(!isset($_SESSION['valid'])){
    echo "<header style=\"background-color: rgb(199 50 50);\">Sessione scaduta, rieffettuare l'accesso.</header>";
    exit;
}
if(!$_SESSION['valid']){
    echo "<header style=\"background-color: rgb(199 50 50);\">Sessione invalida, rieffettuare l'accesso.</header>";
    exit;
}

if($_SESSION[role] != "Dirigente" && $_SESSION[role] != "Admin"){
    //header('location: bicicletta22235id.altervista.org/Pagine/Utenti/dashboard.php');
}

$servername = "localhost";
$username = "";
$password = "";
$dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

$connessione = new mysqli($servername,$username,$password,$dbname);

$dataqa = "SELECT count(*) as c FROM SEGNALAZIONE WHERE DATACHIUSURA IS NULL AND STATO <> 'in approvazione'";
$dataqc = "SELECT count(*) as c FROM SEGNALAZIONE WHERE DATACHIUSURA IS NOT NULL";
$dataqp = "SELECT count(*) as c FROM SEGNALAZIONE WHERE STATO='in approvazione'";
$dataa = mysqli_fetch_assoc($connessione->query($dataqa));
$datac = mysqli_fetch_assoc($connessione->query($dataqc));
$datap = mysqli_fetch_assoc($connessione->query($dataqp));

//$dataPoints[] = array("label"=> "Aperte", "y"=> $dataa[c]);
//$dataPoints[] = array("label"=> "Chiuse", "y"=> $datac[c]);
//$dataPoints[] = array("label"=> "In Approvazione", "y"=> $datap[c]);
$dataPoints[] = $dataa[c];
$dataPoints[] = $datac[c];
$dataPoints[] = $datap[c];


/*$dataqtrend = "SELECT DISTINCT DATACREAZIONE FROM SEGNALAZIONE ORDER BY DATACREAZIONE ASC";
$datatrend = $connessione->query($dataqtrend);

while($row = mysqli_fetch_assoc($datatrend)){
    $dataqsum = "SELECT COUNT(*) as s FROM SEGNALAZIONE WHERE DATACREAZIONE <= '$row[DATACREAZIONE]'";
    $datasum = mysqli_fetch_assoc($connessione->query($dataqsum));
    $dataPointsLine[] = array("label"=> "$row[DATACREAZIONE]", "y"=> $datasum[s]);
}

$dataqtrendchius = "SELECT DISTINCT DATACHIUSURA FROM SEGNALAZIONE WHERE DATACHIUSURA IS NOT NULL ORDER BY DATACHIUSURA ASC";
$datatrendchius = $connessione->query($dataqtrendchius);

while($row = mysqli_fetch_assoc($datatrendchius)){
    $dataqsum = "SELECT COUNT(*) as s FROM SEGNALAZIONE WHERE DATACHIUSURA <= '$row[DATACHIUSURA]' AND DATACHIUSURA IS NOT NULL";
    $datasum = mysqli_fetch_assoc($connessione->query($dataqsum));
    $dataPointsLineChius[] = array("label"=> "$row[DATACHIUSURA]", "y"=> $datasum[s]);
}*/

$datesq = "(SELECT DATACHIUSURA DATA FROM SEGNALAZIONE WHERE DATACHIUSURA IS NOT NULL) UNION (SELECT DATACREAZIONE DATA FROM SEGNALAZIONE WHERE DATACREAZIONE IS NOT NULL) ORDER BY DATA ASC";
$date = $connessione->query($datesq);
while($row = mysqli_fetch_assoc($date)){
    $dateassoc[] = $row[DATA];
    $ncaperteq = "SELECT count(*) as C FROM SEGNALAZIONE WHERE DATACREAZIONE <= '$row[DATA]'"; // NON METTO IS NULL PERCHE' UNA NC VIENE CREATA E BASTA, CHE SIA IN ESECUZIONE E' UN ALTRA COSA
    $ncchiuseq = "SELECT count(*) as C FROM SEGNALAZIONE WHERE DATACHIUSURA <= '$row[DATA]' AND DATACHIUSURA IS NOT NULL";
    $ncaperte = $connessione->query($ncaperteq);
    $ncchiuse = $connessione->query($ncchiuseq);
    $ncaperte = mysqli_fetch_assoc($ncaperte);
    $ncchiuse = mysqli_fetch_assoc($ncchiuse);
    $ncapertearr[] = $ncaperte[C];
    $ncchiusearr[] = $ncchiuse[C];
}

/*$nomirepartoq = "SELECT NOME FROM REPARTO";
$nomireparto = $connessione->query($nomirepartoq);
while($row = mysqli_fetch_assoc($nomireparto)){
    $nomirep[] = $row[NOME];
}*/
$ncrepartiq = "SELECT R.NOME,count(S.DATACHIUSURA) as C,count(S.NCREPARTO) as CTOT FROM REPARTO R LEFT JOIN SEGNALAZIONE S ON S.NCREPARTO=R.NOME GROUP BY R.NOME"; //count(DATACHIUSURA) conta i record con datachiusura diversi da null e quindi le NC chiuse, mentre l'altro conta il numero di nc totali, uso l'attributo NCREPARTO perchè se un reparto nella left join non ha segnalazioni MySQL conta anche la riga del group by, conto invece così solo le righe che sono effettivamente segnalazioni e quindi col campo valorizzato
$ncrepartir = $connessione->query($ncrepartiq);
while($row = mysqli_fetch_assoc($ncrepartir)){
    $ncreparti[(string) $row[NOME]] = array(
        "a" => (integer)$row[CTOT] - $row[C],
        "c" => (integer)$row[C]
    );
}


	
?>
<!DOCTYPE HTML>
<html>
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://raw.githubusercontent.com/chartjs/Chart.js/master/src/plugins/plugin.filler/index.js"></script>
    <title>Dashboard</title> 
<script>
/*window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
    backgroundColor: "transparent",
	animationEnabled: true,
	exportEnabled: true,
	title:{
        fontColor: "#FFF",
		text: "Stato non conformità",
	},
	data: [{
		type: "pie",
		showInLegend: "true",
		legendText: "{label}",
		indexLabelFontSize: 16,
        indexLabelFontColor: "#FFF",
		indexLabel: "{label} - #percent%",
		yValueFormatString: "฿#,##0",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}],
    legend:{
        fontColor: "#FFF"
    }
});

var chartLine = new CanvasJS.Chart("chartLineContainer", {
    backgroundColor: "transparent",
	animationEnabled: true,
	exportEnabled: true,
	title:{
        fontColor: "#FFF",
		text: "Stato non conformità",
	},
	data: [{
		type: "area",
		showInLegend: "true",
        legendText: "Numero N.C. create",
		indexLabelFontSize: 16,
        indexLabelFontColor: "#FFF",
		yValueFormatString: "Create : #,##0",
		dataPoints: <?php echo json_encode($dataPointsLine, JSON_NUMERIC_CHECK); ?>
	},{
		type: "area",
		showInLegend: "true",
        legendText: "Numero N.C. chiuse",
		indexLabelFontSize: 16,
        indexLabelFontColor: "#FFF",
		yValueFormatString: "Chiuse : #,##0",
		dataPoints: <?php echo json_encode($dataPointsLineChius, JSON_NUMERIC_CHECK); ?>
	}],
    legend:{
        fontColor: "#FFF"
    }
});
chart.render();
chartLine.render();
 
}*/
</script>
</head>
<body>
<?php  
        echo '<header>';
        if($_SESSION['role']=='Admin'){ 
            
                echo "<ul>
                    <li style=\"float:left;\"><a href=\"../Admin/registeracc.php\">Registra Account</a></li>
                    <li style=\"float:left;\"><a href=\"../Admin/modificaAccount.php\">Gestisci Account</a></li>
					<li style=\"float:left;\"><a href=\"../Admin/registersegnalante.php\">Registra segnalante</a></li>
                    ";
        }

        echo "
        <li style=\"float:left;\"><a href=\"../Comuni/risolviNC.php\">Risolvi N.C.</a></li>
        <li style=\"float:left;\"><a href=\"../Comuni/visualizzaNC.php\">Visualziza N.C.</a></li>
		<li style=\"float:left;\"><a href=\""; if($_SESSION['role'] != 'Admin' && $_SESSION['role'] != 'Dirigente') echo "../Utenti/dashboard.php"; else echo "../Dirigenti/dashboarddirigenti.php"; echo "\">Dashboard</a></li>
		<li style=\"float:right;\">{$_SESSION['username']}</li>  
        <li style=\"float: right;\"><a href=\"../Disconnessione/disconnetti.php\">Disconnettiti</a></li>
        </ul>";
        
     	echo "</header>";
?>
<div id="title">Dashboard dirigenza</div>
<div id="flexcontainer" style="display: flex; width: 90%; margin:auto;">
<div id="container" style="flex: 30%; margin: 10px 10px">
    <div id="chartContainer" style="height: 370px; width: 100%;"><canvas id="piechart"></canvas></div>
</div>
<body>
<div id="container" style="flex: 30%; margin: 10px 10px">
    <div id="chartLineContainer" style="height: 370px; width: 100%;"><canvas id="areachart"></canvas></div>
</div>
<body>
<div id="container" style="flex: 30%; margin: 10px 10px">
    <div id="chartContainer" style="height: 370px; width: 100%;"><canvas id="stackchart"></canvas></div>
</div>
<script type="text/javascript">
       const ptx = document.getElementById('piechart');
       const atx = document.getElementById('areachart');
       const stx = document.getElementById('stackchart');

    const dataPie = {
        labels: ['Aperte','Chiuse','In Approvazione'],
        datasets: [{
            label: "Conteggio",
            data: [<?php echo "$dataa[c], $datac[c], $datap[c]";?>],
            fill: true,
            backgroundColor: ['rgba(75, 192, 192,0.4)','rgba(255, 100, 50,0.4)','rgba(74, 232, 116,0.4)'],
            borderColor: ['rgba(75, 192, 192,1)','rgba(255, 100, 50,1)','rgba(74, 232, 116,1)'],
            borderWidth: 1,
            tension: 0.1
        }]
    }

    new Chart(ptx, {
        type: 'polarArea',
        data: dataPie,
        options: {
            scales: {
                r: {
                    ticks: {
                        backdropColor: 'transparent'
                    }
                }
            }
        }
    });

    const data = {
        labels: <?php echo json_encode($dateassoc)?>,
        datasets: [{
            label: "NC Aperte",
            data: <?php echo json_encode($ncapertearr)?>,
            fill: true,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        },
        {
            label: "NC Risolte",
            data: <?php echo json_encode($ncchiusearr)?>,
            fill: true,
            borderColor: 'rgb(255, 100, 50)',
            backgroundColor: 'rgba(255, 100, 50, 0.2)',
            tension: 0.1
        }]
    }

    const dataStack = {
        labels: <?php echo json_encode(array_keys($ncreparti))?>,
        datasets: [{
            label: "Non conformità aperte",
            data: <?php 
            $keys = array_keys($ncreparti);
            while($key = array_shift($keys)){
                $apertearray[] = $ncreparti[$key][a];
            }
            echo json_encode($apertearray);
            ?>,
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
                'rgb(75, 192, 192)'
            ],
            borderWidth: 1,
            tension: 0.1
        },{
            label: "Non conformità chiuse",
            data: <?php 
                $keys = array_keys($ncreparti);
                while($key = array_shift($keys)){
                    $chiusearray[] = $ncreparti[$key][c];
                }
                echo json_encode($chiusearray);
            ?>,
            backgroundColor: [
                'rgba(255, 100, 50, 0.2)'
            ],
            borderColor: [
                'rgb(255, 100, 50)'
            ],
            borderWidth: 1,
            tension: 0.1
        }]
    }

    new Chart(atx, {
    type: 'line',
    data: data,
    });

    new Chart(stx, {
    type: 'bar',
    data: dataStack,
    // options: {
    //         scales: {
    //             x: {
    //                 stacked: true,
    //             },
    //             y: {
    //                 stacked: true
    //             }
    //         }
    //     }
    });
        
    </script>
    </div>
    <form action="../Utenti/compilanc.php" method="GET" style="width: 100%;">
            <input type="submit" value="Segnala una non conformità" class="fullpage">
    </form>
</body>
</html>