<?php
// Cargar controladores necesarios
require_once 'controllers/web/ProductoController.php';
require_once 'controllers/web/AuthController.php';
require_once 'controllers/web/MainController.php';

session_start();

$tiempo_inactividad = 900; //900 15 minutos

if (isset($_SESSION['ultimo_acceso'])) {
    $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
    if ($tiempo_transcurrido > $tiempo_inactividad) {
        session_unset();
        session_destroy();
        header("Location: index.php?action=loginform&mensaje=expirada");
        exit();
    } else {
        $_SESSION['ultimo_acceso'] = time(); // Actualiza el tiempo de acceso
    }
}

// Si no hay sesión activa y no estás accediendo a login, al formulario de login, o a crearusuarioform, redirigir
//if (!isset($_SESSION['usuario']) && !isset($_GET['action']) || !in_array($_GET['action'], ['login', 'loginform', 'crearusuarioform','crearusuario'])) {
//    header("Location: index.php?action=loginform");
//    exit();
//}
if (!isset($_SESSION['usuario']) && (!isset($_GET['action']) || !in_array($_GET['action'], ['login', 'loginform', 'crearusuarioform','crearusuario']))) {
    header("Location: index.php?action=loginform");
    exit();
}


// Ruteo de acciones
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'crear':
        $controller = new ProductoController();
        $controller->crear();
        break;

    case 'guardar':
        $controller = new ProductoController();
        $controller->guardar();
        break;

    case 'editarproductoform':
        $controller = new ProductoController();
        $controller->editarProducto($id);
        break;

    case 'actualizar':
        $controller = new ProductoController();
        $controller->actualizarProducto();
        break;

    case 'eliminarproducto':
        $controller = new ProductoController();
        $controller->eliminarProducto($id);
        break;

    case 'loginform':
        $auth = new AuthController();
        $auth->mostrarLogin();
        break;

    case 'login':
        $auth = new AuthController();
        $auth->login();
        break;

    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
    
    case 'crearusuarioform':
        $auth = new AuthController();
        $auth->mostrarCrearUsuarioForm();
        break;
    
    case 'crearusuario':
        $auth = new AuthController();
        $auth->crearUsuario();
        break;
        
    case 'listarproductos':
        $controller = new ProductoController();
        $controller->listarProductos();
        break;
    default:
        $controller = new MainController();
        $controller->menuPrincipal();
        break;
    
}

