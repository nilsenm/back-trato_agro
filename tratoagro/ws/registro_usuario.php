<?php
include 'db.php';
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$nombre = $_POST['nombre'];
$query = "INSERT INTO usuario (id_usuario, nombre, correo, clave, estado) VALUES (default, '".$nombre."', '".$usuario."', '".$clave."', 'D');";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "registro_usuario", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "registro_usuario", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>