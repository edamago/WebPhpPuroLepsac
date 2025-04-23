<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 220px;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .content {
            flex-grow: 1;
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5 class="text-center mb-4">Menú</h5>
        <div class="list-group">
            <a href="index.php" class="list-group-item list-group-item-action">Inicio</a>
            <a href="index.php?action=crearusuarioform" class="list-group-item list-group-item-action">Usuarios</a>
            <a href="index.php" class="list-group-item list-group-item-action">Clientes</a>
            <a href="index.php?action=listarproductos" class="list-group-item list-group-item-action">Listar Productos</a>
            <a href="index.php?action=logout" class="list-group-item list-group-item-action text-danger">Cerrar Sesión</a>
        </div>
    </div>

    <div class="content">
