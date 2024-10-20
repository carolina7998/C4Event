<?php
include '../../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_usuario'])) {
    // Verificar si se han recibido todos los datos necesarios
    if (!empty($_POST['docid']) && !empty($_POST['rol'])) {
        
        // Escapar los valores para prevenir inyección SQL
        $docid = $conn->real_escape_string($_POST['docid']);
        $rol = $conn->real_escape_string($_POST['rol']);

        // Consulta de actualización
        $query = "UPDATE tblusuarios 
                  SET roles='$rol' 
                  WHERE docid='$docid'";

        // Ejecutar la consulta y verificar el resultado
        if ($conn->query($query) === TRUE) {
            header("location:index.php");
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
