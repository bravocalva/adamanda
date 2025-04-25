<?php
require_once __DIR__ . '/../../controllers/TipoHabitacionController.php';

$controller = new tipoHabitacionController();

$router->add('GET', '/tipohabitacion', [$controller, 'getAll']);

$router->add('POST', '/tipohabitacion', [$controller, 'createTipoHabitacion']);

$router->add('PUT', '/tipohabitacion', [$controller, 'updateTipoHabitacion']);

$router->add('GET', '/tipohabitacion/{id}', [$controller, 'getTipoHabitacionById']);

$router->add('DELETE', '/tipohabitacion/{id}', [$controller, 'deleteTipoHabitacion']);