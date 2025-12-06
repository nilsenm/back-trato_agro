<?php
include 'db.php';
$query = "SELECT * FROM categoria";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_categorias", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_categorias", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>