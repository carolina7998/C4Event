<?php
include '../../conexion.php';

// Servicios de la interfaz cliente

$tipos_eventos = $conn->query("SELECT * FROM tbltipoevento");

$categorias_servicios = [];
$result_categorias = $conn->query("SELECT * FROM tblcategoria");

if ($result_categorias) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categoria_id = $row['codigo'];
        $result_servicios = $conn->query("SELECT * FROM tblservicios WHERE categoria = $categoria_id");
        $servicios = [];
        if ($result_servicios) {
            while ($servicio = $result_servicios->fetch_assoc()) {
                $servicios[] = $servicio;
            }
        }
        $categorias_servicios[$row['nombre']] = $servicios;
        $categorias_array[$categoria_id] = $row['nombre'];
    }
}

// Consulta para obtener las fechas reservadas
$sql = "SELECT fechareservada FROM tblfechasreservadas";
$resultado = $conn->query($sql);

$fechasReservadas = array();

if ($resultado->num_rows > 0) {
    while($fila = $resultado->fetch_assoc()) {
        $fechasReservadas[] = $fila['fechareservada'];
    }
}

// Retornar las fechas en formato JSON para usarlas en JavaScript
json_encode($fechasReservadas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilosAdmin.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Madimi+One&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Reddit+Mono:wght@200..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700&display=swap" rel="stylesheet">
    <link href='https://fullcalendar.io/releases/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../css/servicios_cliente.css">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    
    <title>Servicios | C4Event</title>
   
    <script>
        
        document.addEventListener('DOMContentLoaded', function () {
            // Mostrar los primeros servicios en cada categoría al cargar
            <?php foreach ($categorias_servicios as $categoria_id => $servicios): ?>
                showInitialServices(<?= array_search($categoria_id, $categorias_array) ?>);
            <?php endforeach; ?>

            var serviceBoxes = document.querySelectorAll('.service-box');
            serviceBoxes.forEach(function (box) {
                box.addEventListener('click', function (e) {
                    if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON') {
                        var details = this.querySelector('.service-details');
                        details.style.display = (details.style.display === 'none' || details.style.display === '') ? 'block' : 'none';
                    }
                });
            });
        });
    </script>

    
</head>

<body>

    <?php include "../includes/nav_cliente.php";?>
    
    <div class="container">
        <form action="./procesar_evento.php" method="POST">
            
            <div class="row" id="main-container">
                <div class="col-md-12" id="service-container-wrapper">

                    <div class="col-md-12" class="service-container" id="service-container">

                    <div /*style="background-color:beige;"/*>

                    <div class="info-reserva container d-flex">
                        <div class="align-items-center">
                            <div>
                                <label for="fecha_evento">Fecha de Evento</label>
                            </div>
                            <input type="text" id="fecha_evento" name="fecha_evento" placeholder="dd/mm/aaaa" readonly>
                            <div id="calendario_container" style="display:none; position:absolute; z-index:1000;">
                                <div id="calendario"></div>
                            </div>

                        </div>

                        <div>
                            <div>
                                <label for="tipo_evento">Tipo evento</label>
                            </div>
                            <div>
                                <select id="tipo_evento" name="tipo_evento" required class="form-control">
                                    <?php
                                    while ($row = $tipos_eventos->fetch_assoc()): ?>
                                        <option value="<?= $row['codigo'] ?>"><?= $row['nombre'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <div>
                                <label for="num_invitados">Cantidad invitados</label>
                            </div>
                            <div>
                                <input type="number" class="form-control" name="num_invitados" min="1" max="400" required>
                            </div>
                        </div>

                    </div>
                    <hr>


                        <?php $index = 0; ?>
                        <?php foreach ($categorias_servicios as $categoria => $servicios): ?>
                            
                            <h5 class="mt-4">
                                <span class="category-arrows">
                                    <div class="title-category">
                                        <h3 ><?= $categoria ?></h3>
                                    </div>
                                    <div class="button-category">
                                        <button type="button" class="arrow-left" onclick="moveLeft(<?= array_search($categoria, $categorias_array) ?>)">&lt; </button>
                                        <button type="button" class="arrow-right" onclick="moveRight(<?= array_search($categoria, $categorias_array) ?>)">&gt;</button>
                                    </div>
                                    
                                    
                                </span>
                            </h5>
                            <div id="category-<?= array_search($categoria, $categorias_array) ?>" class="category-container">
                            <?php foreach ($servicios as $servicio): ?>
                                <?php 
                                // Verifica si el servicio está inactivo
                                $esInactivo = $servicio['estado'] == 'inactivo'; 
                                ?>
                                <div class="col-md-4 service-box <?= $esInactivo ? 'inactivo' : '' ?>" id="service-<?= $servicio['codigo'] ?>"
                                    data-categoria="<?= $servicio['categoria'] ?>" data-posicion="<?= $index ?>">
                                    <?php if (!empty($servicio['rutaimg'])): ?>
                                        <img src="../admin/<?= $servicio['rutaimg'] ?>" alt="<?= $servicio['nombre'] ?>">
                                    <?php endif; ?>
                                    <h5 class="service-title"><?= $servicio['nombre'] ?></h5>
                                    
                                    <!-- Si el servicio está inactivo, muestra "No disponible" y no permite seleccionarlo -->
                                    <div class="service-details" id="details-<?= $servicio['codigo'] ?>" <?= $esInactivo ? 'style="display:block;"' : '' ?>>
                                        <?php if ($esInactivo): ?>
                                            <p class="text-muted">Servicio no disponible</p>
                                        <?php else: ?>
                                            <label for="cantidad-<?= $servicio['codigo'] ?>">Cantidad:</label>
                                            <input type="number" class="form-control" id="cantidad-<?= $servicio['codigo'] ?>"
                                                name="cantidad[<?= $servicio['codigo'] ?>]" min="1">
                                            <label for="observaciones-<?= $servicio['codigo'] ?>">Observaciones:</label>
                                            <input type="text" class="form-control" id="observaciones-<?= $servicio['codigo'] ?>"
                                                name="observaciones[<?= $servicio['codigo'] ?>]">
                                            <p>Valor: $<span id="valor-<?= $servicio['codigo'] ?>"><?= $servicio['valor'] ?></span></p>
                                            <button class="btn-seleccion" type="button"
                                                onclick="selectService(<?= $servicio['codigo'] ?>, <?= $servicio['categoria'] ?>)">Seleccionar
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php $index++; ?>
                            <?php endforeach; ?>

                            </div>
                        <?php endforeach; ?>
                    </div>
                    </div>

                </div>
                
                <div class="col-md-5" id="crud-container" style="display: none;">
                    <h2 class="align-items-center text-center">Servicios seleccionados</h2>
                    <div >
                        <table class="crud-table">
                            <thead>
                                <tr>
                                    <th>Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Valor</th>
                                    <th>Observaciones</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="crud-table-body"></tbody>
                        </table>
                        <div class="text-center">
                            <h3>Total: $<span id="total-suma">0.00</span></h3>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn-seleccion">Guardar</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../js/servicios.js"></script>

</body>

</html>