<?php

include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $docid = $_POST['docid'] ?? '';
    $estado = $_POST['estado'] ?? '';

    
    error_log("docid: $docid, estado: $estado");

    if ($docid && $estado) {
        $query = "UPDATE tblusuarios SET estado = ? WHERE docid = ?";
        if ($stmt = $conn->prepare($query)) {
            
            $stmt->bind_param('si', $estado, $docid);
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


