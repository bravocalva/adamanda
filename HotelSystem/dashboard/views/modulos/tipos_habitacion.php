<?php
$activePage = 'habitaciones'; 
session_start(); 
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
    <title>Hotel Adamanda | Tipos de Habitación</title>
    <link rel="shortcut icon" href="../assets/dist/img/favicon.ico" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <!-- En el <head> añade: -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/javascript/tipos_habitacion.js"></script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

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
                            <h1 class="m-0">GESTION TIPO DE HABITACIÓN</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="habitaciones.php">Habitaciones</a></li>
                                <li class="breadcrumb-item active">Gestionar tipo de habitación</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h1 class="card-title"><strong>Lista de Tipos de Habitación</strong></h1>
                            <button type="button" class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#modalAgregarTipoHabitacion">
                                <i class="fas fa-plus"></i> Agregar Tipo de Habitación
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="loading" class="text-center">
                                <i class="fas fa-spinner fa-spin"></i> Cargando...
                            </div>
                            <table id="tiposHabitacionTable" class="table table-hover table-striped" style="width: 100%; display: none;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos se llenarán con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- MODAL PARA AGREGAR TIPO DE HABITACIÓN -->
                    <div class="modal fade" id="modalAgregarTipoHabitacion" tabindex="-1" role="dialog" aria-labelledby="modalAgregarTipoHabitacionLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAgregarTipoHabitacionLabel">Agregar Nuevo Tipo de Habitación</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formAgregarTipoHabitacion">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="nombre">Nombre del Tipo</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-bed"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa nombre del tipo" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="descripcion">Descripción</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingresa descripción" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="precio">Precio</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                        </div>
                                                        <input type="number" class="form-control" id="precio" name="precio" placeholder="Ingresa precio" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" id="btnGuardarTipoHabitacion">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL PARA ACTUALIZAR TIPO DE HABITACIÓN -->
                    <div class="modal fade" id="modalActualizarTipoHabitacion" tabindex="-1" role="dialog" aria-labelledby="modalAgregarTipoHabitacionLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAgregarTipoHabitacionLabel">Actualizar Tipo de Habitación</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formActualizarTipoHabitacion">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="nombre_act">Nombre del Tipo</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-bed"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="nombre_act" name="nombre_act" placeholder="Ingresa nombre del tipo" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="descripcion_act">Descripción</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="descripcion_act" name="descripcion_act" placeholder="Ingresa descripción" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="precio_act">Precio</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                                        </div>
                                                        <input type="number" class="form-control" id="precio_act" name="precio_act" placeholder="Ingresa precio" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" id="btnActualizarTipoHabitacion">Actualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include "footer.php"; ?>
    </div>
    <!-- ./wrapper -->

</body>

</html>
