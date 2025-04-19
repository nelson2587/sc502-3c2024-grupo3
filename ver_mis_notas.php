<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "estudiante") {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION["id_usuario"];

// Obtener el id_estudiante desde usuario_id
$sql = "SELECT id_estudiante FROM estudiantes WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $datos = $resultado->fetch_assoc();
    $id_estudiante = $datos["id_estudiante"];
} else {
    echo "<script>alert('No se encontró el estudiante asociado a este usuario.'); window.location.href='login.html';</script>";
    exit();
}

// Obtener notas filtradas por id_estudiante
$sqlNotas = "SELECT c.nombre_curso, n.nota
             FROM notas n
             JOIN cursos c ON n.id_curso = c.id_curso
             WHERE n.id_estudiante = ?
             ORDER BY n.fecha_registro DESC";

$stmtNotas = $conexion->prepare($sqlNotas);
$stmtNotas->bind_param("i", $id_estudiante);
$stmtNotas->execute();
$notas = $stmtNotas->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Notas</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="container">
    <h2>Mis Notas</h2>

    <button onclick="window.print()">🖨️ Imprimir Reporte</button>

    <?php if ($notas->num_rows > 0): ?>
        <table border="1" width="100%">
            <tr>
                <th>Curso</th>
                <th>Nota</th>
            </tr>
            <?php while ($fila = $notas->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila["nombre_curso"]) ?></td>
                    <td><?= htmlspecialchars($fila["nota"]) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No se han registrado notas aún.</p>
    <?php endif; ?>

    <br>
    <a href="estudiante_panel.php">← Volver al panel</a>
</div>

</body>
</html>
