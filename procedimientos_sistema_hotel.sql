-- PROCEDIMIENTOS ALMACENADOS 
-- Procedimiento para insertar usuarios
DELIMITER $$
CREATE PROCEDURE spInsUsuario(
_nombre VARCHAR(45),
_apellido_p VARCHAR (45),
_apellido_m VARCHAR (45),
_correo VARCHAR (100),
_password VARCHAR(255),
_rol INT
)
BEGIN
IF NOT EXISTS (SELECT id_usuario FROM usuario WHERE correo = _correo) THEN
	INSERT INTO usuario VALUES (NULL,_nombre,_apellido_p,_apellido_m,_correo,_password,_rol);
SELECT '0' as status; -- REGISTRO EXITOSO
ELSE
SELECT '1' as status; -- NO HUBO REGISTRO por que ya existe el correo ingresado
END IF;

END $$
DELIMITER ;

-- Eliminar usuario
DELIMITER $$
	CREATE PROCEDURE spDelUsuario (
    _id_usuario INT
    )
    BEGIN
    IF EXISTS(SELECT * FROM usuario WHERE id_usuario = _id_usuario)THEN		
		DELETE FROM usuario WHERE id_usuario = _id_usuario;
        SELECT '0' as status;
	ELSE
		SELECT '1' as status;
	END IF;
    END $$
DELIMITER ;
-- Actualizar usuario

DELIMITER $$
CREATE PROCEDURE spUpdUsuario(
_id_usuario INT,
_nombre VARCHAR(45),
_apellido_pat VARCHAR (45),
_apellido_mat VARCHAR (45),	
_correo VARCHAR(100),
_password VARCHAR(255),
_rol_id_rol INT
)
BEGIN
IF EXISTS(SELECT id_usuario FROM usuario WHERE _id_usuario = id_usuario)THEN
	IF NOT EXISTS (SELECT id_usuario FROM usuario WHERE _correo = correo AND _id_usuario != id_usuario ) THEN
		IF EXISTS(SELECT id_rol FROM rol WHERE id_rol =_rol_id_rol) THEN
    UPDATE usuario SET
    nombre = _nombre,
    apellido_pat =_apellido_pat,
    apellido_mat = _apellido_mat,
    correo = _correo,
    password = _password,
    rol_id_rol = _rol_id_rol
    WHERE id_usuario = _id_usuario;
SELECT '0' as status; -- REGISTRO EXITOSO
ELSE 
SELECT '3' as status; -- no existe el id rol ingresado
END IF;
ELSE 
SELECT '2' as status; -- existe un usuario igual al nuevo
END IF;
ELSE
SELECT '1' as status; -- no existe el registro a modificar
END IF;
END $$

DELIMITER ;


-- Validar acceso
DELIMITER $$
	CREATE PROCEDURE spValidarAcceso(
    _correo VARCHAR(50),
    _password VARCHAR (250)
    )
    BEGIN
    IF EXISTS (SELECT id_usuario FROM usuario WHERE correo = _correo AND password = _password) THEN
    SELECT '0' as status, CONCAT(u.nombre," " ,u.apellido_pat," ",u.apellido_mat) as Nombre_completo, u.correo, r.nombre as rol, u.id_usuario FROM usuario u, rol r
		WHERE u.rol_id_rol = r.id_rol AND u.correo = _correo AND u.password = _password; -- ACCESSO VALIDO
    ELSE 
    SELECT '1' as status; -- ACCESSO DENEGADO
    END IF;
    
    END $$
    
DELIMITER ;
-- --------------------------------------------------------------------------------------------
-- ACTUALIZAR SUCURSAL
DELIMITER $$
CREATE PROCEDURE spUpdSucursal(
	_nombre VARCHAR(45),
    _direccion VARCHAR(100),
    _ciudad VARCHAR(45),
    _telefono VARCHAR(12),
    _correo VARCHAR (100),
    _rfc VARCHAR (13),
    _ruta TEXT
)
BEGIN
	UPDATE sucursal SET
		nombre = _nombre,
        direccion = _direccion,
        ciudad = _ciudad,
        telefono = _telefono,
        correo = _correo,
        rfc = _rfc,
        ruta = _ruta,
        fecha_act = current_timestamp()
        WHERE id_sucursal = "1";
