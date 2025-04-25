<?php
require_once __DIR__ . '/../models/RolModel.php';

class RolController {
    private $rolModel;

    public function __construct() {
        $this->rolModel = new RolModel();
    }

    // Obtener todos los roles
    public function getRoles() {
        $roles = $this->rolModel->getRoles();
        echo json_encode($roles); // Respuesta en JSON
    }

    // Obtener un rol por ID
    public function getRol($id_rol) {
        $rol = $this->rolModel->getRolById($id_rol);
        if ($rol) {
            echo json_encode($rol);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Rol no encontrado']);
        }
    }

    // Crear un nuevo rol
    public function createRol() {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->rolModel->createRol($data['nombre'], $data['descripcion'])) {
            http_response_code(201);
            echo json_encode(['message' => 'Rol creado']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al crear el rol']);
        }
    }

    // Actualizar un rol existente
    public function updateRol($id_rol) {
        $data = json_decode(file_get_contents('php://input'), true);
        if ($this->rolModel->updateRol($id_rol, $data['nombre'], $data['descripcion'])) {
            echo json_encode(['message' => 'Rol actualizado']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al actualizar el rol']);
        }
    }

    // Eliminar un rol
    public function deleteRol($id_rol) {
        if ($this->rolModel->deleteRol($id_rol)) {
            echo json_encode(['message' => 'Rol eliminado']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Error al eliminar el rol']);
        }
    }
}