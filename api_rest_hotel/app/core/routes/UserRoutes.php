<?php
require_once __DIR__ . '/../../controllers/UserController.php';

$controller = new UserController();

$router->add('GET', '/usuarios', [$controller, 'getAll']);

$router->add('POST', '/usuarios', [$controller, 'createUser']);

$router->add('GET', '/usuarios/{id}', [$controller, 'getUsuario']);

$router->add('PUT', '/usuarios/{id}', [$controller, 'updateUser']);

$router->add('DELETE', '/usuarios/{id}', [$controller, 'deleteUsuario']);

$router->add('POST', '/login', [$controller, 'getAccess']);
