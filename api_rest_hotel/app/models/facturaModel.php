<?php
require_once __DIR__ . '/../core/Database.php';

class facturaModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getGananciaMes()
    {
        $this->db->query('SELECT * FROM vwReporteGananciaMes');
        return $this->db->single();
    }

    public function getFacturas(){
        $this->db->query('SELECT * FROM vwReporteFacturas');
        return $this->db->resultSet(); 
    }

    public function getDatosCompletos($id) {
        // Supongamos que el procedimiento almacenado retorna 3 tablas (reservacion, cargos, movimientos).
        $this->db->query("CALL obtener_desglose_factura(:id)");
        $this->db->bind(':id', $id);
        $result = $this->db->multiResultSet();
    
        // Si no hay cargos adicionales, aseguramos que sea un arreglo vacío
        if (empty($result[1])) {
            $result[1] = [];  // Cargos adicionales vacíos
        }
    
        // Si no hay movimientos de inventario, aseguramos que sea un arreglo vacío
        if (empty($result[2])) {
            $result[2] = [];  // Movimientos de inventario vacíos
        }
    
        return $result;
    }
    

}