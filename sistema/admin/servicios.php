<?php
include '../../conexion.php';

// Agregar categoría
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_category'])) {
    $nombre_categoria = $conn->real_escape_string($_POST['nombre_categoria']);
    $conn->query("INSERT INTO tblcategoria (nombre) VALUES ('$nombre_categoria')");
}

// Agregar servicio
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    if (isset($_POST['nombre_servicio'], $_POST['valor_servicio'], $_POST['categoria_servicio'], $_FILES['imagen_servicio'])) {
        $nombre_servicio = $conn->real_escape_string($_POST['nombre_servicio']);
        $valor_servicio = $conn->real_escape_string($_POST['valor_servicio']);
        $categoria_servicio = $conn->real_escape_string($_POST['categoria_servicio']);
        $ruta = $conn->real_escape_string("fotos/".$_FILES['imagen_servicio']['name']);
        $nimg = $_FILES['imagen_servicio']['tmp_name'];
        move_uploaded_file($nimg, $ruta);

        $conn->query("INSERT INTO tblservicios (nombre, valor, categoria, rutaimg) VALUES ('$nombre_servicio', '$valor_servicio', '$categoria_servicio', '$ruta')");
    } else {
        echo "Por favor complete todos los campos del formulario.";
    }
}

// Obtener categorías
$result_categorias = $conn->query("SELECT * FROM tblcategoria");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
   
    <!-- Datatable -->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/servicios_admin.css">
    <title>Servicios | C4Event</title>