END $$
DELIMITER ;
-- -------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA INSERTAR CLIENTE
DELIMITER $$
CREATE PROCEDURE spInsCliente(
	_curp VARCHAR(18),
    _nombre VARCHAR(45),
    _apellido_p VARCHAR(45),
    _apellido_m VARCHAR(45),
    _telefono VARCHAR(12),
    _correo VARCHAR(45)
)
BEGIN
IF NOT EXISTS(SELECT id_cliente FROM cliente where curp = _curp) THEN
 INSERT INTO cliente VALUES(NULL,_curp,_nombre,_apellido_p,_apellido_m,_telefono,_correo);
				SELECT '0' AS status;
			ELSE 
		SELECT '1' AS status;
 END IF;
END $$
DELIMITER ;

-- ----------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA ACTUALIZAR CLIENTE
DELIMITER $$
CREATE PROCEDURE spUpdCliente(
	_id_cliente INT,
    _curp VARCHAR(18),
    _nombre VARCHAR(45),
    _apellido_p VARCHAR(45),
    _apellido_m VARCHAR(45),
    _telefono VARCHAR(12),
    _correo VARCHAR(45)
    
)
BEGIN
        IF EXISTS(SELECT id_cliente FROM cliente WHERE id_cliente = _id_cliente)THEN
			IF NOT EXISTS (SELECT id_cliente FROM cliente WHERE curp = _curp AND id_cliente != _id_cliente )THEN
            UPDATE cliente SET
			curp = _curp,
            nombre = _nombre,
            apellido_p = _apellido_p,
            apellido_m = _apellido_m,
            telefono = _telefono,
            correo = _correo
            WHERE id_cliente = _id_cliente;
            SELECT '0' AS status; -- registro exitoso
				ELSE
                SELECT '1' AS status;  -- Ya existe el curp a actualizar en otro registro
				END IF;
			ELSE
			SELECT '2' AS status; -- No existe el id ingresado
			END IF ;

END $$
DELIMITER ;
-- --------------------------------------------------------------------------------------
-- ELIMINAR CLIENTE
DELIMITER $$
CREATE PROCEDURE spDelCliente(
_id_cliente INT
)
BEGIN
IF EXISTS (SELECT * FROM cliente WHERE id_cliente = _id_cliente) THEN
	DELETE FROM cliente WHERE id_cliente = _id_cliente;
    SELECT '0' AS status;
    ELSE SELECT '1' AS status; -- no existe el id ingresado
    END IF;
END $$
DELIMITER ;
-- ------------------------------------------------------------------------------------
-- Insertar Tipo Habitacion
 DELIMITER $$
CREATE PROCEDURE spInsTipoHabitacion(
    _nombre VARCHAR(50),
    _descripcion VARCHAR(255),
    _precio DECIMAL(10,2)
)
BEGIN 
	-- Verificar que el nombre del tipo de habitación no exista
	IF NOT EXISTS(SELECT id_tipo_hab FROM tipo_habitacion WHERE nombre = _nombre) THEN
		INSERT INTO tipo_habitacion (nombre, descripcion, precio) VALUES (_nombre, _descripcion, _precio);
		SELECT '0' AS status; -- Inserción exitosa
	ELSE 	
        SELECT '1' AS status; -- El nombre ya existe, no se inserta
	END IF;
