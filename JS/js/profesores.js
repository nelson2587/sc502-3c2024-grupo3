document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("registroProfesor");
    const listaProfesores = document.getElementById("listaProfesores");
    //Carga a los profesores
    function cargarProfesores() {
        fetch("listar_profesores.php")
            .then(response => response.json())
            .then(profesores => {
                listaProfesores.innerHTML = "";
                profesores.forEach((profesor, index) => {
                    let row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${profesor.nombre}</td>
                        <td>${profesor.nivel}</td>
                        <td>${profesor.usuario}</td>
                        <td>${profesor.contrasena}</td>
                    `;
                    listaProfesores.appendChild(row);
                });
            });
    }

    formulario.addEventListener("submit", (event) => {
        event.preventDefault();

        const nombre = document.getElementById("nombre").value.trim();
        const nivel = document.getElementById("nivel").value;

        if (nombre === "" || nivel === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }

        const datos = new URLSearchParams();
        datos.append("nombre", nombre);
        datos.append("nivel", nivel);

        fetch("insertar_profesor.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: datos.toString()
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(`Profesor registrado con éxito.\nID: ${data.id}\nContraseña: ${data.contrasena}`);
                formulario.reset();
                cargarProfesores();
            } else {
                alert("Error: " + data.error);
            }
        });
    });

    cargarProfesores();
});
