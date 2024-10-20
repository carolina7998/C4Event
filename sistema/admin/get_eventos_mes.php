<?php
include '../../conexion.php';

// Array de todos los meses del año
$meses = array(
    'January' => 0,
    'February' => 0,
    'March' => 0,
    'April' => 0,
    'May' => 0,
    'June' => 0,
    'July' => 0,
    'August' => 0,
    'September' => 0,
    'October' => 0,
    'November' => 0,
    'December' => 0,
);

// Consulta SQL para obtener los eventos por mes
$sql = "SELECT MONTHNAME(fechaevento) as mes, COUNT(*) as cantidad_eventos
        FROM tbleventos
        GROUP BY MONTH(fechaevento)
        ORDER BY MONTH(fechaevento)";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $meses[$row['mes']] = $row['cantidad_eventos'];
    }
}

$conn->close();
echo json_encode($meses);
?>
