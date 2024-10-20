<?php
include '../../conexion.php';

// Verificar si se ha pasado el ID del evento como parámetro
if (isset($_GET['evento_id'])) {
    $evento_id = $_GET['evento_id'];
    
    // Consulta para obtener la información del evento seleccionado
    $query = "SELECT e.codigo as evento_id, u.nombres, u.apellidos, u.docid, u.email, e.fechaevento, te.nombre as tipo_evento, e.estado, e.valor_iva
              FROM tbleventos e 
              INNER JOIN tblusuarios u ON e.usuarios = u.docid
              INNER JOIN tbltipoevento te ON e.tipoevento = te.codigo
              WHERE e.codigo = $evento_id";

    $resultado = $conn->query($query);

    if ($resultado->num_rows > 0) {
        $evento = $resultado->fetch_assoc();
    } else {
        echo "Evento no encontrado.";
        exit;
    }

    // Consulta para obtener los servicios del evento
    $queryServicios = "SELECT s.nombre as nombre_servicio, s.codigo, se.observaciones, se.cantidad, se.total_servicio, se.valor_adicional
                       FROM tblservicioseventos se 
                       INNER JOIN tblservicios s ON se.codigoservicios = s.codigo
                       WHERE se.codigoeventos = $evento_id";

    $resultadoServicios = $conn->query($queryServicios);
} else {
    echo "ID del evento no especificado.";
    exit;
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Boostrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilosAdmin.css">
    <link rel="stylesheet" href="../css/editar_cotizacion_admin.css">
    <title>Editar Cotización | C4Event</title>
    

    <script>
        function calculateSubtotal(row) {
            var precio = parseFloat(row.querySelector('.precio').innerText);
            var valorAdicional = parseFloat(row.querySelector('.valor-adicional').value) || 0;
            var subtotal = precio + valorAdicional;
            row.querySelector('.subtotal').innerText = subtotal.toFixed(2);
            calculateTotal();
        }

        function calculateTotal() {
            var subtotales = document.querySelectorAll('.subtotal');
            var total = 0;
            subtotales.forEach(function(subtotal) {
                total += parseFloat(subtotal.innerText);
            });

            var iva = total * 0.19;
            document.getElementById('subtotal').innerText = total.toFixed(2);
            document.getElementById('iva').innerText = iva.toFixed(2);
            document.getElementById('total').innerText = (total + iva).toFixed(2);
        }
    </script>

    
    
</head>
<body>

<?php include "../includes/nav_admin.php"; ?>

<div class="container">
<h2>Datos del Cliente</h2>
    <div class="form-row">
        <div class="form-group">
            <label for="docid">Cédula de ciudadanía</label>
            <input type="text" id="docid" value="<?= htmlspecialchars($evento['docid']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" value="<?= htmlspecialchars($evento['nombres'] . ' ' . $evento['apellidos']); ?>" readonly>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group">
            <label for="tipo_evento">Tipo de evento</label>
            <input type="text" id="tipo_evento" value="<?= htmlspecialchars($evento['tipo_evento']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="fechaevento">Fecha del evento</label>
            <input type="text" id="fechaevento" value="<?= htmlspecialchars($evento['fechaevento']); ?>" readonly>
        </div>
    </div>
</div>

<div class="container">
    <h2>Servicios seleccionados</h2>
    <form action="procesar_cotizacion.php" method="POST">
        <input type="hidden" name="evento_id" value="<?= htmlspecialchars($evento_id); ?>">
        <div class="table-container">
            <div class="table-responsive">
                <table>
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Observación</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Valor adicional</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($servicio = $resultadoServicios->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($servicio['nombre_servicio']); ?></td>
                        <td><?= htmlspecialchars($servicio['observaciones']); ?></td>
                        <td><?= htmlspecialchars($servicio['cantidad']); ?></td>
                        <td class="precio"><?= htmlspecialchars($servicio['total_servicio']); ?></td>
                        <td><input type="number" name="servicios[<?= htmlspecialchars($servicio['codigo']); ?>]" class="valor-adicional" value="<?= htmlspecialchars($servicio['valor_adicional']); ?>" oninput="calculateSubtotal(this.parentNode.parentNode)"></td>
                        <td class="subtotal"><?= htmlspecialchars($servicio['total_servicio'] + $servicio['valor_adicional']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            
        </div>
        <div class="total-container">
            Subtotal &nbsp;&nbsp;&nbsp;  $<span id="subtotal"></span><br>
            IVA (19%) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  $<span id="iva"></span><br>
            Total  &nbsp;&nbsp;&nbsp;&nbsp; $<span id="total"></span>
        </div>
        
        <div class="action-buttons">
            <button type="submit" name="accion" value="guardar" class="btn-process">Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
    // Inicializar el cálculo total al cargar la página
    calculateTotal();
</script>

<!-- Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>





