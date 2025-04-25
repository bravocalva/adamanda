<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hotel Adamanda | Log in</title>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="shortcut icon" href="views/assets/dist/img/favicon.ico" type="image/x-icon">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="views/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="views/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="views/assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">

  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a class="h1"><b>HOTEL</b> ADAMANDA</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Ingresa tus credenciales para iniciar sesion</p>

        <?php if (isset($error)): ?>
          <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>


        <form method="POST">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="email" id="email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Contraseña" name="password" id="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col">
              <button type="submit" class="btn btn-primary btn-block">Iniciar Sesion</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="views/assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="views/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="views/assets/dist/js/adminlte.min.js"></script>
</body>

</html>

<?php
session_start(); // Inicia la sesión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Llamar a la API para validar las credenciales
  $data = json_encode(['email' => $email, 'password' => $password]);
  $options = [
    'http' => [
      'method' => 'POST',
      'header' => 'Content-Type: application/json',
      'content' => $data,
    ],
  ];
  $context = stream_context_create($options);
  $response = file_get_contents('http://localhost/adamanda/api_rest_hotel/public/login', false, $context);
  $result = json_decode($response, true);

  if ($result['statusExec']) {
    // Credenciales válidas: almacenar datos en la sesión
    $_SESSION['user'] = $result['datos']; // Almacena los datos del usuario

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Has iniciado sesión correctamente.',
                    icon: 'success',
                    showConfirmButton: false,
                    heightAuto: false,
                    timer: 1500
                }).then(function() {
                    window.location = 'index.php'; // Redirige al dashboard
                });
            });
          </script>";
    //header('Location: index.php'); // Redirigir al dashboard
    exit();
  } else {
    // Credenciales inválidas: mostrar mensaje de error
    $error = $result['msg'];
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            document.addEventListener('DOMContentLoaded', function() {
                  Swal.fire({
                  icon: 'error',
                  title: 'Credenciales incorrectas',
                  showConfirmButton: false,
                  heightAuto: false,
                  timer: 1500
                });
            });
          </script>";
  }
}
?>