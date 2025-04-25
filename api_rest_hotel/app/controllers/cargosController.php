<?php
require_once __DIR__ . '/../models/cargosModel.php';
class cargosController
{
    private $cargosModel;

    public function __construct()
    {
        $this->cargosModel = new cargosModel();
    }

    public function getCargosById($id)
    {
        $result = $this->cargosModel->getCargosById($id);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function insertCargoAdicional()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->cargosModel->insertCargo(
            $data['id_reservacion'],
            $data['descripcion'],
            $data['monto']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Cargo exitosamente agregado",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
        } elseif ($result->status === '1') {
            $response = [
                "statusExec" => false,
                "msg" => "El ID de reservacion no existe",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(400);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function deleteCargo($id)
    {
        $result = $this->cargosModel->deleteCargo($id);
        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Cargo exitosamente eliminado",
                "ban" => 0
            ];
            http_response_code(201);
        }

        if ($result->status === '1') {
            $response = [
                "statusExec" => true,
                "msg" => "Error con el ID ingresado",
                "ban" => 1
            ];
            http_response_code(400);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
