<?php

include '../../conexion.php';

// Consulta para obtener todos los usuarios registrados en la interfaz administrador
$query = "SELECT u.docid, u.nombres, u.apellidos, u.email, u.telefono, u.estado, r.nombre as rol 
          FROM tblusuarios u INNER JOIN tblroles r ON u.roles = r.codigo";


?>

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
    <link rel="stylesheet" href="../css/index_admin.css">
    <title>Usuarios | C4Event</title>
</head>
<body>

    <?php include "../includes/nav_admin.php"; ?>

    <div class="container my-5">
        <br>
        <br>

        <div class="table-responsive table-bordered ">
            <table id="datos_servicios" class="table table-bordered table-striped">
                <thead>
                    <tr class="table table-dark table-bordered table-striped">
                        <th scope="col">Doc identidad</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    include "../../conexion.php";
                    $usuarios = $conn->query("SELECT u.docid, concat(u.nombres,' ', u.apellidos) as usuario, u.email, u.telefono, u.estado, r.nombre as rol 
                                              FROM tblusuarios u INNER JOIN tblroles r ON u.roles = r.codigo");

                    while ($usu = $usuarios->fetch_object()): ?>
                        <tr>
                            <td><?= $usu->docid ?></td>
                            <td><?= $usu->usuario ?></td>
                            <td><?= $usu->email ?></td>
                            <td><?= $usu->telefono ?></td>
                            <td><?= $usu->rol ?></td>
                            <td>
                                <button id="estadoBtn_<?= $usu->docid ?>"
                                        class="btn <?= $usu->estado == 'activo' ? 'btn-success' : 'btn-danger' ?>"
                                        onclick="toggleEstado('<?= $usu->docid ?>', '<?= $usu->estado ?>')">
                                    <?= $usu->estado == 'activo' ? 'Activo' : 'Inactivo' ?>
                                </button>


                                <script>
                                    function toggleEstado(docid, estadoActual) {
                                        var nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';
                                        var formData = new FormData();
                                        formData.append('docid', docid);
                                        formData.append('estado', nuevoEstado);

                                        fetch('cambiar_estado_usuario.php', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.text())
                                        .then(data => {
                                            if (data.trim() === 'success') { // Asegúrate de que la respuesta del servidor es exactamente 'success'
                                                var button = document.getElementById('estadoBtn_' + docid);
                                                if (nuevoEstado === 'activo') {
                                                    button.textContent = 'Activo';
                                                    button.classList.remove('btn-danger');
                                                    button.classList.add('btn-success');
                                                } else {
                                                    button.textContent = 'Inactivo';
                                                    button.classList.remove('btn-success');
                                                    button.classList.add('btn-danger');
                                                }
                                            } else {
                                                alert('Error al cambiar el estado del usuario');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Error al cambiar el estado del usuario');
                                        });
                                    }
                                </script>


                                
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <button class="edit-button" data-docid="<?= $usu->docid ?>" data-rol="<?= $usu->rol ?>">
                                            <svg class="edit-svgIcon" viewBox="0 0 512 512">
                                                <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                            </svg>
                                        </button>
                        
                                        <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="modalEditUser"><img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Editar Usuario</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                        
                                                    <form action="editar_usuarios.php" method="POST">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="docid" id="edit_docid" value="<?= $usu->docid ?>">
                                                            <label for="edit_rol">Rol:</label>
                                                            <select id="edit_rol" name="rol" class="form-control">
                                                                <?php
                                                                $result_roles = $conn->query("SELECT * FROM tblroles");
                                                                while ($row = $result_roles->fetch_assoc()): ?>
                                                                    <option value="<?= $row['codigo'] ?>" <?= $row['codigo'] == $usu->rol ? 'selected' : '' ?>>
                                                                        <?= $row['nombre'] ?>
                                                                    </option>
                                                                <?php endwhile; ?>
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="submit" name="editar_usuario" class="btn btn-success" value="Guardar">
                                                        </div>
                                                    </form>
                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                    <script>
                    function toggleEstado(docid, estadoActual) {
                        var nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';
                        var formData = new FormData();
                        formData.append('docid', docid);
                        formData.append('estado', nuevoEstado);

                        fetch('cambiar_estado_usuario.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data.trim() === 'success') { 
                                var button = document.getElementById('estadoBtn_' + docid);
                                if (nuevoEstado === 'activo') {
                                    button.textContent = 'Activo';
                                    button.classList.remove('btn-danger');
                                    button.classList.add('btn-success');
                                } else {
                                    button.textContent = 'Inactivo';
                                    button.classList.remove('btn-success');
                                    button.classList.add('btn-danger');
                                }
                            } else {
                                alert('Error al cambiar el estado del usuario');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error al cambiar el estado del usuario');
                        });
                    }
                </script>


                </tbody>
            </table>
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
    <script src="../js/editar_usuarios.js"></script>
    <script src="../js/permiso_usuario.js"></script>

    <script>
        $(document).ready(function() {
            // Verificar si la tabla ya ha sido inicializada
            if (!$.fn.DataTable.isDataTable('#datos_servicios')) {
                $('#datos_servicios').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            }

            // Delegar eventos para los botones de estado
            $('#datos_servicios').on('click', '.btn', function() {
                var docid = $(this).attr('id').split('_')[1];
                var estadoActual = $(this).data('estado');
                toggleEstado(docid, estadoActual);
            });

            // Delegar eventos para el botón de editar
            $('#datos_servicios').on('click', '.edit-button', function() {
                var docid = $(this).data('docid');
                var rol = $(this).data('rol');

                // Configurar el modal con la información del usuario
                $('#edit_docid').val(docid);
                $('#edit_rol').val(rol);

                // Mostrar el modal
                $('#modalEditUser').modal('show');
            });
        });

        function toggleEstado(docid, estadoActual) {
            var nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';
            var formData = new FormData();
            formData.append('docid', docid);
            formData.append('estado', nuevoEstado);

            fetch('cambiar_estado_usuario.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') { // Asegúrate de que la respuesta del servidor es exactamente 'success'
                    var button = $('#estadoBtn_' + docid);
                    if (nuevoEstado === 'activo') {
                        button.text('Activo');
                        button.removeClass('btn-danger').addClass('btn-success');
                        button.data('estado', 'activo'); // Actualizar el estado en el botón
                    } else {
                        button.text('Inactivo');
                        button.removeClass('btn-success').addClass('btn-danger');
                        button.data('estado', 'inactivo'); // Actualizar el estado en el botón
                    }
                } else {
                    alert('Error al cambiar el estado del usuario');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al cambiar el estado del usuario');
            });
        }


    </script>
    
</body>
</html>

