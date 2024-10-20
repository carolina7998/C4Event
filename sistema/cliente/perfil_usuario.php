<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/logooo.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/perfil_cliente.css">
    <title>Perfil | C4Event</title>
    
</head>
<body>
    <?php
    
    include '../../conexion.php';

    session_start();
    
    if (isset($_SESSION['docid'])) {
        $docid = $_SESSION['docid'];
    } else {
        echo "Error: No se ha encontrado el docid del usuario.";
        exit;
    }

    $sql = "SELECT docid, nombres, apellidos, email, telefono, contrasena FROM tblusuarios WHERE docid = '$docid'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $docid = $row['docid'];
        $nombres = $row['nombres'];
        $apellidos = $row['apellidos'];
        $email = $row['email'];
        $telefono = $row['telefono'];
        $contrasena = $row['contrasena'];
    } else {
        echo "No se encontró el usuario.";
        exit;
    }

    $conn->close();
    ?>

    <?php include '../includes/nav_cliente.php';?>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 col-lg-10">
                <form action="actualizar_perfil.php" method="POST">
                    <h1 class="text-center mb-4">Actualizar información</h1>

                    <!-- Primera fila de inputs -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="docid">Documento de Identidad:</label>
                            <input type="text" id="docid" name="docid" value="<?php echo $docid; ?>" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nombres">Nombres:</label>
                            <input type="text" id="nombres" name="nombres" value="<?php echo $nombres; ?>" readonly>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="apellidos">Apellidos:</label>
                            <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" readonly>
                        </div>
                    </div>

                    <!-- Segunda fila de inputs -->
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="email">Correo Electrónico:</label>
                            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" value="<?php echo $telefono; ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="contrasena">Contraseña:</label>
                            <input type="text" id="contrasena" name="contrasena" value="<?php echo $contrasena; ?>" required>
                        </div>
                    </div>

                    <div class="form-submit d-flex justify-content-center">
                        <input type="submit" class="btn-custom" value="Actualizar">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
