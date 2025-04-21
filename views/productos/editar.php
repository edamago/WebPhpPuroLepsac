<?php include 'views/layout/header.php'; ?>
<div class="container mt-5">
    <h2 class="text-primary">Editar Producto</h2>

    <?php if (isset($mensaje)): ?>
        <div class="alert alert-success"><?= $mensaje ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="index.php?action=actualizar" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($producto['id']) ?>">

        <div class="row">
            <div class="col-md-6">
                <label>Código</label>
                <input type="text" class="form-control" name="codigo" value="<?= htmlspecialchars($producto['codigo']) ?>" required>

                <label>Descripción</label>
                <input type="text" class="form-control" name="descripcion" value="<?= htmlspecialchars($producto['descripcion']) ?>" required>

                <label class="mt-3">Unidad de medida</label>
                <input type="text" class="form-control" name="unidad_medida" value="<?= htmlspecialchars($producto['unidad_medida']) ?>" required>

                <label class="mt-3">Stock mínimo</label>
                <input type="number" class="form-control" name="stock_minimo" value="<?= htmlspecialchars($producto['stock_minimo']) ?>" required>

                <label class="mt-3">Stock máximo</label>
                <input type="number" class="form-control" name="stock_maximo" value="<?= htmlspecialchars($producto['stock_maximo']) ?>" required>

                <label class="mt-3">Peso bruto</label>
                <input type="number" step="0.01" class="form-control" name="peso_bruto" value="<?= htmlspecialchars($producto['peso_bruto']) ?>" required>

                <label class="mt-3">Peso neto</label>
                <input type="number" step="0.01" class="form-control" name="peso_neto" value="<?= htmlspecialchars($producto['peso_neto']) ?>" required>
            </div>

            <div class="col-md-6">
                <label>Alto</label>
                <input type="number" step="0.01" class="form-control" name="alto" value="<?= htmlspecialchars($producto['alto']) ?>" required>

                <label class="mt-3">Ancho</label>
                <input type="number" step="0.01" class="form-control" name="ancho" value="<?= htmlspecialchars($producto['ancho']) ?>" required>

                <label class="mt-3">Profundo</label>
                <input type="number" step="0.01" class="form-control" name="profundo" value="<?= htmlspecialchars($producto['profundo']) ?>" required>

                <label class="mt-3">Clasif. Demanda</label>
                <select class="form-control" name="clasif_demanda" required>
                    <option value="">Seleccione</option>
                    <option value="A" <?= $producto['clasif_demanda'] == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $producto['clasif_demanda'] == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C" <?= $producto['clasif_demanda'] == 'C' ? 'selected' : '' ?>>C</option>
                    <option value="D" <?= $producto['clasif_demanda'] == 'D' ? 'selected' : '' ?>>D</option>
                </select>

                <label class="mt-3">Clasif. Comercial</label>
                <select class="form-control" name="clasif_comercial" required>
                    <option value="">Seleccione</option>
                    <option value="A" <?= $producto['clasif_comercial'] == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= $producto['clasif_comercial'] == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C" <?= $producto['clasif_comercial'] == 'C' ? 'selected' : '' ?>>C</option>
                    <option value="D" <?= $producto['clasif_comercial'] == 'D' ? 'selected' : '' ?>>D</option>
                </select>

                <label class="mt-3">Comentarios</label>
                <textarea class="form-control" name="comentarios" required><?= htmlspecialchars($producto['comentarios']) ?></textarea>

                <label class="mt-3">Estado</label>
                <select class="form-control" name="estado" required>
                    <option value="A" <?= $producto['estado'] == 'A' ? 'selected' : '' ?>>Activo</option>
                    <option value="I" <?= $producto['estado'] == 'I' ? 'selected' : '' ?>>Inactivo</option>
                </select>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo" <?= $producto['activo'] == 1 ? 'checked' : '' ?>>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Guardar Cambios</button>
        <a href="index.php?action=listarproductos" class="btn btn-secondary mt-4">Cancelar</a>
    </form>
</div>

<?php include 'views/layout/footer.php'; ?>
