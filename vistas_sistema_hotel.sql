-- VISTA PARA VER LOS USUARIOS
CREATE VIEW vwRptUsuarios AS
SELECT usuario.id_usuario, CONCAT(usuario.nombre,' ', usuario.apellido_pat,' ',usuario.apellido_mat) as nombre_completo,usuario.correo,
rol.nombre as Rol
 FROM usuario
INNER JOIN rol ON usuario.rol_id_rol = rol.id_rol;

-- VISTA PARA VER Habitaciones 
CREATE VIEW vista_habitaciones AS
SELECT 
    h.id_habitacion,
    h.numero,
    h.descripcion,
    t.nombre AS tipo_habitacion,
    t.precio,
    e.descripcion AS estado,
    h.ruta_imagen
FROM habitacion h
JOIN tipo_habitacion t ON h.tipo_habitacion_id_tipo_hab = t.id_tipo_hab
JOIN estado e ON h.estado_id_estado = e.id_estado;
-- --------------------------------------------------------------------------
-- --------------------------------------------------------------------------
CREATE OR REPLACE VIEW vista_inventario_por_habitacion AS
SELECT 
    h.id_habitacion,
    h.numero AS numero_habitacion,
    h.descripcion AS descripcion_habitacion,
    a.id_articulo,
    a.nombre AS nombre_articulo,
    a.descripcion AS descripcion_articulo,
    a.precio AS precio,
    SUM(
        CASE 
            WHEN m.tipo_movimiento = 'entrada' THEN m.cantidad
            WHEN m.tipo_movimiento = 'salida' THEN -m.cantidad
            ELSE 0
        END
    ) AS stock_actual
FROM habitacion h
JOIN movimiento_inventario m ON h.id_habitacion = m.habitacion_id
JOIN articulo a ON m.articulo_id = a.id_articulo
GROUP BY h.id_habitacion, a.id_articulo
ORDER BY h.numero, a.nombre;

-- ------------------------------------------------------------------------
-- VISTA PARA VER TODOS LOS DATOS DE UNA RESERVACION COMPLETA

CREATE OR REPLACE VIEW vista_reservaciones_completa AS
SELECT
    r.id_reservacion,
    r.fecha_entrada,
    r.fecha_salida,
    GREATEST(DATEDIFF(r.fecha_salida, r.fecha_entrada), 1) AS noches,
    r.status_reservacion,
    h.numero AS numero_habitacion,
    th.nombre AS tipo_habitacion,
    
    r.total,
    r.adelanto,
    (r.total - IFNULL(r.adelanto, 0)) AS total_restante,
    
    c.id_cliente,
    CONCAT_WS(' ', c.nombre, c.apellido_p, c.apellido_m) AS nombre_cliente,
    
    u.id_usuario,
    CONCAT_WS(' ', u.nombre, u.apellido_pat, u.apellido_mat) AS nombre_usuario

FROM reservacion r
JOIN reservacion_habitacion rh ON rh.reservacion_id_reservacion = r.id_reservacion
JOIN habitacion h ON h.id_habitacion = rh.habitacion_id_habitacion
JOIN tipo_habitacion th ON th.id_tipo_hab = h.tipo_habitacion_id_tipo_hab
LEFT JOIN cliente c ON c.id_cliente = r.cliente_id_cliente
LEFT JOIN usuario u ON u.id_usuario = r.usuario_id_usuario;

-- ----------------------------------------------------------------------------
CREATE OR REPLACE VIEW vwRptHabitacionesSalida AS
SELECT 
    h.numero AS 'numero_Habitacion',
    h.id_habitacion AS 'id_habitacion',
    r.id_reservacion AS 'id_reservacion',
    c.id_cliente AS 'id_cliente',
    CONCAT(c.nombre, ' ', c.apellido_p, IFNULL(CONCAT(' ', c.apellido_m), '')) AS 'nombre_completo',
    r.fecha_entrada AS 'fecha_entrada',
    r.fecha_salida AS 'fecha_salida'
FROM 
    habitacion h
JOIN 
    reservacion_habitacion rh ON h.id_habitacion = rh.habitacion_id_habitacion
JOIN 
    reservacion r ON rh.reservacion_id_reservacion = r.id_reservacion
JOIN 
    cliente c ON r.cliente_id_cliente = c.id_cliente
JOIN 
    estado e ON h.estado_id_estado = e.id_estado
WHERE 
    e.descripcion = 'OCUPADO'
     AND
     r.status_reservacion = 'ACTIVO'
ORDER BY 
    h.numero;
    
