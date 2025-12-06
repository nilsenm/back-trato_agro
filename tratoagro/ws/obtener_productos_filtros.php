<?php
include 'db.php';
$filtros = $_POST['filtros'];
$query = "SELECT DISTINCT u.nombre, s.imagen, s.precio FROM usuario u, stock s, categoria c, subcategoria su, producto p, persona_natural pn, persona_juridica pj ".$filtros.";";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_productos_filtros", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_productos_filtros", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>