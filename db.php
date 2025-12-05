<?php
if(!file_exists(__DIR__ . '/bd.php')) {
    die('Falta el archivo de configuración de BD: bd.php');
}
require_once __DIR__ . '/bd.php';

if(!isset($pdo) || !$pdo) {
    die('Conexión a la base de datos no inicializada.');
}

$pdo->exec("SET NAMES 'utf8'");

?>
