<?php
include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evento_id = $_POST['evento_id'];

    // Verificar si se ha subido un archivo
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
        // Ruta donde se almacenarán los comprobantes
        $dir_subida = 'comprobantes/';
        $archivo = basename($_FILES['comprobante']['name']);
        $ruta_archivo = $dir_subida . $evento_id . "_" . $archivo; // Nombra el archivo con el ID del evento

        // Mover el archivo a la carpeta de comprobantes
        if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta_archivo)) {
            // Guardar la ruta en la base de datos
            $ruta_db = 'comprobantes/' . $evento_id . "_" . $archivo;
            $query = "UPDATE tbleventos SET rutapago = ? WHERE codigo = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('si', $ruta_db, $evento_id);

            if ($stmt->execute()) {
                echo "Comprobante subido correctamente.";
            } else {
                echo "Error al guardar la ruta en la base de datos.";
            }
        } else {
            echo "Error al subir el archivo.";
        }
    } else {
        echo "No se ha seleccionado ningún archivo o hubo un error en la subida.";
    }
} else {
    echo "Método no permitido.";
}
?>
