<?php
// Simulamos datos, pero en tu sistema vendrían de la base de datos
$hotel = "Hotel Paraíso";
$sucursal = "Sucursal Centro";
$cliente = "Juan Pérez";
$habitacion = "102";
$fecha = date("d/m/Y H:i");
$forma_pago = "Efectivo";
$total_reserva = 1200;
$cargos_extra = 200;
$faltantes = 100;
$total = $total_reserva + $cargos_extra + $faltantes;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ticket</title>
  <style>
    * { font-family: monospace; font-size: 12px; }
    body { max-width: 260px; margin: auto; }
    h2, h3 { text-align: center; margin: 5px 0; }
    .line { border-top: 1px dashed #000; margin: 5px 0; }
    .total { font-weight: bold; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 2px 0; }
    .right { text-align: right; }
    .center { text-align: center; }
  </style>
</head>
<body>
  <h2><?= $hotel ?></h2>
  <h3><?= $sucursal ?></h3>
  <div class="line"></div>
  <p>Fecha: <?= $fecha ?><br>
     Cliente: <?= $cliente ?><br>
     Habitación: <?= $habitacion ?><br>
     Pago: <?= $forma_pago ?>
  </p>
  <div class="line"></div>
  <table>
    <tr><td>Reserva</td><td class="right">$<?= number_format($total_reserva, 2) ?></td></tr>
    <tr><td>Cargos extra</td><td class="right">$<?= number_format($cargos_extra, 2) ?></td></tr>
    <tr><td>Faltantes</td><td class="right">$<?= number_format($faltantes, 2) ?></td></tr>
    <tr><td colspan="2"><div class="line"></div></td></tr>
    <tr><td class="total">TOTAL</td><td class="right total">$<?= number_format($total, 2) ?></td></tr>
  </table>
  <div class="line"></div>
  <p class="center">¡Gracias por su preferencia!</p>
</body>
</html>
<script>
  window.onload = () => window.print();
</script>