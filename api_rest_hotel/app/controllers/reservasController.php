<?php
require_once __DIR__ . '/../models/reservasModel.php';

class reservasController
{
    private $reservasModel;

    public function __construct()
    {
        $this->reservasModel = new reservasModel();
    }

    public function getAllReservas()
    {
        $result = $this->reservasModel->getAllReservas();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function getReservaById($id)
    {
        $result = $this->reservasModel->getReservaById($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }
    }


    public function cancelarReservacion($id)
    {
        $result = $this->reservasModel->cancelarReserva($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } 
    }

    public function crearHospedajeInmediato()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->reservasModel->crearHospedaje(
            $data['fecha_entrada'],
            $data['fecha_salida'],
            $data['adelanto'],
            $data['usuario_id'],
            $data['cliente_id'],
            $data['habitacion_id']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Hospedaje creado exitosamente",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
        }
        if ($result->status === '1') {
            $response = [
                "statusExec" => true,
                "msg" => "Habitacion no disponible para hospedar",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(201);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function crearReservacion()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->reservasModel->crearReservacion(
            $data['fecha_entrada'],
            $data['fecha_salida'],
            $data['adelanto'],
            $data['usuario_id'],
            $data['cliente_id'],
            $data['habitacion_id']
        );

        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Reserva creada exitosamente",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
        }
        if ($result->status === '1') {
            $response = [
                "statusExec" => true,
                "msg" => "Habitacion no disponible para reservar",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(201);
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    public function terminarReservacion()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->reservasModel->terminarReservacion(
            $data['id_reservacion'],
            $data['tipo_pago']
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function CheckIn()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->reservasModel->RealizarCheckIn(
            $data['id_habitacion'],
            $data['id_reservacion']
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function reservasAnual() {
        $result = $this->reservasModel->reservasAnual();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function reservasPorMes(){   
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->reservasModel->reservacionesPorMes(
            $data['mes'],
            $data['anio']
        );
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
