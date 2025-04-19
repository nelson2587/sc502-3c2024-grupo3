<?php
session_start();
include("conexion.php");

// Verificamos que sea un profesor quien está intentando registrar la nota
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== "profesor") {
    echo json_encode(["success" => false, "error" => "Acceso no autorizado"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $estudiante_id = $_POST["estudiante_id"];
    $curso_id = $_POST["curso_id"];
    $nota = $_POST["nota"];
    $id_profesor = $_SESSION["id_profesor"]; // desde la sesión del login

    // Validamos datos
    if (empty($estudiante_id) || empty($curso_id) || $nota === "" || !is_numeric($nota)) {
        echo json_encode(["success" => false, "error" => "Datos incompletos o inválidos"]);
        exit();
    }

    // Insertar la nota
    $sql = "INSERT INTO notas (id_estudiante, id_curso, nota, fecha_registro, id_profesor) 
            VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iidi", $estudiante_id, $curso_id, $nota, $id_profesor);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al registrar nota"]);
    }
}
?>
