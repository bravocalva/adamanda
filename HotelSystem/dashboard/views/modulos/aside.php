<!-- Main Sidebar Container -->
<?php global $activePage; ?>
<style>
    .my-sidebar {
        background-color: rgb(119, 95, 29) !important;
        /* Morado oscuro */
        color: white !important;
        /* Texto en blanco */
    }
</style>

<head>
</head>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/HotelSystem_dashboard/index.php" class="brand-link">
        <img src="../assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text ">Hotel Adamanda</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Link principal -->
                <li class="nav-item">
                    <?php if ($activePage == 'principal'): ?>
                        <span class="nav-link active">
                            <i class="nav-icon fas fa-hotel"></i>
                            <p>Principal</p>
                        </span>
                    <?php else: ?>
                        <a href="principal.php" class="nav-link">
                            <i class="nav-icon fas fa-hotel"></i>
                            <p>Principal</p>
                        </a>
                    <?php endif; ?>
                </li>

                <!-- Link menu recepcion -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-concierge-bell"></i>
                        <p>
                            Recepcion
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <?php if ($activePage == 'reservar'): ?>
                                <a class="nav-link active">
                                    <i class="nav-icon fas fa-bookmark"></i>
                                    <p>Reservar</p>
                                </a>
                            <?php else: ?>
                                <a href="reservar.php" class="nav-link">
                                    <i class="nav-icon fas fa-bookmark"></i>
                                    <p>Reservar</p>
                                </a>
                            <?php endif; ?>
                        </li>


                        <li class="nav-item">
                            <?php if ($activePage == 'clientes'): ?>
                                <a class="nav-link active">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Clientes</p>
                                </a>
                            <?php else: ?>
                                <a href="clientes.php" class="nav-link">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Clientes</p>
                                </a>
                            <?php endif; ?>
                        </li>
                        <li class="nav-item">
                            <?php if ($activePage == 'reservas'): ?>
                                <a class="nav-link active">
                                    <i class="nav-icon fas fa-concierge-bell"></i>
                                    <p>Reporte de reservas</p>
                                </a>
                            <?php else: ?>
                                <a href="reporte_reservas.php" class="nav-link">
                                    <i class="nav-icon fas fa-concierge-bell"></i>
                                    <p>Reporte de reservas</p>
                                </a>
                            <?php endif; ?>
                        </li>

                        <li class="nav-item">
                            <?php if ($activePage == 'agenda'): ?>
                                <a class="nav-link active">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                    <p>Agenda</p>
                                </a>
                            <?php else: ?>
                                <a href="agenda.php" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                    <p>Agenda</p>
                                </a>
                            <?php endif; ?>
                        </li>

                        <li class="nav-item">
                            <?php if ($activePage == 'verificar_hab'): ?>
                                <a class="nav-link active">
                                    <i class="nav-icon fas fa-door-open"></i>
                                    <p>Verificar Habitaciones</p>
                                </a>
                            <?php else: ?>
                                <a href="verificar_hab.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-open"></i>
                                    <p>Verificar Habitaciones</p>
                                </a>
                            <?php endif; ?>
                        </li>

                        <li class="nav-item">
                            <?php if ($activePage == 'facturas'): ?>
                                <a class="nav-link active">
                                <i class="nav-icon far fa-file-alt"></i>
                                    <p>Reporte de facturas</p>
                                </a>
                            <?php else: ?>
                                <a href="reporteFacturas.php" class="nav-link">
                                <i class="nav-icon far fa-file-alt"></i>
                                    <p>Reporte de facturas</p>
                                </a>
                            <?php endif; ?>
                        </li>



                    </ul>
                </li>

                <!-- Link menu mantenimiento -->
                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class=" nav-icon fas fa-hammer"></i>
                        <p>
                            Mantenimiento
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <!-- Link articulos -->
                        <li class="nav-item">
                            <?php if ($activePage == 'habitaciones'): ?>
                                <a class="nav-link active">
                                    <i class="nav-icon fas fa-door-open"></i>
                                    <p>Habitaciones</p>
                                </a>
                            <?php else: ?>
                                <a href="habitaciones.php" class="nav-link">
                                    <i class="nav-icon fas fa-door-open"></i>
                                    <p>Habitaciones</p>
                                </a>
                            <?php endif; ?>
                        </li>


                        <li class="nav-item">
                            <?php if ($activePage == 'inventario'): ?>
                                <a class="nav-link active">
                                    <i class=" nav-icon fas fa-h-square"></i>
                                    <p>Inventario</p>
                                </a>
                            <?php else: ?>
                                <a href="inventarios.php" class="nav-link">
                                    <i class=" nav-icon fas fa-h-square"></i>
                                    <p>Inventario</p>
                                </a>
                            <?php endif; ?>
                        </li>


                    </ul>

                </li>


                <!-- Link Usuarios -->
                <li class="nav-item">
                    <?php if ($activePage == 'usuarios'): ?>
                        <a class="nav-link active">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Usuarios</p>
                        </a>
                    <?php else: ?>
                        <a href="usuarios.php" class="nav-link">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Usuarios</p>
                        </a>
                    <?php endif; ?>
                </li>

                <!-- Link Sucursal -->
                <li class="nav-item">
                    <?php if ($activePage == 'sucursal'): ?>
                        <a class="nav-link active">
                            <i class=" nav-icon fas fa-store-alt"></i>
                            <p>Datos sucursal</p>
                        </a>
                    <?php else: ?>
                        <a href="sucursal.php" class="nav-link">
                            <i class=" nav-icon fas fa-store-alt"></i>
                            <p>Datos sucursal</p>
                        </a>
                    <?php endif; ?>
                </li>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>