<?php
include("conexion.php");

$sql = "SELECT id_estudiante, nombre FROM estudiantes ORDER BY nombre";
$resultado = $conexion->query($sql);

$estudiantes = [];
while ($row = $resultado->fetch_assoc()) {
    $estudiantes[] = $row;
}

echo json_encode($estudiantes);
?>
