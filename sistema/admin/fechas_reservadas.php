<html>

<head>
    <title>Fechas reservadas | C4Event</title>


    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/es.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/fechas_reservadas_admin.css">
    
</head>

<body>

    <?php include "../includes/nav_admin.php"; ?>

    <div class="text-center"><a class="btn btn-success m-3" href="registrar_reservas.php">Agregar nueva</a></div>

    <h2>
        <center>Calendario de Fechas Reservadas</center>
    </h2>
    <div class="container-fluid">
        <div id="calendar"></div>
    </div>
    <br>
    <!--Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
include('../../conexion.php');

// Consulta para obtener las fechas reservadas de la tabla tblfechasreservadas
$fetch_reserved_dates = mysqli_query($conn, "SELECT * FROM tblfechasreservadas");
?>

<script>
    $(document).ready(function() {
        $('#calendar').fullCalendar({
            header: {
                left: 'month, agendaWeek, agendaDay, list',
                center: 'title',
                right: 'prev, today, next'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            events: [
                // Añadir eventos desde la tabla 'tblfechasreservadas'
                <?php while ($reserved = mysqli_fetch_array($fetch_reserved_dates)) { ?> {
                        id: '<?php echo $reserved['id']; ?>',
                        title: 'Fecha Reservada: <?php echo $reserved['nombre']; ?>',
                        start: '<?php echo $reserved['fechareservada']; ?>', // Solo una fecha para el evento
                        color: '#FF6347',  // Color específico para las fechas reservadas
                        textColor: 'white'
                    },
                <?php } ?>
            ],
            editable: true,
            eventRender: function(event, element) {
                var eventDate = moment(event.start);
                var today = moment().startOf('day');
                if (eventDate.isBefore(today)) {
                    element.css('background-color', 'lightcoral'); // Eventos pasados en color rojo claro
                } else {
                    // element.css('background-color', 'lightseagreen'); // Eventos futuros en verde aguamarina claro
                }
            },
            eventDrop: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                var id = event.id;
                $.ajax({
                    url: "update_reservas.php",
                    type: "POST",
                    data: {
                        id: id,
                        start: start
                    },
                    success: function() {
                        alert("Fecha reservada actualizada exitosamente");
                    }
                });
            },
            eventClick: function(event) {
                if (confirm("¿Estás seguro de que quieres eliminar esta fecha reservada?")) {
                    var id = event.id;
                    $.ajax({
                        url: "delete_reservas.php",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function() {
                            alert("Fecha reservada eliminada exitosamente");
                            window.location.reload();
                        }
                    });
                }
            }
        });
    });
</script>
