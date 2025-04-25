<?php
$activePage = 'clientes'; // Define la página activa 
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
    <script src="../assets/javascript/clientes.js"></script>


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
                            <h1 class="m-0">PAGINA CLIENTES</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Pagina clientes</li>
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
                            <h1 class="card-title"><strong>Lista de Clientes</strong></h1>
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#modalAgregarCliente">
                                <i class="fas fa-plus"></i> Agregar Cliente
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="loading" class="text-center">
                                <i class="fas fa-spinner fa-spin"></i> Cargando...
                            </div>
                            <table id="clientesTable" class="table table-hover table-striped" style="width: 100%; display: none;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>CURP</th>
                                        <th>Nombre</th>
                                        <th>Apellido Paterno</th>
                                        <th>Apellido Materno</th>
                                        <th>Telefono</th>
                                        <th>Correo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos se llenarán con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
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

                    <!-- MODAL PARA ACTUALIZAR CLIENTE -->
                    <div class="modal fade" id="modalActualizarCliente" tabindex="-1" role="dialog" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Nuevo Cliente</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formActualizarCliente">
                                        <!-- Fila 1: CURP y Nombre -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="curp_act">CURP</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="curp_act" name="curp_act" placeholder="Ingresa tu CURP" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="nombre_act">Nombre</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="nombre_act" name="nombre_act" placeholder="Ingresa nombre" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fila 2: Apellido Materno y Apellido Materno -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="apellido_paterno_act">Apellido Paterno</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="apellido_paterno_act" name="apellido_paterno_act" placeholder="Ingresa apellido" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="apellido_materno_act">Apellido Materno</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="apellido_materno_act" name="apellido_materno_act" placeholder="Ingresa apellido" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fila 3: Telefono y Email -->
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="telefono_act">Telefono</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-phone-square-alt"></i></span>
                                                        </div>
                                                        <input type="text" class="form-control" id="telefono_act" name="telefono_act" placeholder="Ingresa un telefono" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="email_act">Email</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                                                        </div>
                                                        <input type="email" class="form-control" id="email_act" name="email_act" placeholder="Ingresa un correo electronico" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-warning" id="btnActualizarCliente">Actualizar</button>
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