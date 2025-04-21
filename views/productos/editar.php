<?php include 'views/layout/header.php'; ?>


<h2>Editar Producto</h2>

<?php if (isset($mensaje)): ?>
    <div style="color: green;"><?= $mensaje ?></div>
<?php elseif (isset($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>

<form action="index.php?action=actualizar" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

    <label>Código:</label>

    <input type="text" name="codigo" value="<?= htmlspecialchars($producto['codigo']) ?>">


    
    <button type="submit">Guardar Cambios</button>
</form>

<?php include 'views/layout/footer.php'; ?>
