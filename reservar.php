<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
require 'db.php';
// Aceptar solo POST
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: disponibilidad.php");
    exit;
}

$fecha = $_POST['fecha'] ?? null;
$hora = $_POST['hora'] ?? null;
$cancha = $_POST['cancha'] ?? null;
$usuario_id = $_SESSION['user_id'];

if(!$fecha || !$hora || !$cancha) {
    header("Location: disponibilidad.php?msg=Datos incompletos para la reserva");
    exit;
}

// Validar formato de fecha y anticipación mínima 2 días
try {
    $fecha_dt = new DateTime($fecha);
    $minima = new DateTime('+' . 2 . ' days');
    $minima->setTime(0,0,0);
    $fecha_dt->setTime(0,0,0);
    if($fecha_dt < $minima) {
        header("Location: disponibilidad.php?msg=No puedes reservar con menos de 2 días de anticipación");
        exit;
    }
} catch(Exception $e) {
    header("Location: disponibilidad.php?msg=Fecha inválida");
    exit;
}

// Normalizar hora a HH:MM:SS si viene sin segundos
if(strlen($hora) <= 5) $hora = $hora . ':00';

// Verificar si ya está ocupada
$stmt = $pdo->prepare("SELECT COUNT(*) as cnt FROM reservas WHERE fecha = ? AND hora = ? AND cancha = ?");
$stmt->execute([$fecha, $hora, $cancha]);
$row = $stmt->fetch();
if($row && $row['cnt'] > 0) {
    header("Location: disponibilidad.php?msg=Cancha ocupada en ese horario");
    exit;
}

// Reservar
$stmt = $pdo->prepare("INSERT INTO reservas (usuario_id, cancha, fecha, hora) VALUES (?, ?, ?, ?)");
try {
    $stmt->execute([$usuario_id, $cancha, $fecha, $hora]);
    // Mostrar pantalla de confirmación y pago próximamente
    echo '<div style="font-family: Arial; text-align:center; padding:50px; background:#e0f7fa; min-height:100vh;">
            <div style="background:white; padding:40px; border-radius:20px; display:inline-block; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                <h1 style="color:#00796b;">¡Reserva exitosa! 🎾</h1>
                <p><strong>Cancha:</strong> ' . htmlspecialchars($cancha) . '</p>
                <p><strong>Fecha:</strong> ' . htmlspecialchars($fecha) . '</p>
                <p><strong>Hora:</strong> ' . htmlspecialchars(substr($hora,0,5)) . '</p>
                <hr style="margin:20px 0;">
                <h2 style="color:#d81b60;">Módulo de pago próximamente</h2>
                <div style="font-size:20px; background:#fff3e0; padding:20px; border:3px solid #ff9800; border-radius:15px; color:#e65100; margin-bottom:20px;">
                    Podrás pagar tu reserva aquí en breve.
                </div>
                <p><a href="disponibilidad.php?fecha=' . urlencode($fecha) . '" style="background:#26a69a; color:white; padding:15px 30px; text-decoration:none; border-radius:10px; display:inline-block; margin-top:20px;">Volver a disponibilidad</a></p>
                <p><a href="dashboard.php" style="background:#4dd0e1; color:white; padding:10px 20px; text-decoration:none; border-radius:8px; display:inline-block; margin-top:10px;">Ir al dashboard</a></p>
            </div>
          </div>';
    exit;
} catch(Exception $e) {
    header("Location: disponibilidad.php?msg=Error al guardar la reserva");
    exit;
}
?>