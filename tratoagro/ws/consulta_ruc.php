<?php
// if (isset($_REQUEST['ruc']) && ($_REQUEST['ruc'] != '')) {
//   $html = file_get_contents('https://api.sunat.cloud/ruc/'.$_REQUEST['ruc']);
//   $dni = json_decode($html, TRUE);
//   header('Content-Type: application/json');
//   echo json_encode($dni, JSON_PRETTY_PRINT);
// }

if (isset($_REQUEST['ruc']) && ($_REQUEST['ruc'] != '')) {
   $html = file_get_contents('https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=RUC&usuario=10447915125&password=985511933&nro_documento='.$_REQUEST['ruc']);
   $dni = json_decode($html, TRUE);
   header('Content-Type: application/json');
   echo json_encode($dni, JSON_PRETTY_PRINT);
}

//$dni=$_GET['dni'];

//$output = file_get_contents("https://www.facturacionelectronica.us/facturacion/controller/ws_consulta_rucdni_v2.php?documento=DNI&usuario=10447915125&password=985511933&nro_documento=".$dni);

//$array = json_decode($output);
//$dni = $array->result->DNI;
//$paterno = $array->result->Paterno;
//$materno = $array->result->Materno;
//$nombres = $array->result->Nombre;
//$datos = array('dni'=>$dni, 'apellido_paterno'=>$paterno, 'apellido_materno'=>$materno, 'nombres'=>$nombres);
//echo json_encode($datos);

?>