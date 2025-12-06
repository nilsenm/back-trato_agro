<?php

namespace App\Application\Services;

use Illuminate\Support\Facades\DB;

class ReporteService
{
    public function obtenerVentasPorCategoria(int $idCategoria, ?string $fechaDesde = null): array
    {
        $query = DB::table('venta as v')
            ->join('detalle_venta as dv', 'v.id_venta', '=', 'dv.id_venta')
            ->join('stock as s', 'dv.id_stock', '=', 's.id_stock')
            ->join('producto as p', 's.id_producto', '=', 'p.id_producto')
            ->join('subcategoria as sc', 'p.id_subcategoria', '=', 'sc.id_subcategoria')
            ->join('usuario as u', 'v.id_usuario_compra', '=', 'u.id_usuario')
            ->join('distrito as d', 'v.id_distrito', '=', 'd.id_distrito')
            ->join('provincia as pr', 'd.id_provincia', '=', 'pr.id_provincia')
            ->join('departamento as dep', 'pr.id_departamento', '=', 'dep.id_departamento')
            ->where('sc.id_categoria', $idCategoria)
            ->select(
                'p.id_subcategoria',
                'sc.id_categoria',
                'p.id_producto',
                'u.nombre',
                's.cantidad as cant',
                'u.documento',
                'u.tipo_vendedor',
                'v.id_distrito',
                'pr.id_provincia',
                'dep.id_departamento'
            )
            ->groupBy('p.id_producto', 'p.id_subcategoria', 'sc.id_categoria', 'u.nombre', 's.cantidad', 'u.documento', 'u.tipo_vendedor', 'v.id_distrito', 'pr.id_provincia', 'dep.id_departamento');

        if ($fechaDesde) {
            $query->whereBetween('v.fecha', [$fechaDesde, now()]);
        } else {
            $query->whereBetween('v.fecha', [now()->subDays(500), now()]);
        }

        return $query->get()->toArray();
    }
}









