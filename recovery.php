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
                <h3>Contraseña olvidada</h3>
                <form action="config/recovery.php" method="post">

                    <div class="input-box">
                        <input type="text"  name="email"  placeholder="Ingrese email" class="input-control">
                    </div>

                    <button type="submit" class="btn">Recuperar contraseña</button>
                </form>

                <p>No tienes una cuenta? <a href="registro.php" class="gradient-text">Crear cuenta</a></p>

            </div>

        </div>

    </section>
    
</body>
</html>