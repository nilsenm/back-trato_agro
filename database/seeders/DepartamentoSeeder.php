<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            ['id_departamento' => 1, 'nombre' => 'Amazonas'],
            ['id_departamento' => 2, 'nombre' => 'Áncash'],
            ['id_departamento' => 3, 'nombre' => 'Apurímac'],
            ['id_departamento' => 4, 'nombre' => 'Arequipa'],
            ['id_departamento' => 5, 'nombre' => 'Ayacucho'],
            ['id_departamento' => 6, 'nombre' => 'Cajamarca'],
            ['id_departamento' => 7, 'nombre' => 'Callao'],
            ['id_departamento' => 8, 'nombre' => 'Cusco'],
            ['id_departamento' => 9, 'nombre' => 'Huancavelica'],
            ['id_departamento' => 10, 'nombre' => 'Huánuco'],
            ['id_departamento' => 11, 'nombre' => 'Ica'],
            ['id_departamento' => 12, 'nombre' => 'Junín'],
            ['id_departamento' => 13, 'nombre' => 'La Libertad'],
            ['id_departamento' => 14, 'nombre' => 'Lambayeque'],
            ['id_departamento' => 15, 'nombre' => 'Lima'],
            ['id_departamento' => 16, 'nombre' => 'Loreto'],
            ['id_departamento' => 17, 'nombre' => 'Madre de Dios'],
            ['id_departamento' => 18, 'nombre' => 'Moquegua'],
            ['id_departamento' => 19, 'nombre' => 'Pasco'],
            ['id_departamento' => 20, 'nombre' => 'Piura'],
            ['id_departamento' => 21, 'nombre' => 'Puno'],
            ['id_departamento' => 22, 'nombre' => 'San Martín'],
            ['id_departamento' => 23, 'nombre' => 'Tacna'],
            ['id_departamento' => 24, 'nombre' => 'Tumbes'],
            ['id_departamento' => 25, 'nombre' => 'Ucayali'],
        ];

        foreach ($departamentos as $departamento) {
            DB::table('departamento')->updateOrInsert(
                ['id_departamento' => $departamento['id_departamento']],
                $departamento
            );
        }
    }
}

