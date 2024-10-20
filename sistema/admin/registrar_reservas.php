<?php

include('../../conexion.php');  // Incluye la conexión a la base de datos

if(isset($_POST['save_event'])) {
    // Escapamos los datos para mayor seguridad
    $nombre_reserva = mysqli_real_escape_string($conn, $_POST['nombre_reserva']);  // Capturamos el nombre de la reserva
    $fechareservada = mysqli_real_escape_string($conn, $_POST['fechareservada']);  // Capturamos la fecha reservada

    if(!empty($nombre_reserva) && !empty($fechareservada)) {
        // Inserción de los datos en la base de datos
        $insert_query = mysqli_query($conn, "INSERT INTO tblfechasreservadas (nombre, fechareservada) VALUES ('$nombre_reserva', '$fechareservada')");
        
        if($insert_query) {
            header('location:fechas_reservadas.php');  // Redirigir si la inserción fue exitosa
        } else {
            $msg = "Error: " . mysqli_error($conn);  // Mostrar mensaje de error en caso de fallo
        }
    } else {
        $msg = "Por favor, completa todos los campos.";  // Validación para evitar campos vacíos
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <title>Crear Reserva | C4Event</title>

    <style>

        nav {
            background-color: #007bff;
            color: white;
            padding: 1px;
            text-align: center;
            position: fixed;
            top: 0;
            left: 0; /* Asegúrate de que ocupe todo el ancho */
            right: 0; /* Asegúrate de que ocupe todo el ancho */
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000; /* Asegúrate de que esté por encima de otros elementos */
        }
        .btn {
            --primary-color: #16a291;
            --secondary-color: #fff;
            --third-color : #6d6f6f;
            --hover-color: #111;
            --arrow-width: 10px;
            --arrow-stroke: 2px;
            box-sizing: border-box;
            border: 0;
            border-radius: 20px;
            color: var(--secondary-color);
            padding: 0.6em 1.8em;
            background: linear-gradient(to right, var(--primary-color),var(--third-color));
            display: flex;
            transition: 0.2s background;
            align-items: center;
            gap: 0.6em;
            font-weight: bold;
        }

        .box {
        width: 100%;
        max-width: 600px;
        background-color: #f9f9f9;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 16px;
        margin: 0 auto;
        }
        .error {
        color: red;
        font-weight: 700;
        }
    </style>
</head>


<body>

    <?php include "../includes/nav_admin.php";?>

    <div class="container">
        <div class="table-responsive"></div>
        <h1 align="center">Crear Reserva</h1><br>
        <div class="box">
            <form method="post">
                <div class="form-group">
                    <label for="nombre_reserva">Nombre de la Reserva:</label>
                    <input type="text" name="nombre_reserva" id="nombre_reserva" required class="form-control"/>  <!-- Nuevo campo para el nombre -->
                </div>
                
                <div class="form-group">
                    <label for="fechareservada">Fecha de Reserva:</label>
                    <input type="datetime-local" name="fechareservada" id="fechareservada" required class="form-control"/>
                </div>

                <div class="form-group">
                    <input type="submit" id="save_event" name="save_event" value="Guardar Reserva" class="btn btn-success"/>
                </div>

                <p class="error"> <?php if(!empty($msg)){ echo $msg; } ?></p>
            </form>
        </div>
    </div>
</body>
</html>