END $$
DELIMITER ;
-- --------------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA ACTUALIZAR TIPO DE HABITACIÓN
DELIMITER $$
CREATE PROCEDURE spUpdTipoHabitacion(
    _id_tipo_hab INT,
    _nombre VARCHAR(50),
    _descripcion VARCHAR(150),
    _precio DECIMAL(10,2)
)
BEGIN
    IF EXISTS(SELECT id_tipo_hab FROM tipo_habitacion WHERE id_tipo_hab = _id_tipo_hab) THEN
        IF NOT EXISTS(SELECT id_tipo_hab FROM tipo_habitacion WHERE nombre = _nombre AND id_tipo_hab != _id_tipo_hab) THEN
            UPDATE tipo_habitacion 
            SET nombre = _nombre, 
                descripcion = _descripcion, 
                precio = _precio
            WHERE id_tipo_hab = _id_tipo_hab;
            SELECT '0' AS status; -- Actualización exitosa
        ELSE
            SELECT '2' AS status; -- El nombre ya existe en otro registro
        END IF;
    ELSE
        SELECT '1' AS status; -- No existe el registro a modificar
    END IF;
END $$
DELIMITER ;
-- ------------------------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA ELIMINAR TIPO DE HABITACIÓN
DELIMITER $$
CREATE PROCEDURE spDelTipoHabitacion(
    _id_tipo_hab INT
)
BEGIN
    IF EXISTS(SELECT id_tipo_hab FROM tipo_habitacion WHERE id_tipo_hab = _id_tipo_hab) THEN
        DELETE FROM tipo_habitacion WHERE id_tipo_hab = _id_tipo_hab;
        SELECT '0' AS status; -- Eliminación exitosa
    ELSE
        SELECT '1' AS status; -- No existe el registro
    END IF;
END $$
DELIMITER ;
-- ----------------------------------------------------------------------------------------------
-- Procedimiento para insertar Habitacion
DELIMITER $$

CREATE PROCEDURE spInsHabitacion(
    _numero VARCHAR(10),
    _descripcion VARCHAR(500),
    _tipo_habitacion_id INT,
    _estado_id INT,
    _ruta_imagen TEXT
)
BEGIN
    -- Validar que el número de habitación no exista
    IF NOT EXISTS (SELECT id_habitacion FROM habitacion WHERE numero = _numero) THEN
        INSERT INTO habitacion (numero, descripcion, tipo_habitacion_id_tipo_hab, estado_id_estado, ruta_imagen)
        VALUES (_numero, _descripcion, _tipo_habitacion_id, _estado_id, _ruta_imagen);
        SELECT '0' AS status; -- Inserción exitosa
    ELSE
        SELECT '1' AS status; -- El número de habitación ya existe
    END IF;
END $$

DELIMITER ;
-- --------------------------------------------------------------------------------------------------------
DELIMITER $$

DELIMITER $$

CREATE PROCEDURE spDelHabitacion(
    _id_habitacion INT
)
BEGIN
    -- Verificar si la habitación existe antes de proceder
    IF EXISTS(SELECT * FROM habitacion WHERE id_habitacion = _id_habitacion) THEN
        IF EXISTS(SELECT * FROM habitacion WHERE id_habitacion = _id_habitacion AND estado_id_estado = 1) THEN
            DELETE FROM habitacion WHERE id_habitacion = _id_habitacion;
            SELECT '0' AS status; -- Actualización exitosa
        ELSE
        SELECT '2' AS status; -- El estado es no es 1 por lo que no esta libre
        END IF;
            ELSE
        SELECT '1' AS status; -- No existe el id
        END IF;
        
END $$

DELIMITER ;
-- ------------------------------------------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE spUpdHabitacion(
    _id_habitacion INT,
    _numero VARCHAR(10),
    _descripcion VARCHAR(500),
    _tipo_habitacion_id INT,
    _ruta_imagen TEXT
)
BEGIN
    -- Verificar si la habitación existe
    IF EXISTS(SELECT id_habitacion FROM habitacion WHERE id_habitacion = _id_habitacion) THEN
        -- Validar que el número de habitación no esté repetido (excepto para la habitación actual)
        IF NOT EXISTS (SELECT id_habitacion FROM habitacion WHERE numero = _numero AND id_habitacion != _id_habitacion) THEN
            UPDATE habitacion 
            SET numero = _numero, 
                descripcion = _descripcion, 
                tipo_habitacion_id_tipo_hab = _tipo_habitacion_id, 
                ruta_imagen = _ruta_imagen
            WHERE id_habitacion = _id_habitacion;
            SELECT '0' AS status; -- Actualización exitosa
        ELSE
            SELECT '2' AS status; -- El número de habitación ya existe
        END IF;
    ELSE
        SELECT '1' AS status; -- La habitación no existe
    END IF;
