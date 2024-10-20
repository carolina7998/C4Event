<?php

include 'conexion.php';

// Asegúrate de que el docid se pase correctamente ya sea en la URL o en el POST
if (isset($_GET['docid'])) {
    // Guardar el docid de la URL para usarlo en el formulario de cambio de contraseña
    $docid = $_GET['docid'];
} elseif (isset($_POST['docid'])) {
    // Guardar el docid que se envía con el formulario (vía POST)
    $docid = $_POST['docid'];
} else {
    die("No se ha proporcionado un docid válido.");
}

// Procesar el cambio de contraseña solo si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = $_POST['new_password'];

    if ($conn) {
        // Actualizar la contraseña utilizando el docid obtenido
        $query = "UPDATE tblusuarios SET contrasena= ? WHERE docid= ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $pass, $docid);
        $stmt->execute();

        header("Location: login.php?message=success_password");
    } else {
        echo "Error en la conexión a la base de datos";
    }
}

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña | C4Event</title>
    <link rel="shortcut icon" href="img/logooo.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/estilosLogin.css">
</head>
<body>

    <section class="form-main">
        <div class="form-content">
            <div class="box">
                <h3>Recupera tu contraseña</h3>
                <form action="change_password.php" method="post">
                    <!-- Campo oculto para pasar el docid -->
                    <input type="hidden" name="docid" value="<?php echo isset($_GET['docid']) ? htmlspecialchars($_GET['docid']) : ''; ?>">

                    <div class="input-box">
                        <input type="password" name="new_password" placeholder="Nueva contraseña" class="input-control" required>
                    </div>

                    <button type="submit" class="btn">Recuperar contraseña</button>
                </form>
            </div>
        </div>
    </section>
    
</body>
</html>
