<?php
// if (isset($_REQUEST['dni']) && ($_REQUEST['dni'] != '')) {
//   $html = file_get_contents('https://api.reniec.cloud/dni/'.$_REQUEST['dni']);
//   $dni = json_decode($html, TRUE);
//   header('Content-Type: application/json');
//   echo json_encode($dni, JSON_PRETTY_PRINT);
// }



$dni=$_POST['dni'];

$output = file_get_contents("https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10447915125&password=985511933&nro_documento=".$dni);
// $output = file_get_contents("https://searchpe.herokuapp.com/public/api/dni/".$dni);
$array = json_decode($output);
$dni = $array->result->DNI;
$paterno = $array->result->Paterno;
$materno = $array->result->Materno;
$nombres = $array->result->Nombre;
$datos = array('accion' => 'buscar_dni', 'consulta' => 'buscar_dni', 'correcto' => true, 'dni' => $dni, 'apellido_paterno' => $paterno, 'apellido_materno' => $materno, 'nombres' => $nombres);
// $paterno = $array->apellidoPaterno;
// $materno = $array->apellidoMaterno;
// $nombres = $array->nombres;
// $datos = array('dni'=>$dni, 'apellido_paterno'=>$paterno, 'apellido_materno'=>$materno, 'nombres'=>$nombres);
echo json_encode($datos);
?>