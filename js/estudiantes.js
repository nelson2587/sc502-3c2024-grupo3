document.addEventListener("DOMContentLoaded", function () {
    const formEstudiante = document.getElementById("formEstudiante");
    const listaEstudiantes = document.getElementById("listaEstudiantes");
    const filtroGrado = document.getElementById("filtroGrado");
    const filtroSeccion = document.getElementById("filtroSeccion");
    const filtroEstudiante = document.getElementById("filtroEstudiante");
    const btnFiltrar = document.getElementById("btnFiltrar");

    const gradoSelect = document.getElementById("grado");
    const seccionSelect = document.getElementById("seccion");

    function generarID() {
        return "EST-" + Math.floor(100000 + Math.random() * 900000);
    }

    function generarContraseña() {
        return Math.random().toString(36).slice(-8);
    }

    function cargarOpciones() {
        const grados = ["Primero", "Segundo", "Tercero", "Cuarto", "Quinto", "Sexto"];
        const secciones = ["A-1", "A-2", "B-1", "B-2"];
    
        // Limpiar opciones previas antes de agregar nuevas
        gradoSelect.innerHTML = "<option value=''>Seleccione un grado</option>";
        filtroGrado.innerHTML = "<option value=''>Todos los grados</option>";
        
        seccionSelect.innerHTML = "<option value=''>Seleccione una sección</option>";
        filtroSeccion.innerHTML = "<option value=''>Todas las secciones</option>";
    
        // Agregar grados
        grados.forEach(grado => {
            let option1 = new Option(grado, grado);
            let option2 = new Option(grado, grado);
            gradoSelect.appendChild(option1);
            filtroGrado.appendChild(option2);
        });
    
        // Agregar secciones
        secciones.forEach(seccion => {
            let option1 = new Option(seccion, seccion);
            let option2 = new Option(seccion, seccion);
            seccionSelect.appendChild(option1);
            filtroSeccion.appendChild(option2);
        });
    }
    

    function cargarEstudiantes() {
        listaEstudiantes.innerHTML = "";
        const estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];
        const gradoSeleccionado = filtroGrado.value;
        const seccionSeleccionada = filtroSeccion.value;
        const textoBusqueda = filtroEstudiante.value.toLowerCase().trim();

        const estudiantesFiltrados = estudiantes.filter(est => 
            (gradoSeleccionado === "" || est.grado === gradoSeleccionado) &&
            (seccionSeleccionada === "" || est.seccion === seccionSeleccionada) &&
            (textoBusqueda === "" || est.nombre.toLowerCase().includes(textoBusqueda) || est.id.includes(textoBusqueda))
        );

        if (estudiantesFiltrados.length === 0) {
            listaEstudiantes.innerHTML = "<tr><td colspan='6'>No hay estudiantes en esta categoría.</td></tr>";
            return;
        }

        estudiantesFiltrados.forEach((estudiante, index) => {
            let row = document.createElement("tr");
            row.innerHTML = `
                <td>${estudiante.nombre}</td>
                <td>${estudiante.grado}</td>
                <td>${estudiante.seccion}</td>
                <td>${estudiante.id}</td>
                <td>${estudiante.contraseña}</td>
                <td>
                    <button onclick="editarEstudiante(${index})" class="btn-editar">Editar</button>
                    <button onclick="eliminarEstudiante(${index})" class="btn-eliminar">Eliminar</button>
                </td>
            `;
            listaEstudiantes.appendChild(row);
        });
    }

    formEstudiante.addEventListener("submit", function (e) {
        e.preventDefault();

        const nombre = document.getElementById("nombre").value.trim();
        const grado = gradoSelect.value.trim();
        const seccion = seccionSelect.value.trim();
        const index = formEstudiante.dataset.index;

        if (nombre === "" || grado === "" || seccion === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }

        let estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];

        if (index) {
            estudiantes[index].nombre = nombre;
            estudiantes[index].grado = grado;
            estudiantes[index].seccion = seccion;
            delete formEstudiante.dataset.index;
        } else {
            const id = generarID();
            const contraseña = generarContraseña();
            const nuevoEstudiante = { nombre, grado, seccion, id, contraseña };
            estudiantes.push(nuevoEstudiante);
            alert(`Estudiante registrado con éxito.\nID: ${id}\nContraseña: ${contraseña}`);
        }

        localStorage.setItem("estudiantes", JSON.stringify(estudiantes));
        formEstudiante.reset();
        cargarEstudiantes();
    });

    window.editarEstudiante = function (index) {
        let estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];
        let estudiante = estudiantes[index];

        document.getElementById("nombre").value = estudiante.nombre;
        gradoSelect.value = estudiante.grado;
        seccionSelect.value = estudiante.seccion;
        formEstudiante.dataset.index = index;
    };

    window.eliminarEstudiante = function (index) {
        let estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];
        estudiantes.splice(index, 1);
        localStorage.setItem("estudiantes", JSON.stringify(estudiantes));
        cargarEstudiantes();
    };

    btnFiltrar.addEventListener("click", cargarEstudiantes);
    filtroGrado.addEventListener("change", cargarEstudiantes);
    filtroSeccion.addEventListener("change", cargarEstudiantes);
    filtroEstudiante.addEventListener("input", cargarEstudiantes);

    cargarOpciones();
    cargarEstudiantes();
});
