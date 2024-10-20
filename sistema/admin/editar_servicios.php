<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_service'])) {
    if (isset($_POST['codigo_servicio'], $_POST['nombre_servicio'], $_POST['valor_servicio'], $_POST['categoria_servicio'])) {
        $codigo_servicio = $conn->real_escape_string($_POST['codigo_servicio']);
        $nombre_servicio = $conn->real_escape_string($_POST['nombre_servicio']);
        $valor_servicio = $conn->real_escape_string($_POST['valor_servicio']);
        $categoria_servicio = $conn->real_escape_string($_POST['categoria_servicio']);

        $query = "UPDATE tblservicios SET nombre='$nombre_servicio', valor='$valor_servicio', categoria='$categoria_servicio'";

        if (isset($_FILES['imagen_servicio']) && $_FILES['imagen_servicio']['error'] == 0) {
            $ruta = $conn->real_escape_string("fotos/".$_FILES['imagen_servicio']['name']);
            $nimg = $_FILES['imagen_servicio']['tmp_name'];
            move_uploaded_file($nimg, $ruta);

            $query .= ", rutaimg='$ruta'";
        }

        $query .= " WHERE codigo='$codigo_servicio'";

        if ($conn->query($query) === TRUE) {
            echo header("location:servicios.php");
        } else {
            echo "Error de actualización: " . $conn->error;
        }
    } else {
        echo "Por favor complete todos los campos del formulario.";
    }
} else {
    echo "No se recibieron datos.";
}

?>

