<?php
$activePage = 'inventario_habitacion'; // Define la página activa 
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
    <title>Hotel Adamanda | Inventario </title>
    <link rel="shortcut icon" href="../assets/dist/img/favicon.ico" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- SweetAlert -->
    <script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="../assets/javascript/inventario_habitacion.js"></script>
</head>

<style>
    
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
                            <h1 class="m-0" id="labelTitulo">INVENTARIO DE HABITACIÓN</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="inventarios.php">Inventario</a></li>
                                <li class="breadcrumb-item active">Habitación</li>
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
                            <div class="card card-outline card-primary mb-2" data-card-widget="collapse">
                                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                    <h3 class="card-title text-md mb-0"><strong><i class="fas fa-info-circle mr-1"></i> Habitación: <span id="header-number"></span></strong></h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" data-target="#modalAgregar">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Cuerpo de la tarjeta colapsable-->
                                <div class="card-body py-2" style="display: none;">
                                    <div class="row">
                                        <div class="col-3 mb-1">
                                            <strong>ID:</strong> <span id="info-habitacion-id"></span>
                                        </div>
                                        <div class="col-3 mb-1">
                                            <strong>Número:</strong> <span id="info-habitacion-numero"></span>
                                        </div>
                                        <div class="col-3 mb-1">
                                            <strong>Tipo:</strong> <span id="info-habitacion-tipo"></span>
                                        </div>
                                        <div class="col-3 mb-1">
                                            <strong>Status:</strong>
                                            <span id="info-habitacion-estado"></span>
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
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Inventario</h3>
                                        <button class="btn btn-sm btn-primary float-right " id="editarBtn" data-toggle="modal" data-target="#modalAgregar">
                                            <i class="fas fa-edit mr-1"></i> Agregar articulo
                                        </button>

                                        <button class="btn btn-sm btn-danger float-right mr-2" id="quitarBtn" data-toggle="modal" data-target="#modalquitar">
                                            <i class="fas fa-edit mr-1"></i> Quitar articulo
                                        </button>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered m-0" id="tablaArticulos">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%">ID</th>
                                                        <th>Nombre</th>
                                                        <th>Decripcion</th>
                                                        <th>Precio</th>
                                                        <th>Stock</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                    <!-- se llenara por medio de la api -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div><!-- /.container-fluid -->





                </div><!-- /.container-fluid -->

                <!-- Modal: Agregar artículo -->
                <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="modalAgregarLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="modalAgregarLabel"><i class="fas fa-plus"></i> Agregar artículo</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formAgregarArticulo">
                                    <div class="form-group">
                                        <label for="selectArticulo">Artículo</label>
                                        <select id="selectArticulo" name="selectArticulo" class="select2" required>
                                        <option value="">Seleccione un articulo</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button id="btnAgregarArticulo" type="button" form="formAgregarArticulo" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal: Quitar artículo -->
                <div class="modal fade" id="modalquitar" tabindex="-1" role="dialog" aria-labelledby="modalQuitarLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalQuitarLabel"><i class="fas fa-plus"></i> Quitar artículo</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formQuitarArticulo">
                                    <div class="form-group">
                                        <label for="selectArticulo_Q">Artículo</label>
                                        <select id="selectArticulo_Q" name="articulo_id_Q" class="form-control" required></select>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label for="cantidad_Q">Cantidad Disponible</label>
                                                <input type="number" class="form-control" id="stockActual" name="stockActual" min="1" readonly>
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="cantidad_Q">Cantidad a Quitar</label>
                                                <input type="number" class="form-control" id="cantidad_Q" name="cantidad_Q" min="1"  required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button id="btnQuitarArticulo" type="button" form="formQuitarArticulo" class="btn btn-danger">Quitar</button>
                            </div>
                        </div>
                    </div>
                </div>




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

<style>
    /* Estilo para el contenedor del select (la parte visible) */
    .select2-container--bootstrap4 .select2-selection {
        border: 1px solid #6c757d;
        border-radius: 5px;
        height: 38px;
        line-height: 36px;
    }

    /* Estilo para el borde cuando el select está enfocado (focus) */
    .select2-container--bootstrap4 .select2-selection--single:focus {
        border-color: #6c757d;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    }
</style>