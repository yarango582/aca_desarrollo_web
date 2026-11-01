<?php
namespace Tests;

/**
 * Clase base para pruebas. Los métodos que empiecen por "test" se ejecutan.
 * Ejecutar con: docker compose exec app php tests/run.php
 */
abstract class TestCase
{
    protected function setUp(): void {}
    protected function tearDown(): void {}

    public function runAll(): array
    {
        $results = [];
        foreach (get_class_methods($this) as $method) {
            if (!str_starts_with($method, 'test')) continue;
            try {
                $this->setUp();
                $this->$method();
                $this->tearDown();
                $results[$method] = null;
            } catch (\Throwable $e) {
                $this->tearDown();
                $results[$method] = $e->getMessage() . " ({$e->getFile()}:{$e->getLine()})";
            }
        }
        return $results;
    }

    protected function assertTrue(mixed $v, string $msg = ''): void
    {
        if ($v !== true) throw new \Exception($msg ?: 'Se esperaba true, se obtuvo ' . var_export($v, true));
    }

    protected function assertFalse(mixed $v, string $msg = ''): void
    {
        if ($v !== false) throw new \Exception($msg ?: 'Se esperaba false, se obtuvo ' . var_export($v, true));
    }

    protected function assertSame(mixed $expected, mixed $actual, string $msg = ''): void
    {
        if ($expected !== $actual) {
            throw new \Exception($msg ?: 'Se esperaba ' . var_export($expected, true) . ', se obtuvo ' . var_export($actual, true));
        }
    }

    protected function assertNull(mixed $v, string $msg = ''): void
    {
        if ($v !== null) throw new \Exception($msg ?: 'Se esperaba null, se obtuvo ' . var_export($v, true));
    }

    protected function assertNotNull(mixed $v, string $msg = ''): void
    {
        if ($v === null) throw new \Exception($msg ?: 'Se esperaba un valor, se obtuvo null');
    }

    protected function assertContains(string $needle, string $haystack, string $msg = ''): void
    {
        if (!str_contains($haystack, $needle)) throw new \Exception($msg ?: "No se encontró '{$needle}' en la respuesta");
    }
}
