<?php
include '../../conexion.php';

if (isset($_GET['evento_id'])) {
    $evento_id = $_GET['evento_id'];

    // Obtener la ruta del comprobante desde la base de datos
    $query = "SELECT rutapago FROM tbleventos WHERE codigo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $evento_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $evento = $resultado->fetch_assoc();

    if ($evento && !empty($evento['rutapago'])) {
        $ruta_comprobante = $evento['rutapago'];
        //echo "<h3>Comprobante de pago:</h3>";
        echo "<img src='../cliente/$ruta_comprobante' alt='Comprobante de pago' ' class='img-fluid'>";

    } else {
        echo "No se ha subido un comprobante para este evento.";
    }
} else {
    echo "ID del evento no proporcionado.";
}
?>
