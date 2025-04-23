<?php include 'views/layout/header.php'; ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Editar Producto</h4>
                </div>
                <div class="card-body">
                    <form id="form-producto" class="mt-4">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($producto['id']); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" class="form-control" name="codigo" id="codigo" value="<?php echo htmlspecialchars($producto['codigo']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" name="descripcion" id="descripcion" value="<?php echo htmlspecialchars($producto['descripcion']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="unidad_medida" class="form-label">Unidad de medida</label>
                                    <input type="text" class="form-control" name="unidad_medida" id="unidad_medida" value="<?php echo htmlspecialchars($producto['unidad_medida']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock_minimo" class="form-label">Stock mínimo</label>
                                    <input type="number" class="form-control" name="stock_minimo" id="stock_minimo" value="<?php echo htmlspecialchars($producto['stock_minimo']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock_maximo" class="form-label">Stock máximo</label>
                                    <input type="number" class="form-control" name="stock_maximo" id="stock_maximo" value="<?php echo htmlspecialchars($producto['stock_maximo']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="peso_bruto" class="form-label">Peso bruto</label>
                                    <input type="number" step="0.01" class="form-control" name="peso_bruto" id="peso_bruto" value="<?php echo htmlspecialchars($producto['peso_bruto']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="peso_neto" class="form-label">Peso neto</label>
                                    <input type="number" step="0.01" class="form-control" name="peso_neto" id="peso_neto" value="<?php echo htmlspecialchars($producto['peso_neto']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alto" class="form-label">Alto</label>
                                    <input type="number" step="0.01" class="form-control" name="alto" id="alto" value="<?php echo htmlspecialchars($producto['alto']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ancho" class="form-label">Ancho</label>
                                    <input type="number" step="0.01" class="form-control" name="ancho" id="ancho" value="<?php echo htmlspecialchars($producto['ancho']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="profundo" class="form-label">Profundo</label>
                                    <input type="number" step="0.01" class="form-control" name="profundo" id="profundo" value="<?php echo htmlspecialchars($producto['profundo']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="clasif_demanda" class="form-label">Clasif. Demanda</label>
                                    <select class="form-control" name="clasif_demanda" id="clasif_demanda" required>
                                        <option value="">Seleccione</option>
                                        <option value="A" <?php echo $producto['clasif_demanda'] === 'A' ? 'selected' : ''; ?>>A</option>
                                        <option value="B" <?php echo $producto['clasif_demanda'] === 'B' ? 'selected' : ''; ?>>B</option>
                                        <option value="C" <?php echo $producto['clasif_demanda'] === 'C' ? 'selected' : ''; ?>>C</option>
                                        <option value="D" <?php echo $producto['clasif_demanda'] === 'D' ? 'selected' : ''; ?>>D</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="clasif_comercial" class="form-label">Clasif. Comercial</label>
                                    <select class="form-control" name="clasif_comercial" id="clasif_comercial" required>
                                        <option value="">Seleccione</option>
                                        <option value="A" <?php echo $producto['clasif_comercial'] === 'A' ? 'selected' : ''; ?>>A</option>
                                        <option value="B" <?php echo $producto['clasif_comercial'] === 'B' ? 'selected' : ''; ?>>B</option>
                                        <option value="C" <?php echo $producto['clasif_comercial'] === 'C' ? 'selected' : ''; ?>>C</option>
                                        <option value="D" <?php echo $producto['clasif_comercial'] === 'D' ? 'selected' : ''; ?>>D</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="comentarios" class="form-label">Comentarios</label>
                                    <textarea class="form-control" name="comentarios" id="comentarios" required><?php echo htmlspecialchars($producto['comentarios']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-control" name="estado" id="estado" required>
                                        <option value="A" <?php echo $producto['estado'] === 'A' ? 'selected' : ''; ?>>Activo</option>
                                        <option value="I" <?php echo $producto['estado'] === 'I' ? 'selected' : ''; ?>>Inactivo</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" <?php echo $producto['activo'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="activo">Activo</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar Cambios</button>
                        <a href="index.php?action=listarproductos" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    </form>
                    <div id="mensaje" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enlazamos el JS de edición -->
<script src="public/js/producto-editar.js"></script>

<?php include 'views/layout/footer.php'; ?>
