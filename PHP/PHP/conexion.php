<?php
$host = "localhost";
$user = "root";
$password = "Sql.Server248";
$database = "sistema_educativo";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}
?>
