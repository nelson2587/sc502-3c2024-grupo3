formulario.addEventListener("submit", (event) => {
    event.preventDefault();

    const nombre = document.getElementById("nombre").value.trim();
    const apellido = document.getElementById("apellido").value.trim();
    const grado = document.getElementById("grado").value;
    const seccion = document.getElementById("seccion").value;

    if (nombre === "" || apellido === "" || grado === "" || seccion === "") {
        alert("Por favor, complete todos los campos.");
        return;
    }

    const datos = new URLSearchParams();
    datos.append("nombre", nombre);
    datos.append("apellido", apellido);
    datos.append("grado", grado);
    datos.append("seccion", seccion);

    fetch("insertar_estudiante.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: datos.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Estudiante registrado.\nID: ${data.id}\nContraseña: ${data.contrasena}`);
            formulario.reset();
        } else {
            alert("Error al registrar: " + data.error);
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
        alert("Hubo un error al conectar con el servidor.");
    });
});