-- -----------------------------------------------------------------------------
CREATE OR REPLACE VIEW vwRptTotalHabitacionesOcupadas AS
SELECT COUNT(*) AS total 
FROM habitacion 
WHERE estado_id_estado = 2;
-- ------------------------------------------------------------------
CREATE OR REPLACE VIEW vwRptTotalLimpieza AS
SELECT COUNT(*) AS total 
FROM habitacion 
WHERE estado_id_estado = 4;
-- ------------------------------------------------------------------
CREATE OR REPLACE VIEW vwRptTotalDisp AS
SELECT COUNT(*) AS total 
FROM habitacion 
WHERE estado_id_estado = 1;
-- ------------------------------------------------------------------
CREATE OR REPLACE VIEW vwRptTotalReservaciones AS
SELECT COUNT(*) AS total 
FROM reservacion 
WHERE status_reservacion = 'RESERVADO';
-- ------------------------------------------------------------------
CREATE OR REPLACE VIEW vista_habitaciones_limpieza AS
SELECT * FROM habitacion WHERE estado_id_estado = 4;
-- -----------------------------------------------------------------
CREATE OR REPLACE VIEW vista_reservaciones_espera AS
SELECT 
  r.id_reservacion,
  h.id_habitacion,
 CONCAT(c.nombre," ",c.apellido_p," ",c.apellido_m) AS cliente,
  r.fecha_entrada,
  r.fecha_salida,
  h.numero as habitaciones
FROM reservacion r
JOIN usuario u ON r.usuario_id_usuario = u.id_usuario
JOIN reservacion_habitacion rh ON rh.reservacion_id_reservacion = r.id_reservacion
JOIN habitacion h ON rh.habitacion_id_habitacion = h.id_habitacion
JOIN cliente c ON r.usuario_id_usuario = c.id_cliente
WHERE r.status_reservacion = 'reservado'
ORDER BY r.fecha_entrada;
-- -----------------------------------------------------------------------
-- VISTA PARA VER LA HABITACIÓN MAS RESERVADA DEL MES
CREATE OR REPLACE VIEW vista_habitacion_mas_reservada_mes AS
SELECT 
    h.id_habitacion,
    h.numero AS numero_habitacion,
    h.ruta_imagen,
    h.descripcion,
    h.estado_id_estado,
    e.descripcion as estado,
    th.nombre AS tipo_habitacion,
    th.precio,
    COUNT(rh.id_reservacion_habitacion) AS total_reservas,
    MONTH(r.fecha_entrada) AS mes,
    YEAR(r.fecha_entrada) AS año
FROM 
    habitacion h
JOIN 
    reservacion_habitacion rh ON h.id_habitacion = rh.habitacion_id_habitacion
JOIN
	estado e ON h.estado_id_estado = e.id_estado 	
JOIN 
    reservacion r ON rh.reservacion_id_reservacion = r.id_reservacion
JOIN 
    tipo_habitacion th ON h.tipo_habitacion_id_tipo_hab = th.id_tipo_hab
WHERE 
    r.status_reservacion IN ('RESERVADO', 'ACTIVO', 'TERMINADO') AND
    MONTH(r.fecha_entrada) = MONTH(CURRENT_DATE()) AND
    YEAR(r.fecha_entrada) = YEAR(CURRENT_DATE())
GROUP BY 
    h.id_habitacion, MONTH(r.fecha_entrada), YEAR(r.fecha_entrada)
ORDER BY 
    total_reservas DESC
LIMIT 1;

-- ==================================================================================
-- VISTA PARA RESERVAS POR MES DEL AÑO
CREATE OR REPLACE VIEW vwReporteReservasMes AS
SELECT 
  YEAR(fecha_entrada) AS anio,
  MONTH(fecha_entrada) AS mes,
  COUNT(*) AS total_reservas
FROM reservacion
WHERE 
  YEAR(fecha_entrada) = YEAR(CURDATE()) AND
  status_reservacion IN ('RESERVADO', 'ACTIVO', 'TERMINADO')
GROUP BY anio, mes
ORDER BY mes;
-- ======================================================
CREATE OR REPLACE VIEW vwReporteGananciaMes AS
SELECT 
    IFNULL(SUM(f.total), 0)AS ganancia_total_mes
FROM factura f
WHERE MONTH(f.fecha_emision) = MONTH(CURRENT_DATE())
  AND YEAR(f.fecha_emision) = YEAR(CURRENT_DATE());
-- ================================================================
CREATE OR REPLACE VIEW vwReporteFacturas AS
	SELECT f.id_factura,r.id_reservacion,f.fecha_emision,f.total,f.tipo_pago,
    CONCAT(u.nombre," ",u.apellido_mat," ",u.apellido_pat) AS nombre_usuario,
    CONCAT(cl.nombre," ",cl.apellido_m," ",cl.apellido_p) AS nombre_cliente 
    FROM factura f
    JOIN reservacion r ON f.reservacion_id_reservacion = r.id_reservacion
    JOIN cliente cl ON r.cliente_id_cliente = cl.id_cliente
    JOIN usuario u ON r.usuario_id_usuario = u.id_usuario; 
-- =============================================================================

SELECT * FROM vwreportegananciames;