<?php
require_once __DIR__ . '/../../controllers/clienteController.php';

$controller = new clienteController();
$router->add('GET', '/cliente', [$controller, 'getAll']);

$router->add('POST', '/cliente', [$controller, 'createNewClient']);

$router->add('PUT', '/cliente', [$controller, 'updateCliente']);

$router->add('GET', '/cliente/{id}', [$controller, 'getClienteById']);

$router->add('DELETE', '/cliente/{id}', [$controller, 'deleteCliente']);
