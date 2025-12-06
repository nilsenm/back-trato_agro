<?php
include 'db.php';
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$query = "SELECT * FROM usuario WHERE correo = '".$correo."' AND clave = '".$clave."'";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "iniciar_sesion", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "iniciar_sesion", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>