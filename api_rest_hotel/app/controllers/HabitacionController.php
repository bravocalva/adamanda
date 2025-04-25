<?php
require_once __DIR__ . '/../models/HabitacionModel.php';

class HabitacionController
{
    private $habitacionModel;

    public function __construct()
    {
        $this->habitacionModel = new HabitacionModel();
    }
    public function getAllInfo()
    {
        $result = $this->habitacionModel->getAllInfoHabitaciones();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getOcupadas(){
        $result = $this->habitacionModel->getHabitacionesOcupadas();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getAllInfoById($id)
    {
        $result = $this->habitacionModel->getAllInfoHabitacionesById($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function getAll()
    {
        $result = $this->habitacionModel->getAllHabitaciones();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getHabitacionById($id)
    {
        $result = $this->habitacionModel->getHabitacion($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function createHabitacion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->habitacionModel->createHabitacion(
            $data['numero'],
            $data['descripcion'],
            $data['tipo_habitacion_id'],
            $data['estado_id'],
            $data['ruta_imagen']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Habitación creada con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
        } elseif ($result->status === '1') {
            $response = [
                "statusExec" => false,
                "msg" => "El número de habitación ya existe",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(400);
        } elseif ($result->status === '2') {
            $response = [
                "statusExec" => false,
                "msg" => "Error al crear la habitación",
                "ban" => 2,
                "datos" => null
            ];
            http_response_code(500);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function updateHabitacion()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->habitacionModel->updateHabitacion(
            $data['id'],
            $data['numero'],
            $data['descripcion'],
            $data['tipo_habitacion_id'],
            $data['ruta_imagen']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Habitación actualizada con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(200);
        } elseif ($result->status === '2') {
            $response = [
                "statusExec" => false,
                "msg" => "El número ingresado ya existe",
                "ban" => 2,
                "datos" => $data
            ];
            http_response_code(400);
        } elseif ($result->status === '1') {
            $response = [
                "statusExec" => false,
                "msg" => "No existe el ID ingresado",
                "ban" => 1,
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

    public function deleteHabitacion($id)
    {
        $result = $this->habitacionModel->deleteHabitacion($id);

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Habitación eliminada con éxito",
                "ban" => 0
            ];
            http_response_code(200);
        } elseif ($result->status === '2') {
            $response = [
                "statusExec" => false,
                "msg" => "La habitación está ocupada, no se puede eliminar",
                "ban" => 2
            ];
            http_response_code(400);
        } else {
            $response = [
                "statusExec" => false,
                "msg" => "Habitación no encontrada",
                "ban" => 1
            ];
            http_response_code(404);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function GetHabitacionByDate(){
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->habitacionModel->GetByDateHabitacion($data['fecha_e'],$data['fecha_s']);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getTotalDisponible(){
        $result = $this->habitacionModel->vwTotalDisponible();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getTotalOcupado(){
        $result = $this->habitacionModel->vwTotalOcupados();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
 
    public function getTotalReservados(){
        $result = $this->habitacionModel->vwTotalReservados();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
 public function getTotalLimpieza(){
        $result = $this->habitacionModel->vwTotalLimpeza();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getHabitacionLimpieza(){
        $result = $this->habitacionModel->GetHabitacionLimpieza();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getHabitacionReservado(){
        $result = $this->habitacionModel->getHabitacionReservado();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getHabitacionMasReservado(){
        $result = $this->habitacionModel->vwHabitacionMasReservas();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function cambiarDisponible($id){
        $result = $this->habitacionModel->cambiarDisponible($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }

    }
}