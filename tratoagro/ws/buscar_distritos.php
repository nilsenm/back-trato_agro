<?php
include 'db.php';
$id_provincia = $_POST['id_provincia'];
$query = "SELECT * FROM distrito WHERE id_provincia = ".$id_provincia;
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "buscar_distritos", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "buscar_distritos", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>