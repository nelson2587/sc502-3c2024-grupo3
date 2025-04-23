<?php
include("conexion.php");

$sql = "SELECT n.id_nota, e.nombre AS estudiante, c.nombre_curso AS curso, n.nota, n.fecha_registro
        FROM notas n
        JOIN estudiantes e ON n.id_estudiante = e.id_estudiante
        JOIN cursos c ON n.id_curso = c.id_curso
        ORDER BY n.fecha_registro DESC";

$resultado = $conexion->query($sql);

$notas = [];
while ($fila = $resultado->fetch_assoc()) {
    $notas[] = $fila;
}

echo json_encode($notas);
?>
