<?php 
require_once __DIR__ . '/../core/Database.php';

class cargosModel
{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCargosById($id){
        $this->db->query('SELECT * FROM cargo_adicional WHERE reservacion_id_reservacion = :id');
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function insertCargo($id_reservacion,$descripcion,$monto)
    {
        $this->db->query('CALL spInsCargoAd(:id_reservacion,:descripcion,:monto);');
        $this->db->bind(':id_reservacion', $id_reservacion);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':monto', $monto);
        return $this->db->single(); 
    }

    public function deleteCargo($id){
        $this->db->query('CALL spDelCargoAd(:id);');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

}