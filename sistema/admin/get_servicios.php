<?php
include '../../conexion.php';

// Consulta para obtener los servicios más solicitados
$sql = "SELECT s.nombre, COUNT(se.codigoServicios) as cantidad_solicitudes
    FROM tblservicioseventos se
    JOIN tblservicios s ON se.codigoServicios = s.codigo
    GROUP BY s.nombre
    ORDER BY cantidad_solicitudes DESC
";
$result = $conn->query($sql);

// Array para almacenar los datos
$data = array();

// Si hay resultados, añadirlos al array
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Cerrar la conexión
$conn->close();

// Devolver los datos como JSON
echo json_encode($data);
?>
