<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body">

                    <?php
                    if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'expirada') {
                        echo "<div class='alert alert-warning text-center'>La sesión ha expirado por inactividad.</div>";
                    }
                    ?>

                    <form action="index.php?action=login" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="clave" class="form-label">Contraseña</label>
                            <input type="password" name="clave" id="clave" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="index.php?action=crearusuarioform" class="btn btn-link">Crear nuevo usuario</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
