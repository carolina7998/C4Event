<?php
include ("../../conexion.php");

if(isset($_POST['id'])) {
    // Obtenemos los datos del POST
    $nombre = $_POST['title']; // En este caso, el nombre o título del evento reservado
    $fecha_reservada = $_POST['start']; // La fecha reservada proviene del campo 'start'
    $id = $_POST['id']; // ID del evento a actualizar

    // Consulta para actualizar los datos en la tabla tblfechasreservadas
    $update_query = mysqli_query($conn, "UPDATE tblfechasreservadas  
    SET nombre = '$nombre', fechareservada = '$fecha_reservada' WHERE id='$id'");

    if($update_query) {
        echo "Fecha reservada actualizada exitosamente.";
    } else {
        echo "Error al actualizar la fecha reservada: " . mysqli_error($conn);
    }
}
?>
