<?php
$uploadDir = dirname(__DIR__, 4) . '/uploads/habitaciones/';

// Crear la carpeta si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ruta']) && !empty($_POST['ruta'])) {
        eliminarImagen($_POST['ruta']);
    } else {
        manejarImagen($_FILES['image'] ?? null, $_POST['rutaImagenActual'] ?? '');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'eliminar_imagen') {
    echo "ejecutando accion eliminar";  // Para depuración

    // Recuperar la ruta de la imagen
    $ruta = $_POST['ruta'];

    echo "Ruta recibida: " . $ruta;  // Depuración para asegurarnos de que la ruta llega

    // Verificar si el archivo existe y eliminarlo
    $rutaAbsoluta = $_SERVER['DOCUMENT_ROOT'] . $ruta;  // Asegúrate de que la ruta sea absoluta

    if (file_exists($rutaAbsoluta)) {
        unlink($rutaAbsoluta);  // Eliminar la imagen
        echo json_encode(['success' => true, 'mensaje' => 'Imagen eliminada']);
    } else {
        echo json_encode(['success' => false, 'mensaje' => 'No se encontró la imagen']);
    }
    exit;
}


function eliminarImagen($ruta)
{
    $rutaAbsoluta = dirname(__DIR__, 5)  . $ruta;
    if (file_exists($rutaAbsoluta)) {
        unlink($rutaAbsoluta);
        echo json_encode(['success' => true, 'message' => 'Imagen eliminada']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Imagen no encontrada']);
    }
    exit();
}

function manejarImagen($archivo, $rutaImagenActual)
{
    global $uploadDir;
    $nuevaRuta = '';

    if (!empty($archivo['name']) && $archivo['error'] === UPLOAD_ERR_OK) {
        // Eliminar la imagen anterior si existe
        if (!empty($rutaImagenActual)) {
            $rutaAnterior = dirname(__DIR__, 5) . $rutaImagenActual;
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
        }

        // Guardar nueva imagen
        $fileName = uniqid() . '_' . basename($archivo['name']);
        $rutaFinal = $uploadDir . $fileName;

        if (move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
            $nuevaRuta = '/adamanda/uploads/habitaciones/' . $fileName;
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al subir la nueva imagen']);
            exit();
        }
    }

    echo json_encode(['success' => true, 'ruta' => $nuevaRuta]);
    exit();
}

?>
<?php
$activePage = 'habitaciones'; // Define la página activa 
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- dataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">

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
    <script src="../assets/javascript/habitaciones.js"></script>



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
                            <h1 class="m-0">PAGINA HABITACIONES</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Home</a></li>
                                <li class="breadcrumb-item active">Habitaciones</li>
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
                        <div id="loading" class="text-center">
                            <i class="fas fa-spinner fa-spin"></i> Cargando...
                        </div>

                        <div class="d-flex justify-content-end mb-2">
                            <a href="tipos_habitacion.php" class="btn btn-secondary btn-sm mr-2">
                                <i class="fas fa-cogs"></i> Gestionar Tipos de Habitación
                            </a>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalAgregarHabitacion">
                                <i class="fas fa-plus"></i> Agregar Habitación
                            </button>
                        </div>

                        <table id="HabitacionesTable" class="table table-hover table-striped" style="width: 100%; display: none;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Numero Hab.</th>
                                    <th>Descripcion</th>
                                    <th>Tipo Hab.</th>
                                    <th>Estado</th>
                                    <th>Imagen</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los datos se llenarán con JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <!-- MODAL PARA AGREGAR HABITACION -->
                    <div class="modal fade" id="modalAgregarHabitacion">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAgregarHabitacion">Agregar Nueva Habitacion</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        x
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formHabitacion" enctype="multipart/form-data" method="post">
                                        <!-- Sección del Logo -->
                                        <div class="card card-secondary mb-3">
                                            <div class="card-header py-2">
                                                <h6 class="card-title mb-0">Imagen de la habitación</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 text-center">
                                                        <label>Imagen Actual</label>
                                                        <img id="imagePreview" src="../assets/dist/img/default.jpg" alt="ImagenActual"
                                                            class="img-fluid img-thumbnail mb-2" style="max-width: 150px;">
                                                        <p class="small text-muted mb-0">Imagen actual</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Seleccionar Imagen</label>
                                                            <input type="file" id="imageInput" name="image" class="form-control" accept=".jpg, .jpeg, .png">
                                                            <small class="text-muted">Selecciona una imagen /.jpg/.jpeg/.png.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información de la habitacion -->
                                        <div class="card card-secondary mb-3">
                                            <div class="card-header py-2">
                                                <h6 class="card-title mb-0">Información Básica</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="nombre">Numero de Habitación</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                                                            </div>
                                                            <input type="text" maxlength="10" class="form-control" id="numeroHab" name="numeroHab" placeholder="Ingresa el numero de habitación" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="rol">Tipo de habitación</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                            </div>
                                                            <select class="form-control" id="tipoHabitacion" name="tipoHabitacion" required>
                                                                <!-- aqui va a llenarse con api -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <label for="descripcion">Descripción</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-align-center"></i></span>
                                                        </div>
                                                        <input type="text" maxlength="500" class="form-control py-3" id="descripcion" name="descripcion" placeholder="Ingresa una descripcion" required>
                                                    </div>


                                                </div>


                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                <i class="fas fa-times mr-1"></i> Cancelar
                                            </button>
                                            <button type="button" class="btn btn-primary" id="btnGuardarHabitacion">
                                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL PARA ACTUALIZAR HABITACION -->
                    <div class="modal fade" id="modalActualizarHabitacion">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalAgregarHabitacion">Actualizar Habitacion</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        x
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formActualizarHabitacion" enctype="multipart/form-data" method="post">

                                        <input type="hidden" id="rutaImagenActual" name="rutaImagenActual">

                                        <!-- Sección del Logo -->
                                        <div class="card card-secondary mb-3">
                                            <div class="card-header py-2">
                                                <h6 class="card-title mb-0">Imagen de la habitación</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 text-center">
                                                        <label>Imagen Actual</label>
                                                        <img id="imagePreview_Act" src="../assets/dist/img/default.jpg" alt="ImagenActual"
                                                            class="img-fluid img-thumbnail mb-2" style="max-width: 150px;">
                                                        <p class="small text-muted mb-0">Imagen actual</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Seleccionar Imagen</label>
                                                            <input type="file" id="imageInput_Act" name="imageInput_Act" class="form-control" accept=".jpg, .jpeg, .png">
                                                            <small class="text-muted">Selecciona una imagen /.jpg/.jpeg/.png.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información de la habitacion -->
                                        <div class="card card-secondary mb-3">
                                            <div class="card-header py-2">
                                                <h6 class="card-title mb-0">Información Básica</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="nombre">Numero de Habitación</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="numeroHab_Act" name="numeroHab_Act" placeholder="Ingresa el numero de habitación" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="rol">Tipo de habitación</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                                            </div>
                                                            <select class="form-control" id="tipoHabitacion_Act" name="tipoHabitacion_Act" required>
                                                                <!-- aqui va a llenarse con api -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <label for="descripcion">Descripción</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="fas fa-align-center"></i></span>
                                                        </div>
                                                        <textarea type="text" maxlength="500" class="form-control py-3" id="descripcion_Act" name="descripcion_Act" placeholder="Ingresa una descripcion" rows="4" required></textarea>
                                                    </div>


                                                </div>


                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                <i class="fas fa-times mr-1"></i> Cancelar
                                            </button>
                                            <button type="button" class="btn btn-primary" id="btnActualizarHabitacion">
                                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
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