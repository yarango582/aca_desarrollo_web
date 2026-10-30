<?php
/**
 * Front controller: TODAS las peticiones entran por aquí (ver .htaccess).
 */
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

session_start();

// Autoload simple: App\Core\Router  ->  app/Core/Router.php
spl_autoload_register(function (string $class): void {
    $file = BASE_PATH . '/' . str_replace(['App\\', '\\'], ['app/', '/'], $class) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$router = new App\Core\Router();
require BASE_PATH . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
