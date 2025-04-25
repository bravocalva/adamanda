<?php
// RolController.php
require_once __DIR__ . '/../models/SucursalModel.php';

class SucursalController
{

    private $sucursalModel;

    public function __construct()
    {
        $this->sucursalModel = new SucursalModel();
    }

    public function getInfo()
    {
        $suc = $this->sucursalModel->getSucusal();
        echo json_encode($suc); // Respuesta en JSON
    }

    public function updSucursal()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->sucursalModel->UpdateSucursal(
            $data['nombre'],
            $data['direccion'],
            $data['ciudad'],
            $data['telefono'],
            $data['correo'],
            $data['rfc'],
            $data['ruta']
        )) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    }
}
