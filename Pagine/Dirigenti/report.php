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

$servername = "localhost";
$username = "";
$password = "";
$dbname = "my_bicicletta22235id";  //va cambiato il nome del db secondo il nome usato

$connessione = mysqli_connect($servername, $username, $password, $dbname);
    
if ($connessione->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

require('./fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',26);
//intestazione

$agrave = iconv('UTF-8', 'iso-8859-1', 'Report non conformità');
$pdf->Cell(0,10,$agrave,0,1,'C');

$pdf->SetFont('Arial','',14);
$pdf->Ln(3);
$pdf->SetFont('Courier','B',12);
$pdf->Cell(40,10,'Descrizione:',0,1);
$pdf->SetFont('Courier','',12);

$val = iconv('UTF-8', 'iso-8859-1', $_POST[descrizione]);
$pdf->MultiCell(0, 10, $val, 0);

$pdf->Ln(3);
$pdf->SetFont('Courier','B',12);
$pdf->Cell(40,10,"Autore:");
$pdf->SetFont('Courier','',12);
$pdf->Cell(10,10,"$_SESSION[username]");
$pdf->Ln(10);
//fine intestazione

$reportq = "SELECT ID,TIPO,DATACREAZIONE,DATACHIUSURA,AUTORE,STATO,NCREPARTO,NCFORNITORE,NOTE FROM SEGNALAZIONE WHERE DATACREAZIONE <= '$_POST[dmax]' AND DATACREAZIONE >= '$_POST[dmin]' AND STATO='$_POST[stato]'";
$report = $connessione->query($reportq);
$pdf->SetFont('Courier','B',7);

//intestazione tabella

$pdf->Cell(5,10,"ID",1,0,'C');
$pdf->Cell(8,10,"TIPO",1,0,'C');
$pdf->Cell(25,10,"DATA CREAZIONE",1,0,'C');
$pdf->Cell(25,10,"DATA CHIUSURA",1,0,'C');
$pdf->Cell(10,10,"AUTORE",1,0,'C');
$pdf->Cell(30,10,"STATO",1,0,'C');
$pdf->Cell(30,10,"NCREPARTO",1,0,'C');
$pdf->Cell(20,10,"NCFORNITORE",1,0,'C');
$pdf->Cell(40,10,"NOTE",1,1,'C');

//fine intestazione tabella
while($row = mysqli_fetch_assoc($report)){
    $pdf->Cell(5,10,"$row[ID]",1,0,'C');
    $pdf->Cell(8,10,"$row[TIPO]",1,0,'C');
    $pdf->Cell(25,10,"$row[DATACREAZIONE]",1,0,'C');
    $pdf->Cell(25,10,"$row[DATACHIUSURA]",1,0,'C');
    $pdf->Cell(10,10,"$row[AUTORE]",1,0,'C');
    $pdf->Cell(30,10,"$row[STATO]",1,0,'C');
    $pdf->Cell(30,10,"$row[NCREPARTO]",1,0,'C');
    $pdf->Cell(20,10,"$row[NCFORNITORE]",1,0,'C');
    $pdf->Cell(40,10,"$row[NOTE]",1,1,'C');
}


$pdf->Output();
?>