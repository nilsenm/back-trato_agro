<?php
include 'db.php';
$fecha = $_GET['fecha'];
$query = "SELECT p.id_subcategoria, sc.id_categoria, p.id_producto, u.nombre, s.cantidad AS 'cant', u.documento, u.tipo_vendedor, v.id_distrito, pr.id_provincia, r.id_departamento FROM venta v, detalle_venta dv, producto p, subcategoria sc, stock s, usuario u, distrito d, provincia pr, departamento r WHERE v.id_venta = dv.id_venta AND p.id_producto = s.id_producto AND dv.id_stock = s.id_stock AND p.id_subcategoria = sc.id_subcategoria AND u.id_usuario = v.id_usuario_compra AND v.id_distrito = d.id_distrito AND d.id_provincia = pr.id_provincia AND pr.id_departamento = r.id_departamento AND fecha BETWEEN date_add(NOW(), INTERVAL -500 DAY) AND NOW() GROUP BY p.id_producto";
//$query = "SELECT p.id_subcategoria, sc.id_categoria, p.id_producto, p.nombre, SUM(dv.cantidad) AS 'cant' FROM venta v, detalle_venta dv, producto p, subcategoria sc, stock s WHERE v.id_venta = dv.id_venta AND p.id_producto = s.id_producto AND dv.id_stock = s.id_stock AND p.id_subcategoria = sc.id_subcategoria AND fecha BETWEEN date_add(NOW(), INTERVAL -500 DAY) AND NOW() GROUP BY p.id_producto";
$result = $pdo->query($query);
$datos = array();
foreach ($result as $row) {
    $datos[] = $row;
}
if (sizeof($datos) > 0) {
    $output = ["accion" => "obtener_cantidad_ventas_ganaderia", "data" => $datos, "consulta" => $query, "correcto" => true];
} else {
    $output = ["accion" => "obtener_cantidad_ventas_ganaderia", "data" => $datos, "consulta" => $query, "correcto" => false];
}
echo json_encode($output);
?>