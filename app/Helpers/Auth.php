<?php
namespace App\Helpers;

/**
 * Manejo del usuario autenticado en sesión.
 * Requiere que session_start() ya se haya llamado (lo hace public/index.php).
 */
class Auth
{
    private const KEY = 'usuario';

    public static function login(array $user): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true); // evita fijación de sesión
        }
        $_SESSION[self::KEY] = $user;
    }

    public static function logout(): void
    {
        unset($_SESSION[self::KEY]);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function check(): bool
    {
        return isset($_SESSION[self::KEY]);
    }

    public static function user(): ?array
    {
        return $_SESSION[self::KEY] ?? null;
    }

    /** Redirige a /login si no hay sesión. Llamar al inicio de rutas protegidas. */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }
}
