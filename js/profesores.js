document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("registroProfesor");
    const listaProfesores = document.getElementById("listaProfesores");

    function generarID() {
        return "PROF-" + Math.floor(Math.random() * 1000000);
    }

    function generarContraseña() {
        return Math.random().toString(36).slice(-8);
    }

    function cargarProfesores() {
        listaProfesores.innerHTML = "";
        const profesores = JSON.parse(localStorage.getItem("profesores")) || [];

        profesores.forEach((profesor, index) => {
            let row = document.createElement("tr");
            row.innerHTML = `
                <td>${profesor.nombre}</td>
                <td>${profesor.nivel}</td>
                <td>${profesor.id}</td>
                <td>${profesor.contraseña}</td>
                <td>
                    <button onclick="editarProfesor(${index})" class="btn-editar">Editar</button>
                    <button onclick="eliminarProfesor(${index})" class="btn-eliminar">Eliminar</button>
                </td>
            `;
            listaProfesores.appendChild(row);
        });
    }

    formulario.addEventListener("submit", (event) => {
        event.preventDefault();

        const nombre = document.getElementById("nombre").value.trim();
        const nivel = document.getElementById("nivel").value;
        const index = formulario.dataset.index;

        if (nombre === "" || nivel === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }

        let profesores = JSON.parse(localStorage.getItem("profesores")) || [];

        if (index) {
            // Edición
            profesores[index].nombre = nombre;
            profesores[index].nivel = nivel;
            delete formulario.dataset.index;
        } else {
            // Nuevo registro
            const id = generarID();
            const contraseña = generarContraseña();
            const nuevoProfesor = { nombre, nivel, id, contraseña };
            profesores.push(nuevoProfesor);
            alert(`Profesor registrado con éxito.\nID: ${id}\nContraseña: ${contraseña}`);
        }

        localStorage.setItem("profesores", JSON.stringify(profesores));
        formulario.reset();
        cargarProfesores();
    });

    window.editarProfesor = function (index) {
        let profesores = JSON.parse(localStorage.getItem("profesores")) || [];
        let profesor = profesores[index];

        document.getElementById("nombre").value = profesor.nombre;
        document.getElementById("nivel").value = profesor.nivel;
        formulario.dataset.index = index; // Guardar índice para edición
    };

    window.eliminarProfesor = function (index) {
        let profesores = JSON.parse(localStorage.getItem("profesores")) || [];
        profesores.splice(index, 1);
        localStorage.setItem("profesores", JSON.stringify(profesores));
        cargarProfesores();
    };

    cargarProfesores();
});
