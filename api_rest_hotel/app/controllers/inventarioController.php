<?php
require_once __DIR__ . '/../models/inventarioModel.php';

class inventarioController
{
    private $inventarioModel;
    function __construct()
    {
        $this->inventarioModel = new inventarioModel;
    }

    public function getAllInventarioById($id)
    {
        $result = $this->inventarioModel->getInventarioById($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function insertArticulo()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->inventarioModel->insertArticulo($data['id_habitacion'], $data['id_articulo'], $data['cantidad']);
        if ($result) {
            echo json_encode(['message' => 'Articulo Ingresado con Exito']);
        } else {
            echo json_encode(['message' => 'Error al Ingresar Articulo']);
        }
    }

    public function RemoveArticulo()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->inventarioModel->RemoveArticulo($data['id_habitacion'], $data['id_articulo'], $data['cantidad']);
        if ($result) {
            echo json_encode(['message' => 'Articulo Removido con Exito']);
        } else {
            echo json_encode(['message' => 'Error al remover Articulo']);
        }
    }


    public function RemoveVariosArt() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Validar que sea un array no vacío
        if (!is_array($data) || empty($data)) {
            http_response_code(400);
            echo json_encode(['message' => 'Se esperaba un arreglo de artículos.']);
            return;
        }
        
        $result = $this->inventarioModel->RemoveVariosArticulos($data);
        
        if ($result) {
            echo json_encode(['message' => 'Artículos ingresados con éxito', 'data' => $result]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al ingresar artículos']);
        }
    }
}
