<?php
namespace Tests;

use App\Models\Usuario;

class UsuarioTest extends TestCase
{
    public function testVerificaCredencialesCorrectasDevuelveUsuario(): void
    {
        $user = (new Usuario())->verificarCredenciales('admin@lunaazul.com', 'admin123');
        $this->assertNotNull($user);
        $this->assertSame('admin', $user['rol']);
    }

    public function testUsuarioDevueltoNoIncluyeElHash(): void
    {
        $user = (new Usuario())->verificarCredenciales('admin@lunaazul.com', 'admin123');
        $this->assertFalse(array_key_exists('password_hash', $user));
    }

    public function testContrasenaIncorrectaDevuelveNull(): void
    {
        $this->assertNull((new Usuario())->verificarCredenciales('admin@lunaazul.com', 'otra'));
    }

    public function testEmailInexistenteDevuelveNull(): void
    {
        $this->assertNull((new Usuario())->verificarCredenciales('nadie@lunaazul.com', 'admin123'));
    }
}
