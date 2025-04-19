<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "estudiante") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Estudiante</title>
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
    <h2>Bienvenido, Estudiante</h2>

    <div class="panel-links">
        <a href="ver_mis_notas.php">📋 Ver mis notas</a>
        <a href="logout.php">🔒 Cerrar sesión</a>
    </div>
</div>

</body>
</html>
