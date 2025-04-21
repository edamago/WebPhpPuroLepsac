<?php include 'views/layout/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-primary">Agregar nuevo producto</h2>

    <form id="form-producto" class="mt-4">
        <div class="row">
            <div class="col-md-6">
                
                <label>Código</label>
                <input type="text" class="form-control" name="codigo" required>

                <label>Descripción</label>
                <input type="text" class="form-control" name="descripcion" required>

                <label class="mt-3">Unidad de medida</label>
                <input type="text" class="form-control" name="unidad_medida" required>

                <label class="mt-3">Stock mínimo</label>
                <input type="number" class="form-control" name="stock_minimo" required>

                <label class="mt-3">Stock máximo</label>
                <input type="number" class="form-control" name="stock_maximo" required>

                <label class="mt-3">Peso bruto</label>
                <input type="number" step="0.01" class="form-control" name="peso_bruto" required>

                <label class="mt-3">Peso neto</label>
                <input type="number" step="0.01" class="form-control" name="peso_neto" required>
            </div>

            <div class="col-md-6">
                <label>Alto</label>
                <input type="number" step="0.01" class="form-control" name="alto" required>

                <label class="mt-3">Ancho</label>
                <input type="number" step="0.01" class="form-control" name="ancho" required>

                <label class="mt-3">Profundo</label>
                <input type="number" step="0.01" class="form-control" name="profundo" required>

                <!-- Clasif. Demanda -->
                <label class="mt-3">Clasif. Demanda</label>
                <select class="form-control" name="clasif_demanda" required>
                    <option value="">Seleccione</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>

                <!-- Clasif. Comercial -->
                <label class="mt-3">Clasif. Comercial</label>
                <select class="form-control" name="clasif_comercial" required>
                    <option value="">Seleccione</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>



                <label class="mt-3">Comentarios</label>
                <textarea class="form-control" name="comentarios" required></textarea>

                <!-- Estado -->
                <label class="mt-3">Estado</label>
                <select class="form-control" name="estado" required>
                    <option value="">Seleccione</option>
                    <option value="A" selected>Activo</option>
                    <option value="I">Inactivo</option>
                </select>

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="activo" value="1" id="activo" checked>
                    <label class="form-check-label" for="activo">Activo</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-4">Guardar producto</button>
        <a href="index.php" class="btn btn-secondary mt-4">Cancelar</a>
    </form>

    <div id="mensaje" class="mt-3"></div>
</div>

<script src="public/js/producto-crear.js"></script>

<?php include 'views/layout/footer.php'; ?>
