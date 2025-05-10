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
    $sql = "SELECT p.id_estudiante, p.nombre, p.apellido, p.nivel, u.usuario, u.id as usuario_id
            FROM estudiantes p 
            JOIN usuarios u ON p.usuario_id = u.id 
            WHERE p.id_estudiante = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $datos_editar = $stmt->get_result()->fetch_assoc();
}

if (isset($_GET["eliminar"])) {
    $id = $_GET["eliminar"]; $conexion->query("DELETE FROM estudiantes WHERE id_estudiante = $id"); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $nivel = $_POST["nivel"];
    $usuario_id = $_POST["usuario"];

    // Validar nivel
    $niveles_validos = ["Primero", "Segundo", "Tercero", "Cuarto", "Quinto", "Sexto"];
    if (!in_array($nivel, $niveles_validos)) {
        echo "<script>alert('El nivel ingresado no es válido. Debe ser Primero, Segundo, Tercero, Cuarto, Quinto o Sexto.'); window.location.href='gestionar_estudiantes.php';</script>";
        exit();
    }

    // Validar si usuario_id existe en la tabla usuarios
    $sql_usuario = "SELECT id FROM usuarios WHERE id = ?";
    $stmt_usuario = $conexion->prepare($sql_usuario);
    $stmt_usuario->bind_param("i", $usuario_id);
    $stmt_usuario->execute();
    $resultado_usuario = $stmt_usuario->get_result();

    if ($resultado_usuario->num_rows == 0) {
        echo "<script>alert('El usuario asociado no existe en la tabla de usuarios.'); window.location.href='gestionar_estudiantes.php';</script>";
        exit();
    }

    if ($editando) {
        $sqlP = "UPDATE estudiantes SET nombre = ?, apellido = ?, nivel = ?, usuario_id = ? WHERE id_estudiante = ?";
        $stmtP = $conexion->prepare($sqlP);
        $stmtP->bind_param("sssii", $nombre, $apellido, $nivel, $usuario_id, $_POST["id_prof"]);
    } else {
        $sqlP = "INSERT INTO estudiantes (nombre, apellido, nivel, usuario_id) VALUES (?, ?, ?, ?)";
        $stmtP = $conexion->prepare($sqlP);
        $stmtP->bind_param("sssi", $nombre, $apellido, $nivel, $usuario_id);
    }

    $stmtP->execute();
    header("Location: gestionar_estudiantes.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Estudiantes</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

<div class="container">
    <h2><?php echo $editando ? "Editar Estudiante" : "Registrar Estudiante"; ?></h2>

    <form method="POST">
        <?php if ($editando): ?>
            <input type="hidden" name="id_prof" value="<?php echo $datos_editar["id_estudiante"]; ?>">
            <input type="hidden" name="usuario_id" value="<?php echo $datos_editar["usuario_id"]; ?>">
        <?php endif; ?>

        <label>Nombre:</label>
        <input type="text" name="nombre" required value="<?php echo $editando ? $datos_editar["nombre"] : ""; ?>">

        <label>Apellido:</label>
        <input type="text" name="apellido" required value="<?php echo $editando ? $datos_editar["apellido"] : ""; ?>">

        <label>Nivel:</label>
        <input type="text" name="nivel" required value="<?php echo $editando ? $datos_editar["nivel"] : ""; ?>">

        <label>Usuario ID:</label>
        <input type="text" name="usuario" required value="<?php echo $editando ? $datos_editar["usuario_id"] : ""; ?>">

        <input type="submit" name="<?php echo $editando ? "actualizar" : "registrar"; ?>" 
               value="<?php echo $editando ? "Actualizar" : "Registrar"; ?>">
    </form>

    <hr>

    <h3>Lista de Estudiantes</h3>
    <table border="1" width="100%">
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Nivel</th>
            <th>Usuario</th>
            <th>Acciones</th>
        </tr>
        <?php
        $sql = "SELECT p.id_estudiante, p.nombre, p.apellido, p.nivel, u.usuario 
                FROM estudiantes p 
                JOIN usuarios u ON p.usuario_id = u.id";
        $resultado = $conexion->query($sql);

        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$fila['id_estudiante']}</td>";
            echo "<td>{$fila['nombre']} {$fila['apellido']}</td>";
            echo "<td>{$fila['nivel']}</td>";
            echo "<td>{$fila['usuario']}</td>"; // Muestra el nombre de usuario en el listado
            echo "<td>
                    <a href='gestionar_estudiantes.php?editar={$fila['id_estudiante']}'>Editar</a> | 
                    <a href='gestionar_estudiantes.php?eliminar={$fila['id_estudiante']}'
                       onclick=\"return confirm('¿Eliminar este estudiante?');\">Eliminar</a>
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

