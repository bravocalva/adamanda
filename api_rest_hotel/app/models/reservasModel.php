<?php
require_once __DIR__ . '/../core/Database.php';

class reservasModel
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllReservas()
    {
        $this->db->query('SELECT id_reservacion,fecha_entrada,fecha_salida,
        noches,numero_habitacion,tipo_habitacion,total,adelanto,total_restante,
        nombre_cliente,status_reservacion,nombre_usuario FROM vista_reservaciones_completa;');
        return $this->db->resultSet();
    }

    public function getReservaById($id)
    {
        $this->db->query('SELECT id_reservacion,fecha_entrada,fecha_salida,
        noches,numero_habitacion,tipo_habitacion,total,adelanto,total_restante,
        nombre_cliente,status_reservacion,nombre_usuario FROM vista_reservaciones_completa WHERE id_reservacion = :id;');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function crearHospedaje($fecha_entrada, $fecha_salida, $adelanto, $usuario_id, $cliente_id, $habitacion_id)
    {
        $this->db->query('CALL spCrearHospedaje(:fecha_entrada,:fecha_salida,:adelanto,:usuario_id,:cliente_id,:habitacion_id);');
        $this->db->bind(':fecha_entrada', $fecha_entrada);
        $this->db->bind(':fecha_salida', $fecha_salida);
        $this->db->bind(':adelanto', $adelanto);
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':cliente_id', $cliente_id);
        $this->db->bind(':habitacion_id', $habitacion_id);
        return $this->db->single();
    }
    public function crearReservacion($fecha_entrada, $fecha_salida, $adelanto, $usuario_id, $cliente_id, $habitacion_id)
    {
        $this->db->query('CALL spCrearReservacion(:fecha_entrada,:fecha_salida,:adelanto,:usuario_id,:cliente_id,:habitacion_id);');
        $this->db->bind(':fecha_entrada', $fecha_entrada);
        $this->db->bind(':fecha_salida', $fecha_salida);
        $this->db->bind(':adelanto', $adelanto);
        $this->db->bind(':usuario_id', $usuario_id);
        $this->db->bind(':cliente_id', $cliente_id);
        $this->db->bind(':habitacion_id', $habitacion_id);
        return $this->db->single();
    }

    public function terminarReservacion($id_reservacion, $tipo_pago)
    {
        $this->db->query('CALL spCheckOut(:id_reservacion,:tipo_pago);');
        $this->db->bind(':id_reservacion', $id_reservacion);
        $this->db->bind(':tipo_pago', $tipo_pago);
        return $this->db->single();
    }

    public function RealizarCheckIn($id_habitacion, $id_reservacion)
    {
        $this->db->query('CALL spCheckIn(:id_reservacion,:id_habitacion);');
        $this->db->bind(':id_habitacion', $id_habitacion);
        $this->db->bind(':id_reservacion', $id_reservacion);
        return $this->db->single();
    }

    public function reservasAnual()
    {
        $this->db->query('SELECT 
        m.mes AS mes,
        IFNULL(r.total_reservas, 0) AS total_reservas,
        YEAR(CURDATE()) AS anio
      FROM (
        SELECT 1 AS mes UNION ALL
        SELECT 2 UNION ALL
        SELECT 3 UNION ALL
        SELECT 4 UNION ALL
        SELECT 5 UNION ALL
        SELECT 6 UNION ALL
        SELECT 7 UNION ALL
        SELECT 8 UNION ALL
        SELECT 9 UNION ALL
        SELECT 10 UNION ALL
        SELECT 11 UNION ALL
        SELECT 12
      ) AS m
      LEFT JOIN vwReporteReservasMes r ON m.mes = r.mes
      ORDER BY m.mes;');
        return $this->db->resultSet();
    }

    public function reservacionesPorMes($mes,$anio){
        $this->db->query('CALL sp_obtener_reservaciones_mes(:mes,:anio);');
        $this->db->bind(':mes', $mes);
        $this->db->bind(':anio', $anio);
        return $this->db->resultSet();

    }

    public function cancelarReserva($id){
        $this->db->query('CALL cancelar_reservacion(:id);');
        $this->db->bind(':id', $id);
        return $this->db->execute(); 
    }

}
