document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-producto");
    const mensaje = document.getElementById("mensaje");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        data.activo = form.activo.checked ? 1 : 0;

        fetch('routes/api/producto/crear.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(respuesta => {
            if (respuesta.success) {
                mensaje.innerHTML = `<div class="alert alert-success">Producto registrado correctamente. Redirigiendo...</div>`;
                
                // Redirigir después de 2 segundos
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);

            } else {
                mensaje.innerHTML = `<div class="alert alert-danger">Error: ${respuesta.message || 'No se pudo registrar'}</div>`;
            }
        })
        .catch(err => {
            console.error("Error al registrar producto", err);
            mensaje.innerHTML = `<div class="alert alert-danger">Error al registrar producto.</div>`;
        });
    });
});
