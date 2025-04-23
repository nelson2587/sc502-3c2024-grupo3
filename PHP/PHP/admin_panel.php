<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .panel-links {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        .panel-links a {
            padding: 10px 20px;
            background-color: #2a3f54;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }

        .panel-links a:hover {
            background-color: #1a2e40;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bienvenido, Administrador</h2>

    <div class="panel-links">
        <a href="gestionar_usuarios.php">👥 Gestionar Usuarios</a>
        <a href="gestionar_profesores.php">👨‍🏫 Gestionar Profesores</a>
        <a href="gestionar_estudiantes.php">👨‍🎓 Gestionar Estudiantes</a>
        <a href="gestionar_cursos.php">📚 Gestionar Cursos</a>
        <a href="gestionar_notas.php">📝 Gestionar Notas</a>
        <a href="reporte_notas_print.php" target="_blank">🖨️ Ver Reporte Imprimible</a>
        <a href="logout.php">🔒 Cerrar sesión</a>
    </div>
</div>

</body>
</html>