END $$

DELIMITER ;
-- -----------------------------------------------------------------------------------------------------------------
-- Procedimiento para insertar articulo

DELIMITER $$
CREATE PROCEDURE spInsArticulo(
	_nombre VARCHAR(45),
    _descripcion VARCHAR(100),
    _precio DECIMAL(10,2)    
)
BEGIN 
IF NOT EXISTS (SELECT 1 FROM articulo where nombre = _nombre) THEN
INSERT INTO articulo VALUES (NULL,_nombre,_descripcion,_precio);
SELECT '0' AS status; -- Registro exitoso
	ELSE
		SELECT '1' AS status; -- Ya existe un registro con ese nombre
END IF ;

END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE spDelArticulo(
	_id_articulo INT
)
BEGIN
	IF EXISTS (SELECT 1 FROM articulo WHERE id_articulo = _id_articulo) THEN
    DELETE FROM articulo WHERE id_articulo = _id_articulo;
    SELECT  '0' AS status;
    ELSE 
	SELECT '1' AS status;
END IF ;
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE spUpdArticulo (
	_id_articulo INT,
    _nombre VARCHAR(45),
    _descripcion VARCHAR(100),
    _precio DECIMAL(10,2) 
)
BEGIN 
	IF EXISTS(SELECT 1 FROM articulo WHERE id_articulo = _id_articulo) THEN
		IF NOT EXISTS (SELECT 1 FROM articulo WHERE nombre = _nombre AND id_articulo != _id_articulo) THEN
        UPDATE articulo SET
        nombre = _nombre,
        descripcion = _descripcion,
        precio = _precio
        WHERE id_articulo = _id_articulo;
        SELECT '0' AS status; -- registro actualizado con exito
        ELSE 
        SELECT '1' AS status; -- YA EXISTE UN ARTICULO CON EL NOMBRE REGISTRADO
        END IF; 
        ELSE 
        SELECT '2' AS status; -- NO EXISTE EL ID INGRESADO
        END IF; 
END $$
DELIMITER ;
-- --------------------------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA INGRESAR UN ARTICULO A UNA HABITACION
DELIMITER $$

CREATE PROCEDURE spInsertarInventarioHab (
    IN p_habitacion_id INT,
    IN p_articulo_id INT,
    IN p_cantidad INT,
    IN p_reservacion_id INT
)
BEGIN
    INSERT INTO movimiento_inventario (
        habitacion_id,
        articulo_id,
        cantidad,
        tipo_movimiento,
        reservacion_id,
        fecha
    ) VALUES (
        p_habitacion_id,
        p_articulo_id,
        p_cantidad,
        'entrada',
        p_reservacion_id,
        NOW()
    );
    SELECT '0' AS status;
    
END $$

DELIMITER ;
-- -----------------------------------------------------------------------------
-- Procedimiento para eliminar articulo de habitacion
DELIMITER $$
CREATE PROCEDURE spQuitarInventario (
    IN p_habitacion_id INT,
    IN p_articulo_id INT,
    IN p_cantidad INT,
    IN p_reservacion_id INT 
)
BEGIN
    INSERT INTO movimiento_inventario (
        habitacion_id,
        articulo_id,
        cantidad,
        tipo_movimiento,
        reservacion_id,
        fecha
    ) VALUES (
        p_habitacion_id,
        p_articulo_id,
        p_cantidad,
        'salida',
        p_reservacion_id,
        NOW()
    );
    SELECT '0' AS status;
