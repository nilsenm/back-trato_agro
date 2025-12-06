<?php
include 'db.php';
$id_usuario_compra = $_POST['id_usuario_compra'];
$query = "SELECT * FROM venta WHERE id_usuario_compra = ".$id_usuario_compra." ORDER BY id_venta DESC";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_id_venta", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_id_venta", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>