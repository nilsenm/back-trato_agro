<?php
include 'db.php';
$subcategoria = $_POST['subcategoria'];
$query = "SELECT * FROM producto WHERE id_subcategoria = ".$subcategoria;
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_productos_subcategoria", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_productos_subcategoria", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>