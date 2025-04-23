<?php
include("conexion.php");

// Validar datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $grado = $_POST["grado"];
    $seccion = $_POST["seccion"];

    // Generar ID único y contraseña
    $id = uniqid("EST-");
    $contrasena = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

    // Insertar en usuarios
    $sqlU = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, 'estudiante')";
    $stmtU = $conexion->prepare($sqlU);
    $stmtU->bind_param("ss", $id, $contrasena);
    if ($stmtU->execute()) {
        $usuario_id = $stmtU->insert_id;

        // Insertar en estudiantes
        $sqlE = "INSERT INTO estudiantes (nombre, apellido, usuario_id) VALUES (?, ?, ?)";
        $stmtE = $conexion->prepare($sqlE);
        $stmtE->bind_param("ssi", $nombre, $apellido, $usuario_id);
        if ($stmtE->execute()) {
            echo json_encode(["success" => true, "id" => $id, "contrasena" => $contrasena]);
        } else {
            echo json_encode(["success" => false, "error" => "Error al guardar en estudiantes"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Error al guardar en usuarios"]);
    }
}
?>
