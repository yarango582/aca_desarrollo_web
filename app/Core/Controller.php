<?php
namespace App\Core;

use App\Helpers\Auth;

/**
 * Controlador base. Da a los controladores concretos helpers para
 * renderizar vistas, responder JSON (AJAX) y redirigir.
 */
abstract class Controller
{
    /** Si es true (por defecto) se exige sesión iniciada para toda acción del controlador. */
    protected bool $requiereAuth = true;

    public function __construct()
    {
        if ($this->requiereAuth) {
            Auth::requireLogin();
        }
    }

    /** Renderiza app/Views/{$view}.php dentro del layout principal. */
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        ob_start();
        require BASE_PATH . "/app/Views/{$view}.php";
        $content = ob_get_clean();
        require BASE_PATH . "/app/Views/layouts/{$layout}.php";
    }

    /** Respuesta JSON para peticiones AJAX. */
    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }
}
