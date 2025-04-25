<?php 
require_once __DIR__ . '/../core/Database.php';

class articulosModel{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    public function getAllArticulos (){
        $this->db->query('SELECT * FROM articulo');
        return $this->db->resultSet();
    }

    public function getArticuloById($id){
        $this->db->query('SELECT * FROM articulo where id_articulo = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function InsertArticulo($nombre,$descripcion,$precio){
        $this->db->query('CALL spInsArticulo(:nombre,:descripcion,:precio);');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':precio', $precio);
        $result = $this->db->single();
        return $result;
    }

    public function UpdateArticulo($id,$nombre,$descripcion,$precio){
        $this->db->query('CALL spUpdArticulo(:id,:nombre,:descripcion,:precio);');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':descripcion', $descripcion);
        $this->db->bind(':precio', $precio);
        return $this->db->single();
    }

    public function DeleteArticulo($id){
        $this->db->query('CALL spDelArticulo(:id);');
        $this->db->bind(':id', $id);
        return  $this->db->single();
        
    }

}