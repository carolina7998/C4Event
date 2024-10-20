<?php

include '../../conexion.php';

// Obtener servicios por categoría
$categorias_servicios = [];
$result_categorias = $conn->query("SELECT * FROM tblcategoria");

while ($row = $result_categorias->fetch_assoc()) {
    $categoria_id = $row['codigo'];
    $result_servicios = $conn->query("SELECT * FROM tblservicios WHERE categoria = $categoria_id");
    $servicios = [];
    while ($servicio = $result_servicios->fetch_assoc()) {
        $servicios[] = $servicio;
    }
    $categorias_servicios[$row['nombre']] = $servicios;
}

foreach ($categorias_servicios as $categoria => $servicios) {
    echo '<h3>' . htmlspecialchars($categoria) . '</h3>';
    foreach ($servicios as $servicio) {
        echo '<div class="service-box" id="service-' . htmlspecialchars($servicio['codigo']) . '">';
        if (!empty($servicio['rutaimg'])) {
            echo '<img src="' . htmlspecialchars($servicio['rutaimg']) . '" alt="' . htmlspecialchars($servicio['nombre']) . '">';
        }
        echo '<h4>' . htmlspecialchars($servicio['nombre']) . '</h4>';
        echo '<div class="service-details" id="details-' . htmlspecialchars($servicio['codigo']) . '">';
        echo '<label for="cantidad-' . htmlspecialchars($servicio['codigo']) . '">Cantidad:</label>';
        echo '<input type="number" class="form-control" id="cantidad-' . htmlspecialchars($servicio['codigo']) . '" name="cantidad[' . htmlspecialchars($servicio['codigo']) . ']" min="1" max="100">';
        echo '<label for="observaciones-' . htmlspecialchars($servicio['codigo']) . '">Observaciones:</label>';
        echo '<input type="text" class="form-control" id="observaciones-' . htmlspecialchars($servicio['codigo']) . '" name="observaciones[' . htmlspecialchars($servicio['codigo']) . ']">';
        echo '<button type="button" onclick="selectService(' . htmlspecialchars($servicio['codigo']) . ')">Seleccionar</button>';
        echo '<span style="display: none;" id="valor-' . htmlspecialchars($servicio['codigo']) . '">' . htmlspecialchars($servicio['valor']) . '</span>';
        echo '</div></div>';
    }
}
?>
