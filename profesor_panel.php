<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "profesor") {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION["id_usuario"];

// Obtener el nivel del profesor
$sql = "SELECT nivel FROM profesores WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$nivel = $resultado->fetch_assoc()["nivel"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Profesor</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        .panel-links {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
        }

        .panel-links a,
        .panel-links form button {
            padding: 10px 20px;
            background-color: #2a3f54;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .panel-links a:hover,
        .panel-links form button:hover {
            background-color: #1a2e40;
        }

        .panel-links form {
            margin: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bienvenido, Profesor</h2>

    <div class="panel-links">
        <a href="ver_estudiantes.php">👨‍🎓 Ver Estudiantes</a>
        <a href="gestionar_notas.php">📝 Gestionar Notas</a>

        <!-- Botón para generar el reporte del nivel asignado -->
        <form action="reporte_notas_print.php" method="GET">
            <input type="hidden" name="nivel" value="<?= htmlspecialchars($nivel) ?>">
            <button type="submit">🖨️ Ver Reporte Imprimible</button>
        </form>

        <a href="logout.php">🔒 Cerrar sesión</a>
    </div>
</div>

</body>
</html>
