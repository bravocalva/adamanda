<?php
$activePage = 'verificar_hab'; // Define la página activa 
session_start(); // Inicia la sesión

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php'); // Redirigir al login si no hay sesión
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>

    <script src="../assets/javascript/entradas.js"></script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper d-flex flex-column vh-100">

        <?php
        include "aside.php";
        include "navbar.php";
        ?>


        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>VERIFICACIÓN DE HABITACIONES</h1>
                            <p class="text-muted">Seleccione una opción para gestionar el estado actual de las habitaciones.</p>
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Verificar Habitaciones</li>
                            </ol>
                        </div>

                    </div>

                    

                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">

                        <!-- Verificar entradas -->
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-door-open fa-3x mb-2"></i>
                                    <h4>Verificar entradas</h4>
                                    <p>Habitaciones que deben ser ocupadas hoy.</p>
                                    <a href="entradas.php" class="btn btn-outline-light mt-2">Ir a verificar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Verificar salidas -->
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <i class="fas fa-door-closed fa-3x mb-2"></i>
                                    <h4>Verificar salidas</h4>
                                    <p>Habitaciones que deben desocuparse hoy.</p>
                                    <a href="salidas.php" class="btn btn-outline-light mt-2">Ir a verificar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Verificar limpieza -->
                        <div class="col-md-4">
                            <div class="card bg-primary text-dark">
                                <div class="card-body text-center">
                                    <i class="fas fa-broom fa-3x mb-2"></i>
                                    <h4>Verificar limpieza</h4>
                                    <p>Habitaciones marcadas para limpieza.</p>
                                    <a href="limpieza.php" class="btn btn-outline-light mt-2">Ir a verificar</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>


        </div>


        <?php

        include "footer.php";


        ?>
    </div>
    <!-- ./wrapper -->


</body>

</html>