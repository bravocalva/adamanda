<?php
require_once __DIR__ . '/../../controllers/SucursalController.php';
$controller = new SucursalController();

$router->add('GET', '/sucursal', [$controller, 'getInfo']);

$router->add('PUT', '/sucursal', [$controller, 'updSucursal']);