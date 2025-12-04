<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Tenis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="navbar"><h1>🎾 Registro</h1></div>
    <?php if(isset($_GET['msg'])) echo "<div class='alert error'>".htmlspecialchars(
        $_GET['msg'])."</div>"; ?>
    <form action="procesar_registro.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="telefono" placeholder="Teléfono" required>
        <label>Fecha de nacimiento (debes tener +18 años)</label>
        <input type="date" name="fecha_nacimiento" required>
        <button type="submit">Registrarme</button>
    </form>
    <p style="text-align:center; margin-top:20px;">
        <a href="index.php">← Volver al login</a>
    </p>
</div>
</body>
</html>