<?php
namespace App\Helpers;

/**
 * Token CSRF guardado en sesión. En cada formulario POST se incluye
 * <?= Csrf::campo() ?> y el controlador valida con Csrf::verificar($_POST['_csrf'] ?? null).
 */
class Csrf
{
    private const KEY = '_csrf';

    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function verificar(?string $token): bool
    {
        return is_string($token) && hash_equals(self::token(), $token);
    }

    public static function campo(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::token()) . '">';
    }
}
