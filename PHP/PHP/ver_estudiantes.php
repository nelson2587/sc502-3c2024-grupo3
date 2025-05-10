<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "profesor") {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION["id_usuario"];
$sql = "SELECT nivel FROM profesores WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$nivel = $stmt->get_result()->fetch_assoc()["nivel"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estudiantes de mi nivel</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<div class="container">
    <h2>Estudiantes de Nivel: <?= htmlspecialchars($nivel) ?></h2>
    <table border="1" width="100%">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
        </tr>
        <?php
        $sql = "SELECT id_estudiante, nombre, apellido FROM estudiantes WHERE nivel = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nivel);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($fila = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['id_estudiante']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['apellido']) . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <br>
    <a href="profesor_panel.php">← Volver al panel</a>
</div>
</body>
</html>
