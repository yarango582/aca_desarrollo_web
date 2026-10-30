-- Esquema inicial. Se ejecuta automáticamente la primera vez que arranca el
-- contenedor de MySQL. Si se cambia, borrar docker/mysql/data y reiniciar.
-- En XAMPP: importar este archivo desde phpMyAdmin.

CREATE DATABASE IF NOT EXISTS hotel_luna_azul CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hotel_luna_azul;

-- Usuarios del sistema (recepcionistas / administrador) para autenticación
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'recepcion') NOT NULL DEFAULT 'recepcion',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Huéspedes (campos tomados del enunciado)
CREATE TABLE IF NOT EXISTS huespedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    tipo_documento ENUM('CC', 'CE', 'TI', 'PASAPORTE') NOT NULL,
    numero_documento VARCHAR(30) NOT NULL UNIQUE,
    direccion VARCHAR(200),
    telefono VARCHAR(20),
    celular VARCHAR(20),
    edad TINYINT UNSIGNED,
    email VARCHAR(150),
    contacto_emergencia VARCHAR(150),
    parentesco_emergencia VARCHAR(50),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tipos de habitación (sencilla, doble, suite...)
CREATE TABLE IF NOT EXISTS tipos_habitacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    capacidad TINYINT UNSIGNED NOT NULL,
    precio_noche DECIMAL(10,2) NOT NULL
);

-- Habitaciones físicas del hotel
CREATE TABLE IF NOT EXISTS habitaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10) NOT NULL UNIQUE,
    tipo_habitacion_id INT NOT NULL,
    estado ENUM('disponible', 'mantenimiento') NOT NULL DEFAULT 'disponible',
    FOREIGN KEY (tipo_habitacion_id) REFERENCES tipos_habitacion(id)
);

-- Reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    huesped_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    fecha_checkin DATE NOT NULL,
    fecha_checkout DATE NOT NULL,
    numero_huespedes TINYINT UNSIGNED NOT NULL DEFAULT 1,
    estado ENUM('activa', 'cancelada', 'finalizada') NOT NULL DEFAULT 'activa',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (huesped_id) REFERENCES huespedes(id),
    FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id),
    CONSTRAINT chk_fechas CHECK (fecha_checkout > fecha_checkin)
);

-- Índice para consultar disponibilidad por rango de fechas rápidamente
CREATE INDEX idx_reservas_habitacion_fechas ON reservas (habitacion_id, fecha_checkin, fecha_checkout);
