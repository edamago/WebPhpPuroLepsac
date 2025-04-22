document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const mensaje = document.getElementById("mensaje");

    // Obtener el ID del producto desde el campo oculto en el formulario
    const productoId = document.querySelector('input[name="id"]').value;
    //console.log("id: ", productoId); // Verifica si el ID se está tomando correctamente

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Recoger los datos del formulario
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.activo = form.activo && form.activo.checked ? 1 : 0;

        //console.log("Datos a enviar:", data); // Asegúrate de que los datos están completos

        // Modificar la URL para incluir el ID como parámetro de consulta
        fetch(`routes/api/producto/actualizar.php?id=${productoId}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(respuesta => {
            if (respuesta.success) {
                mensaje.innerHTML = `<div class="alert alert-success">Producto actualizado correctamente. Redirigiendo...</div>`;
                setTimeout(() => {
                    window.location.href = 'index.php?action=listarproductos';
                }, 2000);
            } else {
                mensaje.innerHTML = `<div class="alert alert-danger">Error: ${respuesta.message || 'No se pudo actualizar'}</div>`;
            }
        })
        .catch(err => {
            console.error("Error al actualizar producto", err);
            mensaje.innerHTML = `<div class="alert alert-danger">Error al actualizar producto.</div>`;
        });
    });
});

