document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formularioNotas");
    const listaNotas = document.getElementById("listaNotas");

    function cargarNotas() {
        fetch("listar_notas.php")
            .then(res => res.json())
            .then(notas => {
                listaNotas.innerHTML = "";
                notas.forEach((nota, index) => {
                    let row = document.createElement("tr");
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${nota.estudiante}</td>
                        <td>${nota.curso}</td>
                        <td>${nota.nota}</td>
                        <td>${nota.fecha_registro}</td>
                    `;
                    listaNotas.appendChild(row);
                });
            });
    }

    function cargarSelects() {
        // Estudiantes
        fetch("listar_estudiantes.php")
            .then(res => res.json())
            .then(data => {
                const selectEstudiante = document.getElementById("estudiante");
                selectEstudiante.innerHTML = "<option value=''>Seleccione...</option>";
                data.forEach(est => {
                    let option = document.createElement("option");
                    option.value = est.id_estudiante;
                    option.textContent = est.nombre;
                    selectEstudiante.appendChild(option);
                });
            });

        // Cursos
        fetch("listar_cursos.php")
            .then(res => res.json())
            .then(data => {
                const selectCurso = document.getElementById("curso");
                selectCurso.innerHTML = "<option value=''>Seleccione...</option>";
                data.forEach(cur => {
                    let option = document.createElement("option");
                    option.value = cur.id_curso;
                    option.textContent = cur.nombre_curso;
                    selectCurso.appendChild(option);
                });
            });
    }

    formulario.addEventListener("submit", (e) => {
        e.preventDefault();

        const estudiante_id = document.getElementById("estudiante").value;
        const curso_id = document.getElementById("curso").value;
        const nota = parseFloat(document.getElementById("nota").value);

        if (!estudiante_id || !curso_id || isNaN(nota)) {
            alert("Complete todos los campos.");
            return;
        }

        const datos = new URLSearchParams();
        datos.append("estudiante_id", estudiante_id);
        datos.append("curso_id", curso_id);
        datos.append("nota", nota);

        fetch("insertar_nota.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: datos.toString()
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Nota registrada correctamente.");
                formulario.reset();
                cargarNotas();
            } else {
                alert("Error: " + data.error);
            }
        });
    });

    //Asegurarse de que se carguen datos al cargar la página
    cargarSelects();
    cargarNotas();
});
