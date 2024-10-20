<?php

include '../../conexion.php';

// Consulta para obtener los servicios de la tabla tblservicios
$sql = "SELECT codigo, nombre FROM tblservicios WHERE codigo > 0";
$result = $conn->query($sql);

$servicios = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios[] = $row;
    }
}

// Devolver los servicios en formato JSON
echo json_encode($servicios);

$conn->close();
?>
