<?php
/**
 * Configuración global. Los valores vienen del archivo .env (vía Docker)
 * y aquí se les da un valor por defecto para trabajar sin Docker (XAMPP).
 */
return [
    'app' => [
        'name' => getenv('APP_NAME') ?: 'Hotel Luna Azul',
        'env'  => getenv('APP_ENV') ?: 'local',
    ],
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'hotel_luna_azul',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
];
