<?php
include 'db.php';
$fecha = $_POST['fecha'];
$query = "SELECT p.id_subcategoria, sc.id_categoria, p.id_producto, p.nombre, SUM(dv.cantidad) AS 'cant' FROM venta v, detalle_venta dv, producto p, subcategoria sc, stock s WHERE v.id_venta = dv.id_venta AND p.id_producto = s.id_producto AND dv.id_stock = s.id_stock AND p.id_subcategoria = sc.id_subcategoria AND fecha BETWEEN date_add(NOW(), INTERVAL -500 DAY) AND NOW() GROUP BY p.id_producto";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_cantidad_ventas_insumos", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_cantidad_ventas_insumos", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>