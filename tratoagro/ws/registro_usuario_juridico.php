<?php
include 'db.php';
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$nombre = $_POST['nombre'];
$tipo_usuario = $_POST['tipo_usuario'];
$query = "INSERT INTO usuario (id_usuario, nombre, correo, clave, estado, tipo_usuario) VALUES (default, '".$nombre."', '".$usuario."', '".$clave."', 'D', '".$tipo_usuario."');";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "registro_usuario_juridico", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "registro_usuario_juridico", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>