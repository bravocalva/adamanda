<?php
// UserController.php
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    //Controler  
    public function getAll()
    {
        $user = $this->userModel->getUsers();
        echo json_encode($user);
    }

    // Obtener un rol por ID
    public function getUsuario($id)
    {
        $user = $this->userModel->getUserById($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Usuario no encontrado']);
        }
    }
    // Eliminar un usuario
    public function deleteUsuario($id)
    {
        if ($this->userModel->deleteUser($id)) {
            echo json_encode(['message' => 'Rol eliminado']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al eliminar el rol']);
        }
    }

    // Crear un nuevo usuario
    public function createUser()
    {
        $data = json_decode(file_get_contents('php://input'), true); // Recibimos todos los datos del formulario
        $result = $this->userModel->createUsuario($data['nombre'], $data['apellido_p'], $data['apellido_m'], $data['email'], $data['password'], $data['rol']);

        // Verificar el valor de la bandera retornada por el procedimiento
        if ($result->status === '0') {
            // Registro exitoso
            $response = [
                "statusExec" => true,
                "msg" => "Usuario creado con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } elseif ($result->status === '1') {
            // Error, el correo ya existe
            $response = [
                "statusExec" => false,
                "msg" => "El correo ya está registrado",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(201); // Código de estado 400: Solicitud incorrecta
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Error en la inserción
            $response = [
                "statusExec" => false,
                "msg" => "Error al crear el usuario",
                "ban" => 1,
                "datos" => null
            ];
            http_response_code(500); // Código de estado 500: Error interno del servidor
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
    }

    public function updateUser($id)
    {
        try {
            // // Verificar si el método de la solicitud es PUT
            // if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            //     http_response_code(405); // Método no permitido
            //     echo json_encode([
            //         "statusExec" => false,
            //         "msg" => "Método no permitido. Se esperaba una solicitud PUT.",
            //         "ban" => -1,
            //         "datos" => null
            //     ], JSON_UNESCAPED_UNICODE);
            //     return;
            // }

            // Recibir y decodificar los datos JSON
            $data = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400); // Solicitud incorrecta
                echo json_encode([
                    "statusExec" => false,
                    "msg" => "Datos JSON no válidos",
                    "ban" => -1,
                    "datos" => null
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Validar que los datos requeridos estén presentes
            if (empty($data['nombre']) || empty($data['apellido_p']) || empty($data['apellido_m']) || empty($data['email']) || empty($data['rol'])) {
                http_response_code(400); // Solicitud incorrecta
                echo json_encode([
                    "statusExec" => false,
                    "msg" => "Faltan campos obligatorios",
                    "ban" => -1,
                    "datos" => null
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            // Actualizar el usuario
            $result = $this->userModel->updateUsuario(
                $id,
                $data['nombre'],
                $data['apellido_p'],
                $data['apellido_m'],
                $data['email'],
                $data['password'] ?? null, // Password es opcional
                $data['rol']
            );

            // Manejar el resultado
            if ($result->status === '0') {
                // Éxito
                http_response_code(200);
                echo json_encode([
                    "statusExec" => true,
                    "msg" => "Usuario actualizado con éxito",
                    "ban" => 0,
                    "datos" => $data
                ], JSON_UNESCAPED_UNICODE);
            } elseif ($result->status === '1') {
                // No existe el registro
                http_response_code(200);
                echo json_encode([
                    "statusExec" => false,
                    "msg" => "No existe el registro a modificar",
                    "ban" => 1,
                    "datos" => null
                ], JSON_UNESCAPED_UNICODE);
            } elseif ($result->status === '2') {
                // Conflicto (usuario ya existe)
                http_response_code(200);
                echo json_encode([
                    "statusExec" => false,
                    "msg" => "Ya existe un usuario con ese correo",
                    "ban" => 2,
                    "datos" => null
                ], JSON_UNESCAPED_UNICODE);
            } else {
                // Error desconocido
                http_response_code(500);
                echo json_encode([
                    "statusExec" => false,
                    "msg" => "Error desconocido al actualizar el usuario",
                    "ban" => -1,
                    "datos" => null
                ], JSON_UNESCAPED_UNICODE);
            }
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
    }

    public function getAccess()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            http_response_code(400);
            echo json_encode([
                "statusExec" => false,
                "msg" => "Faltan datos requeridos"
            ]);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        // Llamar al procedimiento almacenado a través del modelo
        $result = $this->userModel->getAccess($email, $password);

        // Verificar la respuesta del procedimiento almacenado
        if ($result && $result->status === '0') {
            // Acceso válido
            $response = [
                "statusExec" => true,
                "msg" => "Acceso exitoso",
                "ban" => 0,
                "datos" => [
                    "id_usuario" => $result->id_usuario,
                    "nombre" => $result->Nombre_completo,
                    "rol" => $result->rol,
                    "email" => $result->correo
                ]
            ];

            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            // Acceso denegado
            // http_response_code(401); 
            echo json_encode([
                "statusExec" => false,
                "msg" => "Credenciales incorrectas",
                "ban" => 0,
                "datos" => null
            ]);
        }
    }
}
