<?php
require_once __DIR__ . '/../../controllers/facturaController.php';

    $controller = new facturaController();

    $router->add('GET', '/gananciaMensual', [$controller, 'getGananciaMensual']);
    
    $router->add('GET', '/facturas', [$controller, 'getAll']);

    $router->add('GET', '/factura_detalle/{id}', [$controller, 'getDetallesFactura']);
