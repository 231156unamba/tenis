<?php

if(!isset($pdo) || !$pdo) {
    die('Conexión a la base de datos no inicializada.');
}

// Opcional: forzar utf8
$pdo->exec("SET NAMES 'utf8'");

?>
