<?php
// Archivo de compatibilidad para exportar $pdo como conexión de BD
// Incluye el archivo real `bd.php` que crea la conexión PDO en $pdo
if(!file_exists(__DIR__ . '/bd.php')) {
    die('Falta el archivo de configuración de BD: bd.php');
}
require_once __DIR__ . '/bd.php';

// Asegurar que $pdo exista
if(!isset($pdo) || !$pdo) {
    die('Conexión a la base de datos no inicializada.');
}

// Opcional: forzar utf8
$pdo->exec("SET NAMES 'utf8'");

?>
