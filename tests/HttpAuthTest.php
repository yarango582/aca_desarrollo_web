<?php
namespace Tests;

/**
 * Pruebas de integración: hacen peticiones HTTP reales al servidor Apache
 * del contenedor (localhost:80) manteniendo cookies como un navegador.
 */
class HttpAuthTest extends TestCase
{
    private string $base = 'http://localhost';
    private string $jar;

    protected function setUp(): void
    {
        $this->jar = tempnam(sys_get_temp_dir(), 'cookies');
    }

    protected function tearDown(): void
    {
        @unlink($this->jar);
    }

    /** @return array{0:int,1:string,2:string} [status, location, body] */
    private function request(string $method, string $path, array $data = []): array
    {
        $ch = curl_init($this->base . $path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_COOKIEJAR      => $this->jar,
            CURLOPT_COOKIEFILE     => $this->jar,
            CURLOPT_FOLLOWLOCATION => false,
        ]);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        $raw = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $headers = substr($raw, 0, $headerSize);
        $body = substr($raw, $headerSize);
        preg_match('/^Location:\s*(.+)$/mi', $headers, $m);
        return [$status, trim($m[1] ?? ''), $body];
    }

    private function csrfDeLogin(): string
    {
        [, , $body] = $this->request('GET', '/login');
        preg_match('/name="_csrf" value="([a-f0-9]+)"/', $body, $m);
        return $m[1] ?? '';
    }

    private function iniciarSesion(): void
    {
        $this->request('POST', '/login', [
            'email' => 'admin@lunaazul.com', 'password' => 'admin123', '_csrf' => $this->csrfDeLogin(),
        ]);
    }

    public function testRutaProtegidaSinSesionRedirigeALogin(): void
    {
        [$status, $location] = $this->request('GET', '/');
        $this->assertSame(302, $status);
        $this->assertSame('/login', $location);
    }

    public function testPaginaDeLoginMuestraFormulario(): void
    {
        [$status, , $body] = $this->request('GET', '/login');
        $this->assertSame(200, $status);
        $this->assertContains('name="email"', $body);
        $this->assertContains('name="password"', $body);
        $this->assertContains('name="_csrf"', $body);
    }

    public function testLoginCorrectoRedirigeAlInicio(): void
    {
        [$status, $location] = $this->request('POST', '/login', [
            'email' => 'admin@lunaazul.com', 'password' => 'admin123', '_csrf' => $this->csrfDeLogin(),
        ]);
        $this->assertSame(302, $status);
        $this->assertSame('/', $location);

        [$status, , $body] = $this->request('GET', '/');
        $this->assertSame(200, $status);
        $this->assertContains('Administrador', $body);
    }

    public function testLoginConContrasenaIncorrectaMuestraError(): void
    {
        [$status, , $body] = $this->request('POST', '/login', [
            'email' => 'admin@lunaazul.com', 'password' => 'mala', '_csrf' => $this->csrfDeLogin(),
        ]);
        $this->assertSame(200, $status);
        $this->assertContains('Credenciales incorrectas', $body);
        $this->assertContains('name="email"', $body);
    }

    public function testLoginSinTokenCsrfEsRechazado(): void
    {
        $this->request('GET', '/login');
        [$status] = $this->request('POST', '/login', [
            'email' => 'admin@lunaazul.com', 'password' => 'admin123',
        ]);
        $this->assertSame(403, $status);
    }

    public function testLogoutCierraLaSesion(): void
    {
        $this->iniciarSesion();
        [, , $body] = $this->request('GET', '/');
        preg_match('/name="_csrf" value="([a-f0-9]+)"/', $body, $m);

        [$status, $location] = $this->request('POST', '/logout', ['_csrf' => $m[1] ?? '']);
        $this->assertSame(302, $status);
        $this->assertSame('/login', $location);

        [$status] = $this->request('GET', '/');
        $this->assertSame(302, $status);
    }

    public function testUsuarioAutenticadoQueVaALoginEsRedirigidoAlInicio(): void
    {
        $this->iniciarSesion();
        [$status, $location] = $this->request('GET', '/login');
        $this->assertSame(302, $status);
        $this->assertSame('/', $location);
    }
}
