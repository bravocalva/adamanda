<?php
require_once __DIR__ . '/../core/Database.php';
class SucursalModel
{
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }

    public function getSucusal() {
        $this->db->query('SELECT * FROM sucursal where id_sucursal = 1');
        return $this->db->single();
    }

    public function updateSucursal($nombre,$direccion,$ciudad,$telefono,$correo,$rfc,$ruta) {
        $this->db->query('call spUpdSucursal(:nombre,:direccion,:ciudad,:telefono,:correo,:rfc, :ruta)');
        $this->db->bind(':nombre',$nombre);
        $this->db->bind(':direccion',$direccion);
        $this->db->bind(':ciudad',$ciudad);
        $this->db->bind(':telefono',$telefono);
        $this->db->bind(':correo',$correo);
        $this->db->bind(':rfc',$rfc);
        $this->db->bind(':ruta',$ruta);
        
        return $this->db->execute();

    }

}