<?php
require_once __DIR__ . '/../models/clienteModel.php';
class clienteController
{

    private $clienteModel;
    public function __construct()
    {
        $this->clienteModel = new clienteModel();
    }


    public function getAll()
    {
        $result = $this->clienteModel->getAllCliente();
        echo json_encode($result); // Respuesta en JSON
    }
    public function getClienteById($id)
    {

        $result = $this->clienteModel->getCliente($id);
        if ($result) {
            echo json_encode($result); // Respuesta en JSON
        } else {
            echo json_encode(array('error' => 'No se encontraron resultados')); // Respuesta
        }
    }

    // Crear un nuevo usuario
    public function createNewClient()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $this->clienteModel->createCliente($data['curp'], $data['nombre'], $data['apellido_p'], $data['apellido_m'], $data['telefono'], $data['correo']);

        // Verificar el valor de la bandera retornada por el procedimiento
        if ($result->status === '0') {
            // Registro exitoso
            $response = [
                "statusExec" => true,
                "msg" => "Cliente creado con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } elseif ($result->status === '1') {
            // Error, el correo ya existe
            $response = [
                "statusExec" => false,
                "msg" => "El curp ingresado ya esta registrado",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(201); // Código de estado 400: Solicitud incorrecta
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Error en la inserción
            $response = [
                "statusExec" => false,
                "msg" => "Error al crear el cliente",
                "ban" => 1,
                "datos" => null
            ];
            http_response_code(500); // Código de estado 500: Error interno del servidor
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }

    public function deleteCliente($id)
    {
        $result = $this->clienteModel->deleteCliente($id);
        // Verificar el valor de la bandera retornada por el procedimiento
        if ($result->status === '0') {
            // Registro exitoso
            $response = [
                "statusExec" => true,
                "msg" => "Cliente eliminado con éxito",
                "ban" => 0
            ];
            http_response_code(201);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Registro exitoso
            $response = [
                "statusExec" => true,
                "msg" => "Cliente no encontrado",
                "ban" => 1
            ];
            http_response_code(500);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }

    public function updateCliente()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        // Actualizar el usuario
        try {
            $result = $this->clienteModel->updateCliente(
                $data['id'], //agregar en data exactamente como esta escrito en el modelo
                $data['curp'],
                $data['nombre'],
                $data['apellido_p'],
                $data['apellido_m'],
                $data['telefono'],
                $data['correo']
            );

            // Manejar el resultado
            if ($result->status === '0') {
                // Éxito
                http_response_code(200);
                echo json_encode([
                    "statusExec" => true,
                    "msg" => "Cliente actualizado con éxito",
                    "ban" => 0,
                    "datos" => $data
                ], JSON_UNESCAPED_UNICODE);
            } //end if

             // Manejar el resultado
             if ($result->status === '1') {
                // Éxito
                http_response_code(500);
                echo json_encode([
                    "statusExec" => true,
                    "msg" => "CURP ingresado ya existente",
                    "ban" => 1,
                    "datos" => $data
                ], JSON_UNESCAPED_UNICODE);
            } //end if

             // Manejar el resultado
             if ($result->status === '2') {
                // Éxito
                http_response_code(500);
                echo json_encode([
                    "statusExec" => true,
                    "msg" => "No existe el id ingresado",
                    "ban" => 2,
                    "datos" => $data
                ], JSON_UNESCAPED_UNICODE);
            } //end if

        } catch (Exception $e) {
            // Capturar excepciones inesperadas
            http_response_code(500);
            echo json_encode([
                "statusExec" => false,
                "msg" => "Error interno del servidor: " . $e->getMessage(),
                "ban" => -1,
                "datos" => null
            ], JSON_UNESCAPED_UNICODE);
        }
    } //end function

}
