<?php
session_start(); // Iniciar la sesión

include '../../conexion.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['docid'])) {
    die("Error: Debes iniciar sesión para dejar un comentario.");
}

// Verificar que se han enviado los datos
if (!isset($_POST['servicio']) || !isset($_POST['comentario']) || !isset($_POST['calificacion'])) {
    die("Error: Faltan datos en el formulario.");
}

// Recibir datos
$comentario = $_POST['comentario'];
$calificacion = (int)$_POST['calificacion'];
$servicio_id = (int)$_POST['servicio'];
$usuario_id = $_SESSION['docid']; // Capturamos el docid del usuario desde la sesión

// Verificar que el servicio existe
$servicioCheck = $conn->prepare("SELECT codigo FROM tblservicios WHERE codigo = ?");
$servicioCheck->bind_param("i", $servicio_id);
$servicioCheck->execute();
$servicioCheck->store_result();

if ($servicioCheck->num_rows > 0) {
    // Insertar la calificación junto con el usuario
    $stmt = $conn->prepare("INSERT INTO tblcalificacionservicios (comentario, calificacion, servicio, usuario, fecha) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("siii", $comentario, $calificacion, $servicio_id, $usuario_id);
    
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error al guardar la calificación: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Error: El servicio seleccionado no existe.";
}

$servicioCheck->close();
$conn->close();

?>
