<?php
include 'db.php';
$dni = $_POST['dni'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$celular = $_POST['celular'];
$direccion = $_POST['direccion'];
$query = "INSERT INTO persona_natural (dni, nombres, apellidos, direccion, celular) VALUES ('".$dni."', '".$nombres."', '".$apellidos."', '".$direccion."', '".$celular."');";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "registro_natural", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "registro_natural", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>