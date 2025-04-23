<?php include 'views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Agregar Producto</h4>
                </div>
                <div class="card-body">
                    <form id="form-producto" class="mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código</label>
                                    <input type="text" class="form-control" name="codigo" id="codigo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" name="descripcion" id="descripcion" required>
                                </div>
                                <div class="mb-3">
                                    <label for="unidad_medida" class="form-label">Unidad de medida</label>
                                    <input type="text" class="form-control" name="unidad_medida" id="unidad_medida" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock_minimo" class="form-label">Stock mínimo</label>
                                    <input type="number" class="form-control" name="stock_minimo" id="stock_minimo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock_maximo" class="form-label">Stock máximo</label>
                                    <input type="number" class="form-control" name="stock_maximo" id="stock_maximo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="peso_bruto" class="form-label">Peso bruto</label>
                                    <input type="number" step="0.01" class="form-control" name="peso_bruto" id="peso_bruto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="peso_neto" class="form-label">Peso neto</label>
                                    <input type="number" step="0.01" class="form-control" name="peso_neto" id="peso_neto" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="alto" class="form-label">Alto</label>
                                    <input type="number" step="0.01" class="form-control" name="alto" id="alto" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ancho" class="form-label">Ancho</label>
                                    <input type="number" step="0.01" class="form-control" name="ancho" id="ancho" required>
                                </div>
                                <div class="mb-3">
                                    <label for="profundo" class="form-label">Profundo</label>
                                    <input type="number" step="0.01" class="form-control" name="profundo" id="profundo" required>
                                </div>
                                <div class="mb-3">
                                    <label for="clasif_demanda" class="form-label">Clasif. Demanda</label>
                                    <select class="form-control" name="clasif_demanda" id="clasif_demanda" required>
                                        <option value="">Seleccione</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="clasif_comercial" class="form-label">Clasif. Comercial</label>
                                    <select class="form-control" name="clasif_comercial" id="clasif_comercial" required>
                                        <option value="">Seleccione</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="comentarios" class="form-label">Comentarios</label>
                                    <textarea class="form-control" name="comentarios" id="comentarios" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-control" name="estado" id="estado" required>
                                        <option value="">Seleccione</option>
                                        <option value="A" selected>Activo</option>
                                        <option value="I">Inactivo</option>
                                    </select>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo" checked>
                                    <label class="form-check-label" for="activo">Activo</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Guardar producto</button>
                        <a href="index.php?action=listarproductos" class="btn btn-secondary w-100 mt-2">Cancelar</a>
                    </form>
                    <div id="mensaje" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="public/js/producto-crear.js"></script>

<?php include 'views/layout/footer.php'; ?>
