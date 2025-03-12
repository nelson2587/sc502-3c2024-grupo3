document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("registroCurso");
    const listaCursos = document.getElementById("listaCursos");

    const cursos = JSON.parse(localStorage.getItem("cursos")) || [];
    actualizarLista();

    formulario.addEventListener("submit", (event) => {
        event.preventDefault();

        const nombreCurso = document.getElementById("nombreCurso").value.trim();
        const codigoCurso = document.getElementById("codigoCurso").value.trim();

        if (nombreCurso === "" || codigoCurso === "") {
            alert("Por favor, complete todos los campos.");
            return;
        }

        let cursos = JSON.parse(localStorage.getItem("cursos")) || [];
        const index = formulario.dataset.index;

        if (index) { 
            cursos[index] = { nombreCurso, codigoCurso };
            delete formulario.dataset.index;
        } else {
            cursos.push({ nombreCurso, codigoCurso });
        }

        localStorage.setItem("cursos", JSON.stringify(cursos));
        formulario.reset();
        actualizarLista();
    }); 

    function actualizarLista() {
        listaCursos.innerHTML = "";
        const cursos = JSON.parse(localStorage.getItem("cursos")) || [];
        cursos.forEach((curso, index) => {
            const fila = document.createElement("tr");
            fila.innerHTML = `
                <td>${curso.nombreCurso}</td>
                <td>${curso.codigoCurso}</td>
                <td>
                    <button onclick="editarCurso(${index})">Editar</button>
                    <button onclick="eliminarCurso(${index})">Eliminar</button>
                </td>
            `;
            listaCursos.appendChild(fila);
        });
    }

    window.editarCurso = function (index) {
        let cursos = JSON.parse(localStorage.getItem("cursos")) || [];
        let curso = cursos[index];

        document.getElementById("nombreCurso").value = curso.nombreCurso;
        document.getElementById("codigoCurso").value = curso.codigoCurso;
        formulario.dataset.index = index;
    };

    window.eliminarCurso = function (index) {
        let cursos = JSON.parse(localStorage.getItem("cursos")) || [];
        cursos.splice(index, 1);
        localStorage.setItem("cursos", JSON.stringify(cursos));
        actualizarLista();
    };

    actualizarLista();
});
