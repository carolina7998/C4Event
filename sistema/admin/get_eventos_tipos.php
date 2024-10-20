<?php
include '../../conexion.php';

// Consulta para obtener los nombres de los tipos de eventos y la cantidad de eventos
$sql = "SELECT tbltipoevento.nombre as tipo_evento, COUNT(*) as cantidad_eventos
        FROM tbleventos
        INNER JOIN tbltipoevento ON tbleventos.tipoevento = tbltipoevento.codigo
        GROUP BY tbltipoevento.nombre
        ORDER BY cantidad_eventos DESC";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();
echo json_encode($data);
?>
