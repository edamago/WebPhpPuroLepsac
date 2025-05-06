<?php
$request_uri = $_SERVER['REQUEST_URI'];
$api_prefix = '/pro/api/';

if (strpos($request_uri, $api_prefix) === 0) {
    $uri = str_replace('/pro/api', '', $request_uri);
    $uri = trim($uri, '/'); // Quita barras iniciales y finales
    $method = $_SERVER['REQUEST_METHOD'];

    //echo "Request URI: " . $request_uri . "<br />";
    //echo "REQUEST_METHOD: " . $method . "<br />";
    //echo "URI después de eliminar '/pro/api': " . $uri . "<br />";

    $segments = explode('/', $uri);

    if ($segments[0] === 'producto') {
        $id = $segments[1] ?? null;

        switch ($method) {
            case 'GET':
                if ($id) {
                    $_GET['id'] = $id;
                    require_once 'routes/api/producto/obtener.php';
                } else {
                    require_once 'routes/api/producto/listar.php';
                }
                break;
            case 'POST':
                require_once 'routes/api/producto/crear.php';
                break;
            case 'PUT':
                if ($id) {
                    $_GET['id'] = $id;
                    require_once 'routes/api/producto/actualizar.php';
                } else {
                    echo json_encode(['error' => 'ID no especificado en la ruta']);
                }
                break;
            case 'DELETE':
                if ($id) {
                    $_GET['id'] = $id;
                    require_once 'routes/api/producto/eliminar.php';
                } else {
                    echo json_encode(['error' => 'ID no especificado en la ruta']);
                }
                break;
            default:
                echo json_encode(['error' => 'Método no soportado']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Ruta de la API no encontrada']);
    }

} else {
    // ------------------- MVC CLÁSICO ---------------------
    require_once 'controllers/web/ProductoController.php';
    require_once 'controllers/web/AuthController.php';
    require_once 'controllers/web/MainController.php';

    session_start();

    $tiempo_inactividad = 900;

    if (isset($_SESSION['ultimo_acceso'])) {
        $tiempo_transcurrido = time() - $_SESSION['ultimo_acceso'];
        if ($tiempo_transcurrido > $tiempo_inactividad) {
            session_unset();
            session_destroy();
            header("Location: index.php?action=loginform&mensaje=expirada");
            exit();
        } else {
            $_SESSION['ultimo_acceso'] = time();
        }
    }

    if (!isset($_SESSION['usuario']) && (!isset($_GET['action']) || !in_array($_GET['action'], ['login', 'loginform', 'crearusuarioform', 'crearusuario']))) {
        header("Location: index.php?action=loginform");
        exit();
    }

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
}
?>