END $$
DELIMITER ;

-- -----------------------------------------------------------------------
DELIMITER $$

CREATE PROCEDURE obtener_habitaciones_disponibles(
    IN p_fecha_entrada DATE,
    IN p_fecha_salida DATE
)
BEGIN
    SELECT h.id_habitacion, h.numero, h.descripcion,th.id_tipo_hab,th.nombre AS tipo_habitacion, h.estado_id_estado, th.precio,h.ruta_imagen
    FROM habitacion h
    JOIN tipo_habitacion th ON h.tipo_habitacion_id_tipo_hab = th.id_tipo_hab
    WHERE h.id_habitacion NOT IN (
        SELECT rh.habitacion_id_habitacion
        FROM reservacion_habitacion rh
        JOIN reservacion r ON rh.reservacion_id_reservacion = r.id_reservacion
        WHERE (p_fecha_entrada < r.fecha_salida AND p_fecha_salida > r.fecha_entrada)
    ) ORDER BY h.numero ASC;
END$$

DELIMITER ;

-- -----------------------------------------------------------------------------
-- PROCEDIMIENTO PARA HACER UNA RESERVACION, OBTENER SU TOTAL, y asignarle la habitacion
DELIMITER $$

CREATE PROCEDURE spCrearReservacion (
    IN p_fecha_entrada DATE,
    IN p_fecha_salida DATE,
    IN p_adelanto DECIMAL(10,2),
    IN p_usuario_id INT,
    IN p_cliente_id INT,
    IN p_habitacion_id INT
)
BEGIN
    DECLARE v_reservacion_id INT;
    DECLARE v_dias INT;
    DECLARE v_precio DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);
label_proc: BEGIN

    -- Validar si ya hay otra reserva para esa habitación en las fechas indicadas
    IF EXISTS (
        SELECT 1 FROM reservacion r
        JOIN reservacion_habitacion rh ON r.id_reservacion = rh.reservacion_id_reservacion
        WHERE rh.habitacion_id_habitacion = p_habitacion_id
        AND r.status_reservacion IN ('RESERVADO', 'ACTIVO')
        AND (
            p_fecha_entrada <= r.fecha_salida AND
            p_fecha_salida >= r.fecha_entrada
        )
    ) THEN
        SELECT '1' AS status; -- habitación ocupada en esas fechas
        LEAVE label_proc;
    END IF;
        
    -- Calcular días (mínimo 1)
    SET v_dias = DATEDIFF(p_fecha_salida, p_fecha_entrada);
    IF v_dias < 1 THEN
        SET v_dias = 1;
    END IF;

    -- Obtener el precio de la habitación
    SELECT th.precio INTO v_precio
    FROM habitacion h
    JOIN tipo_habitacion th ON h.tipo_habitacion_id_tipo_hab = th.id_tipo_hab
    WHERE h.id_habitacion = p_habitacion_id;

    -- Calcular total
    SET v_total = v_precio * v_dias;

    -- Insertar la reservación
    INSERT INTO reservacion (
        fecha_entrada, fecha_salida, adelanto, usuario_id_usuario, cliente_id_cliente, total, status_reservacion
    ) VALUES (
        p_fecha_entrada, p_fecha_salida, p_adelanto, p_usuario_id, p_cliente_id, v_total, 'RESERVADO'
    );

    SET v_reservacion_id = LAST_INSERT_ID();

    -- Insertar en tabla intermedia
    INSERT INTO reservacion_habitacion (
        reservacion_id_reservacion, habitacion_id_habitacion
    ) VALUES (
        v_reservacion_id, p_habitacion_id
    );

    -- Marcar la habitación como RESERVADO (id_estado = 5)
    UPDATE habitacion SET estado_id_estado = 5
    WHERE id_habitacion = p_habitacion_id;
    
    -- Retornar estado exitoso como 0
    SELECT '0' AS status;
    
