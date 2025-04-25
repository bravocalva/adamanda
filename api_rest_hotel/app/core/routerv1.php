<?php
require_once __DIR__ . '/../controllers/RolController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/SucursalController.php';
require_once __DIR__ . '/../controllers/clienteController.php';
require_once __DIR__ . '/../controllers/tipoHabitacionController.php';
require_once __DIR__ . '/../controllers/HabitacionController.php';
require_once __DIR__ . '/../controllers/articuloController.php';
require_once __DIR__ . '/../controllers/inventarioController.php';
require_once __DIR__ . '/../controllers/reservasController.php';
require_once __DIR__ . '/../controllers/cargosController.php';

class Router
{
    private $rolController;
    private $userController;
    private $sucursalController;
    private $clienteController;
    private $tipoHabitacionController;
    private $habitacionController;
    private $articuloController;
    private $inventarioController;
    private $reservasController;
    private $cargosController;
    private $method;

    public function __construct()
    {
        $this->rolController = new RolController();
        $this->userController = new UserController();
        $this->sucursalController = new SucursalController();
        $this->clienteController = new clienteController();
        $this->tipoHabitacionController = new tipoHabitacionController();
        $this->habitacionController = new HabitacionController();
        $this->articuloController = new articuloController();
        $this->inventarioController = new inventarioController();
        $this->reservasController = new reservasController();
        $this->cargosController = new cargosController();
    }

