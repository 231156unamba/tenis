<!-- disponibilidad.php -->
<?php
session_start();
if(!isset($_SESSION['user_id'])) { header("Location: index.php"); exit; }
require 'db.php';

$fecha_seleccionada = $_POST['fecha'] ?? $_GET['fecha'] ?? date('Y-m-d', strtotime('+2 days'));
if($fecha_seleccionada < date('Y-m-d', strtotime('+2 days'))) {
    $fecha_seleccionada = date('Y-m-d', strtotime('+2 days'));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Disponibilidad</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="navbar"><h1>🎾 Disponibilidad</h1></div>
    
    <form method="POST" style="margin-bottom:20px;">
        <input type="date" name="fecha" value="<?= $fecha_seleccionada ?>" min="<?= date('Y-m-d', strtotime('+2 days')) ?>" required>
        <button type="submit">Buscar</button>
    </form>

    <table>
        <tr><th>Hora</th><th>Cancha 1</th><th>Cancha 2</th><th>Cancha 3</th></tr>
        <?php
        $horas = ['08:00','10:00','12:00','14:00','16:00','18:00','20:00'];
        foreach($horas as $hora) {
            echo "<tr><td><strong>$hora</strong></td>";
            for($cancha=1; $cancha<=3; $cancha++) {
                $stmt = $pdo->prepare("SELECT * FROM reservas WHERE fecha = ? AND hora = ? AND cancha = ?");
                $stmt->execute([$fecha_seleccionada, $hora . ':00', $cancha]);
                $reservada = $stmt->fetch();
                if($reservada) {
                    echo "<td class='ocupada'>Ocupada</td>";
                } else {
                    echo "<td class='disponible'>
                        <form method='POST' action='reservar.php'>
                            <input type='hidden' name='fecha' value='$fecha_seleccionada'>
                            <input type='hidden' name='hora' value='$hora:00'>
                            <input type='hidden' name='cancha' value='$cancha'>
                            <button type='submit' style='background:#4caf50; padding:5px 10px;'>Reservar</button>
                        </form>
                    </td>";
                }
            }
            echo "</tr>";
        }
        ?>
    </table>
    <p><a href="dashboard.php">← Volver</a></p>
</div>
</body>
</html>