END label_proc;
END$$

DELIMITER ;


-- PROCEDIMIENTO PARA HOSPEDAJE INMEDIATO, OBTENER TOTAL Y ASIGNAR HABITACION
DELIMITER $$

CREATE PROCEDURE spCrearHospedaje (
    IN p_fecha_entrada DATE,
    IN p_fecha_salida DATE,
    IN p_adelanto DECIMAL(10,2),
    IN p_usuario_id INT,
    IN p_cliente_id INT,
    IN p_habitacion_id INT
)
BEGIN
    DECLARE v_reservacion_id INT;
    DECLARE v_dias INT;
    DECLARE v_precio DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);
    DECLARE v_estado INT;

    label_proc: BEGIN
        -- Obtener el estado actual de la habitación
        SELECT estado_id_estado INTO v_estado
        FROM habitacion
        WHERE id_habitacion = p_habitacion_id;

        -- Si la habitación no está libre (por ejemplo, estado 1 = LIBRE)
        IF v_estado <> 1 THEN
            SELECT '1' AS status; -- habitación no disponible
            LEAVE label_proc;
        END IF;

        -- Calcular días (mínimo 1)
        SET v_dias = DATEDIFF(p_fecha_salida, p_fecha_entrada);
        IF v_dias < 1 THEN
            SET v_dias = 1;
        END IF;

        -- Obtener el precio de la habitación
        SELECT th.precio INTO v_precio
        FROM habitacion h
        JOIN tipo_habitacion th ON h.tipo_habitacion_id_tipo_hab = th.id_tipo_hab
        WHERE h.id_habitacion = p_habitacion_id;

        -- Calcular total
        SET v_total = v_precio * v_dias;

        -- Insertar la reservación
        INSERT INTO reservacion (fecha_entrada, fecha_salida, adelanto, usuario_id_usuario, cliente_id_cliente, total,status_reservacion)
        VALUES (p_fecha_entrada, p_fecha_salida, p_adelanto, p_usuario_id, p_cliente_id, v_total,'ACTIVO');
        

        SET v_reservacion_id = LAST_INSERT_ID();

        -- Insertar en tabla intermedia
        INSERT INTO reservacion_habitacion (reservacion_id_reservacion, habitacion_id_habitacion)
        VALUES (v_reservacion_id, p_habitacion_id);

        -- Marcar la habitación como OCUPADO
        UPDATE habitacion
        SET estado_id_estado = 2
        WHERE id_habitacion = p_habitacion_id;

        -- Retornar estado exitoso como 0
        SELECT '0' AS status;
    END label_proc;

END$$
DELIMITER ;
-- -------------------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA INSERTAR UN CARGO ADICIONAL A RESERVACIÓN

DELIMITER $$
CREATE PROCEDURE spInsCargoAd(
	IN _id_reserva INT,
    IN _descripcion VARCHAR(45),
    IN _monto DECIMAL(10,2)
)
BEGIN
    IF EXISTS (SELECT 1 FROM reservacion WHERE id_reservacion = _id_reserva) THEN
	INSERT INTO cargo_adicional VALUES (NULL,_id_reserva,_descripcion,_monto);
    SELECT '0' AS status;
    ELSE 
    SELECT '1' AS status; -- NO EXISTE ID DE LA RESERVACIÓN
    END IF;
END$$
DELIMITER ;

-- --------------------------------------------------------------------------------
-- PROCEDIMIENTO PARA ELIMINAR CARGO ADICIONAL
DELIMITER $$
CREATE PROCEDURE spDelCargoAd(
IN _id_cargo INT    
)
BEGIN
	IF EXISTS (SELECT 1 FROM cargo_adicional WHERE id_cargo = _id_cargo) THEN
    DELETE FROM cargo_adicional WHERE id_cargo = _id_cargo;
    SELECT '0' AS status;
    ELSE
    SELECT '1' AS status;
	END IF;
    
END$$
DELIMITER ;

