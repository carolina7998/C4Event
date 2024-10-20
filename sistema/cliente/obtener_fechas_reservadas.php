<?php

include '../../conexion.php';

// Consulta para obtener las fechas reservadas en formato 'YYYY-MM-DD'
$sql = "SELECT DATE(fechareservada) AS fechareservada FROM tblfechasreservadas";
$resultado = $conn->query($sql);

$fechasReservadas = array();

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $fechasReservadas[] = $fila['fechareservada'];
    }
}

// Retornar las fechas en formato JSON para usarlas en JavaScript
header('Content-Type: application/json');
echo json_encode($fechasReservadas);
?>
