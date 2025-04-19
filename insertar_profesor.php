<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $nivel = $_POST["nivel"];

    $id = uniqid("PROF-");
    $contrasena = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);

    $sqlU = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (?, ?, 'profesor')";
    $stmtU = $conexion->prepare($sqlU);
    $stmtU->bind_param("ss", $id, $contrasena);
    if ($stmtU->execute()) {
        $usuario_id = $stmtU->insert_id;

        $sqlP = "INSERT INTO profesores (nombre, nivel, usuario_id) VALUES (?, ?, ?)";
        $stmtP = $conexion->prepare($sqlP);
        $stmtP->bind_param("ssi", $nombre, $nivel, $usuario_id);
        if ($stmtP->execute()) {
            echo json_encode(["success" => true, "id" => $id, "contrasena" => $contrasena]);
        } else {
            echo json_encode(["success" => false, "error" => "Error al guardar profesor"]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Error al guardar usuario"]);
    }
}
?>
