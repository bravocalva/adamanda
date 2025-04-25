<?php
require_once __DIR__ . '/../../controllers/inventarioController.php';

    $controller = new inventarioController();

    $router->add('POST', '/inventario_hab', [$controller, 'insertArticulo']);

    $router->add('DELETE', '/inventario_hab', [$controller, 'RemoveArticulo']);

    $router->add('POST', '/inventario_faltante', [$controller, 'RemoveVariosArt']);

    $router->add('GET', '/inventario_hab/{id}', [$controller, 'getAllInventarioById']);

