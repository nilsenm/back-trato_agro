<?php
include 'db.php';
$dni = $_POST['dni'];
$id_usuario = $_POST['id_usuario'];
$query = "UPDATE persona_natural SET id_usuario = ".$id_usuario." WHERE dni = '".$dni."'";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "enlazar_natural_usuario", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "enlazar_natural_usuario", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>