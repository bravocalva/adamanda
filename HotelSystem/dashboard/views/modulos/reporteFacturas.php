<?php
$activePage = 'facturas'; // Define la página activa 
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

    <!-- REQUIRED SCRIPTS -->
    <!-- SweetAlert -->
    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>

    <script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

    <script src="../assets/javascript/facturas.js"></script>

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
                            <h1 class="m-0">REPORTE DE FACTURAS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Facturas</li>
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

                        <table id="FacturasTable" class="table table-hover table-striped" style="width: 100%; ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID Reservacion</th>
                                    <th>F.Emision</th>
                                    <th>Tipo de pago</th>
                                    <th>Total</th>
                                    <th>Usuario</th>
                                    <th>Cliente</th>
                                    <th>Acciones</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <div id="ticketPreview">

                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="reservacionModal" tabindex="-1" role="dialog" aria-labelledby="reservacionModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reservacionModalLabel">Detalles de la Reservación</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <!-- Tabla para la Reservación -->
                                    <h5>Reservación</h5>
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td><strong>Cliente:</strong></td>
                                                <td id="reservacionCliente"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha Entrada:</strong></td>
                                                <td id="reservacionFechaEntrada"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha Salida:</strong></td>
                                                <td id="reservacionFechaSalida"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Adelanto:</strong></td>
                                                <td id="reservacionAdelanto"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total Reservación:</strong></td>
                                                <td id="reservacionTotal"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td id="reservacionStatus"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Usuario:</strong></td>
                                                <td id="reservacionUsuario"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <!-- Tabla para los Artículos -->
                                    <h5>Artículos</h5>
                                    <table class="table table-striped" id="articulosDetails">
                                        <thead>
                                            <tr>
                                                <th>Artículo</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Habitación</th>
                                                <th>Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="5">No hay artículos registrados.</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <!-- Tabla para los Movimientos Adicionales -->
                                    <h5>Movimientos Adicionales</h5>
                                    <table class="table table-striped" id="movimientosDetails">
                                        <thead>
                                            <tr>
                                                <th>Descripción</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">No hay movimientos adicionales registrados.</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                </div> 
                            </div>
                        </div>
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