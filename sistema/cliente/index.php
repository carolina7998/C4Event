<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilosAdmin.css">
    <link rel="stylesheet" href="../css/index_cliente.css">
    <title>Inicio | C4Event</title>
</head>

<body>

    <?php include "../includes/nav_cliente.php";?>

    <header class="hero" style="background-image: url('../img/principal5.jpeg'); background-size: cover; background-position: center; height: 270px; display: flex; justify-content: center; align-items: center; color: white; text-align: center; margin-bottom: 0;">
        <div class="hero-content" style="background-color: rgba(0, 0, 0, 0.3); padding: 20px; font-size: 20px;">
            <h1>Planea <em>momentos</em> inolvidables</h1>
        </div>
    </header>

    <!-- Section: Servicios -->
    <section class="section_servicios" style="background: rgb(234, 230, 225); padding: 20px; width: 100%;">
        <div class="container-fluid" style="max-width: 650px; padding: 0;">

            <?php 
            include '../../conexion.php';

            // Consulta para obtener los comentarios y las calificaciones
            $sql = "SELECT c.calificacion, s.nombre AS servicio, c.comentario, c.fecha
                    FROM tblcalificacionservicios c
                    JOIN tblservicios s ON c.servicio = s.codigo
                    WHERE c.estado = 'activo'
                    ORDER BY c.fecha DESC";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Inicio del carrusel
                echo '<div id="commentCarousel" class="carousel slide" data-bs-ride="carousel">';
                echo '  <div class="carousel-inner">';
                echo '<h2 class="card-title" style="text-align:center; margin-bottom:1.2rem;"></h2>';

                $count = 0;
                while ($row = $result->fetch_assoc()) {
                    $rating = intval($row['calificacion']);
                    $servicio = $row['servicio'];
                    $comentario = $row['comentario'];
                    $fecha = date('d-m-Y', strtotime($row['fecha']));

                    // Generar las estrellas
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= ($i <= $rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                    }

                    // Clase active solo para el primer item
                    $activeClass = ($count == 0) ? 'active' : '';

                    // Elemento del carrusel
                    echo '<div class="carousel-item ' . $activeClass . '">';
                    echo '  <div class="row justify-content-center">';
                    echo '      <div class="col-md-12">';  
                    echo '          <div class="card mb-3" style="text-align: center;">';
                    echo '              <div class="card-body">';
                    echo '                  <h5 class="card-title">' . htmlspecialchars($servicio) . '</h5>';
                    echo '                  <div class="stars mb-2">' . $stars . '</div>';
                    echo '                  <p class="comment">' . htmlspecialchars($comentario) . '</p>';
                    echo '                  <div class="comment-date">' . $fecha . '</div>';
                    echo '              </div>';
                    echo '          </div>';
                    echo '      </div>';
                    echo '  </div>';
                    echo '</div>';

                    $count++;
                }

                echo '  </div>'; // Fin del carousel-inner

                // Flechas de navegación
                echo '  <a class="carousel-control-prev" href="#commentCarousel" role="button" data-bs-slide="prev">';
                echo '      <span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                echo '      <span class="visually-hidden">Anterior</span>';
                echo '  </a>';
                echo '  <a class="carousel-control-next" href="#commentCarousel" role="button" data-bs-slide="next">';
                echo '      <span class="carousel-control-next-icon" aria-hidden="true"></span>';
                echo '      <span class="visually-hidden">Siguiente</span>';
                echo '  </a>';

                echo '</div>'; // Fin del carrusel
            } else {
                echo "<p>No hay comentarios disponibles.</p>";
            }

            $conn->close();
            ?>

            <!-- Botón para abrir el modal -->
            <div class="text-center mt-2">
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#commentModal">Dejar comentario</button>
            </div>

        </div>
    </section>

    <!-- Modal para dejar comentario -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="commentModalLabel">
                        <img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Deja tu comentario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm" action="guardar_calificacion.php" method="POST">
                        <!-- Comentario -->
                        <label for="comentario">Deja tu comentario:</label><br>
                        <textarea id="comentario" name="comentario" rows="5" class="form-control" required></textarea><br>

                        <!-- Calificación (Estrellas) -->
                        <label for="calificacion">Calificación:</label><br>
                        <div class="stars" id="modalStars" style="font-size:15px;">
                            <i data-value="1" class="star">&#9733;</i>
                            <i data-value="2" class="star">&#9733;</i>
                            <i data-value="3" class="star">&#9733;</i>
                            <i data-value="4" class="star">&#9733;</i>
                            <i data-value="5" class="star">&#9733;</i>
                        </div>
                        <input type="hidden" id="calificacion" name="calificacion" value="0" required>

                        <!-- Desplegable de servicios -->
                        <label for="servicio">Selecciona un servicio:</label><br>
                        <select id="servicio" name="servicio" class="form-control" required>
                            <?php
                            // Conectar a la base de datos
                            include '../../conexion.php';

                            // Consulta para obtener los servicios activos
                            $sql_servicios = "SELECT codigo, nombre FROM tblservicios WHERE estado = 'activo'";
                            $result_servicios = $conn->query($sql_servicios);

                            if ($result_servicios->num_rows > 0) {
                                // Llenar el desplegable con los servicios
                                while ($servicio = $result_servicios->fetch_assoc()) {
                                    echo '<option value="' . htmlspecialchars($servicio['codigo']) . '">' . htmlspecialchars($servicio['nombre']) . '</option>';
                                }
                            } else {
                                echo '<option value="">No hay servicios disponibles</option>';
                            }

                            // Cerrar conexión
                            $conn->close();
                            ?>
                        </select><br>

                        <button type="submit" class="btn btn-primary w-100">Enviar Calificación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS y dependencias -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Manejo de las estrellas
        const stars = document.querySelectorAll('.star');
        const calificacionInput = document.getElementById('calificacion');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = star.getAttribute('data-value');
                calificacionInput.value = rating;

                // Eliminar clase 'filled' de todas las estrellas
                stars.forEach(s => s.classList.remove('fas'));
                stars.forEach(s => s.classList.add('far'));

                // Añadir clase 'filled' a las estrellas seleccionadas
                for (let i = 0; i < rating; i++) {
                    stars[i].classList.remove('far');
                    stars[i].classList.add('fas');
                }
            });
        });
    </script>

</body>

</html>
