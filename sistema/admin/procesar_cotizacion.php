
<?php
include '../../conexion.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id']) && isset($_POST['servicios'])) {
    $evento_id = $_POST['evento_id'];
    $servicios = $_POST['servicios'];

    foreach ($servicios as $servicio_id => $valor_adicional) {
        $valor_adicional = floatval($valor_adicional);

        // Actualizar el valor adicional en la base de datos
        $query = "UPDATE tblservicioseventos SET valor_adicional = ? WHERE codigoservicios = ? AND codigoeventos = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('dii', $valor_adicional, $servicio_id, $evento_id);

        if ($stmt->execute() === FALSE) {
            echo "Error al actualizar el valor adicional para el servicio ID $servicio_id: " . $conn->error;
            exit;
        }
    }

    // Calcular el nuevo valor total para el evento
    $querySuma = "SELECT SUM(total_servicio + valor_adicional) as total FROM tblservicioseventos WHERE codigoeventos = ?";
    $stmtSuma = $conn->prepare($querySuma);
    $stmtSuma->bind_param('i', $evento_id);
    $stmtSuma->execute();
    $resultadoSuma = $stmtSuma->get_result();
    $total_evento = 0;
    if ($resultadoSuma->num_rows > 0) {
        $fila = $resultadoSuma->fetch_assoc();
        $total_evento = $fila['total'];
    }

    $valor_iva = $total_evento * 0.19;
    $valor_total = $total_evento + $valor_iva;

    // Actualizar el valor total y el valor del IVA en la tabla tbleventos
    $queryUpdateTotal = "UPDATE tbleventos SET valor_total = ?, valor_iva = ? WHERE codigo = ?";
    $stmtUpdateTotal = $conn->prepare($queryUpdateTotal);
    $stmtUpdateTotal->bind_param('ddi', $valor_total, $valor_iva, $evento_id);

    if ($stmtUpdateTotal->execute() === FALSE) {
        echo "Error al actualizar el valor total del evento: " . $conn->error;
        exit;
    }

    // Obtener la información del cliente para enviar el correo
    $queryCliente = "SELECT u.email, u.nombres, u.apellidos FROM tbleventos e 
                     INNER JOIN tblusuarios u ON e.usuarios = u.docid 
                     WHERE e.codigo = ?";
    $stmtCliente = $conn->prepare($queryCliente);
    $stmtCliente->bind_param('i', $evento_id);
    $stmtCliente->execute();
    $resultadoCliente = $stmtCliente->get_result();

    if ($resultadoCliente->num_rows > 0) {
        $cliente = $resultadoCliente->fetch_assoc();
        $email_cliente = $cliente['email'];
        $nombre_cliente = $cliente['nombres'] . ' ' . $cliente['apellidos'];

        // Configurar y enviar el correo electrónico con PHPMailer
        $mail = new PHPMailer(true);

        try {
            
            $mail->SMTPDebug = 0;
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                       
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'cataoquendo56@gmail.com';               
            $mail->Password   = 'rhgo ujmz hrkg boxx';                  
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;             
            $mail->Port       = 465; 

            // Remitente y destinatario
            $mail->setFrom('cataoquendo56@gmail.com', 'C4Event');        
            $mail->addAddress($email_cliente, $nombre_cliente);

            
            // Caracteres especiales
            $mail->CharSet = 'UTF-8';                                   

            // Adjuntar imagen embebida
            $mail->addEmbeddedImage('../../img/logo.png', 'logo_cid');

            // Contenido del correo
            $mail->isHTML(true);                                        
            $mail->Subject = 'Cotización revisada y actualizada';
            $mail->Body    = '
                <div style="text-align: center;">
                    <img src="cid:logo_cid" alt="Logo" style="width: 200px; height: auto;" /><br><br>
                    Estimado/a cliente
                    <p>Le informamos que a partir de este momento ya puede visualizar la cotización 
                    actualizada en el apartado de solicitudes de su cuenta.</p>

                    <p>En caso de haber realizado observaciones o solicitado modificaciones en alguno de
                    los servicios seleccionados, tenga en cuenta que estos cambios podrían haber generado 
                    un valor adicional, el cual ya ha sido incluido en la cotización final. Esta revisión ha 
                    sido efectuada con base en las observaciones que usted nos ha proporcionado previamente.</p>

                    <p>Le invitamos a revisar detenidamente los detalles de la cotización. Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nosotros.</p> 
   
                    <p><b>Gracias por confiar en nuestros servicios.</b></p>
                </div>';
            $mail->AltBody = 'Este es el cuerpo en texto plano para clientes de correo que no soportan HTML.';

            // Enviar correo
            $mail->send();
            echo 'El correo de actualización ha sido enviado al cliente.';
        } catch (Exception $e) {
            echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "No se encontró la información del cliente para enviar el correo.";
    }

    // Redirigir de vuelta a la página de edición de cotización
    header("Location: cotizaciones.php?evento_id=$evento_id");
    exit;
} else {
    echo "Datos incompletos o solicitud inválida.";
    exit;
}
?>
