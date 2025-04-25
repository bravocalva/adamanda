<?php

class Router {
    private $routes = [];

    public function add($method, $path, $callback) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function route($url, $method) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($this->convertPathToRegex($route['path']), $url, $params)) {
                array_shift($params);
                return call_user_func_array($route['callback'], $params);
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Ruta no encontrada', 'URI' => $url, 'METHOD' => $method]);
    }

    private function convertPathToRegex($path) {
        $pattern = preg_replace('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', '(\d+)', $path);
        return '#^' . $pattern . '$#';
    }
}
