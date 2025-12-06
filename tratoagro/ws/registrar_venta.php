<?php
include 'db.php';
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$id_usuario_compra = $_POST['id_usuario_compra'];
$query = "INSERT INTO venta (fecha, hora, id_usuario_compra) VALUES ('".$fecha."', '".$hora."', ".$id_usuario_compra.");";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "registrar_venta", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "registrar_venta", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>