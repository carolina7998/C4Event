<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

include '../conexion.php';
$email = $_POST['email'];

// Consulta para obtener el 'docid' del usuario según el email
$query = "SELECT docid, email FROM tblusuarios WHERE email = ? AND estado = 'activo'";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userId = $row['docid'];

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

        $mail->setFrom('cataoquendo56@gmail.com', 'C4Event');        
        $mail->addAddress($email);                                  

        // Caracteres especiales
        $mail->CharSet = 'UTF-8';                                   

        // Adjuntar imagen
        $mail->addEmbeddedImage('../img/logo.png', 'logo_cid');

        // Generar el enlace con el docid del usuario
        $resetLink = "http://localhost/c4event/change_password.php?docid=" . urlencode($userId);

        // Contenido del correo
        $mail->isHTML(true);                                        
        $mail->Subject = 'Restablecer contraseña';
        $mail->Body    = '
            <div style="text-align: center;">
                <img src="cid:logo_cid" alt="Logo" style="width: 200px; height: auto;" /><br><br>
                <p>Buen día, este es un correo generado para solicitar la recuperación de contraseña.</p>
                <p>Por favor ingresa a la página a través del siguiente enlace:</p>
                <p><a href="' . $resetLink . '" style="color: #ff6600; text-decoration: none;"><b>Recuperación de contraseña</b></a></p>
                <p><b>¡Gracias por confiar en C4Event!</b></p>
            </div>';
        $mail->AltBody = 'Este es el cuerpo en texto plano para clientes de correo que no soportan HTML.';

        $mail->send();
        header("location:../inicio.php");
        session_start();
        $_SESSION['status'] = 'ok';

    } catch (Exception $e) {
        echo "El mensaje no se pudo enviar. Error de Mailer: {$mail->ErrorInfo}";
    }
} else {
    echo "El correo no existe o el usuario no está activo.";
}

?>

