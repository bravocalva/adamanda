<?php
$activePage = 'reservar'; // Define la página activa 
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- SweetAlert -->
    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <script src="../assets/javascript/reservar_hab.js"></script>
    <!-- Select2 CSS y JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



</head>
<style>
    /* Ajusta la altura del contenedor de Select2 en AdminLTE */
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px;
        font-size: 1rem;
        line-height: 1.5;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 24px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
    }
</style>


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
                            <h1 class="m-0">Reservacion Habitacion No. <a id="NumHabTitulo"></a></h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="reservar.php">Hospedar</a></li>
                                <li class="breadcrumb-item active">Reservar</li>
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
                        <!-- Tarjeta: Información de la habitación -->
                        <div class="col-md-12">
                            <div class="card card-outline card-primary mb-2">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                    <h3 class="card-title text-md mb-0"><strong><i class="fas fa-info-circle mr-1"></i> Info de la habitación:</strong></h3>
                                </div>
                                <!-- Cuerpo de la tarjeta-->
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-2 mb-1">
                                            <strong>ID:</strong> <span id="info-habitacion-id"></span>
                                        </div>
                                        <div class="col-2 mb-1">
                                            <strong>Número:</strong> <span id="info-habitacion-numero"></span>
                                        </div>
                                        <div class="col-2 mb-1">
                                            <strong>Tipo:</strong> <span id="info-habitacion-tipo"></span>
                                        </div>
                                        <div class="col-3 mb-1">
                                            <strong>Fecha entrada:</strong>
                                            <span id="fecha_entrada"></span>
                                        </div>
                                        <div class="col-3 mb-1">
                                            <strong>Fecha Salida:</strong>
                                            <span id="fecha_salida"></span>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-2 mb-1">
                                            <strong>Noches:</strong>
                                            <span id="id_noches"></span>
                                        </div>
                                        <div class="col-4 mb-1">
                                            <strong>Precio por noche:</strong>
                                            <span>$</span><span id="id_precio"></span>
                                        </div>
                                        <div class="col-3 mb-1">
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
                    </div> <!-- .row -->
                    <div class="row">
                        <!-- Tarjeta: Datos del Cliente e informacion adicional -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                    <h3 class="card-title text-md mb-0"><strong><i class="fas fa-info-circle mr-1"></i> Datos adicionales:</strong></h3>
                                </div>
                                <div class="card-body">
                                    <form id="formReserva">

                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <label for="cliente_id" class="form-label">Seleccionar cliente</label>
                                                <select class="form-control select2" id="cliente_id" name="cliente_id" style="width: 100%;">
                                                    <option value="">Seleccione un cliente</option>
                                                    <!-- Opciones serán llenadas dinámicamente con JS -->
                                                </select>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button type="button" class="btn btn-success w-100" id="btnNuevoCliente" data-toggle="modal" data-target="#modalAgregarCliente">
                                                    <i class="fas fa-user-plus"></i> Nuevo cliente
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="adelanto" class="form-label">Adelanto</label>
                                                <input type="number" step="0.01" class="form-control" id="adelanto" name="adelanto" placeholder="0.00">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="total" class="form-label">Total</label>
                                                <input type="number" step="0.01" class="form-control" id="total" name="total" placeholder="0.00" disabled>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="total_pagar" class="form-label">Total a pagar</label>
                                                <input type="number" step="0.01" class="form-control" id="total_pagar" name="total" placeholder="0.00" disabled>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a href="reservar.php">
                                                    <button type="button" class="btn btn-danger w-100">
                                                        Regresar
                                                    </button>
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-primary w-100" id="btnRegistrarRerserva">
                                                    Registrar Reserva
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                        </div>
                    </div> <!-- .row -->

                    <!-- MODAL PARA AGREGAR CLIENTE -->
                    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Nuevo Cliente</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formAgregarCliente">
                                        <!-- Fila 1: CURP y Nombre -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="curp">CURP</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="curp" name="curp" placeholder="Ingresa tu CURP" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="nombre">Nombre</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa nombre" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fila 2: Apellido Materno y Apellido Materno -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="apellido_paterno">Apellido Paterno</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Ingresa apellido" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="apellido_materno">Apellido Materno</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Ingresa apellido" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fila 3: Telegono y Email -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="telefono">Telefono</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-phone-square-alt"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ingresa un telefono" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                        </div>
                                                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa un correo electronico" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" id="btnGuardarCliente">Guardar</button>
                                    <!-- <button id="btnActualizarUsuario" class="btn btn-warning" style="display: none;">Actualizar</button> -->
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