<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav ">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link"></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link"></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Menú desplegable del usuario -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i> <!-- Ícono de usuario -->            
                <span><?php echo $_SESSION['user']['nombre']; ?></span> <!-- Nombre del usuario -->
                <i class="right fas fa-angle-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- Encabezado del menú -->
                <span class="dropdown-header">
                    <i class="fas fa-user mr-2"></i> <!-- Ícono de usuario -->
                    <?php echo $_SESSION['user']['nombre']; ?> <!-- Nombre del usuario -->
                </span>
                <div class="dropdown-divider"></div>

                <!-- Información del usuario -->
                <a class="dropdown-item">
                    <i class="fas fa-id-card mr-2"></i> <!-- Ícono de ID -->
                    ID: <?php echo $_SESSION['user']['id_usuario']; ?> <!-- ID del usuario -->
                </a>

                <a class="dropdown-item">
                <i class="fa fa-envelope mr-2"></i><!-- Ícono de email -->
                     Email: <?php echo $_SESSION['user']['email']; ?> <!-- ID del email -->
                </a>

                <a class="dropdown-item">
                    <i class="fas fa-user-tag mr-2"></i> <!-- Ícono de rol -->
                    Rol: <?php echo $_SESSION['user']['rol']; ?> <!-- Rol del usuario -->
                </a>
                <div class="dropdown-divider"></div>

                <!-- Botón de Logout -->
                <a href="logout.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> <!-- Ícono de logout -->
                    Cerrar sesión
                </a>
            </div>
        </li>

    </ul>
</nav>
<!-- /.navbar -->