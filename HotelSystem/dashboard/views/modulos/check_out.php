<?php
$activePage = 'salidas'; // Define la página activa 
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

    <script src="../assets/javascript/check_out.js"></script>

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
                            <h1 class="m-0">CHECK-OUT</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="salidas.php">Verificar Salidas</a></li>
                                <li class="breadcrumb-item active">Check-Out</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Tarjeta: Información de la habitación -->
                    <div class="col-md-12">
                        <div class="card card-outline card-primary mb-2">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-md mb-0"><strong><i class="fas fa-info-circle mr-1"></i> Información de la habitación:</strong></h3>
                            </div>
                            <!-- Cuerpo de la tarjeta de habitacion-->
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-2 mb-1">
                                        <strong>Número:</strong> <span id="info-habitacion-numero"></span>
                                    </div>
                                    <div class="col-2 mb-1">
                                        <strong>Tipo:</strong> <span id="info-habitacion-tipo"></span>
                                    </div>

                                    <div class="col-3 mb-1">
                                        <strong>Precio por noche:</strong>
                                        <span>$</span><span id="id_precio"></span>
                                    </div>

                                    <div class="col-2 mb-1">
                                        <strong>ID Usuario:</strong>
                                        <span id="id_usuario"><?php echo $_SESSION['user']['id_usuario']; ?></span>
                                    </div>

                                    <div class="col-3 mb-1">
                                        <strong>Atiende:</strong>
                                        <span id="nombre_usuario"><?php echo $_SESSION['user']['nombre']; ?></span>
                                    </div>

                                </div>

                                <div class="row mt-2">
                                    <div class="col-12 d-flex align-items-center">
                                        <img id="info-habitacion-imagen" src="" alt="Habitación 101" class="img-fluid rounded mr-3" style="width: 90px; height: 90px; object-fit: cover;">
                                        <p id="info-habitacion-descripcion" class="mb-0 text-sm">
                                        </p>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card card-outline card-secondary mb-2">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-md mb-0"><strong><i class="fas fa-info-circle mr-1"></i> Información de la reservación:</strong></h3>
                            </div>
                            <!-- Cuerpo de la tarjeta de reservacion-->
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-2 mb-1">
                                        <strong>ID reservación:</strong><br> <span id="idReservacion"></span>
                                    </div>

                                    <div class="col-2 mb-1">
                                        <strong>Entrada:</strong><br> <span id="fechaEntrada"></span>
                                    </div>
                                    <div class="col-2 mb-1">
                                        <strong>Salida:</strong><br> <span id="fechaSalida"></span>
                                    </div>
                                    <div class="col-2 mb-1">
                                        <strong>Noches:</strong><br> <span id="cantidadNoches"></span>
                                    </div>
                                    <div class="col-2 mb-1">
                                        <strong>Adelanto:</strong><br>$<span id="adelanto"></span>
                                    </div>

                                    <div class="col-2 mb-1">
                                        <strong>Restante a pagar:</strong> $<span id="total"></span>
                                    </div>
                                </div>




                            </div> <!--  cierre de la tarjeta -->



                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-md mb-0"><strong><i class="fas fa-dolly-flatbed"></i> Inventario Faltante</strong></h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body py-2">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered m-0" id="tablaArticulos">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%">ID</th>
                                                <th>Nombre</th>
                                                <th>Decripcion</th>
                                                <th>Precio</th>
                                                <th>Stock</th>
                                                <th class="text-center">Faltantes</th>
                                            </tr>

                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total faltante:</td>
                                                <td id="total-faltante" class="fw-bold">$0.00</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header py-2 px-3">
                                <div class="row w-100">
                                    <div class="col-6 d-flex align-items-center">
                                        <h3 class="card-title text-md mb-0">
                                            <strong><i class="fas fa-hand-holding-usd"></i> Cargos Adicionales</strong>
                                        </h3>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end align-items-center">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalAgregarCargo">
                                            <i class="fas fa-plus"></i> Agregar Cargo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body py-2">
                                <table class="table table-sm table-bordered m-0" id="tablaCargos">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">ID</th>
                                            <th>Descripcion</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        <!-- se llenara por medio de la api -->
                                    </tbody>

                                </table>
                            </div>


                            <!-- /.card -->
                        </div>


                        <div class="card mt-4">

                            <div class="card-header py-2 px-3">
                                <div class="row w-100">
                                        <h3 class="card-title text-md mb-0">
                                            <strong><i class="fas fa-hand-holding-usd"></i> Tipo de pago y total</strong>
                                        </h3>
                                </div>
                            </div>

                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="tipoPago" class="form-label fw-bold">Tipo de pago:</label>
                                        <select class="form-control" id="tipoPago">
                                            <option value="efectivo">Efectivo</option>
                                            <option value="tarjeta">Tarjeta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                            <Label>Total a pagar: </Label>
                                        <h4> $<span id="totalFinalFactura">0.00</span> </h4>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="salidas.php">
                                    <button type="button" class="btn btn-danger w-100">
                                        Regresar
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="button" id="btnTerminar" class="btn btn-primary w-100">Terminar y limpiar</button>
                            </div>
                        </div>
                    </div>

                </div><!-- /.container-fluid -->



            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- MODAL PARA AGREGAR CARGO ADICIONAL -->
        <div class="modal fade" id="modalAgregarCargo" tabindex="-1" role="dialog" aria-labelledby="modalAgregarCargoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarCargoLabel">Agregar Nuevo Cargo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formAgregarCargo">
                            <!-- Descripción y monto -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="descripcion">Descripción</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingresa una descripción" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="monto">Monto</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input type="number" class="form-control" id="monto" name="monto" placeholder="$ 0.0" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnAgregarCargo">Agregar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php

        include "footer.php";


        ?>
    </div>
    <!-- ./wrapper -->


</body>

</html>