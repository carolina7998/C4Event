<?php

include ('../../conexion.php');
require ('fpdf/fpdf.php'); 

$evento_id = $_GET['evento_id'];

// Consultar detalles del evento
$evento_query = $conn->query("SELECT e.*, t.nombre AS tipoevento_nombre FROM tbleventos e
                                JOIN tbltipoevento t ON e.tipoevento = t.codigo
                                WHERE e.codigo = '$evento_id'");

if ($evento_query->num_rows > 0) {
    $evento = $evento_query->fetch_assoc();
    $usuario_id = $evento['usuarios'];

    // Consultar detalles del cliente
    $cliente_query = $conn->query("SELECT * FROM tblusuarios WHERE docid = '$usuario_id'");
    $cliente = $cliente_query->fetch_assoc();

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // Agregar logo
    $pdf->Image('../img/logo.png', 5, 2, 35, 38, 'PNG');
    $pdf->Ln(30);

    // Información de la empresa
    $pdf->SetFont('Arial', 'B', 24);
    $pdf->Cell(0, 10, utf8_decode('Cotización c4Event'), 0, 1, 'C');
    $pdf->SetFont('Arial', '', 13);
    $pdf->Cell(0, 6, utf8_decode('Teléfono: 320 217 2712'), 0, 1, 'C');
    $pdf->Cell(0, 6, utf8_decode('Correo: info@c4event.com'), 0, 1, 'C');
    $pdf->Ln(10);

    // Información de la factura
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(159, 6, utf8_decode('No. Factura:'), 0, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(10, 6, $evento['codigo'], 0, 1, 'R');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(163, 6, utf8_decode('Fecha evento:'), 0, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(24, 6, date('d/m/Y', strtotime($evento['fechaevento'])), 0, 1, 'R');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(159, 6, utf8_decode('Tipo evento:'), 0, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(27, 6, utf8_decode($evento['tipoevento_nombre']), 0, 1, 'R');

    $pdf->Ln(8); // Espacio entre secciones

    // Cambiar el título de "Cliente" a "Información del Cliente"
    $pdf->SetFont('Arial', 'B', 15);
    $pdf->Cell(0, 10, utf8_decode('Información del Cliente'), 0, 1);

    // Mostrar la información en el orden deseado
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 6, utf8_decode('Cédula:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(80, 6, $cliente['docid'], 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 6, utf8_decode('Nombre:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(80, 6, utf8_decode($cliente['nombres']), 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 6, utf8_decode('Teléfono:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(80, 6, $cliente['telefono'], 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(30, 6, utf8_decode('Correo:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(80, 6, utf8_decode($cliente['email']), 0, 1);

    $pdf->Ln(15); // Espacio entre secciones

    // Encabezados de la tabla de servicios con fondo oscuro y texto blanco
    $pdf->SetFillColor(50, 50, 50); // Fondo gris oscuro
    $pdf->SetTextColor(255, 255, 255); // Texto blanco
    $pdf->SetFont('Arial', 'B', 12);

    // Dibuja los encabezados de la tabla sin bordes internos
    $pdf->Cell(20, 10, utf8_decode('Cant.'), 0, 0, 'C', true);
    $pdf->Cell(53, 10, utf8_decode('Servicio'), 0, 0, 'C', true);
    $pdf->Cell(35, 10, utf8_decode('Valor Unitario'), 0, 0, 'C', true);
    $pdf->Cell(40, 10, utf8_decode('Valor Adicional'), 0, 0, 'C', true);
    $pdf->Cell(40, 10, utf8_decode('Precio Total'), 0, 1, 'C', true);

    // Restaurar colores predeterminados para el texto de las filas
    $pdf->SetTextColor(0, 0, 0); // Texto negro

    // Consultar servicios
    $servicios_query = $conn->query("SELECT s.nombre AS servicio_nombre, s.valor AS valor_unitario, se.cantidad AS cantidad,
                                    se.total_servicio AS total_servicio, se.valor_adicional AS valor_adicional
                                    FROM tblservicioseventos se
                                    JOIN tblservicios s ON se.codigoservicios = s.codigo
                                    WHERE se.codigoeventos = '$evento_id'");

    if ($servicios_query->num_rows > 0) {
        $pdf->SetFont('Arial', '', 12);
        $subtotal = 0;

        while ($servicio = $servicios_query->fetch_assoc()) {
            // Calcular el precio total de cada servicio sumando total_servicio y valor_adicional
            $precio_total = $servicio['total_servicio'] + $servicio['valor_adicional'];
            
            // Altura de celda fija para celdas de cantidad, servicio, valor adicional y precio total
            $cellHeight = 10;

            // Mostrar cada fila de servicio sin bordes laterales, pero con línea inferior
            $pdf->Cell(20, $cellHeight, $servicio['cantidad'], 0, 0, 'C');
            $pdf->Cell(53, $cellHeight, utf8_decode($servicio['servicio_nombre']), 0, 0);
            $pdf->Cell(35, $cellHeight, number_format($servicio['valor_unitario']), 0, 0, 'C');
            $pdf->Cell(40, $cellHeight, number_format($servicio['valor_adicional']), 0, 0, 'C');
            $pdf->Cell(40, $cellHeight, number_format($precio_total), 0, 1, 'C');
            
            // Agregar línea negra completa debajo de cada fila
            $pdf->SetLineWidth(0.10);
            $pdf->SetDrawColor(0, 0, 0); // Color negro
            $currentY = $pdf->GetY();
            // Dibuja la línea negra desde la primera columna hasta la última
            $pdf->Line(11, $currentY, 197, $currentY); // Ajustar el rango de la línea
            $pdf->SetLineWidth(0); // Restaurar el grosor de línea predeterminado
            
            $subtotal += $precio_total;
        }

        // Calcular IVA
        $iva = $subtotal * 0.19;  // 19% de IVA

        // Calcular el Total incluyendo el IVA
        $total = $subtotal + $iva; // Sumar el IVA al subtotal

        // Actualizar el valor del IVA y valor total en la base de datos
        $update_iva_query = $conn->query("UPDATE tbleventos SET valor_iva = '$iva', valor_total = '$total' WHERE codigo = '$evento_id'");

        if ($update_iva_query) {
            // Mostrar Subtotal, IVA, Total
            $pdf->Ln(10); // Espacio antes de los subtotales

            $pdf->SetXY(123, $pdf->GetY()); // Ajustar la posición vertical para el siguiente renglón
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(25, 10, utf8_decode('SUBTOTAL'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 7, number_format($subtotal), 0, 1, 'R');

            $pdf->SetXY(123, $pdf->GetY()); // Ajustar la posición vertical para el siguiente renglón
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(25, 10, utf8_decode('IVA (19%)'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 7, number_format($iva), 0, 1, 'R');

            $pdf->SetXY(123, $pdf->GetY()); // Ajustar la posición vertical para el siguiente renglón
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(25, 10, utf8_decode('TOTAL A PAGAR'), 0, 0, 'L');
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 10, number_format($total), 0, 1, 'R');
        }
    }

    // Mostrar el PDF en el navegador
    $pdf->Output();
}

?>
