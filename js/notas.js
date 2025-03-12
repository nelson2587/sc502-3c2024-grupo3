document.addEventListener("DOMContentLoaded", function () {
    const usuarioActual = JSON.parse(localStorage.getItem("usuarioActual"));
    const formNota = document.getElementById("formNota");
    const listaNotas = document.getElementById("listaNotas");
    const gradoSelect = document.getElementById("grado");
    const seccionSelect = document.getElementById("seccion");
    const materiaSelect = document.getElementById("materia");
    const estudianteSelect = document.getElementById("estudiante");
    const notaInput = document.getElementById("nota");

    const filtroGrado = document.getElementById("filtroGrado");
    const filtroSeccion = document.getElementById("filtroSeccion");
    const filtroEstudiante = document.getElementById("filtroEstudiante");
    const btnFiltrar = document.getElementById("btnFiltrar");

    function cargarMaterias() {
        materiaSelect.innerHTML = "<option value=''>Seleccione una materia</option>";
        const cursos = JSON.parse(localStorage.getItem("cursos")) || [];

        if (cursos.length === 0) {
            materiaSelect.innerHTML = "<option value=''>No hay materias registradas</option>";
            return;
        }

        cursos.forEach(curso => {
            let option = document.createElement("option");
            option.value = curso.nombreCurso;
            option.textContent = curso.nombreCurso;
            materiaSelect.appendChild(option);
        });
    }

    function cargarEstudiantes() {
        estudianteSelect.innerHTML = "<option value=''>Seleccione un estudiante</option>";
        const gradoSeleccionado = gradoSelect.value;
        const seccionSeleccionada = seccionSelect.value;
        const estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];

        if (!gradoSeleccionado || !seccionSeleccionada) {
            return;
        }

        const estudiantesFiltrados = estudiantes.filter(est => 
            est.grado === gradoSeleccionado && est.seccion === seccionSeleccionada
        );

        if (estudiantesFiltrados.length === 0) {
            estudianteSelect.innerHTML = "<option value=''>No hay estudiantes en esta sección</option>";
            return;
        }

        estudiantesFiltrados.forEach(estudiante => {
            let option = document.createElement("option");
            option.value = estudiante.id;
            option.textContent = `${estudiante.nombre} (ID: ${estudiante.id})`;
            estudianteSelect.appendChild(option);
        });
    }

    function obtenerProfesorPorGrado(grado) {
        const profesores = JSON.parse(localStorage.getItem("profesores")) || [];
        const profesor = profesores.find(prof => prof.nivel === grado);
        return profesor ? profesor.nombre : "No asignado";
    }

    function actualizarProfesoresEnNotas() {
        let notas = JSON.parse(localStorage.getItem("notas")) || [];
        let profesores = JSON.parse(localStorage.getItem("profesores")) || [];

        notas.forEach(nota => {
            if (!nota.profesor || nota.profesor === "undefined") {
                let profesor = profesores.find(prof => prof.nivel === nota.grado);
                nota.profesor = profesor ? profesor.nombre : "No asignado";
            }
        });

        localStorage.setItem("notas", JSON.stringify(notas));
    }

    function filtrarNotasPorUsuario(notas) {
        if (!usuarioActual) return notas;

        if (usuarioActual.rol === "profesor") {
            return notas.filter(nota => nota.profesor === usuarioActual.nombre);
        } else if (usuarioActual.rol === "estudiante") {
            return notas.filter(nota => nota.estudianteID === usuarioActual.id);
        }
        return notas;
    }

    function cargarNotas() {
        listaNotas.innerHTML = "";
        let notas = JSON.parse(localStorage.getItem("notas")) || [];
        actualizarProfesoresEnNotas(); // 🔹 Se asegura de que el profesor esté bien asignado

        const gradoSeleccionado = filtroGrado.value;
        const seccionSeleccionada = filtroSeccion.value;
        const textoBusqueda = filtroEstudiante.value.toLowerCase().trim();

        const notasFiltradas = notas.filter(nota => {
            return (
                (gradoSeleccionado === "" || nota.grado === gradoSeleccionado) &&
                (seccionSeleccionada === "" || nota.seccion === seccionSeleccionada || seccionSeleccionada === "Todas las secciones") &&
                (textoBusqueda === "" || 
                 nota.estudianteNombre.toLowerCase().includes(textoBusqueda) ||
                 nota.estudianteID.toLowerCase().includes(textoBusqueda))
            );
        });

        if (notasFiltradas.length === 0) {
            listaNotas.innerHTML = "<tr><td colspan='7'>No hay notas para la selección.</td></tr>";
            return;
        }

        notasFiltradas.forEach((nota, index) => {
            let row = document.createElement("tr");
            row.innerHTML = `
                <td>${nota.estudianteNombre} (ID: ${nota.estudianteID})</td>
                <td>${nota.grado}</td>
                <td>${nota.seccion}</td>
                <td>${nota.materia}</td>
                <td>${nota.profesor}</td> <!-- ✅ Ahora muestra el profesor correctamente -->
                <td>${nota.nota}</td>
                <td>
                    <button onclick="editarNota(${index})" class="btn-editar">Editar</button>
                    <button onclick="eliminarNota(${index})" class="btn-eliminar">Eliminar</button>
                </td>
            `;
            listaNotas.appendChild(row);
        });
    }

    formNota.addEventListener("submit", function (e) {
        e.preventDefault();

        const grado = gradoSelect.value;
        const seccion = seccionSelect.value;
        const materia = materiaSelect.value;
        const estudianteID = estudianteSelect.value;
        const nota = notaInput.value;
        const index = formNota.dataset.index;

        if (!grado || !seccion || !materia || !estudianteID || !nota) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        let notas = JSON.parse(localStorage.getItem("notas")) || [];
        const estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];
        const estudiante = estudiantes.find(est => est.id === estudianteID);
        const profesor = obtenerProfesorPorGrado(grado);

        if (!estudiante) {
            alert("Error: Estudiante no encontrado.");
            return;
        }

        if (index) {
            notas[index].grado = grado;
            notas[index].seccion = seccion;
            notas[index].materia = materia;
            notas[index].nota = nota;
            notas[index].profesor = profesor;
            delete formNota.dataset.index;
        } else {
            const nuevaNota = {
                estudianteID: estudiante.id,
                estudianteNombre: estudiante.nombre,
                grado: estudiante.grado,
                seccion: estudiante.seccion,
                materia: materia,
                nota: nota,
                profesor: profesor
            };
            notas.push(nuevaNota);
        }

        localStorage.setItem("notas", JSON.stringify(notas));
        formNota.reset();
        cargarNotas();
    });

    window.editarNota = function (index) {
        let notas = JSON.parse(localStorage.getItem("notas")) || [];
        let nota = notas[index];

        gradoSelect.value = nota.grado;
        seccionSelect.value = nota.seccion;
        materiaSelect.value = nota.materia;
        estudianteSelect.value = nota.estudianteID;
        notaInput.value = nota.nota;
        formNota.dataset.index = index;
    };

    window.eliminarNota = function (index) {
        let notas = JSON.parse(localStorage.getItem("notas")) || [];
        notas.splice(index, 1);
        localStorage.setItem("notas", JSON.stringify(notas));
        cargarNotas();
    };

    cargarNotas();
    cargarMaterias();
    gradoSelect.addEventListener("change", cargarEstudiantes);
    seccionSelect.addEventListener("change", cargarEstudiantes);
    btnFiltrar.addEventListener("click", cargarNotas);
});