-- PROCEDIMIENTO PARA CHECK-OUT
DELIMITER $$
CREATE PROCEDURE spCheckOut(
IN _id_reservacion INT,
IN _tipo_pago ENUM('efectivo','tarjeta')
)
BEGIN
 -- Obtener la suma de todos los cargos e inventario por medio del id de la reservación
	 DECLARE suma_cargos DECIMAL(10,2);
     DECLARE suma_inventario DECIMAL(10,2);
     DECLARE suma_reservacion DECIMAL(10,2);
     DECLARE total_final DECIMAL(10,2);
     DECLARE _id_habitacion INT;
     
     -- Suma de cargos adicionales
     SELECT SUM(monto) INTO suma_cargos  FROM cargo_adicional WHERE reservacion_id_reservacion = _id_reservacion;
      IF suma_cargos IS NULL THEN SET suma_cargos = 0; 
      END IF;
	 -- Suma de inventario
     SELECT  SUM(a.precio * m.cantidad) INTO suma_inventario
      FROM articulo a INNER JOIN movimiento_inventario m ON a.id_articulo = m.articulo_id 
      WHERE m.reservacion_id= _id_reservacion; 
      IF suma_inventario IS NULL THEN SET suma_inventario = 0; 
      END IF;
      
      -- Total de reservacion
      SELECT IFNULL(total, 0) INTO suma_reservacion FROM reservacion where id_reservacion = _id_reservacion;
      
      -- Obtener el id_habitacion de la reservacion
      SELECT habitacion_id_habitacion INTO _id_habitacion FROM reservacion_habitacion WHERE reservacion_id_reservacion = _id_reservacion LIMIT 1;
      
      SET total_final = suma_cargos+suma_inventario+suma_reservacion;
      -- ==== INSERTAR EN FACTURA ==============================================
      INSERT INTO factura(reservacion_id_reservacion,fecha_emision,total,tipo_pago) 
      VALUES (_id_reservacion,CURDATE(),total_final,_tipo_pago);
      -- ====== CAMBIAR STATUS DE RESERVACION A TERMINADO========================
      UPDATE reservacion SET status_reservacion = 'TERMINADO' WHERE id_reservacion =  _id_reservacion;
      -- ====== CAMBIAR STATUS DE LA HABITACION A LIMPIEZA ======================
		UPDATE habitacion SET estado_id_estado = 4 -- LIMPIEZA ES ID 4
        WHERE id_habitacion = _id_habitacion;
       
END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE spRealizarLimpieza(
IN _id_habitacion INT
)
BEGIN
UPDATE habitacion SET estado_id_estado = 1 WHERE id_habitacion = _id_habitacion;
SELECT '0' AS status;
END $$

DELIMITER ;

-- ------------------------------------------------------------------------------
DELIMITER $$
CREATE PROCEDURE spCheckIn(
IN _id_reservacion INT,
IN _id_habitacion INT
)
BEGIN
UPDATE habitacion SET estado_id_estado = 2 WHERE id_habitacion = _id_habitacion; -- CAMBIAR STATUS DE LA HABITACION A OCUPADO
UPDATE reservacion SET status_reservacion = 'ACTIVO' WHERE id_reservacion = _id_reservacion;
SELECT '0' as status;
END $$
DELIMITER ;
-- ----------------------------------------------------------
-- PROCEDIMIENTO PARA OBTENER RESERVACIONES POR MES Y AÑO INGRESADO
DELIMITER $$

