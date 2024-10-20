<?php

include '../conexion.php';

// Asegúrate de que el docid se pase correctamente en la URL
if (isset($_GET['docid'])) {
    $docid = $_GET['docid'];
} else {
    die("No se ha proporcionado un docid válido.");
}

$pass = $_POST['new_password'];

if ($conn) {
    // Actualizar la contraseña utilizando el docid obtenido
    $query = "UPDATE tblusuarios SET contrasena= ? WHERE docid= ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $pass, $docid);
    $stmt->execute();

    header("Location: ../login.php?message=success_password");
} else {
    echo "Error en la conexión a la base de datos";
}
?>
