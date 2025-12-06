<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadSeeder extends Seeder
{
    public function run(): void
    {
        $unidades = [
            ['id_unidad' => 1, 'nombre' => 'Kilogramo (kg)'],
            ['id_unidad' => 2, 'nombre' => 'Gramo (gr)'],
            ['id_unidad' => 3, 'nombre' => 'Miligramo (mg)'],
            ['id_unidad' => 4, 'nombre' => 'Hectogramo (hg)'],
            ['id_unidad' => 5, 'nombre' => 'Decagramo (dag)'],
            ['id_unidad' => 6, 'nombre' => 'Decigramo (dg)'],
            ['id_unidad' => 7, 'nombre' => 'Centigramo (cg)'],
            ['id_unidad' => 8, 'nombre' => 'Metro (m)'],
            ['id_unidad' => 9, 'nombre' => 'Joule/segundo (J/s)'],
        ];

        foreach ($unidades as $unidad) {
            DB::table('unidad')->updateOrInsert(
                ['id_unidad' => $unidad['id_unidad']],
                $unidad
            );
        }
    }
}

