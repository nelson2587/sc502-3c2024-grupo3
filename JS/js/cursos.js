document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("registroCurso");
    const listaCursos = document.getElementById("listaCursos");
    //Carga los cursos
    function cargarCursos() {
        fetch("listar_cursos.php")
            .then(res => res.json())
            .then(cursos => {
                listaCursos.innerHTML = "";
                cursos.forEach((curso, index) => {
                    let row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${curso.nombre_curso}</td>
                    `;
                    listaCursos.appendChild(row);
                });
            });
    }

    formulario.addEventListener("submit", (e) => {
        e.preventDefault();

        const nombre = document.getElementById("nombre").value.trim();
        if (nombre === "") {
            alert("Por favor ingrese el nombre del curso.");
            return;
        }

        const datos = new URLSearchParams();
        datos.append("nombre", nombre);

        fetch("insertar_curso.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: datos.toString()
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Curso registrado correctamente.");
                formulario.reset();
                cargarCursos();
            } else {
                alert("Error: " + data.error);
            }
        });
    });

    cargarCursos();
});
