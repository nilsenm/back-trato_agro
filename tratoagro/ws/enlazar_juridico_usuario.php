<?php
include 'db.php';
$ruc = $_POST['ruc'];
$id_usuario = $_POST['id_usuario'];
$query = "UPDATE persona_juridica SET id_usuario = ".$id_usuario." WHERE ruc = '".$ruc."'";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "enlazar_juridico_usuario", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "enlazar_juridico_usuario", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>