<?php
include 'db.php';
$id_departamento = $_POST['id_departamento'];
$query = "SELECT * FROM provincia WHERE id_departamento = ".$id_departamento;
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "buscar_provincias", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "buscar_provincias", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>