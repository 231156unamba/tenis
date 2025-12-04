<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
require 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="navbar">
        <h1>🎾 Bienvenido <?= $_SESSION['nombre'] ?></h1>
    </div>
    <h2>Opciones</h2>
    <div style="text-align:center; margin:30px 0;">
        <a href="disponibilidad.php"><button style="background:#4dd0e1;">Consultar Disponibilidad</button></a><br><br>
        <a href="logout.php"><button style="background:#c62828;">Cerrar Sesión</button></a>
    </div>
</div>
</body>
</html>