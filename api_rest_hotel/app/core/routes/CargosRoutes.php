<?php
require_once __DIR__ . '/../../controllers/cargosController.php';

    $controller = new cargosController();

    
    $router->add('GET', '/cargos/{id}', [$controller, 'getCargosById']);

    $router->add('DELETE', '/cargos/{id}', [$controller, 'deleteCargo']);

    $router->add('POST', '/cargos', [$controller, 'insertCargoAdicional']);