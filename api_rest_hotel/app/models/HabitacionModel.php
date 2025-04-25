<?php
require_once __DIR__ . '/../core/Database.php';

class HabitacionModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllInfoHabitaciones(){
        $this->db->query('SELECT * FROM vista_habitaciones;');
        return $this->db->resultset();
    }

    public function getAllInfoHabitacionesById($id){
        $this->db->query('SELECT * FROM vista_habitaciones WHERE id_habitacion = :id;');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllHabitaciones()
    {
        $this->db->query('SELECT * FROM habitacion');
        return $this->db->resultSet();
    }

    public function getHabitacion($id)
    {
        $this->db->query('SELECT * FROM habitacion WHERE id_habitacion = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getHabitacionesOcupadas()
    {
        $this->db->query('SELECT * FROM vwRptHabitacionesSalida');
        return $this->db->resultSet();
    }

    public function deleteHabitacion($id)
    {
        $this->db->query('CALL spDelHabitacion(:id);');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createHabitacion($numero, $descripcion, $tipo_habitacion_id, $estado_id, $ruta_imagen)
    {
        $this->db->query('CALL spInsHabitacion(:numero, :descripcion, :tipo_habitacion_id, :estado_id, :ruta_imagen);');
        $this->db->bind(':numero', $numero);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':tipo_habitacion_id', $tipo_habitacion_id);
        $this->db->bind(':estado_id', $estado_id);
        $this->db->bind(':ruta_imagen', $ruta_imagen);

        return $this->db->single();
    }

    public function updateHabitacion($id, $numero, $descripcion, $tipo_habitacion_id, $ruta_imagen)
    {
        $this->db->query('CALL spUpdHabitacion(:id, :numero, :descripcion, :tipo_habitacion_id, :ruta_imagen);');
        $this->db->bind(':id', $id);
        $this->db->bind(':numero', $numero);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':tipo_habitacion_id', $tipo_habitacion_id);
        $this->db->bind(':ruta_imagen', $ruta_imagen);

        return $this->db->single();
    }

    public function GetByDateHabitacion($fecha_e,$fecha_s){
        $this->db->query('CALL obtener_habitaciones_disponibles(:fecha_e,:fecha_s);');
        $this->db->bind(':fecha_e', $fecha_e);
        $this->db->bind(':fecha_s', $fecha_s);
        return $this->db->resultSet();
    }

    public function GetHabitacionLimpieza(){
        $this->db->query('SELECT * FROM vista_habitaciones_limpieza;');
        return $this->db->resultSet();
    }

    public function GetHabitacionReservado(){
        $this->db->query('SELECT * FROM vista_reservaciones_espera;');
        return $this->db->resultSet();
    }

    public function cambiarDisponible($id){
        $this->db->query('CALL spRealizarLimpieza(:id);');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function vwTotalDisponible(){
        $this->db->query('SELECT * FROM vwRptTotalDisp');
        return $this->db->single();
    }

    public function vwTotalOcupados(){
        $this->db->query('SELECT * FROM vwRptTotalHabitacionesOcupadas');
        return $this->db->single();
    }

    public function vwTotalReservados(){
        $this->db->query('SELECT * FROM vwRptTotalReservaciones');
        return $this->db->single();
    }
      public function vwTotalLimpeza(){
        $this->db->query('SELECT * FROM vwRptTotalLimpieza');
        return $this->db->single();
    }

    public function vwHabitacionMasReservas(){
        $this->db->query('SELECT * FROM vista_habitacion_mas_reservada_mes');
        return $this->db->single();
    }
}
