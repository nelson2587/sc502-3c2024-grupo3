// auth.js - Gestión de autenticación y roles

document.addEventListener("DOMContentLoaded", function () {
    const usuarioActual = JSON.parse(localStorage.getItem("usuarioActual"));
    if (!usuarioActual) {
        window.location.href = "index.html"; // Redirigir si no está logueado
    }

    // Definir accesos según rol
    const accesos = {
        "admin": ["inicio.html", "cursos.html", "estudiantes.html", "profesores.html", "notas.html", "reportes.html"],
        "profesor": ["notas.html", "reportes.html", "estudiantes.html"],
        "estudiante": ["notas.html"]
    };

    const paginaActual = window.location.pathname.split("/").pop();
    if (!accesos[usuarioActual.rol].includes(paginaActual)) {
        alert("No tienes permiso para acceder a esta página.");
        window.location.href = accesos[usuarioActual.rol][0];
    }
});