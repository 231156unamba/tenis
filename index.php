<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas Tenis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="navbar"><h1>🎾 Club de Tenis</h1></div>
    <h2>Iniciar Sesión</h2>
    <?php if(isset($_GET['msg'])) echo "<div class='alert error'>".htmlspecialchars($_GET['msg'])."</div>"; ?>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <p style="text-align:center; margin-top:20px;">
        ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
    </p>
</div>
</body>
</html>