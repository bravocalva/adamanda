<?php
require_once __DIR__ . '/../models/articulosModel.php';

class articuloController{

    private $articuloModel;
    function __construct()
    {
        $this->articuloModel = new articulosModel;
    }

    public function getAllArticulos()
    {
        $result = $this->articuloModel->getAllArticulos();
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    
    public function getArticuloById($id)
    {
        $result = $this->articuloModel->getArticuloById($id);
        if ($result) {
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(["error" => "No se encontraron resultados"], JSON_UNESCAPED_UNICODE);
        }
    }

    public function InsertArticulo()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->articuloModel->InsertArticulo(
            $data ['nombre'],
            $data ['descripcion'],
            $data ['precio']
        );

    try{
        if($result -> status === '0'){
            $response = [
                "statusExec" => true,
                "msg" => "Articulo creada con éxito",
                "ban" => 0,
                "datos" => $data
            ];
            http_response_code(201);
        }

        if($result -> status === '1'){
            $response = [
                "statusExec" => true,
                "msg" => "Ya existe un registro con ese nombre",
                "ban" => 1,
                "datos" => $data
            ];
            http_response_code(201);
        }
        echo json_encode($response);
    } 
    catch (Exception $e) {
        $response = [
            "statusExec" => false,
            "msg" => "Error al crear la habitación",
            "ban" => 1,
            "datos" => $data,
            "exception" => $e
            ];
            http_response_code(500);
            echo json_encode($response);
    }
    }

    public function DeleteArticulo($id){
        $result = $this->articuloModel->DeleteArticulo($id);
        if ($result->status === '0') {
            $response = [
                "statusExec" => true,
                "msg" => "Habitación eliminada con éxito",
                "ban" => 0
            ];
            http_response_code(200);
        }

        if ($result->status === '1') {
            $response = [
                "statusExec" => true,
                "msg" => "Error al eliminar no existe ID",
                "ban" => 1
            ];
            http_response_code(200);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);    
    }

    public function UpdateArticulo($id){
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $this->articuloModel->UpdateArticulo(
            $id,
            $data ['nombre'],
            $data ['descripcion'],
            $data ['precio']
        );
        try{

        if($result -> status === '0'){
            $response = [
                "statusExec" => true,
                "msg" => "Articulo actualizado con éxito",
                "ban" => 0             
            ];
            http_response_code(200);
        }

        else if ($result -> status === '1'){
            $response = [
                "statusExec" => true,
                "msg" => "Un articulo ya esta registrado con ese nombre",
                "ban" => 1              
            ];
            http_response_code(200);
        }

        else if ($result -> status === '2'){
            $response = [
                "statusExec" => true,
                "msg" => "ID no encontrado",
                "ban" => 2             
            ];
            http_response_code(200);
        }

        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        }
        catch (Exception $ex){
            echo json_encode($ex,JSON_UNESCAPED_UNICODE);
        }

    }


}