CREATE PROCEDURE sp_obtener_reservaciones_mes (
    IN p_mes INT,
    IN p_anio INT
)
BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcula el rango de fechas del mes
    SET fecha_inicio = STR_TO_DATE(CONCAT(p_anio, '-', p_mes, '-01'), '%Y-%m-%d');
    SET fecha_fin = LAST_DAY(fecha_inicio);

    SELECT 
        r.id_reservacion,
        CONCAT('Reservación: ', c.nombre, ' ', c.apellido_p) AS titulo,
        r.fecha_entrada,
        r.fecha_salida,
        rh.habitacion_id_habitacion,
        h.numero AS habitacion,
        r.status_reservacion
    FROM reservacion r
    JOIN cliente c ON c.id_cliente = r.cliente_id_cliente
    JOIN reservacion_habitacion rh ON rh.reservacion_id_reservacion = r.id_reservacion
    JOIN habitacion h ON h.id_habitacion = rh.habitacion_id_habitacion
    WHERE r.fecha_salida >= fecha_inicio AND r.fecha_entrada <= fecha_fin
    ORDER BY r.fecha_entrada;
    
END $$

DELIMITER ;
-- =================================================================================================
-- PROCEDIMIENTO PARA OBTENER EL DESGLOCE DE UNA FACTURA POR MEDIO DE SU ID

DELIMITER $$

CREATE PROCEDURE obtener_desglose_factura(IN p_id_factura INT)
BEGIN
  DECLARE v_id_reservacion INT;

  -- Obtener ID de la reservación asociada
  SELECT reservacion_id_reservacion
  INTO v_id_reservacion
  FROM factura
  WHERE id_factura = p_id_factura;

  -- Datos generales de la reservación
  SELECT 
    r.id_reservacion,
    r.fecha_entrada,
    r.fecha_salida,
    r.adelanto,
    r.total AS total_reservacion,
    r.status_reservacion,
    CONCAT(c.nombre, ' ', c.apellido_p, ' ', IFNULL(c.apellido_m, '')) AS cliente,
    u.nombre AS usuario
  FROM reservacion r
  LEFT JOIN cliente c ON r.cliente_id_cliente = c.id_cliente
  LEFT JOIN usuario u ON r.usuario_id_usuario = u.id_usuario
  WHERE r.id_reservacion = v_id_reservacion;

  -- Cargos adicionales
  SELECT 
    ca.descripcion,
    ca.monto
  FROM cargo_adicional ca
  WHERE ca.reservacion_id_reservacion = v_id_reservacion;

  -- Inventario relacionado
  SELECT 
    a.nombre AS articulo,
    a.precio,
    mi.cantidad,
    mi.tipo_movimiento,
    h.numero AS habitacion,
    mi.fecha
  FROM movimiento_inventario mi
  JOIN articulo a ON mi.articulo_id = a.id_articulo
  JOIN habitacion h ON mi.habitacion_id = h.id_habitacion
  WHERE mi.reservacion_id = v_id_reservacion;

END$$

DELIMITER ;

DELIMITER $$
-- ===================================================================================
-- PROCEDIMIENTO PARA CANCELAR UN RESERVACION ACTIVA O RESERVADA
CREATE PROCEDURE cancelar_reservacion(IN p_id_reservacion INT)
BEGIN
    DECLARE estado_actual ENUM('RESERVADO','ACTIVO','TERMINADO','CANCELADO');

    -- Obtener el estado actual de la reservación
    SELECT status_reservacion INTO estado_actual
    FROM reservacion
    WHERE id_reservacion = p_id_reservacion;

    -- Si la reservación existe y no está ya cancelada ni terminada
    IF estado_actual IS NOT NULL AND estado_actual NOT IN ('CANCELADO', 'TERMINADO') THEN

        -- Actualizar la reservación a CANCELADO
        UPDATE reservacion
        SET status_reservacion = 'CANCELADO'
        WHERE id_reservacion = p_id_reservacion;

        -- Si estaba activa, pasar las habitaciones asociadas a LIMPIEZA
        IF estado_actual = 'ACTIVO' THEN
            UPDATE habitacion
            SET estado_id_estado = 4 -- LIMPIEZA
            WHERE id_habitacion IN (
                SELECT habitacion_id_habitacion
                FROM reservacion_habitacion
                WHERE reservacion_id_reservacion = p_id_reservacion
            );
        END IF;
    END IF;
END$$

DELIMITER ;


