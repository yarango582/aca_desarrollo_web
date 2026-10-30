<?php
namespace App\Core;

/**
 * Router mínimo. Las rutas se registran en routes/web.php así:
 *   $router->get('/huespedes', [HuespedController::class, 'index']);
 *   $router->post('/huespedes', [HuespedController::class, 'store']);
 * Soporta parámetros: '/huespedes/{id}/editar'
 */
class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void  { $this->routes['GET'][$path] = $handler; }
    public function post(string $path, array $handler): void { $this->routes['POST'][$path] = $handler; }

    public function dispatch(string $method, string $uri): void
    {
        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        foreach ($this->routes[$method] ?? [] as $path => [$class, $action]) {
            $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path) . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                (new $class())->$action(...array_values($params));
                return;
            }
        }

        http_response_code(404);
        echo '404 - Página no encontrada';
    }
}
