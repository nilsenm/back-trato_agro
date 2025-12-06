<?php
include 'db.php';
$cantidad = $_POST['cantidad'];
$id_stock = $_POST['id_stock'];
$id_venta = $_POST['id_venta'];
$query = "INSERT INTO detalle_venta (cantidad, id_stock, id_venta) VALUES (".$cantidad.", ".$id_stock.", ".$id_venta.");";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "insertar_detalles_venta", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "insertar_detalles_venta", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>