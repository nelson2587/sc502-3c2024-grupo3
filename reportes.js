document.addEventListener("DOMContentLoaded", () => {
    const filtroReportes = document.getElementById("filtroReportes");
    const resultadoReporte = document.getElementById("resultadoReporte");
    const exportarPDF = document.getElementById("exportarPDF");
    const exportarExcel = document.getElementById("exportarExcel");

    const filtroGrado = document.getElementById("filtroGrado");
    const filtroSeccion = document.getElementById("filtroSeccion");
    const filtroProfesor = document.getElementById("filtroProfesor");
    const filtroEstudiante = document.getElementById("filtroEstudiante");
    const filtroCurso = document.getElementById("filtroCurso");
    const btnGenerarReporte = document.getElementById("btnGenerarReporte");

    function cargarOpciones() {
        const notas = JSON.parse(localStorage.getItem("notas")) || [];
        const profesores = JSON.parse(localStorage.getItem("profesores")) || [];
        const estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];
        const cursos = JSON.parse(localStorage.getItem("cursos")) || [];

        // Poblar grados y secciones
        const grados = [...new Set(notas.map(nota => nota.grado))];
        const secciones = [...new Set(notas.map(nota => nota.seccion))];
        //Para las filtraciones
        filtroGrado.innerHTML = "<option value=''>Todos</option>";
        filtroSeccion.innerHTML = "<option value=''>Todos</option>";
        grados.forEach(grado => filtroGrado.innerHTML += `<option value="${grado}">${grado}</option>`);
        secciones.forEach(seccion => filtroSeccion.innerHTML += `<option value="${seccion}">${seccion}</option>`);

        // Poblar profesores
        filtroProfesor.innerHTML = "<option value=''>Todos</option>";
        profesores.forEach(prof => filtroProfesor.innerHTML += `<option value="${prof.nombre}">${prof.nombre}</option>`);

        // Poblar estudiantes
        filtroEstudiante.innerHTML = "<option value=''>Todos</option>";
        estudiantes.forEach(est => filtroEstudiante.innerHTML += `<option value="${est.id}">${est.nombre} (ID: ${est.id})</option>`);

        // Poblar cursos
        filtroCurso.innerHTML = "<option value=''>Todos</option>";
        cursos.forEach(curso => filtroCurso.innerHTML += `<option value="${curso.nombreCurso}">${curso.nombreCurso}</option>`);
    }

    function obtenerProfesorPorGrado(grado) {
        const profesores = JSON.parse(localStorage.getItem("profesores")) || [];
        const profesor = profesores.find(prof => prof.nivel === grado);
        return profesor ? profesor.nombre : "No asignado";
    }

    function generarReporte() {
        resultadoReporte.innerHTML = "";
        const notas = JSON.parse(localStorage.getItem("notas")) || [];

        const gradoSeleccionado = filtroGrado.value;
        const seccionSeleccionada = filtroSeccion.value;
        const profesorSeleccionado = filtroProfesor.value;
        const estudianteSeleccionado = filtroEstudiante.value;
        const cursoSeleccionado = filtroCurso.value;

        const notasFiltradas = notas.filter(nota => {
            const profesorAsignado = nota.profesor || obtenerProfesorPorGrado(nota.grado);
            return (
                (gradoSeleccionado === "" || nota.grado === gradoSeleccionado) &&
                (seccionSeleccionada === "" || nota.seccion === seccionSeleccionada) &&
                (profesorSeleccionado === "" || profesorAsignado === profesorSeleccionado) &&
                (estudianteSeleccionado === "" || nota.estudianteID === estudianteSeleccionado) &&
                (cursoSeleccionado === "" || nota.materia === cursoSeleccionado)
            );
        });

        if (notasFiltradas.length === 0) {
            resultadoReporte.innerHTML = "<p>No hay datos para mostrar.</p>";
            return;
        }

        let reporteHTML = `<table border="1">
            <tr>
                <th>Estudiante</th>
                <th>Grado</th>
                <th>Sección</th>
                <th>Profesor</th>
                <th>Curso</th>
                <th>Nota</th>
            </tr>`;

        notasFiltradas.forEach(nota => {
            const profesorAsignado = nota.profesor || obtenerProfesorPorGrado(nota.grado);
            
            reporteHTML += `<tr>
                <td>${nota.estudianteNombre} (ID: ${nota.estudianteID})</td>
                <td>${nota.grado}</td>
                <td>${nota.seccion}</td>
                <td>${profesorAsignado}</td>
                <td>${nota.materia}</td>
                <td>${nota.nota}</td>
            </tr>`;
        });

        reporteHTML += "</table>";
        resultadoReporte.innerHTML = reporteHTML;
    }
    //Reporte en pdf
    exportarPDF.addEventListener("click", () => {
        const contenido = resultadoReporte.innerHTML;
        if (!contenido) {
            alert("Genera un reporte antes de exportar.");
            return;
        }
        const ventana = window.open("", "", "width=800,height=600");
        ventana.document.write(`<html><head><title>Reporte</title></head><body>${contenido}</body></html>`);
        ventana.document.close();
        ventana.print();
    });
    //Reporte en excel
    exportarExcel.addEventListener("click", () => {
        const contenido = resultadoReporte.innerHTML;
        if (!contenido) {
            alert("Genera un reporte antes de exportar.");
            return;
        }
        const blob = new Blob([contenido], { type: "application/vnd.ms-excel" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "reporte.xls";
        link.click();
    });

    btnGenerarReporte.addEventListener("click", generarReporte);
    cargarOpciones();
});
