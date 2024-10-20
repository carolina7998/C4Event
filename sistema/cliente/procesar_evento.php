<?php
include '../../conexion.php';
session_start();

if (!isset($_SESSION['docid'])) {
    die("Error: El usuario no ha iniciado sesión. Por favor, inicie sesión y vuelva a intentarlo.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_evento = mysqli_real_escape_string($conn, $_POST['fecha_evento']);
    $tipo_evento = mysqli_real_escape_string($conn, $_POST['tipo_evento']);
    $num_invitados = mysqli_real_escape_string($conn, $_POST['num_invitados']);
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : [];
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : [];

    $ciudad_mockup = '05001';
    $usuarios = $_SESSION['docid'];
    $estado = 1;

    $conn->begin_transaction();

    try {
        $sql_event = "INSERT INTO tbleventos (fechaevento, tipoevento, numinvitados, ciudad, usuarios, estado)
                      VALUES ('$fecha_evento', '$tipo_evento', '$num_invitados', '$ciudad_mockup', '$usuarios', '$estado')";

        if (mysqli_query($conn, $sql_event)) {
            $event_id = $conn->insert_id;
            echo "Evento insertado con ID: $event_id<br>";

            $valor_total_evento = 0;

            foreach ($cantidad as $servicio_id => $cant) {
                if ($cant > 0) {
                    $obs = mysqli_real_escape_string($conn, $observaciones[$servicio_id]);

                    $result_servicio = $conn->query("SELECT valor FROM tblservicios WHERE codigo = '$servicio_id'");
                    if ($result_servicio && $servicio = $result_servicio->fetch_assoc()) {
                        $valor_unitario = $servicio['valor'];
                        $total_servicio = $cant * $valor_unitario;
                        $valor_total_evento += $total_servicio;

                        $sql_service = "INSERT INTO tblservicioseventos (codigoeventos, codigoservicios, cantidad, observaciones, total_servicio)
                                        VALUES ('$event_id', '$servicio_id', '$cant', '$obs', '$total_servicio')";
                        if (mysqli_query($conn, $sql_service)) {
                            header("location:servicios.php");
                        } else {
                            throw new Exception("Error al insertar el servicio $servicio_id: " . mysqli_error($conn));
                        }
                    } else {
                        throw new Exception("Error al obtener el valor del servicio: " . mysqli_error($conn));
                    }
                }
            }

            $conn->commit();
        } else {
            throw new Exception("Error al insertar el evento: " . mysqli_error($conn));
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}
?>

