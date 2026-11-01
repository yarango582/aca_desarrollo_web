<?php
namespace Tests;

use App\Helpers\Csrf;

class CsrfTest extends TestCase
{
    protected function setUp(): void { $_SESSION = []; }

    public function testElTokenSeMantieneDuranteLaSesion(): void
    {
        $this->assertSame(Csrf::token(), Csrf::token());
        $this->assertTrue(strlen(Csrf::token()) >= 32);
    }

    public function testVerificaTokenCorrecto(): void
    {
        $this->assertTrue(Csrf::verificar(Csrf::token()));
    }

    public function testRechazaTokenIncorrectoONulo(): void
    {
        Csrf::token();
        $this->assertFalse(Csrf::verificar('falso'));
        $this->assertFalse(Csrf::verificar(null));
    }

    public function testCampoOcultoContieneElToken(): void
    {
        $this->assertContains('name="_csrf"', Csrf::campo());
        $this->assertContains(Csrf::token(), Csrf::campo());
    }
}
