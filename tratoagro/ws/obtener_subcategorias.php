<?php
include 'db.php';
$categoria = $_POST['categoria'];
$query = "SELECT * FROM subcategoria WHERE id_categoria = ".$categoria;
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_subcategorias", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_subcategorias", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>