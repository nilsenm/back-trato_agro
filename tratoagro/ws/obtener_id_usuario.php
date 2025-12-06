<?php
include 'db.php';
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$query = "SELECT * FROM usuario WHERE correo = '".$usuario."' AND clave = '".$clave."'";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_id_usuario", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_id_usuario", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>