<?php
$activePage = 'reservas'; // Define la página activa 
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


    <!-- CSS -->
    <link rel="stylesheet" href="../assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <!-- JS -->

    <!-- REQUIRED SCRIPTS -->
    <!-- SweetAlert -->
    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <script src="../assets/javascript/reporte_reservas.js"></script>
    <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script> 
    <script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>


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
                            <h1 class="m-0">REPORTE DE RESERVACIONES</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Reservas</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="card-body">
                        <div id="loading" class="text-center" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Cargando...
                        </div>

                        <table id="ReservasTable" class="table table-hover table-striped" style="width: 100%; ">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>F.Entrada</th>
                                    <th>F.Salida</th>
                                    <th>No. Hab.</th>
                                    <th>Total</th>
                                    <th>Adelanto</th>
                                    <th>Restante</th>
                                    <th>Nombre cliente</th>
                                    <th>Status</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>


                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->


</body>

</html>