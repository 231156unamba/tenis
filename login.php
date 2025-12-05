<?php
session_start();
require 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user) {
        if($user['bloqueado_hasta'] && new DateTime() < new DateTime($user['bloqueado_hasta'])) {
            header("Location: index.php?msg=Cuenta bloqueada. Intenta en 5 minutos");
            exit;
        }

        if(password_verify($password, $user['password'])) {
            // Reset intentos
            $pdo->prepare("UPDATE usuarios SET intentos_fallidos = 0, bloqueado_hasta = NULL WHERE id = ?")->execute([$user['id']]);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            header("Location: dashboard.php");
            exit;
        } else {
            $intentos = $user['intentos_fallidos'] + 1;
            if($intentos >= 3) {
                $bloqueo = date('Y-m-d H:i:s', time() + 300); // 5 min
                $pdo->prepare("UPDATE usuarios SET intentos_fallidos = ?, bloqueado_hasta = ? WHERE id = ?")
                    ->execute([$intentos, $bloqueo, $user['id']]);
                header("Location: index.php?msg=Cuenta bloqueada por 5 minutos");
            } else {
                $pdo->prepare("UPDATE usuarios SET intentos_fallidos = ? WHERE id = ?")->execute([$intentos, $user['id']]);
                header("Location: index.php?msg=Contraseña incorrecta ($intentos/3)");
            }
            exit;
        }
    } else {
        header("Location: index.php?msg=Email no encontrado");
    }
}
?>