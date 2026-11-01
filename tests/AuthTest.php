<?php
namespace Tests;

use App\Helpers\Auth;

class AuthTest extends TestCase
{
    protected function setUp(): void { $_SESSION = []; }

    public function testSinSesionNoHayUsuarioAutenticado(): void
    {
        $this->assertFalse(Auth::check());
        $this->assertNull(Auth::user());
    }

    public function testLoginGuardaUsuarioEnSesion(): void
    {
        Auth::login(['id' => 1, 'nombre' => 'Admin', 'email' => 'a@a.com', 'rol' => 'admin']);
        $this->assertTrue(Auth::check());
        $this->assertSame('Admin', Auth::user()['nombre']);
    }

    public function testLogoutLimpiaLaSesion(): void
    {
        Auth::login(['id' => 1, 'nombre' => 'Admin', 'email' => 'a@a.com', 'rol' => 'admin']);
        Auth::logout();
        $this->assertFalse(Auth::check());
    }
}
