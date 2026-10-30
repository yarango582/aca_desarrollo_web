# Hotel Luna Azul - Sistema de reservas

Proyecto final de **Desarrollo Web**. Aplicación en PHP con patrón MVC y base de datos MySQL
para gestionar huéspedes y reservas del hotel Luna Azul. El enunciado completo está en
`docs/document.pdf` (no se sube a git; pedirlo al grupo y guardarlo ahí).

**Fecha de entrega: 8 de noviembre de 2026.**

## Cómo empezar (Docker, recomendado)

Requisitos: Docker y Docker Compose instalados. Nada más.

```bash
git clone <url-del-repo>
cd proyecto_final_desarrolloweb
cp .env.example .env
docker compose up --build
```

Cuando termine de arrancar:

| Servicio    | URL                    | Credenciales          |
|-------------|------------------------|-----------------------|
| Aplicación  | http://localhost:8080  | -                     |
| phpMyAdmin  | http://localhost:8081  | root / root123        |
| MySQL       | localhost:3306         | hotel / hotel123      |

La base de datos se crea sola la primera vez con `database/schema.sql` y `database/seed.sql`.
Si cambias el esquema y quieres regenerar todo desde cero:

```bash
docker compose down
rm -rf docker/mysql/data
docker compose up --build
```

Para detener: `Ctrl+C` o `docker compose down`.

## Cómo empezar (XAMPP, alternativa)

1. Clonar el repo dentro de `htdocs`.
2. Configurar el VirtualHost de Apache para que el DocumentRoot sea la carpeta `public/`.
3. Importar `database/schema.sql` y luego `database/seed.sql` desde phpMyAdmin.
4. Ajustar usuario y contraseña en `config/config.php` (por defecto usa `root` sin contraseña).

## Estructura del proyecto

```
.
├── app/
│   ├── Core/          Base del MVC: Router, Controller, Model, Database (no tocar salvo necesidad)
│   ├── Controllers/   Un controlador por módulo (HuespedController, ReservaController...)
│   ├── Models/        Un modelo por tabla (Huesped, Reserva, Habitacion, Usuario)
│   ├── Views/         Vistas PHP/HTML, una carpeta por módulo + layouts/
│   └── Helpers/       Funciones auxiliares (validación, sesión, formato de fechas)
├── config/            Configuración (lee variables del .env)
├── routes/web.php     Todas las rutas de la aplicación
├── public/            DocumentRoot. index.php es el único punto de entrada
│   └── assets/        css/, js/, img/
├── database/          schema.sql, seed.sql y migraciones futuras
├── docker/            Datos persistentes de MySQL (ignorado por git)
├── docs/              Enunciado, arquitectura y material de entrega
├── Dockerfile
├── docker-compose.yml
├── TAREAS.md          Qué falta por hacer y cómo repartirlo
└── README.md
```

## Flujo de una petición

1. El navegador pide `/huespedes/5/editar`.
2. Apache reenvía todo a `public/index.php` (ver `.htaccess`).
3. `index.php` carga el `Router` y las rutas de `routes/web.php`.
4. El Router llama a `HuespedController::edit(5)`.
5. El controlador usa el modelo `Huesped` para consultar MySQL.
6. El controlador renderiza `app/Views/huespedes/editar.php` dentro del layout.

## Cómo añadir un módulo nuevo

1. Crear el modelo en `app/Models/`, extendiendo `App\Core\Model` y definiendo `$table`.
2. Crear el controlador en `app/Controllers/`, extendiendo `App\Core\Controller`.
3. Crear las vistas en `app/Views/<modulo>/`.
4. Registrar las rutas en `routes/web.php`.

Hay ejemplos comentados en `routes/web.php` y un `HomeController` de referencia.

## Reglas de trabajo en equipo

- Rama `main` siempre funcional. Cada persona trabaja en su rama: `feature/huespedes`, `feature/reservas`, etc.
- Antes de hacer merge a `main`, levantar con Docker y comprobar que no se rompió nada.
- No subir el archivo `.env` ni la carpeta `docker/mysql/data`.
- Los cambios al esquema van en `database/schema.sql` y se avisa al grupo.
- Commits en español, cortos y descriptivos: `feat: CRUD de huéspedes`, `fix: validación de fechas`.

## Ver qué hay que hacer

Revisa [TAREAS.md](TAREAS.md).
