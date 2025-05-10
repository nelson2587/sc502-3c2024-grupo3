<?php
include("conexion.php");

$sql = "SELECT * FROM cursos ORDER BY nombre_curso";
$resultado = $conexion->query($sql);

$cursos = [];
while ($row = $resultado->fetch_assoc()) {
    $cursos[] = $row;
}

echo json_encode($cursos);
?>
