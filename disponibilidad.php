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
    
    <form method="POST" action="reservar.php" class="form-reserva" style="margin-bottom:30px; background:#f5f5f5; padding:25px 20px; border-radius:16px; box-shadow:0 2px 10px rgba(0,0,0,0.07); max-width:500px; margin-left:auto; margin-right:auto;">
        <div style="display:flex; flex-wrap:wrap; gap:15px; justify-content:space-between;">
            <div style="flex:1 1 120px; min-width:120px;">
                <label for="fecha" style="font-weight:bold; color:#00796b;">Fecha</label>
                <input type="date" id="fecha" name="fecha" value="<?= $fecha_seleccionada ?>" min="<?= date('Y-m-d', strtotime('+2 days')) ?>" required>
            </div>
            <div style="flex:1 1 120px; min-width:120px;">
                <label for="cancha" style="font-weight:bold; color:#00796b;">Cancha</label>
                <select id="cancha" name="cancha" required>
                    <option value="">Selecciona</option>
                    <option value="1">Cancha 1</option>
                    <option value="2">Cancha 2</option>
                    <option value="3">Cancha 3</option>
                </select>
            </div>
            <div style="flex:1 1 120px; min-width:120px;">
                <label for="hora" style="font-weight:bold; color:#00796b;">Hora</label>
                <select id="hora" name="hora" required>
                    <option value="">Selecciona</option>
                    <?php
                    $horas = ['08:00','10:00','12:00','14:00','16:00','18:00','20:00'];
                    foreach($horas as $h) {
                        // Verificar si la hora está ocupada para la cancha y fecha seleccionadas
                        $ocupada = false;
                        if(isset($_POST['cancha']) && $_POST['cancha'] && isset($_POST['fecha']) && $_POST['fecha']) {
                            $stmt = $pdo->prepare("SELECT * FROM reservas WHERE fecha = ? AND hora = ? AND cancha = ?");
                            $stmt->execute([$_POST['fecha'], $h . ':00', $_POST['cancha']]);
                            if($stmt->fetch()) $ocupada = true;
                        }
                        echo '<option value="' . $h . ':00"' . ($ocupada ? ' disabled style="color:#aaa;"' : '') . '>' . $h . ($ocupada ? ' (Ocupada)' : '') . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <button type="submit" style="margin-top:18px; font-size:18px;">Reservar</button>
    </form>

    <div style="margin:30px auto 0 auto; max-width:700px;">
        <h2 style="color:#d81b60; margin-bottom:10px;">Tabla de Disponibilidad (Referencia)</h2>
        <table style="font-size:15px;">
            <tr><th>Hora</th><th>Cancha 1</th><th>Cancha 2</th><th>Cancha 3</th></tr>
            <?php
            foreach($horas as $hora) {
                echo "<tr><td><strong>$hora</strong></td>";
                for($cancha=1; $cancha<=3; $cancha++) {
                    $stmt = $pdo->prepare("SELECT * FROM reservas WHERE fecha = ? AND hora = ? AND cancha = ?");
                    $stmt->execute([$fecha_seleccionada, $hora . ':00', $cancha]);
                    $reservada = $stmt->fetch();
                    if($reservada) {
                        echo "<td class='ocupada'>Ocupada</td>";
                    } else {
                        echo "<td class='disponible'>Libre</td>";
                    }
                }
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <p style="text-align:center; margin-top:30px;"><a href="dashboard.php">← Volver</a></p>
</div>
</body>
</html>