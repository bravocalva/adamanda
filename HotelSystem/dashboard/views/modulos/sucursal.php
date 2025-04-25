<?php
$activePage = 'sucursal';
session_start();

//Redirieccionar si no ha iniciado sesion el usuario
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit();
}
?>

<?php
// 1. Configuración de la carpeta de imágenes
$uploadDir = dirname(__DIR__, 4) . '/uploads/sucursal/'; // Cambiado a la subcarpeta adamanda

// 2. Crear carpeta si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rutaImagen = $_POST['rutaLogoActual'] ?? ''; // Ruta actual por defecto

    // Solo procesar imagen si se subió una nueva
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        // 1. Eliminar imagen anterior si existe
        if (!empty($rutaImagen)) {
             $rutaAbsolutaAnterior =dirname(__DIR__, 5) . $rutaImagen; //subimos 5 niveles
            if (file_exists($rutaAbsolutaAnterior)) {
                unlink($rutaAbsolutaAnterior); // Esto elimina la imagen anterior
            }
        }

        // Subir nueva imagen
        $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $rutaFinal = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['logo']['tmp_name'], $rutaFinal)) {
            $rutaImagen = '/adamanda/uploads/sucursal/' . $fileName; // Cambiado a la subcarpeta adamanda
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Error al subir la nueva imagen'
            ]);
            exit();
        }
    }

    // Devolver la ruta (nueva o la misma si no hubo cambios)
    echo json_encode([
        'success' => true,
        'ruta' => $rutaImagen
    ]);
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
    <!-- SweetAlert2 -->
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/javascript/sucursal.js"></script>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper d-flex flex-column vh-100 ">

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
                            <h1 class="m-0">PAGINA SUCURSAL</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Sucursal</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Tarjeta Principal de Información -->
                    <div class="card card-primary card-outline">
                        <div class="card-header bg-white">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-store-alt mr-2"></i>Datos de la Sucursal
                            </h3>
                            <button class="btn btn-sm btn-primary float-right" id="editarBtn" data-toggle="modal" data-target="#modalEditar">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Columna Izquierda - Logo e Información Básica -->
                                <div class="col-md-4 text-center border-right">
                                    <img id="logoSucursal" src="" alt="Logo Sucursal"
                                        class="img-thumbnail mb-3" style="max-width: 200px; height: auto;">
                                    <h4 id="nombreSucursal" class="text-primary">-</h4>
                                    <p class="text-muted mb-1"><i class="fas fa-map-marker-alt mr-2"></i> <span id="ciudadSucursal">-</span></p>
                                </div>

                                <!-- Columna Derecha - Detalles -->
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box bg-light mb-3">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-map-marked-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Dirección</span>
                                                    <span id="direccionSucursal" class="info-box-number">-</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box bg-light mb-3">
                                                <span class="info-box-icon bg-info"><i class="fas fa-envelope"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Correo</span>
                                                    <span id="correoSucursal" class="info-box-number">-</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box bg-light mb-3">
                                                <span class="info-box-icon bg-success"><i class="fas fa-phone"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Teléfono</span>
                                                    <span id="telefonoSucursal" class="info-box-number">-</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info-box bg-light mb-3">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-file-invoice-dollar"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">RFC</span>
                                                    <span id="rfcSucursal" class="info-box-number">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white">
                            <small class="text-muted">Última actualización: <span id="fechaActualizacion">-</span></small>
                        </div>
                    </div>

                    <!-- MODAL EDITAR -->
                    <div class="modal fade" id="modalEditar">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white py-2">
                                    <h5 class="modal-title"><i class="fas fa-edit mr-2"></i> Editar Sucursal</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <form id="formEditar" enctype="multipart/form-data" method="post">

                                        <input type="hidden" id="rutaLogoActual" name="rutaLogoActual">

                                        <!-- Sección del Logo -->
                                        <div class="card card-secondary mb-3">
                                            <div class="card-header py-2">
                                                <h6 class="card-title mb-0">Logo de la Sucursal</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4 text-center">
                                                        <label>Logo Actual</label>
                                                        <img id="logoPreview" src="" alt="Logo Actual"
                                                            class="img-fluid img-thumbnail mb-2" style="max-width: 150px;">
                                                        <p class="small text-muted mb-0">Logo actual</p>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Seleccionar Nuevo Logo</label>
                                                            <input type="file" id="logoInput" name="logo" class="form-control" accept=".jpg, .jpeg, .png">
                                                            <small class="text-muted">Si no seleccionas un logo, se mantendrá el actual.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información de la Sucursal -->
                                        <div class="card card-secondary mb-3">
                                            <div class="card-header py-2">
                                                <h6 class="card-title mb-0">Información Básica</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Nombre de la Sucursal</label>
                                                        <input type="text" name="nombre" id="nombreInput" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Teléfono</label>
                                                        <input type="text" name="telefono" id="telefonoInput" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Dirección</label>
                                                    <input type="text" name="direccion" id="direccionInput" class="form-control">
                                                </div>

                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Ciudad</label>
                                                        <input type="text" name="ciudad" id="ciudadInput" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Correo Electrónico</label>
                                                        <input type="email" name="correo" id="correoInput" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>RFC</label>
                                                        <input type="text" name="rfc" id="rfcInput" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                <i class="fas fa-times mr-1"></i> Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save mr-1"></i> Guardar Cambios
                                            </button>
                                        </div>
                                    </form>
                                </div>
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

</body>

</html>