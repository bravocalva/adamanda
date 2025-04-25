<?php
$activePage = 'usuarios'; // Define la página activa 
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
    <title>Hotel Adamanda | Usuarios </title>
    <link rel="shortcut icon" href="../assets/dist/img/favicon.ico" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<style>

.my-header-color {
    background-color:rgb(120, 168, 120) !important; /* Verde personalizado */
    color: white !important; /* Texto en blanco */
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
                            <h1 class="m-0">PAGINA USUARIOS</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">usuarios</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Tabla de Usuarios -->
                    <div class="card">
                        <div class="card-header my-header-color">
                        <h3 class="card-title"><i class="fas fa-users"></i> <strong>Lista de Usuarios</strong></h3>
                            <button type="button" class="btn btn-secondary float-right" data-toggle="modal" data-target="#modalAgregarUsuario">
                                <i class="fas fa-plus"></i> Agregar Usuario
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div id="loading" class="text-center">
                                <i class="fas fa-spinner fa-spin"></i> Cargando...
                            </div>
                            <table id="usuariosTable" class="table table-hover table-striped" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Los datos se llenarán con JavaScript -->
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Modal para agregar usuario -->
        <div class="modal fade" id="modalAgregarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalAgregarUsuarioLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAgregarUsuarioLabel">Agregar Nuevo Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formAgregarUsuario">
                            <!-- Fila 1: Nombre y Apellido Paterno -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="apellido_paterno">Apellido Paterno</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Ingresa tu apellido" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 2: Apellido Materno y Email -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="apellido_materno">Apellido Materno</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Ingresa tu apellido" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Ingresa tu email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fila 3: Contraseña y Rol -->
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="password">Contraseña</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa una contraseña" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="rol">Rol</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                            </div>
                                            <select class="form-control" id="rol" name="rol" required>
                                                <!-- aqui va a llenarse con api -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnGuardarUsuario">Guardar</button>
                        <button id="btnActualizarUsuario" class="btn btn-warning" style="display: none;">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        include "footer.php";
        ?>
    </div>
    <!-- ./wrapper -->
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/javascript/usuarios.js"></script>
    <script>
        const idUsuarioSesion = <?php echo $_SESSION['user']['id_usuario']; ?>;
    </script>
</body>

</html>