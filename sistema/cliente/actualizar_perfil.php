<?php

include '../../conexion.php';

session_start();

// Verifica si el docid está en la sesión
if (isset($_SESSION['docid'])) {
    $docid = $_SESSION['docid']; // Recupera el docid de la sesión
} else {
    echo "Error: No se ha encontrado el docid del usuario.";
    exit;
}

// Verifica si los datos han sido enviados por el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Recupera los datos enviados por el formulario
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);
    
    // Consulta para actualizar los datos del usuario
    $sql = "UPDATE tblusuarios 
            SET email = '$email', telefono = '$telefono', contrasena = '$contrasena'
            WHERE docid = '$docid'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Datos actualizados correctamente.";
        
        header("Location: index.php");
    } else {
        echo "Error actualizando los datos: " . $conn->error;
    }
    
    $conn->close();
}
?>