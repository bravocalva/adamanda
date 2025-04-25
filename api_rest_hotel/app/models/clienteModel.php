<?php
require_once __DIR__ . '/../core/Database.php';

class ClienteModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function getAllCliente(){
        $this->db->query('SELECT * FROM cliente');
        return $this->db->resultSet();

    }

    public function getCliente($id) {
        $this->db->query('SELECT * FROM cliente where id_cliente = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function deleteCliente($id){
        $this->db->query('call spDelCliente(:id);');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function createCliente($curp,$nombre,$apellido_p,$apellido_m,$telefono,$correo){
        $this->db->query('call spInsCliente(:curp,:nombre,:apellido_p,:apellido_m,:telefono,:correo);');
        $this->db->bind(':curp',$curp);
        $this->db->bind(':nombre',$nombre);
        $this->db->bind(':apellido_p',$apellido_p);
        $this->db->bind(':apellido_m',$apellido_m);
        $this->db->bind(':telefono',$telefono);
        $this->db->bind(':correo',$correo);
       
        return $this->db->single();
    } 

    public function updateCliente($id,$curp,$nombre,$apellido_p,$apellido_m,$telefono,$correo){
        $this->db->query('call spUpdCliente(:id,:curp,:nombre,:apellido_p,:apellido_m,:telefono,:correo);');
        $this->db->bind(':id',$id);
        $this->db->bind(':curp',$curp);
        $this->db->bind(':nombre',$nombre);
        $this->db->bind(':apellido_p',$apellido_p);
        $this->db->bind(':apellido_m',$apellido_m);
        $this->db->bind(':telefono',$telefono);
        $this->db->bind(':correo',$correo);     
        return $this->db->single();
    }

}
