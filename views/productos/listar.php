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
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="text-primary">Productos</h1>
                <a href="index.php?action=crear" class="btn btn-success">Agregar producto</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Descripción</th>
                            <th>Unidad</th>
                            <th>Stock Min</th>
                            <th>Stock Max</th>
                            <th>Clasif. Demanda</th>
                            <th>Clasif. Comercial</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo $producto['codigo']; ?></td>
                                <td><?php echo $producto['descripcion']; ?></td>
                                <td><?php echo $producto['unidad_medida']; ?></td>
                                <td><?php echo $producto['stock_minimo']; ?></td>
                                <td><?php echo $producto['stock_maximo']; ?></td>
                                <td><?php echo $producto['clasif_demanda']; ?></td>
                                <td><?php echo $producto['clasif_comercial']; ?></td>
                                <td><?php echo $producto['estado']; ?></td>
                                <td>
                                    <a href="index.php?action=editarproductoform&id=<?php echo $producto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                    <a href="index.php?action=eliminarproducto&id=<?php echo $producto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?php include 'views/layout/footer.php'; ?>
</body>
</html>
