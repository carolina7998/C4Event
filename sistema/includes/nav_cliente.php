<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../css/estilos_navbar.css">
  <!--Boostrap-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="shortcut icon" href="img/logooo.ico" type="image/x-icon">
  
  <title>Document</title>
  <style>
    .navbar{
      background-color: rgb(194,191,203);
      padding-right: 30px;
      padding-left: 50px;
      padding-bottom: 0;  /*Add some bottom padding to create space between the navigation bar and the carousel */
      font-size: 0.7rem;
    }
    .navbar-collapse{
      align-items: center;
      justify-content: space-between;
    }

    .navbar-nav .nav-item {
        /* Add your styles here, e.g. */
      color: rgb(132, 106, 54);
      padding: 9px; 
    }

    .navbar-nav .nav-item .nav-link{
      color: rgb(132, 106, 54);
      font-size: 1rem;
    }

    .navbar-nav .nav-item a {
      text-decoration: none;
      color: rgb(132, 106, 54);
      font-size: 1rem;
    }

    .nombrelogo img{
      justify-content: center;
      
    }



/* From Uiverse SALIR */ 
    
   
.Btn-salir {
        --black: #000000;
        --ch-black: #141414;
        --eer-black: #1b1b1b;
        --night-rider: #2e2e2e;
        --white: #ffffff;
        --af-white: #f3f3f3;
        --ch-white: #e1e1e1;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition-duration: .3s;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
        background-color: var(--night-rider);
        }

        /* plus sign */
        .sign {
        width: 100%;
        transition-duration: .3s;
        display: flex;
        align-items: center;
        justify-content: center;
        }

        .sign svg {
        width: 17px;
        }

        .sign svg path {
        fill: var(--af-white);
        }
        /* text */
        .text {
        position: absolute;
        right: 0%;
        width: 0%;
        opacity: 0;
        color: var(--af-white);
        font-size: 1.2em;
        font-weight: 600;
        transition-duration: .3s;
        }
        /* hover effect on button width */
        .Btn-salir:hover {
        width: 120px;
        border-radius: 5px;
        transition-duration: .3s;
        }

        .Btn-salir:hover .sign {
        width: 30%;
        transition-duration: .3s;
        padding-left: 20px;
        }
        /* hover effect button's text */
        .Btn-salir:hover .text {
        opacity: 1;
        width: 80%;
        transition-duration: .3s;
        padding-right: 8px;
        }
        /* button click effect*/
        .Btn-salir:active {
        transform: translate(3px ,3px);
        }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-md navbar-light">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-toggler" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbar-toggler">
        <a class="navbar-brand" href="index.php">
            <img src="../img/logonavbar.png" width="55" alt="Logo C4Event">
        </a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <div class="nombrelogo">
          <img class="nombrelogo" src="../img/nombrelogo.png" width="130" alt="Logo C4Event">
        </div>

        <ul class="navbar-nav d-flex justify-content align-items-center">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="perfil_usuario.php">Mi perfil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="servicios.php">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="solicitudes.php">Solicitudes</a>
          </li>
          &nbsp;&nbsp;
          <button class="Btn-salir" onclick="window.location.href='../salir.php'">          
            <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>                    
            <div class="text">Salir</div>
          </button>
          
        </ul>
      </div>
    </div>
  </nav>
</body>
</html>



