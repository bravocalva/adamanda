<?php
require_once __DIR__ . '/../models/facturaModel.php';

class facturaController{

    private $facturaModel;

    public function __construct()
    {
        $this->facturaModel = new facturaModel();
    }

    public function getGananciaMensual()
    {
        $result = $this->facturaModel->getGananciaMes();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getAll()
    {
        $result = $this->facturaModel->getFacturas();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getDetallesFactura($id)
    {
        $result = $this->facturaModel->getDatosCompletos($id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }


    
}