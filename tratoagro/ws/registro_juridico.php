<?php
include 'db.php';
$ruc = $_POST['ruc'];
$razon_social = $_POST['razon_social'];
$domicilio_fiscal = $_POST['domicilio_fiscal'];
$nombre_representante_legal = $_POST['nombre_representante_legal'];
$celular = $_POST['celular'];
$query = "INSERT INTO persona_juridica (ruc, razon_social, domicilio_fiscal, nombre_representante_legal, celular) VALUES ('".$ruc."', '".$razon_social."', '".$domicilio_fiscal."', '".$nombre_representante_legal."', '".$celular."');";
$exec = $pdo->prepare($query);
$exec->execute();
if ($exec) {
    $output = ["accion" => "registro_juridico", "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "registro_juridico", "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>