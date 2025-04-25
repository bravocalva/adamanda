<?php
require_once __DIR__ . '/../core/Database.php';

class TipoHabitacionModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllTipoHabitacion()
    {
        $this->db->query('SELECT * FROM tipo_habitacion');
        return $this->db->resultSet();
    }

    public function getTipoHabitacion($id)
    {
        $this->db->query('SELECT * FROM tipo_habitacion WHERE id_tipo_hab = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function deleteTipoHabitacion($id)
    {
        $this->db->query('CALL spDelTipoHabitacion(:id);');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createTipoHabitacion($nombre, $descripcion, $precio)
    {
        $this->db->query('CALL spInsTipoHabitacion(:nombre, :descripcion, :precio);');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':precio', $precio);

        return $this->db->single();
    }

    public function updateTipoHabitacion($id, $nombre, $descripcion, $precio)
    {
        $this->db->query('CALL spUpdTipoHabitacion(:id, :nombre, :descripcion, :precio);');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':precio', $precio);

        return $this->db->single();
    }
}
