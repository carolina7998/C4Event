<?php
include '../../conexion.php';

$query = "SELECT 
            e.codigo AS evento_id, 
            CONCAT(u.nombres, ' ', u.apellidos) AS cliente, 
            e.fechaevento, 
            te.nombre AS tipo_evento, 
            e.valor_total,  
            e.estado
          FROM tbleventos e 
          INNER JOIN tblusuarios u ON e.usuarios = u.docid 
          INNER JOIN tbltipoevento te ON e.tipoevento = te.codigo";

$resultado = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/cotizaciones_admin.css">
    <title>Usuarios | C4Event</title>
</head>
<body>
    <?php include "../includes/nav_admin.php";?>
    <div class="container">
        <br><br>
        <div class="table-responsive table-bordered">
            <table id="datos_servicios" class="table table-bordered table-striped">
                <thead>
                    <tr class="table table-dark table-bordered table-striped">
                        <th scope="col">ID Evento</th>
                        <th scope="col">Cliente</th>
                        <th scope="col">Fecha Evento</th>
                        <th scope="col">Tipo Evento</th>
                        <th scope="col">Total</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
            while ($evento = $resultado->fetch_object()) {
                switch ($evento->estado) {
                    case 1:
                        $estado_texto = 'Pendiente';
                        $estado_clase = 'estado-pendiente';
                        break;
                    case 2:
                        $estado_texto = 'Aprobado';
                        $estado_clase = 'estado-aprobado';
                        break;
                    case 3:
                        $estado_texto = 'Pagada';
                        $estado_clase = 'estado-pagada';
                        break;
                    case 4:
                        $estado_texto = 'Rechazada';
                        $estado_clase = 'estado-rechazada';
                        break;
                    default:
                        $estado_texto = 'Desconocido';
                        $estado_clase = 'estado-desconocido';
                        break;
                }
            ?>
            <tr>
                <td><?= $evento->evento_id ?></td>
                <td><?= $evento->cliente ?></td>
                <td><?= $evento->fechaevento ?></td>
                <td><?= $evento->tipo_evento ?></td>
                <td><?= number_format($evento->valor_total, 0) ?></td>
                <td>
                    <button class="btn <?= $estado_clase ?> estado-boton" data-evento-id="<?= $evento->evento_id ?>" data-estado="<?= $evento->estado ?>">
                        <?= $estado_texto ?>
                    </button>
                </td>
                <td>
                    <!-- Botón para ver cotización -->
                    <button type="button" class="btn btn-small  ver-cotizacion-boton" data-evento-id="<?= htmlspecialchars($evento->evento_id) ?>" data-bs-toggle="modal" data-bs-target="#verCotizacionModal">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    
                    <button type="button" class="btn btn-small editar-boton" onclick="window.location.href='editar_cotizacion.php?evento_id=<?= $evento->evento_id ?>'">
                        <i class="bi bi-pencil-square"></i>
                    </button>

                    <button type="button" class="btn-pago ver-comprobantepago-boton" data-evento-id="<?= $evento->evento_id ?>" data-bs-toggle="modal" data-bs-target="#modalComprobante">
                        Pago
                    </button>
                </td>
            </tr>
            <?php } ?>

                </tbody>
            </table>
        </div>
    </div>



    <!-- Modal para mostrar el PDF de la cotización -->
    <div class="modal fade" id="verCotizacionModal" tabindex="-1" aria-labelledby="verCotizacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="width: 850px; margin-left:9rem">
                <div class="modal-header">
                    <h5 class="modal-title" id="verCotizacionLabel"><img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Cotización del Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="iframeCotizacion" src="" frameborder="0" width="100%" height="500px"></iframe>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para el comprobante -->
    <div class="modal fade" id="modalComprobante" tabindex="-1" aria-labelledby="modalComprobanteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalComprobanteLabel"><img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Comprobante de Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="comprobanteFrame" src="" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Datatable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script src="../js/datatables.js"></script>

    <script>
           
        $(document).ready(function() {
            // Inicializar DataTable
            $('#datos_servicios').DataTable();

            // Manejar clic en el botón de comprobante de pago
            $('#datos_servicios').on('click', '.ver-comprobantepago-boton', function() {
                var eventoId = $(this).data('evento-id');
                var comprobanteUrl = 'ver_comprobante.php?evento_id=' + eventoId;

                // Validar URL antes de asignar
                if (comprobanteUrl) {
                    $('#comprobanteFrame').attr('src', comprobanteUrl);
                    $('#modalComprobante').modal('show');
                } else {
                    alert('URL del comprobante no válida.');
                }
            });

            // Manejar clic en el botón de estado
            $('#datos_servicios').on('click', '.estado-boton', function() {
                var boton = $(this);
                var eventoId = boton.data('evento-id');
                var estadoActual = boton.data('estado');
                var nuevoEstado;

                // Cambiar al siguiente estado (Pendiente -> Aprobado -> Pagada -> Rechazada)
                switch (estadoActual) {
                    case 1:
                        nuevoEstado = 2;  // De Pendiente a Aprobado
                        break;
                    case 2:
                        nuevoEstado = 3;  // De Aprobado a Pagada
                        break;
                    case 3:
                        nuevoEstado = 4;  // De Pagada a Rechazada
                        break;
                    case 4:
                        nuevoEstado = 1;  // De Rechazada a Pendiente (ciclo)
                        break;
                    default:
                        alert('Estado desconocido.');
                        return;
                }

                // Enviar la solicitud AJAX para cambiar el estado
                $.ajax({
                    url: 'cambiar_estado_cotizacion.php',
                    type: 'POST',
                    data: {
                        evento_id: eventoId,
                        estado_nuevo: nuevoEstado
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            if (result.success) {
                                // Actualizar el botón con el nuevo estado
                                var estadoTexto, estadoClase;

                                switch (nuevoEstado) {
                                    case 1:
                                        estadoTexto = 'Pendiente';
                                        estadoClase = 'estado-pendiente';
                                        break;
                                    case 2:
                                        estadoTexto = 'Aprobado';
                                        estadoClase = 'estado-aprobado';
                                        break;
                                    case 3:
                                        estadoTexto = 'Pagada';
                                        estadoClase = 'estado-pagada';
                                        break;
                                    case 4:
                                        estadoTexto = 'Rechazada';
                                        estadoClase = 'estado-rechazada';
                                        break;
                                }

                                // Actualizar el botón con el nuevo estado y color
                                boton.text(estadoTexto);
                                boton.removeClass('estado-pendiente estado-aprobado estado-pagada estado-rechazada')
                                    .addClass(estadoClase);
                                boton.data('estado', nuevoEstado);
                            } else {
                                alert('Error al cambiar el estado: ' + result.error);
                            }
                        } catch (e) {
                            alert('Error al procesar la respuesta: ' + e.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error en la solicitud AJAX: ' + error);
                    }
                });
            });
        });




        $(document).ready(function() {

            // Funcionalidad para cargar el PDF en el modal
            $(document).on('click', '.ver-cotizacion-boton', function() {
                var eventoId = $(this).data('evento-id');
                var pdfUrl = 'cotizacion_pdf.php?evento_id=' + eventoId; // URL al archivo PHP que genera el PDF
                $('#iframeCotizacion').attr('src', pdfUrl);
                $('#verCotizacionModal').modal('show');
            });
        });

    </script>
    
</body>
</html>
