<?php
include 'db.php';
$id_producto = $_POST['id_producto'];
$query = "SELECT * FROM stock s, usuario u WHERE s.id_usuario = u.id_usuario AND s.id_producto = ".$id_producto;
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_proveedores_producto", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_proveedores_producto", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>