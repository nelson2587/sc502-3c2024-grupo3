<?php
session_start();
include("conexion.php");

// Validar rol
if (!isset($_SESSION["rol"]) || !in_array($_SESSION["rol"], ["administrador", "profesor"])) {
    header("Location: login.html");
    exit();
}

$nivel = null;
$id_estudiante_seleccionado = isset($_GET["id_estudiante"]) ? $_GET["id_estudiante"] : null;

// Si es profesor, obtener su nivel asignado
if ($_SESSION["rol"] === "profesor") {
    $id_usuario = $_SESSION["id_usuario"];
    $stmt = $conexion->prepare("SELECT nivel FROM profesores WHERE usuario_id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $res = $stmt->get_result();
    $nivel = $res->fetch_assoc()["nivel"];
}

// Si es administrador, permitir elegir nivel por GET
if ($_SESSION["rol"] === "administrador" && isset($_GET["nivel"])) {
    $nivel = $_GET["nivel"];
}

// Obtener niveles para el select si es administrador
$niveles = [];
if ($_SESSION["rol"] === "administrador") {
    $nivelQuery = $conexion->query("SELECT DISTINCT nivel FROM estudiantes ORDER BY nivel");
    while ($row = $nivelQuery->fetch_assoc()) {
        $niveles[] = $row["nivel"];
    }
}

// Obtener lista de estudiantes para profesores
$estudiantes = [];
if ($_SESSION["rol"] === "profesor") {
    $estudiantesQuery = $conexion->prepare("SELECT id_estudiante, CONCAT(nombre, ' ', apellido) AS nombre_estudiante FROM estudiantes WHERE nivel = ? ORDER BY nombre");
    $estudiantesQuery->bind_param("s", $nivel);
    $estudiantesQuery->execute();
    $resultEstudiantes = $estudiantesQuery->get_result();

    while ($row = $resultEstudiantes->fetch_assoc()) {
        $estudiantes[] = $row;
    }
}

// Consulta SQL para reporte
$sql = "SELECT CONCAT(e.nombre, ' ', e.apellido) AS nombre_estudiante, 
               c.nombre_curso, 
               n.nota, 
               n.fecha_registro
        FROM notas n
        JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
        JOIN cursos c ON n.id_curso = c.id_curso";

// Aplicar filtro por nivel o estudiante si se selecciona
if ($_SESSION["rol"] === "profesor" && !empty($id_estudiante_seleccionado)) {
    $sql .= " WHERE e.id_estudiante = ?";
} elseif (!empty($nivel)) {
    $sql .= " WHERE e.nivel = ?";
}

$sql .= " ORDER BY e.nombre, c.nombre_curso";

$stmt = $conexion->prepare($sql);

if ($_SESSION["rol"] === "profesor" && !empty($id_estudiante_seleccionado)) {
    $stmt->bind_param("i", $id_estudiante_seleccionado);
} elseif (!empty($nivel)) {
    $stmt->bind_param("s", $nivel);
}

$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte General de Notas</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2a3f54;
            color: white;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
        }
        button {
            margin-top: 10px;
            padding: 8px 16px;
            border: none;
            background-color: #2a3f54;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Reporte General de Notas</h2>

    <?php if ($_SESSION["rol"] === "administrador"): ?>
        <form method="GET" style="margin-bottom: 15px;">
            <label for="nivel">Filtrar por nivel:</label>
            <select name="nivel" id="nivel" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach ($niveles as $niv): ?>
                    <option value="<?= $niv ?>" <?= ($niv === $nivel) ? 'selected' : '' ?>><?= $niv ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>

    <?php if ($_SESSION["rol"] === "profesor"): ?>
        <form method="GET" style="margin-bottom: 15px;">
            <label for="id_estudiante">Filtrar por estudiante:</label>
            <select name="id_estudiante" id="id_estudiante" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach ($estudiantes as $est): ?>
                    <option value="<?= $est['id_estudiante'] ?>" <?= ($est['id_estudiante'] == $id_estudiante_seleccionado) ? 'selected' : '' ?>>
                        <?= $est['nombre_estudiante'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    <?php endif; ?>

    <button onclick="window.print()">🖨️ Imprimir Reporte</button>
    
    <?php if ($_SESSION["rol"] === "administrador"): ?>
        <br><a href="admin_panel.php">← Volver al panel</a>
    <?php elseif ($_SESSION["rol"] === "profesor"): ?>
        <br><a href="profesor_panel.php">← Volver al panel</a>
    <?php endif; ?>

    <table>
        <tr>
            <th>Estudiante</th>
            <th>Curso</th>
            <th>Nota</th>
            <th>Fecha</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($fila["nombre_estudiante"]) ?></td>
                <td><?= htmlspecialchars($fila["nombre_curso"]) ?></td>
                <td><?= htmlspecialchars($fila["nota"]) ?></td>
                <td><?= htmlspecialchars($fila["fecha_registro"]) ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>

