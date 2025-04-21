<?php include 'views/layout/header.php'; ?>

<h3>Listado de productos</h3>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-primary">Productos</h1>
        <a href="index.php?action=crear" class="btn btn-success">Agregar producto</a>
    </div>

    <table class="table table-bordered table-striped table-hover shadow-sm">
        <thead class="table-dark">
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
        <tbody id="tabla-productos">
            <!-- Se llenará dinámicamente con JS -->
        </tbody>
    </table>
</div>

<script src="public/js/producto.js"></script>

<?php include 'views/layout/footer.php'; ?>
