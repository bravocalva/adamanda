<?php
require_once __DIR__ . '/../core/Database.php';

class RolModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getRoles() {
        $this->db->query('SELECT * FROM rol');
        return $this->db->resultSet();
    }

    public function getRolById($id) {
        $this->db->query('SELECT * FROM rol WHERE id_rol = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createRol($nombre, $descripcion) {
        $this->db->query('INSERT INTO rol (nombre, descripcion) VALUES (:nombre, :descripcion)');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        return $this->db->execute();
    }

    public function updateRol($id, $nombre, $descripcion) {
        $this->db->query('UPDATE rol SET nombre = :nombre, descripcion = :descripcion WHERE id_rol = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        return $this->db->execute();
    }

    public function deleteRol($id) {
        $this->db->query('DELETE FROM rol WHERE id_rol = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    
}