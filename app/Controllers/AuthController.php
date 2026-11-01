<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Helpers\Auth;
use App\Helpers\Csrf;
use App\Models\Usuario;

class AuthController extends Controller
{
    /** Las acciones de autenticación son públicas. */
    protected bool $requiereAuth = false;

    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
        }
        $this->view('auth/login', ['titulo' => 'Iniciar sesión'], 'auth');
    }

    public function login(): void
    {
        $this->exigirCsrf();

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $user = (new Usuario())->verificarCredenciales($email, $password);
        if ($user === null) {
            $this->view('auth/login', [
                'titulo' => 'Iniciar sesión',
                'error'  => 'Credenciales incorrectas',
                'email'  => $email,
            ], 'auth');
            return;
        }

        Auth::login($user);
        $this->redirect('/');
    }

    public function logout(): void
    {
        $this->exigirCsrf();
        Auth::logout();
        $this->redirect('/login');
    }

    private function exigirCsrf(): void
    {
        if (!Csrf::verificar($_POST['_csrf'] ?? null)) {
            http_response_code(403);
            echo 'Token CSRF inválido';
            exit;
        }
    }
}
