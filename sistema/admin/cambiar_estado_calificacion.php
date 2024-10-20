<?php

include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'] ?? '';
    $estado = $_POST['estado'] ?? '';

    // Depuración
    error_log("codigo: $codigo, estado: $estado");

    if ($codigo && $estado) {
        $query = "UPDATE tblcalificacionservicios SET estado = ? WHERE codigo = ?";
        if ($stmt = $conn->prepare($query)) {
            
            $stmt->bind_param('si', $estado, $codigo);
            if ($stmt->execute()) {
                echo 'success';
            } else {
                error_log('Error en la ejecución: ' . $stmt->error);
                echo 'error';
            }
            $stmt->close();
        } else {
            error_log('Error en la preparación de la consulta: ' . $conn->error);
            echo 'error';
        }
    } else {
        error_log('Datos incompletos: docid o estado vacío.');
        echo 'error';
    }
    $conn->close();
}
?>


