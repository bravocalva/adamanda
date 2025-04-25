<?php
require_once __DIR__ . '/../core/Database.php';
class UserModel{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUsers(){
        $this->db->query('SELECT * FROM vwRptUsuarios');
        return $this->db->resultSet();
    }
   
    public function deleteUser($id){
        $this->db->query('DELETE FROM usuario where id_usuario = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUserById($id){
        $this->db->query('SELECT * FROM usuario where id_usuario = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAccess($email,$password) {
        $this->db->query('call spValidarAcceso(:email, :password );');
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $password);
        return $this->db->single();
    }
    
    
    public function createUsuario($nombre, $apellido_p,$apellido_m,$email,$password,$rol) {
        $this->db->query('CALL spInsUsuario(:nombre, :apellido_p ,:apellido_m ,:email ,:password ,:rol );');
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':apellido_p', $apellido_p);
        $this->db->bind(':apellido_m', $apellido_m);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $password);
        $this->db->bind(':rol', $rol);
        return $this->db->single();
    }

    public function updateUsuario($id,$nombre, $apellido_p,$apellido_m,$email,$password,$rol) {
        $this->db->query('CALL spUpdUsuario(:id,:nombre, :apellido_p ,:apellido_m ,:email ,:password ,:rol );');
        $this->db->bind(':id', $id);
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':apellido_p', $apellido_p);
        $this->db->bind(':apellido_m', $apellido_m);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $password);
        $this->db->bind(':rol', $rol);
        return $this->db->single();
    }
    

    }