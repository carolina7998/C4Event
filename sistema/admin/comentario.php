<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <!--Datatable-->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <!--Boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!--Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--Enlace iconos de boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/comentario_admin.css">
    
    <title>Calificacion Servicios | C4Event</title>
</head>
<body>

    <?php include '../../conexion.php'; ?>
    <?php include "../includes/nav_admin.php"; ?>

    <div class="container my-5">
        <div class="table-responsive table-bordered">
            <table id="datos_servicios" class="table table-bordered table-striped">
                <thead>
                    <tr class="table table-dark table-bordered table-striped">
                        <th>Servicio</th>
                        <th>Usuario</th>
                        <th>Comentario</th>
                        <th>Calificación</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $sql = "SELECT c.comentario, c.calificacion, concat(u.nombres,' ', u.apellidos) as usuario, c.fecha, s.nombre AS servicio, c.estado AS estado_calificacion, c.codigo AS calificacion_id
                            FROM tblcalificacionservicios c
                            JOIN tblservicios s ON c.servicio = s.codigo
                            JOIN tblusuarios u ON c.usuario = u.docid
                            ORDER BY c.fecha DESC";

                    $result = $conn->query($sql);

                    while ($calificacion = $result->fetch_object()): ?>
                        <tr>
                            <td><?= htmlspecialchars($calificacion->servicio) ?></td>
                            <td><?= htmlspecialchars($calificacion->usuario) ?></td>
                            <td><?= htmlspecialchars($calificacion->comentario) ?></td>
                            <td><?= htmlspecialchars($calificacion->calificacion) ?></td>
                            <td><?= htmlspecialchars($calificacion->fecha) ?></td>
                            <td>
                                <button id="estadoBtn_<?= $calificacion->calificacion_id ?>"
                                        class="btn <?= $calificacion->estado_calificacion == 'activo' ? 'btn-success' : 'btn-danger' ?>"
                                        onclick="toggleEstado('<?= $calificacion->calificacion_id ?>', '<?= $calificacion->estado_calificacion ?>')">
                                    <?= $calificacion->estado_calificacion == 'activo' ? 'Activo' : 'Inactivo' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            $('#datos_servicios').DataTable();
        });

        function toggleEstado(codigo, estadoActual) {
            var nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';
            var formData = new FormData();
            formData.append('codigo', codigo);
            formData.append('estado', nuevoEstado);

            fetch('cambiar_estado_calificacion.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') { 
                    var button = document.getElementById('estadoBtn_' + codigo);
                    if (nuevoEstado === 'activo') {
                        button.textContent = 'Activo';
                        button.classList.remove('btn-danger');
                        button.classList.add('btn-success');
                    } else {
                        button.textContent = 'Inactivo';
                        button.classList.remove('btn-success');
                        button.classList.add('btn-danger');
                    }
                    // Actualiza el valor de `estadoActual` para reflejar el nuevo estado
                    button.setAttribute('onclick', `toggleEstado('${codigo}', '${nuevoEstado}')`);
                } else {
                    alert('Error al cambiar el estado de la calificación');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar el estado de la calificación');
            });
        }

    </script>
    
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
</body>
</html>

