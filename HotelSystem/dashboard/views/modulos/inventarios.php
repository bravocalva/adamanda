<?php
$activePage = 'inventario'; // Define la página activa 
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

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>

    <script src="../assets/javascript/inventario.js"></script>

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
                            <h1 class="m-0">PAGINA INVENTARIO</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Inventario</li>
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
                        <div class="col-md-4">
                            <!-- Espacio vacío o para otros elementos -->
                        </div>
                        <div class="col-md-8">
                            <!-- Flexbox con espacio entre el botón y el input -->
                            <div class="d-flex justify-content-end align-items-center mb-2">

                                <!-- Botón más ancho con margen a la derecha -->
                                <a href="articulos.php" class="btn btn-secondary btn-sm mr-3" style="min-width: 150px;">
                                    <i class="fas fa-cogs"></i> Gestionar Artículos
                                </a>

                                <!-- Input más largo y un poco más alto -->
                                <div class="input-group" style="width: 220px;">
                                    <input type="text" class="form-control form-control-sm" id="buscador"
                                        placeholder="Buscar habitación..." aria-label="Buscar habitación">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">

                    <div class="row" id="habitacionesContainer">
                        <!-- Aquí se agregarán las tarjetas dinámicamente -->
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