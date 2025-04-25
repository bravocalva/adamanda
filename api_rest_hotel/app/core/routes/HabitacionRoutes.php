<?php
require_once __DIR__ . '/../../controllers/HabitacionController.php';

    $controller = new HabitacionController();

    $router->add('GET', '/habitaciones', [$controller, 'getAll']);

    $router->add('POST', '/habitaciones', [$controller, 'createHabitacion']);

    $router->add('PUT', '/habitaciones', [$controller, 'updateHabitacion']);

    $router->add('GET', '/habitaciones_info', [$controller, 'getAllInfo']);

    $router->add('POST', '/habitaciones_fecha', [$controller, 'GetHabitacionByDate']);

    $router->add('GET', '/habitaciones_ocupadas', [$controller, 'getOcupadas']);

    $router->add('GET', '/habitacion_mas_reservada', [$controller, 'getHabitacionMasReservado']);
    
    $router->add('GET', '/hab_disponible', [$controller, 'getTotalDisponible']);

    $router->add('GET', '/hab_ocupado', [$controller, 'getTotalOcupado']);

    $router->add('GET', '/hab_reservados', [$controller, 'getTotalReservados']);

    $router->add('GET', '/hab_limpieza', [$controller, 'getTotalLimpieza']);

    $router->add('GET', '/all_hab_limpieza', [$controller, 'getHabitacionLimpieza']);

    $router->add('GET', '/all_hab_reservado', [$controller, 'getHabitacionReservado']);

    $router->add('POST', '/cambiar_disponible/{id}', [$controller, 'cambiarDisponible']);

    $router->add('GET', '/habitaciones_info/{id}', [$controller, 'getAllInfoById']);

    $router->add('GET', '/habitaciones/{id}', [$controller, 'getHabitacionById']);

    $router->add('DELETE', '/habitaciones/{id}', [$controller, 'deleteHabitacion']);

    