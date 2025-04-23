<?php
require_once 'config/Database.php';

class AuthController {
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = $_POST['usuario'] ?? '';
        $clave = $_POST['clave'] ?? '';

        $db = Database::getInstance();
        $conn = $db->getConnection();
        if (!$conn) {
            echo "<div style='color: red; text-align: center;'>Error de conexión a la base de datos</div>";
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM t_usuario WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($clave, $user['password'])) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['ultimo_acceso'] = time();
            header("Location: index.php");
        } else {
            echo "<script>alert('Usuario o contraseña incorrecta'); window.location='index.php?action=loginform';</script>";
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: index.php?action=loginform");
        exit();
    }
    

    public function mostrarLogin() {
        include 'views/auth/login.php';
    }


    public function mostrarCrearUsuarioForm() {
        include 'views/auth/crear_usuario_form.php';
    //    include 'productos/listar.php';
    }
    
    public function crearUsuario() {
        if (isset($_POST['usuario']) && isset($_POST['clave'])) {
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $estado = $_POST['estado'];
            $usuario = $_POST['usuario'];
            $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
            $activo = $_POST['activo'];
    
            require_once 'config/Database.php';
            $db = new Database();
            $conn = $db->connect();
    
            $stmt = $conn->prepare("INSERT INTO t_usuario (nombre, correo, estado, usuario, password, activo) VALUES (?, ?, ?, ?, ?, ?)");
    
            if ($stmt->execute([$nombre, $correo, $estado, $usuario, $clave, $activo])) {
                echo "<div class='alert alert-success text-center mt-4'>Usuario creado correctamente. <a href='index.php'>Volver al login</a></div>";
            } else {
                echo "<div class='alert alert-danger text-center mt-4'>Error al crear el usuario.</div>";
            }
        }
    }
    
    
}
