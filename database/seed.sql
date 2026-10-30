-- Datos de prueba. Se cargan después de schema.sql.
USE hotel_luna_azul;

-- Usuario admin / contraseña: admin123  (hash generado con password_hash en PHP)
INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
('Administrador', 'admin@lunaazul.com', '$2y$10$YO1cBE.NSFYX3oj4gb/Pkeopk0sbDIwyNn/rlHfun9JidXI/ESNfS', 'admin');

INSERT INTO tipos_habitacion (nombre, capacidad, precio_noche) VALUES
('Sencilla', 1, 80000),
('Doble', 2, 120000),
('Suite', 4, 250000);

INSERT INTO habitaciones (numero, tipo_habitacion_id) VALUES
('101', 1), ('102', 1), ('201', 2), ('202', 2), ('301', 3);
