<?php
require_once __DIR__ . '/../models/TipoHabitacionModel.php';

class tipoHabitacionController
{
    private $tipoHabitacionModel;

    public function __construct()
    {
        $this->tipoHabitacionModel = new TipoHabitacionModel();
    }

    public function getAll()
    {
        $result = $this->tipoHabitacionModel->getAllTipoHabitacion();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getTipoHabitacionById($id)
    {
        $result = $this->tipoHabitacionModel->getTipoHabitacion($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function createTipoHabitacion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->tipoHabitacionModel->createTipoHabitacion(
            $data['nombre'],
            $data['descripcion'],
            $data['precio']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Tipo de habitación creada con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
        } elseif ($result->status === '1') {
            $response = [
                "statusExec" => false,
                "msg" => "El nombre de la habitación ya existe",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(400);
        } else {
            $response = [
                "statusExec" => false,
                "msg" => "Error al crear el tipo de habitación",
                "ban" => 2,
                "datos" => null
            ];
            http_response_code(500);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function updateTipoHabitacion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->tipoHabitacionModel->updateTipoHabitacion(
            $data['id'],
            $data['nombre'],
            $data['descripcion'],
            $data['precio']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Tipo de habitación actualizado con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(200);
        } elseif ($result->status === '1') {
            $response = [
                "statusExec" => false,
                "msg" => "El nombre ingresado ya existe",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(400);
        } elseif ($result->status === '2') {
            $response = [
                "statusExec" => false,
                "msg" => "No existe el ID ingresado",
                "ban" => 2,
                "datos" => $data
            ];
            http_response_code(404);
        } else {
            $response = [
                "statusExec" => false,
                "msg" => "Error interno del servidor",
                "ban" => -1,
                "datos" => null
            ];
            http_response_code(500);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function deleteTipoHabitacion($id)
    {
        $result = $this->tipoHabitacionModel->deleteTipoHabitacion($id);

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Tipo de habitación eliminado con éxito",
                "ban" => 0
            ];
            http_response_code(200);
        } else {
            $response = [
                "statusExec" => false,
                "msg" => "Tipo de habitación no encontrado",
                "ban" => 1
            ];
            http_response_code(404);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
