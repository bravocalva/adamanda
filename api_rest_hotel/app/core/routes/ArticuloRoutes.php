<?php
require_once __DIR__ . '/../../controllers/articuloController.php';

    $controller = new articuloController();

    $router->add('GET', '/articulo', [$controller, 'getAllArticulos']);

    $router->add('POST', '/articulo', [$controller, 'InsertArticulo']);

    $router->add('GET', '/articulo/{id}', [$controller, 'getArticuloById']);

    $router->add('DELETE', '/articulo/{id}', [$controller, 'DeleteArticulo']);

    $router->add('PUT', '/articulo/{id}', [$controller, 'UpdateArticulo']);