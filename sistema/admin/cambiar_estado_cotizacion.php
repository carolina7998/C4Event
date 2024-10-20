<?php
include '../../conexion.php';

// Verificar si se han enviado los datos necesarios
if (isset($_POST['evento_id']) && isset($_POST['estado_nuevo'])) {
    $evento_id = $_POST['evento_id'];
    $nuevo_estado = $_POST['estado_nuevo'];

    // Actualizar el estado del evento en la base de datos
    $query = "UPDATE tbleventos SET estado = ? WHERE codigo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $nuevo_estado, $evento_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el estado']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}
?>
