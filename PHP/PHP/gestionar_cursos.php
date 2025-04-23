<?php
session_start();
include("conexion.php");

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: login.php");
    exit();
}

$editando = false;
$datos_editar = null;

if (isset($_GET["editar"])) {
    $editando = true;
    $id = $_GET["editar"];
    $sql = "SELECT * FROM cursos WHERE id_curso = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $datos_editar = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["actualizar"])) {
    $id_curso = $_POST["id_curso"];
    $nombre_curso = $_POST["nombre_curso"];

    $sql = "UPDATE cursos SET nombre_curso = ? WHERE id_curso = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nombre_curso, $id_curso);
    $stmt->execute();

    header("Location: gestionar_cursos.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["registrar"])) {
    $nombre_curso = $_POST["nombre_curso"];

    $sql = "INSERT INTO cursos (nombre_curso) VALUES (?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $nombre_curso);
    $stmt->execute();
}

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $conexion->query("DELETE FROM cursos WHERE id_curso = $id");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Cursos</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="container">
    <h2><?php echo $editando ? "Editar Curso" : "Registrar Curso"; ?></h2>

    <form method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id_curso" value="<?php echo $datos_editar["id_curso"]; ?>">
        <?php endif; ?>

        <label>Nombre del Curso:</label>
        <input type="text" name="nombre_curso" required value="<?php echo $editando ? $datos_editar["nombre_curso"] : ""; ?>">

        <input type="submit" name="<?php echo $editando ? "actualizar" : "registrar"; ?>" 
               value="<?php echo $editando ? "Actualizar" : "Registrar"; ?>">
    </form>

    <hr>

    <h3>Lista de Cursos</h3>
    <table border="1" width="100%">
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT * FROM cursos";
        $resultado = $conexion->query($sql);

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$fila['id_curso']}</td>";
            echo "<td>{$fila['nombre_curso']}</td>";
            echo "<td>
                    <a href='gestionar_cursos.php?editar={$fila['id_curso']}'>Editar</a> | 
                    <a href='gestionar_cursos.php?eliminar={$fila['id_curso']}'
                       onclick=\"return confirm('¿Eliminar este curso?');\">Eliminar</a>
                  </td>";
            echo "</tr>";
        }
        ?>
    </table>

    <br>
    <a href="admin_panel.php">← Volver al panel</a>
</div>

</body>
</html>
