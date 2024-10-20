<?php

include 'conexion.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $docid = $_POST['docid'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $contrasena = $_POST['contrasena']; // Sin encriptación
    $rol = '2'; // Asignar rol automáticamente

    $sql = "INSERT INTO tblusuarios (docid, nombres, apellidos, email, telefono, contrasena, roles)
            VALUES ('$docid', '$nombres', '$apellidos', '$email', '$telefono', '$contrasena', '$rol')";

    if ($conn->query($sql) === TRUE) {
        // Mostrar mensaje de éxito con SweetAlert
        echo "<script>
                Swal.fire({
                  position: 'top-end',
                  icon: 'success',
                  title: 'Registro exitoso!',
                  showConfirmButton: false,
                  timer: 1500
                }).then(function() {
                    window.location = 'login.php'; // Redirige al usuario al iniciar sesión
                });
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | C4Event</title>
    <link rel="shortcut icon" href="img/logooo.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/estilosLogin.css">
    <!-- Incluye SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateForm() {
            const docid = document.querySelector('input[name="docid"]').value;
            const telefono = document.querySelector('input[name="telefono"]').value;

            if (!/^\d+$/.test(docid)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el documento',
                    text: 'El documento debe contener solo números.',
                });
                return false;
            }

            if (telefono.length > 13) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error en el teléfono',
                    text: 'El teléfono no debe tener más de 13 dígitos.',
                });
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

    <section class="form-main">
        <div class="form-content">
            <div class="box">
                <h3>Crear una cuenta</h3>
                <form action="" method="POST" onsubmit="return validateForm()">

                    <div class="input-box">
                        <input type="number" name="docid" placeholder="Ingrese su id" class="input-control" required>
                    </div>

                    <div class="input-box">
                        <input type="text" name="nombres" placeholder="Ingrese nombre" class="input-control" required>
                    </div>

                    <div class="input-box">
                        <input type="text" name="apellidos" placeholder="Ingrese apellidos" class="input-control" required>
                    </div>

                    <div class="input-box">
                        <input type="email" name="email" placeholder="Ingrese email" class="input-control" required>
                    </div>

                    <div class="input-box">
                        <input type="number" name="telefono" placeholder="Ingrese telefono" class="input-control" required maxlength="13">
                    </div>

                    <div class="input-box">
                        <input type="password" name="contrasena" placeholder="Ingrese su contraseña" class="input-control" required>
                    </div>

                    <button type="submit" class="btn">Crear cuenta</button>
                </form>

                <p>¿Ya tienes una cuenta? <a href="login.php" class="gradient-text">Iniciar sesión</a></p>

            </div>
        </div>
    </section>
    
</body>
</html>
