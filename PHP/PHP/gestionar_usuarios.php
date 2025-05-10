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
    $sql = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $datos_editar = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $rol = $_POST["rol"];

    // Verificar si el usuario ya existe para evitar que algun nombre se duplique
    $sql = "SELECT id FROM usuarios WHERE usuario = ? AND id != ?";
    $stmt = $conexion->prepare($sql);
    $id = isset($_POST["id"]) ? $_POST["id"] : 0;
    $stmt->bind_param("si", $usuario, $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "<script>alert('No es posible registrar o editar. El nombre de usuario ya está en uso.');</script>";
    } else {
        if (isset($_POST["id"]) && $_POST["id"] !== "") {
            $sql = "UPDATE usuarios SET usuario = ?, contrasena = ?, rol = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssi", $usuario, $contrasena, $rol, $id);
        } else {
            $sql = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $usuario, $contrasena, $rol);
        }

        $stmt->execute();
        header("Location: gestionar_usuarios.php");
        exit();
    }
}

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"];
    $conexion->query("DELETE FROM usuarios WHERE id = $id");
    header("Location: gestionar_usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<div class="container">
    <h2><?= $editando ? "Editar Usuario" : "Registrar Usuario" ?></h2>
    <form method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id" value="<?= $datos_editar["id"] ?>">
        <?php endif; ?>

        <label>Usuario:</label>
        <input type="text" name="usuario" required value="<?= $editando ? $datos_editar["usuario"] : "" ?>">

        <label>Contraseña:</label>
        <input type="text" name="contrasena" required value="<?= $editando ? $datos_editar["contrasena"] : "" ?>">

        <label>Rol:</label>
        <select name="rol" required>
            <option value="">Seleccione...</option>
            <option value="administrador" <?= ($editando && $datos_editar["rol"] === "administrador") ? "selected" : "" ?>>Administrador</option>
            <option value="profesor" <?= ($editando && $datos_editar["rol"] === "profesor") ? "selected" : "" ?>>Profesor</option>
            <option value="estudiante" <?= ($editando && $datos_editar["rol"] === "estudiante") ? "selected" : "" ?>>Estudiante</option>
        </select>

        <input type="submit" value="<?= $editando ? "Actualizar" : "Registrar" ?>">
    </form>

    <hr>
    <h2>Lista de Usuarios</h2>
    <table border="1" width="100%">
        <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Contraseña</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php
        $res = $conexion->query("SELECT * FROM usuarios");
        while ($fila = $res->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$fila['id']}</td>";
            echo "<td>{$fila['usuario']}</td>";
            echo "<td>{$fila['contrasena']}</td>";
            echo "<td>{$fila['rol']}</td>";
            echo "<td>
                    <a href='gestionar_usuarios.php?editar={$fila['id']}'>Editar</a> |
                    <a href='gestionar_usuarios.php?eliminar={$fila['id']}' onclick=\"return confirm('¿Eliminar este usuario?');\">Eliminar</a>
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
