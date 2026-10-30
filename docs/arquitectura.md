# Arquitectura

## Decisiones

| Tema              | Decisión                                   | Motivo                                                       |
|-------------------|--------------------------------------------|--------------------------------------------------------------|
| Lenguaje backend  | PHP 8.2                                    | Lo pide el enunciado                                         |
| Patrón            | MVC implementado a mano (sin framework)    | La asignatura evalúa que se entienda el patrón               |
| Base de datos     | MySQL 8 con PDO y consultas preparadas     | Lo pide el enunciado; PDO evita inyección SQL                |
| Servidor          | Apache (imagen oficial php:8.2-apache)     | Equivalente a XAMPP, pero reproducible para todo el equipo   |
| Entorno           | Docker Compose (app + db + phpMyAdmin)     | Que los tres integrantes tengan exactamente el mismo entorno |
| Frontend          | HTML5, CSS3 y JavaScript sin librerías     | Es lo que evalúa la rúbrica                                  |
| AJAX              | `fetch()` nativo contra rutas `/api/...`   | Sin dependencias                                             |

## Capas

```
Navegador  ->  public/index.php (front controller)
                  -> App\Core\Router      lee routes/web.php
                      -> Controlador      app/Controllers/*
                          -> Modelo       app/Models/*  ->  MySQL (PDO)
                          -> Vista        app/Views/*   ->  HTML al navegador
```

- **Core** (`app/Core`): código compartido. Router, Controller base, Model base, Database.
- **Controllers**: reciben la petición, validan, llaman a modelos y eligen la vista.
- **Models**: una clase por tabla. Solo SQL y reglas de negocio sobre datos.
- **Views**: solo HTML con PHP mínimo para imprimir datos. Siempre escapar con `htmlspecialchars`.
- **Helpers**: funciones sueltas reutilizables (validar, formatear, sesión, CSRF).

## Modelo de datos

```
usuarios            huespedes               tipos_habitacion
  id                  id                      id
  nombre              nombre_completo         nombre
  email               tipo_documento          capacidad
  password_hash       numero_documento        precio_noche
  rol                 direccion
                      telefono              habitaciones
reservas              celular                 id
  id                  edad                    numero
  huesped_id    --->  email                   tipo_habitacion_id ---> tipos_habitacion
  habitacion_id --->  contacto_emergencia     estado
  fecha_checkin       parentesco_emergencia
  fecha_checkout
  numero_huespedes
  estado
```

Regla clave: dos reservas `activas` de la misma habitación no pueden tener fechas que se crucen.
Se valida en el modelo `Reserva` antes de insertar.

## Convenciones

- Nombres de clases en `PascalCase`, archivos con el mismo nombre que la clase.
- Namespaces: `App\Core`, `App\Controllers`, `App\Models`.
- Rutas en minúscula y en español: `/huespedes`, `/reservas/{id}/cancelar`.
- Tablas y columnas en `snake_case` y en español.
- Formularios siempre por `POST`; nunca modificar datos con `GET`.
