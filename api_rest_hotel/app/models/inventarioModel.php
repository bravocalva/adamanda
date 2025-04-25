<?php
require_once __DIR__ . '/../core/Database.php';

class inventarioModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();    
    }

    public function getInventarioById($id) {
        $this->db->query('SELECT * FROM vista_inventario_por_habitacion where id_habitacion = :id');
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function InsertArticulo($id_habitacion,$id_articulo,$cantidad){
        $this->db->query('CALL spInsertarInventarioHab(:id_habitacion, :id_articulo,:cantidad, null);');
        $this->db->bind(':id_habitacion', $id_habitacion);
        $this->db->bind(':id_articulo', $id_articulo);
        $this->db->bind(':cantidad', $cantidad);
        return $this->db->single();
    }

    public function RemoveArticulo($id_habitacion,$id_articulo,$cantidad){
        $this->db->query('CALL spQuitarInventario(:id_habitacion,:id_articulo,:cantidad,null);');
        $this->db->bind(':id_habitacion', $id_habitacion);
        $this->db->bind(':id_articulo', $id_articulo);
        $this->db->bind(':cantidad', $cantidad);
        return $this->db->single();
    }

    public function RemoveVariosArticulos($articulos) {
        $results = [];
        
        foreach ($articulos as $articulo) {
            
            $this->db->query('CALL spQuitarInventario(:id_habitacion, :id_articulo, :cantidad, :id_reservacion);');
            $this->db->bind(':id_habitacion', $articulo['id_habitacion']);
            $this->db->bind(':id_articulo', $articulo['id_articulo']);
            $this->db->bind(':cantidad', $articulo['cantidad']);
            $this->db->bind(':id_reservacion', $articulo['id_reservacion']);
            
            $result = $this->db->executeProcedure();
            if (!$result) {
                return false; // Si falla uno, retorna error
            }
            $results[] = $result;
        }
        
        return $results; // Retorna los resultados de cada SP
    }

}