
<?php
include '../../conexion.php';

if(isset($_POST['codigo']) && isset($_POST['estado'])) {
    $codigo = $_POST['codigo'];
    $nuevoEstado = $_POST['estado'];

    $sql = "UPDATE tblservicios SET estado = ? WHERE codigo = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al preparar la declaración: ' . $conn->error
        ]);
        exit;
    }

    $stmt->bind_param("ss", $nuevoEstado, $codigo);

    if($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Estado del servicio actualizado correctamente.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al ejecutar la declaración: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parámetros incompletos.'
    ]);
}
?>

