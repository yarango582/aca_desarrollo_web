<?php
/**
 * Definición de rutas. Formato: $router->get('/ruta', [Controlador::class, 'metodo']);
 * Ir descomentando a medida que se creen los controladores.
 */
use App\Controllers\HomeController;

$router->get('/', [HomeController::class, 'index']);

// --- Autenticación ---
// $router->get('/login',  [AuthController::class, 'showLogin']);
// $router->post('/login', [AuthController::class, 'login']);
// $router->get('/logout', [AuthController::class, 'logout']);

// --- Huéspedes (CRUD) ---
// $router->get('/huespedes',                [HuespedController::class, 'index']);
// $router->get('/huespedes/crear',          [HuespedController::class, 'create']);
// $router->post('/huespedes',               [HuespedController::class, 'store']);
// $router->get('/huespedes/{id}/editar',    [HuespedController::class, 'edit']);
// $router->post('/huespedes/{id}',          [HuespedController::class, 'update']);
// $router->post('/huespedes/{id}/eliminar', [HuespedController::class, 'destroy']);

// --- Reservas ---
// $router->get('/reservas',                 [ReservaController::class, 'index']);
// $router->get('/reservas/crear',           [ReservaController::class, 'create']);
// $router->post('/reservas',                [ReservaController::class, 'store']);
// $router->get('/reservas/{id}/editar',     [ReservaController::class, 'edit']);
// $router->post('/reservas/{id}',           [ReservaController::class, 'update']);
// $router->post('/reservas/{id}/cancelar',  [ReservaController::class, 'cancel']);

// --- API para AJAX ---
// $router->get('/api/habitaciones/disponibles', [HabitacionController::class, 'disponibles']);
