<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Estadisticas | C4Event</title>
    <link rel="shortcut icon" href="../img/logooo.ico" type="image/x-icon">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!--fonts--> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/estadisticas_admin.css">
</head>
<body>

    <?php include "../includes/nav_admin.php";?>
    <br>
    <br>
    
    
    <section class="cols-2">
        <figure>
            <h2>Comparativa servicios</h2>
            <canvas id="serviciosChart" width="600" height="200"></canvas> <br> <br>
            <h2>Eventos por mes</h2>
            <canvas id="eventosMesChart" width="400" height="200"></canvas>
        </figure>
        <figure>
            <h2>Comparativa tipos de eventos</h2>
            <canvas id="eventosTiposChart" width="300" height="200"></canvas>
        </figure>
    </section>
   

    <!--Boostrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="js/graficos.js"></script>

</body>
</html>
