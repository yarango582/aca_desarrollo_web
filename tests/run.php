<?php
/**
 * Runner de pruebas sin dependencias.
 *   docker compose exec app php tests/run.php            -> todas
 *   docker compose exec app php tests/run.php Usuario    -> solo UsuarioTest
 */
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class): void {
    $file = BASE_PATH . '/' . str_replace(['App\\', 'Tests\\', '\\'], ['app/', 'tests/', '/'], $class) . '.php';
    if (is_file($file)) require $file;
});

$filter = $argv[1] ?? '';
$files = glob(BASE_PATH . '/tests/*Test.php');
$total = 0; $failed = 0;

foreach ($files as $file) {
    $class = 'Tests\\' . basename($file, '.php');
    if ($filter && !str_contains($class, $filter)) continue;

    echo "\n{$class}\n";
    foreach ((new $class())->runAll() as $method => $error) {
        $total++;
        if ($error === null) {
            echo "  ✔ {$method}\n";
        } else {
            $failed++;
            echo "  ✘ {$method}\n      {$error}\n";
        }
    }
}

echo "\n{$total} pruebas, {$failed} fallidas\n";
exit($failed > 0 ? 1 : 0);