    public function route($url, $method)
    {
        $this->method = $method;

        switch (true) {
        //     case $url === '/roles' && $this->method === 'GET':
        //         $this->rolController->getRoles();
        //         break;

        //     case $url === '/roles' && $this->method === 'POST':
        //         $this->rolController->createRol();
        //         break;

        //     case preg_match('/\/roles\/(\d+)/', $url, $matches):
        //         if ($this->method === 'GET') {
        //             $this->rolController->getRol($matches[1]);
        //         } elseif ($this->method === 'PUT') {
        //             $this->rolController->updateRol($matches[1]);
        //         } elseif ($this->method === 'DELETE') {
        //             $this->rolController->deleteRol($matches[1]);
        //         }
        //         break;

            // case $url === '/usuarios' && $this->method === 'GET':
            //     $this->userController->getAll();
            //     break;

            // case $url === '/usuarios' && $this->method === 'POST':
            //     $this->userController->createUser();
            //     break;

            // case preg_match('/\/usuarios\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->userController->getUsuario($matches[1]);
            //     } elseif ($this->method === 'PUT') {
            //         $this->userController->updateUser($matches[1]);
            //     } elseif ($this->method === 'DELETE') {
            //         $this->userController->deleteUsuario($matches[1]);
            //     }
            //     break;

            // case $url === '/login' && $this->method === 'POST': // Ruta para login
            //     $this->userController->getAccess();
            //     break;

            // case $url === '/sucursal' && $this->method === 'GET':
            //     $this->sucursalController->getInfo();
            //     break;

            // case $url === '/sucursal' && $this->method === 'PUT':
            //     $this->sucursalController->updSucursal();
            //     break;

            // --------------------------------------------------------------------------

            // case $url === '/cliente' && $this->method === 'GET':
            //     $this->clienteController->getAll();
            //     break;

            // case $url === '/cliente' && $this->method === 'POST': // Ruta para crear un cliente
            //     $this->clienteController->createNewClient();
            //     break;

            // case $url === '/cliente' && $this->method === 'PUT': // Ruta para actualizar un cliente
            //     $this->clienteController->updateCliente();
            //     break;

            // case preg_match('/\/cliente\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->clienteController->getClienteById($matches[1]);
            //     } elseif ($this->method === 'DELETE') {
            //         $this->clienteController->deleteCliente($matches[1]);
            //     }
            //     break;

            // --------------------------------------------------------------------------

            // Rutas para tipoHabitacion
            // case $url === '/tipohabitacion' && $this->method === 'GET':
            //     $this->tipoHabitacionController->getAll();
            //     break;

            // case $url === '/tipohabitacion' && $this->method === 'POST': // Ruta para crear un tipo de habitación
            //     $this->tipoHabitacionController->createTipoHabitacion();
            //     break;

            // case $url === '/tipohabitacion' && $this->method === 'PUT': // Ruta para actualizar un tipo de habitación
            //     $this->tipoHabitacionController->updateTipoHabitacion();
            //     break;

            // case preg_match('/\/tipohabitacion\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->tipoHabitacionController->getTipoHabitacionById($matches[1]);
            //     } elseif ($this->method === 'DELETE') {
            //         $this->tipoHabitacionController->deleteTipoHabitacion($matches[1]);
            //     }
            //     break;
            // ------------------------------------------------------------------------------
            // Rutas para habitaciones
            // case $url === '/habitaciones' && $this->method === 'GET':
            //     $this->habitacionController->getAll();
            //     break;

            // case $url === '/habitaciones' && $this->method === 'POST':
            //     $this->habitacionController->createHabitacion();
            //     break;

            // case $url === '/habitaciones' && $this->method === 'PUT':
            //     $this->habitacionController->updateHabitacion();
            //     break;

            // case $url === '/habitaciones_info' && $this->method === 'GET':
            //     $this->habitacionController->getAllInfo();
            //     break;

            // case $url === '/habitaciones_fecha' && $this->method === 'POST':
            //     $this->habitacionController->GetHabitacionByDate();
            //     break;

            // case $url === '/habitaciones_ocupadas' && $this->method === 'GET':
            //     $this->habitacionController->getOcupadas();
            //     break;

            // case $url === '/habitacion_mas_reservada' && $this->method === 'GET':
            //     $this->habitacionController->getHabitacionMasReservado();
            //     break;
            // -----------------------------------------------------------------------

            // case $url === '/hab_disponible' && $this->method === 'GET':
            //     $this->habitacionController->getTotalDisponible();
            //     break;

            // case $url === '/hab_ocupado' && $this->method === 'GET':
            //     $this->habitacionController->getTotalOcupado();
            //     break;

            // case $url === '/hab_reservados' && $this->method === 'GET':
            //     $this->habitacionController->getTotalReservados();
            //     break;
            // case $url === '/hab_limpieza' && $this->method === 'GET':
            //     $this->habitacionController->getTotalLimpieza();
            //     break;

            // case $url === '/all_hab_limpieza' && $this->method === 'GET':
            //     $this->habitacionController->getHabitacionLimpieza();
            //     break;

            // case $url === '/all_hab_reservado' && $this->method === 'GET':
            //     $this->habitacionController->getHabitacionReservado();
            //     break;

            // case preg_match('/\/cambiar_disponible\/(\d+)/', $url, $matches):
            //     if ($this->method === 'POST') {
            //         $this->habitacionController->cambiarDisponible($matches[1]);
            //     }
            //     break;


            // ------------------------------------------------------------------------------
            // case preg_match('/\/habitaciones_info\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->habitacionController->getAllInfoById($matches[1]);
            //     }
            //     break;

            // case preg_match('/\/habitaciones\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->habitacionController->getHabitacionById($matches[1]);
            //     } elseif ($this->method === 'DELETE') {
            //         $this->habitacionController->deleteHabitacion($matches[1]);
            //     }

            //     break;

            // ------------------------------------------------------------------------------
            // Rutas para articulo
            // case $url === '/articulo' && $this->method === 'GET':
            //     $this->articuloController->getAllArticulos();
            //     break;

            // case $url === '/articulo' && $this->method === 'POST':
            //     $this->articuloController->InsertArticulo();
            //     break;

            // case preg_match('/\/articulo\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->articuloController->getArticuloById($matches[1]);
            //     } elseif ($this->method === 'DELETE') {
            //         $this->articuloController->DeleteArticulo($matches[1]);
            //     } elseif ($this->method === 'PUT') {
            //         $this->articuloController->UpdateArticulo($matches[1]);
            //     }
            //     break;

            // =====================================================================================

            // case $url === '/inventario_hab' && $this->method === 'POST':
            //     $this->inventarioController->insertArticulo();
            //     break;

            // case $url === '/inventario_hab' && $this->method === 'DELETE':
            //     $this->inventarioController->RemoveArticulo();
            //     break;

            // case $url === '/inventario_faltante' && $this->method === 'POST':
            //     $this->inventarioController->RemoveVariosArt();
            //     break;


            // case preg_match('/\/inventario_hab\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->inventarioController->getAllInventarioById($matches[1]);
            //     }
            //     break;

            // Rutas para reservas -----------------------------------------------------
            // case $url === '/reservas' && $this->method === 'GET':
            //     $this->reservasController->getAllReservas();
            //     break;

            // // Rutas para reservas -----------------------------------------------------
            // case $url === '/hospedar_inm' && $this->method === 'POST':
            //     $this->reservasController->crearHospedajeInmediato();
            //     break;

            // // Rutas para reservas -----------------------------------------------------
            // case $url === '/generarReserva' && $this->method === 'POST':
            //     $this->reservasController->crearReservacion();
            //     break;
            // Rutas para reservas -----------------------------------------------------
            // case $url === '/check_out_reservacion' && $this->method === 'POST':
            //     $this->reservasController->terminarReservacion();
            //     break;
            // case $url === '/check_in_reservacion' && $this->method === 'POST':
            //      $this->reservasController->CheckIn();
            //      break;

            // case preg_match('/\/reservas\/(\d+)/', $url, $matches):
            //     if ($this->method === 'GET') {
            //         $this->reservasController->getReservaById($matches[1]);
            //     }
            //     break;

            // Rutas para cargos adicionales 
            case preg_match('/\/cargos\/(\d+)/', $url, $matches):
                if ($this->method === 'GET') {
                    $this->cargosController->getCargosById($matches[1]);
                } else if ($this->method === 'DELETE') {
                    $this->cargosController->deleteCargo($matches[1]);
                }
                break;

            case $url === '/cargos' && $this->method === 'POST':
                $this->cargosController->insertCargoAdicional();
                break;


            default:
                http_response_code(404);
                echo json_encode(['message' => 'Ruta no encontrada']);
                break;
        }
    }
}
