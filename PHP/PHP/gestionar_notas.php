<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] === "estudiante") {
    header("Location: login.php");
    exit();
}

$editando = false;
$nota_edit = ["id_nota" => "", "id_estudiante" => "", "id_curso" => "", "nota" => ""];

if ($_SESSION["rol"] === "profesor") {
    $id_usuario = $_SESSION["id_usuario"];
    $sql = "SELECT nivel FROM profesores WHERE usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $nivel_asignado = $stmt->get_result()->fetch_assoc()["nivel"];
} else {
    $nivel_asignado = null;
}

if (isset($_GET['editar'])) {
    $editando = true;
    $id_nota = $_GET['editar'];
    $sql = "SELECT * FROM notas WHERE id_nota = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_nota);
    $stmt->execute();
    $nota_edit = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_estudiante = $_POST["estudiante"];
    $id_curso = $_POST["curso"];
    $nota = $_POST["nota"];

    if (isset($_POST["id_nota"]) && $_POST["id_nota"] !== "") {
        $sql = "UPDATE notas SET id_estudiante = ?, id_curso = ?, nota = ? WHERE id_nota = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iidi", $id_estudiante, $id_curso, $nota, $_POST["id_nota"]);
    } else {
        $sql = "INSERT INTO notas (id_estudiante, id_curso, nota, fecha_registro) VALUES (?, ?, ?, NOW())";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iid", $id_estudiante, $id_curso, $nota);
    }
    $stmt->execute();
    header("Location: gestionar_notas.php");
    exit();
}

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $conexion->query("DELETE FROM notas WHERE id_nota = $id");
    header("Location: gestionar_notas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Notas</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<div class="container">
    <h2><?= $editando ? "Editar Nota" : "Registrar Nota" ?></h2>
    <form method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id_nota" value="<?= $nota_edit['id_nota'] ?>">
        <?php endif; ?>

        <label>Estudiante:</label>
        <select name="estudiante" required>
            <option value="">Seleccione...</option>
            <?php
            $sql_est = "SELECT id_estudiante, nombre, apellido FROM estudiantes";
            if ($nivel_asignado !== null) {
                $sql_est .= " WHERE nivel = '" . $conexion->real_escape_string($nivel_asignado) . "'";
            }
            $sql_est .= " ORDER BY nombre";
            $res = $conexion->query($sql_est);
            while ($row = $res->fetch_assoc()) {
                $sel = ($row['id_estudiante'] == $nota_edit['id_estudiante']) ? "selected" : "";
                echo "<option value='{$row['id_estudiante']}' $sel>{$row['nombre']} {$row['apellido']}</option>";
            }
            ?>
        </select>

        <label>Curso:</label>
        <select name="curso" required>
            <option value="">Seleccione...</option>
            <?php
            $res = $conexion->query("SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso");
            while ($row = $res->fetch_assoc()) {
                $sel = ($row['id_curso'] == $nota_edit['id_curso']) ? "selected" : "";
                echo "<option value='{$row['id_curso']}' $sel>{$row['nombre_curso']}</option>";
            }
            ?>
        </select>

        <label>Nota:</label>
        <input type="number" step="0.01" name="nota" value="<?= $nota_edit['nota'] ?>" required>

        <input type="submit" value="<?= $editando ? "Actualizar Nota" : "Registrar Nota" ?>">
    </form>

    <hr>
    <h3>Listado de Notas</h3>
<table border="1" width="100%">
    <tr>
        <th>Estudiante</th>
        <th>Curso</th>
        <th>Nota</th>
        <th>Fecha</th>
        <th>Acción</th>
    </tr>
    <?php
    $sql = "SELECT n.id_nota, e.nombre, e.apellido, c.nombre_curso, n.nota, n.fecha_registro
            FROM notas n
            JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
            JOIN cursos c ON n.id_curso = c.id_curso
            WHERE e.nivel = (
                SELECT nivel FROM profesores WHERE usuario_id = ?
            )
            ORDER BY n.fecha_registro DESC";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $_SESSION["id_usuario"]);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$fila['nombre']} {$fila['apellido']}</td>";
        echo "<td>{$fila['nombre_curso']}</td>";
        echo "<td>{$fila['nota']}</td>";
        echo "<td>{$fila['fecha_registro']}</td>";
        echo "<td>
                <a href='gestionar_notas.php?editar={$fila['id_nota']}'>Editar</a> |
                <a href='gestionar_notas.php?eliminar={$fila['id_nota']}' onclick=\"return confirm('¿Eliminar esta nota?');\">Eliminar</a>
              </td>";
        echo "</tr>";
    }
    ?>
</table>

    <br>
    <a href="<?= $_SESSION['rol'] === 'profesor' ? 'profesor_panel.php' : 'admin_panel.php' ?>">← Volver al panel</a>
</div>
</body>
</html>