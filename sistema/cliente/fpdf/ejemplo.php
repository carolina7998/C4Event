<?php 
include ('conexion.php');
require ('fpdf.php');

$bibliotecas  =$conn->query("SELECT*FROM bibliotecas"); 

$pdf= new FPDF();
$pdf-> AddPage();
$pdf->SetFont('Arial','B',16);

//$pdf->Cell(60,10,'Hecho con FPDF',0,1,'C');
$pdf->Image('img1.jpg' , 5 ,2, 35 , 38,'JPG');
$pdf->Ln(30);
//$pdf->SetFont(234,156,189);

$nombreBibliotecas= [];
$pdf -> Cell(30,10,'ID',1,0,'C');
$pdf -> Cell(40,10,'CODIGO DANE',1,0,'C');
$pdf -> Cell(60,10,'NOMBRE',1,0,'C');
$pdf -> Cell(60,10,'DIRECCION',1,1,'C');

if ($bibliotecas->num_rows > 0) {
    while($bibli = $bibliotecas->fetch_assoc()) {
        $nombreBibliotecas[] = $bibli; 

        // Escribir en el PDF
        
        $pdf->Cell(30, 10, $bibli['id'], 1);
        $pdf->Cell(40, 10, $bibli['codigo_dane'], 1);
        $pdf->Cell(60, 10, $bibli['nombre'], 1);
        $pdf->Cell(60, 10, $bibli['direccion'], 1);
        $pdf->Ln();
    }
} else {
    $pdf->Cell(0, 10, 'No se encontraron bibliotecas.', 1, 1, 'C');
}

$conn->close();


$pdf->Output();



?>
