<?php $activePage = 'principal'; // Define la página activa 
?>

<?php
session_start(); // Inicia la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php'); // Redirigir al login si no hay sesión
    exit();
}

$user = $_SESSION['user']; // Obtener datos del usuario
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Adamanda | Reservas </title>
    <link rel="shortcut icon" href="../assets/dist/img/favicon.ico" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">


    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <script src="../assets/javascript/principal.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper d-flex flex-column vh-100">

        <?php
        include "aside.php";
        ?>
        <?php
        include "navbar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper flex-grow-1 overflow-auto">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">PAGINA PRINCIPAL</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Pagina Principal</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- fila para tarjeta 1 -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><span id="habDisponibles">-</span></h3>
                                    <p>Hab. Disponibles</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Más info <i class="fas fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>
                        <!-- fila para tarjeta 2 -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><span id="habOcupados">-</span></h3>
                                    <p>Hab. Ocupadas</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-door-closed"></i>
                                </div>
                                <a href="salidas.php" class="small-box-footer">
                                    Más info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- fila para tarjeta 3-->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><span id="habReservadas">-</span></h3>
                                    <p>Hab. Reservados</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-bookmark"></i>
                                </div>
                                <a href="entradas.php" class="small-box-footer">
                                    Más info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <!-- fila para tarjeta 4 -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><span id="habLimpieza">-</span></h3>
                                    <p>Hab. Limpieza</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-hand-sparkles"></i>
                                </div>
                                <a href="limpieza.php" class="small-box-footer">
                                    Más info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-bookmark"></i> Reservaciones por mes</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="myChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-6">

                            <!-- Tarjeta -->
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">Habitación más reservada</h5>
                                    <p class="card-text">
                                        <i class="fas fa-bed text-primary"></i> Habitación No. <span id="habMasReservada"></span>
                                    </p>
                                </div>
                            </div>

                            <!-- Tarjeta -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total ingresos del mes</h5>
                                    <p class="card-text">
                                        <i class="fas fa-dollar-sign text-success"></i> $ <span id="gananciaMes"></span> MX
                                    </p>
                                </div>
                            </div>
                            <!-- Tarjeta -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total reservaciones del mes</h5>
                                    <p class="card-text">
                                    <i class="fas fa-bed text-warning"></i><span id="reservacionesMes"> 15</span> 
                                    </p>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-bookmark"></i> Habitacion mas reservada</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row"> <!-- Fila para dividir el contenido -->
                                        <!-- Columna izquierda (información) -->
                                        <div class="col-md-10">
                                            <h4 class="card-text"><strong>Habitación No. <span id="noHab"></span></strong></h4>
                                            <p class="card-text"><strong>Tipo:</strong><span id="tipoHab"></span> </p>
                                            <p class="card-text"><strong>Precio:</strong>$<span id="precioHab"></span> </p>
                                            <p class="card-text"><strong>Descripción:</strong><span id="descripcionHab"></span> </p>
                                            <p class="card-text">
                                                <strong>Estado:</strong>
                                                <span id="estadoHab" class="badge bg-secondary">Disponible</span>
                                            </p>
                                        </div>

                                        <!-- Columna derecha (imagen) -->
                                        <div class="col-md-2">
                                            <img id="imagenHab" src="ruta-de-tu-imagen.jpg" alt="Habitación 101" class="img-fluid rounded w-15">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <?php

        include "footer.php";


        ?>
    </div>
    <!-- ./wrapper -->
</body>



</html>

<script>
    async function getDisponible() {
        try {
            const response = await fetch('http://localhost/adamanda/api_rest_hotel/hab_disponible');
            const data = await response.json();
            console.log(data);
            document.getElementById('habDisponibles').textContent = data.total;
        } catch (error) {
            console.error('Error:', error);
        }
    }
    async function getOcupados() {
        try {
            const response = await fetch('http://localhost/adamanda/api_rest_hotel/hab_ocupado');
            const data = await response.json();
            console.log(data);
            document.getElementById('habOcupados').textContent = data.total;
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function getReservados() {
        try {
            const response = await fetch('http://localhost/adamanda/api_rest_hotel/hab_reservados');
            const data = await response.json();
            console.log(data);
            document.getElementById('habReservadas').textContent = data.total;
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function getLimpieza() {
        try {
            const response = await fetch('http://localhost/adamanda/api_rest_hotel/hab_limpieza');
            const data = await response.json();
            console.log(data);
            document.getElementById('habLimpieza').textContent = data.total;
        } catch (error) {
            console.error('Error:', error);
        }
    }

    async function getIngresoMensual() {
        try {
            const response = await fetch('http://localhost/adamanda/api_rest_hotel/gananciaMensual');
            const data = await response.json();
            console.log(data);
            document.getElementById('gananciaMes').textContent = data.ganancia_total_mes;
        } catch (error) {
            console.error('Error:', error);
        }
    }


    getDisponible();
    getOcupados();
    getReservados();
    getLimpieza();
    getIngresoMensual();
</script>