<?php
// Iniciar la sesión para usar variables de sesión
session_start();

// Conectar a la base de datos
include '../../conexion.php';

// Verificar si se recibe una solicitud POST y si el formulario para agregar un servicio fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    // Verificar si ya se procesó el formulario para evitar duplicados
    if (!isset($_SESSION['form_submitted'])) {
        // Verificar si se completaron todos los campos requeridos
        if (isset($_POST['nombre_servicio'], $_POST['valor_servicio'], $_POST['categoria_servicio'], $_FILES['imagen_servicio'])) {
            // Escapar datos para evitar inyecciones SQL
            $nombre_servicio = $conn->real_escape_string($_POST['nombre_servicio']);
            $valor_servicio = $conn->real_escape_string($_POST['valor_servicio']);
            $categoria_servicio = $conn->real_escape_string($_POST['categoria_servicio']);
            $ruta = "fotos/" . basename($_FILES['imagen_servicio']['name']);
            $nimg = $_FILES['imagen_servicio']['tmp_name'];

            // Subir imagen
            if (move_uploaded_file($nimg, $ruta)) {
                // Insertar el servicio en la base de datos
                $query = "INSERT INTO tblservicios (nombre, valor, categoria, rutaimg) 
                          VALUES ('$nombre_servicio', $valor_servicio, '$categoria_servicio', '$ruta')";

                $cons = $conn->query($query);

                // Verificar si la consulta fue exitosa
                if ($cons) {
                    // Marcar el formulario como procesado para evitar duplicados
                    $_SESSION['form_submitted'] = true;

                    // Redirigir después de agregar el servicio
                    header("location:servicios.php");
                    exit(); // Salir después de la redirección
                } else {
                    echo "Error al registrar el servicio.";
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            echo "Por favor complete todos los campos del formulario.";
        }
    }
} else {
    // Limpiar la variable de sesión al cargar la página por primera vez (GET request)
    unset($_SESSION['form_submitted']);
}
?>


