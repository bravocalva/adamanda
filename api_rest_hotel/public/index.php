<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Router.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = str_replace('/public', '', $request_uri);

$base_path = BASE_PATH ?? '';
if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

$request_method = $_SERVER['REQUEST_METHOD'];

$router = new Router();

foreach (glob(__DIR__ . '/../app/core/routes/*.php') as $file) {
    require_once $file;
}

$router->route($request_uri, $request_method);