</head>
<body>
    <?php include "../includes/nav_admin.php"; ?>
    <div class="container my-5">
       <!--<h1 class="text-center">Servicios</h1>-->
        <div class="row">
            <div class="col-2 offset-10">
                <div class="text-center">
                    <button type="button" class="button"  data-bs-toggle="modal" data-bs-target="#modaladmin" id="botoncrear">
                    <span class="button__text">Servicio</span>
                    <span class="button__icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24" fill="none" class="svg"><line y2="19" y1="5" x2="12" x1="12"></line><line y2="12" y1="12" x2="19" x1="5"></line></svg></span>
                    </button>
                </div>
            </div>
        </div>
        <br><br>
        <div class="table-responsive table-bordered">
            <table id="datos_servicios" class="table table-bordered table-striped">
                <thead>
                    <tr class="table  table-dark table-bordered table-striped">
                        <!--<th scope="col">Codigo</th>-->
                        <th scope="col">Servicio</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servicios = $conn->query("SELECT s.codigo, s.nombre, s.valor,s.estado, c.nombre AS categoria, s.rutaimg 
                                                FROM tblservicios s INNER JOIN tblcategoria c ON s.categoria = c.codigo");

                    while ($datos = $servicios->fetch_object()) { ?>
                    <tr>
                        <!--<td>?= $datos->codigo ?></td>-->
                        <td><?= $datos->nombre ?></td>
                        <td><?= $datos->valor ?></td>
                        <td><?= $datos->categoria ?></td>
                        <td>
                            <?php if (!empty($datos->rutaimg)) { ?>
                                <img src="<?= $datos->rutaimg ?>" alt="Imagen del servicio" width="100">
                            <?php } ?>
                        </td>

                        <td>
                        
                        <button class="btn <?= $datos->estado == 'activo' ? 'btn-success' : 'btn-danger' ?>" 
                                onclick="toggleEstado('<?= $datos->codigo ?>', '<?= $datos->estado ?>')">
                            <?= $datos->estado == 'activo' ? 'Activo' : 'Inactivo' ?>
                        </button>
                    

                            <script>
                                function toggleEstado(codigo, estadoActual) {
                                    var nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';
                                    var formData = new FormData();
                                    formData.append('codigo', codigo);
                                    formData.append('estado', nuevoEstado);

                                    fetch('cambiar_estado_servicio.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if(data.status === 'success') {
                                            location.reload();
                                        } else {
                                            alert('Error al cambiar el estado del servicio: ' + data.message);
                                        }
                                    })
                                    .catch(error => console.error('Error:', error));
                                }
                            </script>
                        </td>


                        <td>
                            <div class="row">
                                <div class="col">
                                    <button class="edit-button"
                                        data-codigo="<?= $datos->codigo; ?>" 
                                        data-nombre="<?= $datos->nombre; ?>" 
                                        data-valor="<?= $datos->valor; ?>" 
                                        data-categoria="<?= $datos->categoria; ?>" 
                                        data-imagen="<?= $datos->rutaimg; ?>">
                                        <svg class="edit-svgIcon" viewBox="0 0 512 512">
                                            <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                        </svg>
                                    </button>

                                    <div class="modal fade" id="modalEditService" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel"><img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Editar servicio</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="editar_servicios.php" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <label for="edit_nombre_servicio">Nombre del Servicio:</label>
                                                        <input type="text" id="edit_nombre_servicio" name="nombre_servicio" required class="form-control">
                                                        <br><br>
                                                        <label for="edit_categoria_servicio">Categoría del Servicio:</label>
                                                        <select id="edit_categoria_servicio" name="categoria_servicio" required class="form-control">
                                                            <?php
                                                            $result_categorias_edit = $conn->query("SELECT * FROM tblcategoria");
                                                            while ($row = $result_categorias_edit->fetch_assoc()): ?>
                                                                <option value="<?= $row['codigo'] ?>"><?= $row['nombre'] ?></option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                        <br><br>
                                                        <span id="imagen-subida"></span>
                                                        <br>
                                                        <label for="edit_imagen_servicio">Seleccione imagen:</label>
                                                        <input type="file" id="edit_imagen_servicio" name="imagen_servicio" accept="image/*" class="form-control">
                                                        <br><br>
                                                        <label for="edit_valor_servicio">Valor del servicio:</label>
                                                        <input type="number" id="edit_valor_servicio" name="valor_servicio" required class="form-control">
                                                        <input type="hidden" name="codigo_servicio" id="edit_codigo_servicio">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="submit" name="edit_service" class="btn btn-success" value="Guardar">
                                                    </div>
                                                </form>
                                            </div>
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

    <!-- Modal Crear nuevo servicio-->
    <div class="modal fade" id="modaladmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><img src="../img/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">Crear servicio</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-body">
                            <label for="nombre_servicio">Nombre del Servicio:</label>
                            <input type="text" id="nombre_servicio" name="nombre_servicio" required class="form-control">
                            <br><br>
                            <label for="categoria_servicio">Categoría del Servicio:</label>
                            <select id="categoria_servicio" name="categoria_servicio" required class="form-control">
                                <?php
                                while ($row = $result_categorias->fetch_assoc()): ?>
                                    <option value="<?= $row['codigo'] ?>"><?= $row['nombre'] ?></option>
                                <?php endwhile; ?>
                            </select>
                            <br><br>
                            <label for="imagen_servicio">Seleccione imagen:</label>
                            <input type="file" id="imagen_servicio" name="imagen_servicio" accept="image/*" required class="form-control">
                            <br><br>
                            <label for="valor_servicio">Valor del servicio:</label>
                            <input type="number" id="valor_servicio" name="valor_servicio" required class="form-control">
                            <span id="imagen-subida"></span>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id_servicio" id="id_servicio">
                            <input type="hidden" name="operacion" id="operacion">
                            <input type="submit" name="add_service" id="action" class="btn btn-success" value="Crear">
                        </div>
                    </div>
                </form>
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
    <script src="../js/editar_servicios.js"></script>
    
    
    <script>
        $(document).ready(function() {
            // Crear una instancia de DataTable
            var table = $('#datos_servicios').DataTable();

            // Delegar el evento de clic en los botones de cambio de estado dentro del DataTable
            $('#datos_servicios').on('click', '.btn-success, .btn-danger', function() {
                var button = $(this);
                var codigo = button.data('codigo'); // Obtén el código del servicio desde un atributo de datos
                var estadoActual = button.hasClass('btn-success') ? 'activo' : 'inactivo';
                var nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';

                var formData = new FormData();
                formData.append('codigo', codigo);
                formData.append('estado', nuevoEstado);

                // Obtener la página actual del DataTable
                var paginaActual = table.page.info().page;

                fetch('cambiar_estado_servicio.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Actualizar el botón y su estado
                        button.toggleClass('btn-success btn-danger').text(nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1));

                        // Volver a la página actual del DataTable
                        table.page(paginaActual).draw(false);
                    } else {
                        alert('Error al cambiar el estado del servicio: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Delegar el evento de clic en los botones de edición
            $('#datos_servicios').on('click', '.edit-button', function() {
                var codigo = $(this).data('codigo');
                var nombre = $(this).data('nombre');
                var valor = $(this).data('valor');
                var categoria = $(this).data('categoria');
                var imagen = $(this).data('imagen');

                $('#edit_codigo_servicio').val(codigo);
                $('#edit_nombre_servicio').val(nombre);
                $('#edit_valor_servicio').val(valor);
                $('#edit_categoria_servicio').val(categoria);

                if (imagen) {
                    $('#imagen-subida').html('<img src="' + imagen + '" alt="Imagen del servicio" width="100">');
                } else {
                    $('#imagen-subida').html('');
                }

                $('#modalEditService').modal('show');
            });

            // Inicializar DataTable
            $('#datos_servicios').DataTable();
        });

</script>

</body>
</html>

