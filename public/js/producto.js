document.addEventListener("DOMContentLoaded", function () {
    fetch('routes/api/producto/listar.php')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("tabla-productos");
            let rows = '';
            data.forEach(p => {
                //console.log(p); // Verifica que cada objeto tenga el campo 'codigo'
                rows += `
                    <tr>
                        <td>${p.id}</td>
                        <td>${p.codigo}</td>
                        <td>${p.descripcion}</td>
                        <td>${p.unidad_medida}</td>
                        <td>${p.stock_minimo}</td>
                        <td>${p.stock_maximo}</td>
                        <td>${p.clasif_demanda}</td>
                        <td>${p.clasif_comercial}</td>
                        <td>${p.estado}</td>
                        <td>
                            <a href="index.php?action=editar&id=${p.id}" class="btn btn-sm btn-primary">Editar</a>
                            <a href="index.php?action=eliminar&id=${p.id}" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = rows;
        })
        .catch(error => {
            console.error("Error al cargar productos:", error);
            document.getElementById("tabla-productos").innerHTML =
                '<tr><td colspan="9">Error al cargar los productos.</td></tr>';
        });
});
