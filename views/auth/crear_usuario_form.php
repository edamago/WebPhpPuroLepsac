<?php include 'views/layout/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="row w-100 justify-content-center">
        <div class="col-12 col-sm-8 col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Crear Usuario</h4>
                </div>
                <div class="card-body">
                    <form action="index.php?action=crearusuario" method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" name="correo" id="correo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select name="estado" id="estado" class="form-control" required>
                                <option value="A" selected>Activo</option>
                                <option value="I">Inactivo</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" name="clave" id="clave" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <!-- Campo oculto que envía 0 si el checkbox está desmarcado -->
                            <input type="hidden" name="activo" value="0">
                            
                            <!-- Checkbox que sobrescribe el valor si está marcado -->
                            <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" checked>
                            <label class="form-check-label" for="activo">
                                Activo
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Crear Usuario</button>
                    </form>

                    <div class="mt-3 text-center">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'views/layout/footer.php'; ?>
</body>
</html>
