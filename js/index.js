document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const mensajeError = document.getElementById("mensajeError");

    loginForm.addEventListener("submit", (event) => {
        event.preventDefault();
        
        const usuarioID = document.getElementById("usuario").value.trim();
        const password = document.getElementById("password").value.trim();
        const rol = document.getElementById("rol").value;

        let usuarioValido = null;

        if (rol === "admin") {

            if (usuarioID === "admin" && password === "admin123") {
                usuarioValido = { id: "admin", rol: "admin" };
            }
        } else if (rol === "profesor") {
            const profesores = JSON.parse(localStorage.getItem("profesores")) || [];
            usuarioValido = profesores.find(profesor => profesor.id === usuarioID && profesor.contraseña === password);
        } else if (rol === "estudiante") {
            const estudiantes = JSON.parse(localStorage.getItem("estudiantes")) || [];
            usuarioValido = estudiantes.find(estudiante => estudiante.id === usuarioID && estudiante.contraseña === password);
        }

        if (usuarioValido) {
            localStorage.setItem("usuarioActual", JSON.stringify(usuarioValido));
            alert("Inicio de sesión exitoso");

            if (rol === "admin") {
                window.location.href = "inicio.html";
            } else if (rol === "profesor") {
                window.location.href = "notas.html";
            } else if (rol === "estudiante") {
                window.location.href = "reportes.html"; 
            }
        } else {
            mensajeError.textContent = "ID, contraseña o rol incorrectos.";
        }
    });
});
