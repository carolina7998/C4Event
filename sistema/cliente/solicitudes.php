<?php

include '../../conexion.php';


session_start();
if (!isset($_SESSION['docid'])) {
    // Redirige a la página de inicio de sesión si el usuario no está autenticado
    header("Location: login.php");
    exit();
}
$usuario_id = $_SESSION['docid'];

$query = "SELECT e.codigo as evento_id, concat(u.nombres ,' ', u.apellidos) as cliente, e.numinvitados, e.fechaevento, te.nombre as tipo_evento, e.estado
          FROM tbleventos e 
          INNER JOIN tblusuarios u ON e.usuarios = u.docid 
          INNER JOIN tbltipoevento te ON e.tipoevento = te.codigo
          WHERE e.usuarios = ?"; // Filtrar por el usuario actual

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/solicitudes_cliente.css">
    <title>Solicitudes | C4Event</title>
    
</head>
<body>
    <?php include "../includes/nav_cliente.php";?>
    <div class="container">
        <br><br>
        <div class="table-responsive table-bordered">
            <table id="datos_servicios" class="table table-bordered table-striped">
                <thead>
                    <tr class="table table-dark table-bordered table-striped">
                        <th scope="col">Tipo Evento</th>
                        <th scope="col">Fecha Evento</th>
                        <th scope="col">Cant. Invitados</th>
                        <th scope="col">Total</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($evento = $resultado->fetch_object()) {
                        $event_id = $evento->evento_id;
                        $sql_sum_total = "SELECT SUM(total_servicio) AS total_evento FROM tblservicioseventos WHERE codigoeventos = ?";
                        $stmt_totales = $conn->prepare($sql_sum_total);
                        $stmt_totales->bind_param('i', $event_id);
                        $stmt_totales->execute();
                        $resultado_totales = $stmt_totales->get_result();
                        $total_servicio = $resultado_totales->fetch_object()->total_evento;

                        switch ($evento->estado) {
                            case 1:
                                $estado_texto = 'Pendiente';
                                $estado_clase = 'btn-warning';
                                break;
                            case 2:
                                $estado_texto = 'Aprobada';
                                $estado_clase = 'btn-primary';
                                break;
                            case 3:
                                $estado_texto = 'Pagada';
                                $estado_clase = 'btn-success';
                                break;
                            case 4:
                                $estado_texto = 'Rechazada';
                                $estado_clase = 'btn-danger';
                                break;
                            default:
                                $estado_texto = 'Desconocido';
                                $estado_clase = 'btn-secondary';
                                break;
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($evento->tipo_evento) ?></td>
                        <td><?= htmlspecialchars($evento->fechaevento) ?></td>
                        <td><?= htmlspecialchars($evento->numinvitados) ?></td>
                        <td><?= number_format($total_servicio, 0) ?></td>
                        <td><?= htmlspecialchars($estado_texto) ?></td>
                        <td>
                            <!-- Botón para ver cotización -->
                            <button type="button" class="btn btn-small ver-cotizacion-boton" data-evento-id="<?= htmlspecialchars($evento->evento_id) ?>" data-bs-toggle="modal" data-bs-target="#verCotizacionModal">
                                <i class="bi bi-eye-fill"></i>
                            </button>
                            &nbsp; 
                            <!-- Botón para abrir el modal de pago -->
                            <button type="button" class="btn btn-light-blue" data-bs-toggle="modal" data-bs-target="#Modalpagar-<?= htmlspecialchars($evento->evento_id) ?>">
                                <i class="bi bi-credit-card-2-back"> Pagar</i>
                            </button>

                            <!-- Modal para mostrar el QR para pago -->
                            <div class="modal fade" id="Modalpagar-<?= htmlspecialchars($evento->evento_id) ?>" tabindex="-1" aria-labelledby="exampleModalLabel-<?= htmlspecialchars($evento->evento_id) ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel-<?= htmlspecialchars($evento->evento_id) ?>"><img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Realizar pago y subir comprobante</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="../img/qr.png" alt="QR de pago" class="img-fluid mb-3">
                                            <form action="subir_comprobante.php" method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="fileInput">Sube tu comprobante</label>
                                                    <input type="file" class="form-control-file" id="fileInput" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required>
                                                    <input type="hidden" name="evento_id" value="<?= htmlspecialchars($evento->evento_id) ?>">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    <h5 class="modal-title" id="verCotizacionLabel"><img src="../img/logo.png" alt="Logo" style="height: 60px; margin-right: 10px;">Cotización del Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="iframeCotizacion" src="" frameborder="0" width="100%" height="500px"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!--Jquery-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!--Datatable-->
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

    <!--Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../js/datatables.js"></script>
    
    <script>

        $(document).ready(function() {
            $('#datos_servicios').DataTable();  // Inicializa el DataTable
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

