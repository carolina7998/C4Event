<?php
$alert = '';
session_start();

// Validar si hay una sesión activa
if (!empty($_SESSION['active'])) {
    // Redirigir a la página correspondiente según el rol del usuario
    if ($_SESSION['rol'] == '1') {
        header('Location:../c4event/sistema/admin/');
    } elseif ($_SESSION['rol'] == '2') {
        header('Location:../c4event/sistema/cliente/');
    }
    exit();
} else {
    // Validar el formulario
    if (!empty($_POST)) {
        // Comprobar si los campos no están vacíos
        if (empty($_POST['docid']) || empty($_POST['contrasena'])) {
            $alert = "Ingrese su usuario y clave";
        } else {
            // Conectar a la base de datos
            require_once "conexion.php";
            // Limpiar valores de entrada
            $user = mysqli_real_escape_string($conn, $_POST['docid']);
            $pass = mysqli_real_escape_string($conn, $_POST['contrasena']);

            // Consulta para comprobar las credenciales del usuario
            $query = mysqli_query($conn, "SELECT * FROM tblusuarios WHERE docid='$user' AND contrasena='$pass'");

            $result = mysqli_num_rows($query);

            if ($result > 0) {
                $data = mysqli_fetch_array($query);  // Obtener los datos del usuario

                // Verificar el estado del usuario
                if ($data['estado'] == 'inactivo') {
                    $alert = "<script>
                                Swal.fire({
                                    title: 'El usuario está inactivo',
                                    text: 'Por favor, contacte al administrador.',
                                    icon: 'warning',
                                    customClass: {
                                        popup: 'custom-swal'
                                    }
                                });
                              </script>";
                } else {
                    // Guardar variables de sesión
                    $_SESSION['active'] = true;
                    $_SESSION['docid'] = $data['docid'];
                    $_SESSION['nombres'] = $data['nombres'];
                    $_SESSION['apellidos'] = $data['apellidos'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['telefono'] = $data['telefono'];
                    $_SESSION['rol'] = $data['roles'];  // Usar 'roles' según la estructura de tu tabla
                    $_SESSION['contrasena'] = $data['contrasena'];

                    // Redirigir según el rol del usuario
                    if ($_SESSION['rol'] == '1') {
                        header('Location:../c4event/sistema/admin');
                    } elseif ($_SESSION['rol'] == '2') {
                        header('Location:../c4event/sistema/cliente');
                    }
                    exit();
                }
            } else {
                $alert = 'El usuario o la clave están erradas.';
                session_destroy();  // Destruir la sesión si las credenciales son incorrectas
            }

            mysqli_close($conn);  // Cerrar la conexión a la base de datos
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | C4Event</title>
    <link rel="shortcut icon" href="img/logooo.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/estilosLogin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <section class="form-main">
        <div class="form-content">
            <div class="box">
                <h3>Bienvenido</h3>
                <form action="" method="post">

                    <div class="input-box">
                        <input type="text"  name="docid"  placeholder="Ingrese su documento" class="input-control"><br>
                    </div>

                    <div class="input-box">
                        <input type="password"  name="contrasena"  placeholder="Ingrese su contraseña" class="input-control"><br>
                        <div>
                            <a href="recovery.php" class="gradient-text">Has olvidado tu contraseña</a>
                        </div>
                    </div>

                    <div class="alerta">
                        <p><?php echo isset($alert) ? $alert : ''; ?></p>
                    </div>

                    <button type="submit" class="btn">Iniciar sesión</button>
                </form>

                <p>No tienes una cuenta? <a href="registro.php" class="gradient-text">Crear cuenta</a></p>

            </div>
        </div>
    </section>
    
</body>
</html>
