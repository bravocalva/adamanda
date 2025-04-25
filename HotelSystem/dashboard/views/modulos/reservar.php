<?php
$activePage = 'reservar'; // Define la página activa 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit();
}
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
    <script src="../assets/javascript/reservar.js"></script>


</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper d-flex flex-column vh-100">

        <?php
        include "aside.php";
        include "navbar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper flex-grow-1 overflow-auto">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">PAGINA RESERVACIÓN</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Hospedar</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title">Buscar habitaciones disponibles</h3>
                                </div>
                                <div class="card-body">
                                    <form id="form-buscar">
                                        <div class="row">
                                            <div class="form-group">
                                                <label>Tipo de reserva</label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tipo_reserva" id="reserva_inmediata" value="inmediata">
                                                    <label class="form-check-label" for="reserva_inmediata">Hospedaje inmediato</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="tipo_reserva" id="reserva_futura" value="futura">
                                                    <label class="form-check-label" for="reserva_futura">Reservación</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="fecha_entrada">Fecha de entrada</label>
                                                <input type="date" id="fecha_entrada" name="fecha_entrada" class="form-control" required>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="fecha_salida">Fecha de salida</label>
                                                <input type="date" id="fecha_salida" name="fecha_salida" class="form-control" required>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="filtro_tipo_habitacion" class="form-label">Filtrar por tipo de habitación</label>
                                                <select id="filtro_tipo_habitacion" class="form-control select2">
                                                    <option value="">Todos</option>
                                                    <!-- se llenara con api -->
                                                </select>
                                            </div>

                                            <div class="col-md-3 d-flex align-items-end">
                                                <button id="btnDisponible" type="submit" disabled class="btn btn-success btn-block">Ver Disponibles</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loader -->
                    <div id="loader" class="text-center" style="display:none;">
                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                    </div>

                    <!-- Aquí se mostrarán las habitaciones disponibles -->
                    <div class="row" id="room-cards-container"></div>

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