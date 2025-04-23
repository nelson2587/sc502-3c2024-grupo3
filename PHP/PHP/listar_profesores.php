<?php
include("conexion.php");

$sql = "SELECT p.nombre, p.nivel, u.usuario, u.contrasena
        FROM profesores p
        JOIN usuarios u ON p.usuario_id = u.id";
$result = $conexion->query($sql);

$datos = [];
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}
echo json_encode($datos);
?>
