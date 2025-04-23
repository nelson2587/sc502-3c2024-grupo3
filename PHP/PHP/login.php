<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];

    // Consultar el usuario en la base de datos
    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario_data = $resultado->fetch_assoc();

        // Validar contraseña (aquí no usamos hash por simplicidad)
        if ($usuario_data["contrasena"] === $contrasena) {
            $_SESSION["id_usuario"] = $usuario_data["id"];
            $_SESSION["rol"] = $usuario_data["rol"];

            // Redireccionar según rol
            switch ($usuario_data["rol"]) {
                case "administrador":
                    header("Location: admin_panel.php");
                    break;
                case "profesor":
                    header("Location: profesor_panel.php");
                    break;
                case "estudiante":
                    header("Location: estudiante_panel.php");
                    break;
            }
            exit();
        } else {
            echo "❌ Contraseña incorrecta.";
        }
    } else {
        echo "❌ Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Sistema Académico</title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 25px;
        }

        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #2a3f54;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: #1a2e40;
        }

        .footer-note {
            margin-top: 15px;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>

    <form action="login.php" method="POST">
        <input type="text" name="usuario" placeholder="Usuario" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="submit" value="Ingresar">
    </form>

    <div class="footer-note">
        © 2025 Sistema de Gestión Académica
    </div>
</div>

</body>
</html>

