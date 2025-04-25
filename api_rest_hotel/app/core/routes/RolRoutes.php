<?php
require_once __DIR__ . '/../../controllers/RolController.php';

$controller = new RolController();

$router->add('GET', '/roles', [$controller, 'getRoles']);

$router->add('POST', '/roles', [$controller, 'createRol']);

$router->add('GET', '/roles/{id}', [$controller, 'getRol']);

$router->add('PUT', '/roles/{id}', [$controller, 'updateRol']);

$router->add('DELETE', '/roles/{id}', [$controller, 'deleteRol']);
