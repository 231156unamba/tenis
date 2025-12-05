<?php
require 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $fecha_nac = $_POST['fecha_nacimiento'];

    $hoy = new DateTime();
    $nacimiento = new DateTime($fecha_nac);
    $edad = $hoy->diff($nacimiento)->y;
    if($edad < 18) {
        header("Location: registro.php?msg=Tienes que ser mayor de 18 años");
        exit;
    }

    $password_plana = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'), 0, 8);
    $hash = password_hash($password_plana, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, telefono, password, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $email, $telefono, $hash, $fecha_nac]);

        echo '<div style="font-family: Arial; text-align:center; padding:50px; background:#e0f7fa; min-height:100vh;">
                <div style="background:white; padding:40px; border-radius:20px; display:inline-block; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                    <h1 style="color:#00796b;">¡Registro exitoso! 🎾</h1>
                    <p><strong>Nombre:</strong> ' . htmlspecialchars($nombre . ' ' . $apellido) . '</p>
                    <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                    <hr style="margin:20px 0;">
                    <h2 style="color:#d81b60;">GUARDA ESTA CONTRASEÑA</h2>
                    <div style="font-size:28px; background:#fff3e0; padding:20px; border:3px solid #ff9800; border-radius:15px; color:#e65100;">
                        <strong>' . $password_plana . '</strong>
                    </div>
                    <p style="margin-top:20px; color:#d32f2f;">Esta contraseña <strong>solo se muestra una vez</strong>.</p>
                    <p><a href="index.php" style="background:#26a69a; color:white; padding:15px 30px; text-decoration:none; border-radius:10px; display:inline-block; margin-top:20px;">Ir al Login →</a></p>
                </div>
              </div>';
        exit;

    } catch(Exception $e) {
        header("Location: registro.php?msg=Error: Email ya registrado");
        exit;
    }
}
?>