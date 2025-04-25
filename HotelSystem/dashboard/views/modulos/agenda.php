<?php
session_start();
$activePage = 'agenda';

// Verificar sesión activa
if (!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hotel Adamanda | Agenda</title>
    <link rel="shortcut icon" href="../assets/dist/img/favicon.ico" type="image/x-icon">

    <!-- Google Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">

    <!-- FullCalendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper d-flex flex-column vh-100">

        <?php include "aside.php"; ?>
        <?php include "navbar.php"; ?>

        <!-- Contenido principal -->
        <div class="content-wrapper flex-grow-1 overflow-auto">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Agenda</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                                <li class="breadcrumb-item active">Agenda</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de calendario -->
            <div class="content">
                <div class="card card-primary">
                    <div class="card-body">
                        <label for="monthPicker"><strong>Seleccionar mes:</strong></label>
                        <input type="month" id="monthPicker" class="form-control mb-3">
                        <div id="calendar-wrapper">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>
    </div>

    <!-- Scripts -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const monthPicker = document.getElementById('monthPicker'); // Selector de mes y año

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es', // Establece el idioma en español
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                events: [] // Los eventos se cargarán aquí
            });

            calendar.render();

            // Función para cargar las reservas desde la API
            async function loadEvents(monthYear) {
                const [year, month] = monthYear.split('-');


                const body = JSON.stringify({
                    mes: month,
                    anio: year
                });

                console.log("Datos enviados para la api: ", body);

                // Envía la solicitud POST a la API
                const response = await fetch('http://localhost/adamanda/api_rest_hotel/reservasMensual', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: body
                });

                if (!response.ok) {
                    console.error('Error al cargar eventos');
                    return;
                }

                const data = await response.json();
                calendar.removeAllEvents();

                data.forEach(event => {
                    // Colores según el estado
                    let color = '';
                    switch (event.status_reservacion) {
                        case 'RESERVADO':
                            color = '#ffc107'; 
                         
                            break;
                        case 'ACTIVO':
                            color = '#28a745'; // Verde
                            break;
                        case 'TERMINADO':
                            color = '#6c757d'; // Gris
                            break;

                        case 'CANCELADO':
                            color ='#8B0000';
                            break;
                            
                        default:
                        color = '#007bff'; // Azul
                            break;
                    }


                    calendar.addEvent({
                        id: event.id_reservacion,
                        color: color,
                        title: event.titulo,
                        start: event.fecha_entrada,
                        end: event.fecha_salida,
                        extendedProps: {
                            habitacion: event.habitacion,
                            status: event.status_reservacion
                        }
                    });
                });
                calendar.gotoDate(`${year}-${month}-01`);
            }

            // Cargar el mes actual por defecto
            const today = new Date();
            const defaultMonth = today.toISOString().slice(0, 7);
            monthPicker.value = defaultMonth;
            loadEvents(defaultMonth);

            monthPicker.addEventListener('change', (e) => {
                loadEvents(e.target.value);
            });
        });
    </script>
</body>

</html>