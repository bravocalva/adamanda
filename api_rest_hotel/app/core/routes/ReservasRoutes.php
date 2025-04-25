<?php
require_once __DIR__ . '/../../controllers/reservasController.php';

    $controller = new reservasController();

    $router->add('GET', '/reservas', [$controller, 'getAllReservas']);

    $router->add('POST', '/hospedar_inm', [$controller, 'crearHospedajeInmediato']);

    $router->add('POST', '/generarReserva', [$controller, 'crearReservacion']);

    $router->add('POST', '/check_out_reservacion', [$controller, 'terminarReservacion']);

    $router->add('POST', '/check_in_reservacion', [$controller, 'CheckIn']);

    $router->add('GET', '/reservas/{id}', [$controller, 'getReservaById']);

    $router->add('GET', '/reservasAnual', [$controller, 'reservasAnual']);

    $router->add('POST', '/reservasMensual', [$controller, 'reservasPorMes']);

    $router->add('PUT', '/cancelar/{id}', [$controller, 'cancelarReservacion']);
    
    