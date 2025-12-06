<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['id_categoria' => 1, 'nombre' => 'Ganadería', 'icono' => '/img/ganaderia.png'],
            ['id_categoria' => 2, 'nombre' => 'Maquinaria', 'icono' => '/img/maquinaria.png'],
            ['id_categoria' => 3, 'nombre' => 'Tubérculos', 'icono' => '/img/insumos.png'],
            ['id_categoria' => 4, 'nombre' => 'Pesticidas', 'icono' => '/img/pesticidas.png'],
            ['id_categoria' => 5, 'nombre' => 'Fertilizantes', 'icono' => '/img/fertilizantes.png'],
            ['id_categoria' => 6, 'nombre' => 'Pescados', 'icono' => '/img/pesca.png'],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categoria')->updateOrInsert(
                ['id_categoria' => $categoria['id_categoria']],
                $categoria
            );
        }
    }
}

