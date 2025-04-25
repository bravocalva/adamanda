-- Configuración inicial
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Crear base de datos
-- -----------------------------------------------------
-- CREATE SCHEMA IF NOT EXISTS sistema_hotel DEFAULT CHARACTER SET utf8mb4;
-- USE sistema_hotel;

-- -----------------------------------------------------
-- Tabla Roles
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS rol (
  id_rol INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL UNIQUE,  -- ej. 'Cliente', 'Empleado', 'Administrador'
  descripcion VARCHAR(150),
  PRIMARY KEY (id_rol)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Tabla Sucursal
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS sucursal (
  id_sucursal INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL UNIQUE,
  direccion VARCHAR(100) NOT NULL,
  ciudad VARCHAR(45) NOT NULL,
  telefono VARCHAR(15) NOT NULL UNIQUE,
  correo VARCHAR(100) NOT NULL UNIQUE,
  rfc VARCHAR(13) NOT NULL UNIQUE,
  ruta TEXT,
  fecha_act TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id_sucursal)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Usuarios (Unificación de Cliente y Empleado)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT UNIQUE,
  nombre VARCHAR(45) NOT NULL,
  apellido_pat VARCHAR(45) NOT NULL,
  apellido_mat VARCHAR(45) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  rol_id_rol INT,  -- Relación con el rol (cliente, empleado, administrador, etc.)
  PRIMARY KEY (id_usuario),
  FOREIGN KEY (rol_id_rol) REFERENCES rol (id_rol) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Clientes -- 
CREATE TABLE IF NOT EXISTS cliente(
	id_cliente INT NOT NULL AUTO_INCREMENT,
    curp VARCHAR(18) NOT NULL,
    nombre VARCHAR(45) NOT NULL,
    apellido_p VARCHAR(45) NOT NULL,
    apellido_m VARCHAR (45) DEFAULT NULL,
    telefono VARCHAR (45) DEFAULT NULL,
    correo VARCHAR (45) DEFAULT NULL,
     PRIMARY KEY (id_cliente)
)ENGINE = InnoDB;
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Tabla Tipo de Habitación
-- -----------------------------------------------------
-- CREACIÓN DE LA TABLA TIPO_HABITACION
CREATE TABLE IF NOT EXISTS tipo_habitacion (
    id_tipo_hab INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(150),
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Estado de la Habitación
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS estado (
  id_estado INT NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR(60) NOT NULL UNIQUE,
  PRIMARY KEY (id_estado)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Habitación
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS habitacion (
  id_habitacion INT NOT NULL AUTO_INCREMENT,
  numero VARCHAR(10) NOT NULL UNIQUE,
  descripcion VARCHAR(500) NOT NULL,
  tipo_habitacion_id_tipo_hab INT NOT NULL,
  ruta_imagen TEXT,
  estado_id_estado INT NOT NULL,
  PRIMARY KEY (id_habitacion),
  FOREIGN KEY (tipo_habitacion_id_tipo_hab) REFERENCES tipo_habitacion (id_tipo_hab) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (estado_id_estado) REFERENCES estado (id_estado) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Galería de Habitación
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS galeria (
  id_galeria INT NOT NULL AUTO_INCREMENT,
  descripcion VARCHAR(250),
  ruta TEXT NOT NULL,
  id_habitacion INT NOT NULL,
  PRIMARY KEY (id_galeria),
  FOREIGN KEY (id_habitacion) REFERENCES habitacion (id_habitacion) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Reservación
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS reservacion (
  id_reservacion INT NOT NULL AUTO_INCREMENT,
  fecha_entrada DATE NOT NULL,
  fecha_salida DATE NOT NULL,
  adelanto DECIMAL(10,2) NULL DEFAULT 0,
  status_reservacion ENUM('RESERVADO','ACTIVO','TERMINADO','CANCELADO') NULL DEFAULT 'RESERVADO', -- para ver el status actual de la reservación
  usuario_id_usuario INT, 
  cliente_id_cliente INT, 
  total DECIMAL(10,2) NULL DEFAULT 0, -- Total a pagar de reservación (se calculara automaticamente)
  PRIMARY KEY (id_reservacion),
  FOREIGN KEY (usuario_id_usuario) REFERENCES usuario (id_usuario) ON DELETE SET NULL ON UPDATE CASCADE,
  FOREIGN KEY (cliente_id_cliente) REFERENCES cliente (id_cliente) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Intermedia: Reservacion_Habitacion
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS reservacion_habitacion (
  id_reservacion_habitacion INT NOT NULL AUTO_INCREMENT,
  reservacion_id_reservacion INT NOT NULL,
  habitacion_id_habitacion INT NOT NULL,
  PRIMARY KEY (id_reservacion_habitacion),
  FOREIGN KEY (reservacion_id_reservacion) REFERENCES reservacion (id_reservacion) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (habitacion_id_habitacion) REFERENCES habitacion (id_habitacion) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Tabla Artículos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS articulo (
  id_articulo INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(45) NOT NULL,
  descripcion VARCHAR(100) NOT NULL,
  precio DECIMAL(10,2),
  PRIMARY KEY (id_articulo)
) ENGINE = InnoDB;

-- -----------------------------------------------------
CREATE TABLE movimiento_inventario (
    id_movimiento INT AUTO_INCREMENT PRIMARY KEY,
    habitacion_id INT NOT NULL,
    articulo_id INT NOT NULL,
    reservacion_id INT NULL, -- Puede ser NULL si es un movimiento fuera de una reservación
    cantidad INT NOT NULL, -- Positivo para entrada, negativo para salida
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Relaciones
    CONSTRAINT fk_mov_inv_habitacion FOREIGN KEY (habitacion_id) REFERENCES habitacion(id_habitacion) ON DELETE CASCADE,
    CONSTRAINT fk_mov_inv_articulo FOREIGN KEY (articulo_id) REFERENCES articulo(id_articulo) ON DELETE CASCADE,
    CONSTRAINT fk_mov_inv_reservacion FOREIGN KEY (reservacion_id) REFERENCES reservacion(id_reservacion) ON DELETE SET NULL
);


-- -----------------------------------------------------
-- Tabla Factura
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS factura (
  id_factura INT NOT NULL AUTO_INCREMENT,
  reservacion_id_reservacion INT NOT NULL,  
  fecha_emision DATE NOT NULL,              
  total DECIMAL(10,2) NOT NULL, 
  tipo_pago ENUM('efectivo','tarjeta'),
  PRIMARY KEY (id_factura),
  FOREIGN KEY (reservacion_id_reservacion) REFERENCES reservacion (id_reservacion) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;
-- -----------------------------------------------------
-- Tabla Cargos Adicionales
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS cargo_adicional (
  id_cargo INT NOT NULL AUTO_INCREMENT,
  reservacion_id_reservacion INT NOT NULL,                
  descripcion VARCHAR(255) NOT NULL,              
  monto DECIMAL(10,2) NOT NULL,                 
  PRIMARY KEY (id_cargo),
  FOREIGN KEY (reservacion_id_reservacion) REFERENCES reservacion (id_reservacion) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;


INSERT INTO rol VALUES(1,"ADMIN","Administrador de la pagina");
INSERT INTO rol VALUES(2,"USUARIO","Usuario de la pagina, solo tiene acceso limitado");

INSERT INTO estado VALUES(1,"DISPONIBLE");
INSERT INTO estado VALUES(2,"OCUPADO");
INSERT INTO estado VALUES(3,"DESALOJAR");
INSERT INTO estado VALUES(4,"LIMPIEZA");
INSERT INTO estado VALUES(5,"RESERVADO");

INSERT INTO usuario (nombre,apellido_pat,apellido_mat,correo,password,rol_id_rol) 
VALUES("Kevin","Bravo","Calva","bravocalvakevin@gmail.com","12345678","1");

INSERT INTO sucursal VALUES(1,"Nombre Sucursal","Direccion Sucursal","Ciudad","123456789","correo@correo.com","ASDF1234",
"../assets/dist/img/default.jpg",CURDATE());


-- Restaurar configuraciones
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


