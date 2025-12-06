<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubcategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $subcategorias = [
            // Ganadería (categoria 1)
            ['id_subcategoria' => 1, 'nombre' => 'Ovino: Ovejas', 'id_categoria' => 1],
            ['id_subcategoria' => 2, 'nombre' => 'Bovino o vacuno: Bueyes, toros y vacas', 'id_categoria' => 1],
            ['id_subcategoria' => 3, 'nombre' => 'Porcino: Cerdo', 'id_categoria' => 1],
            ['id_subcategoria' => 4, 'nombre' => 'Caprino: Cabras', 'id_categoria' => 1],
            ['id_subcategoria' => 5, 'nombre' => 'Equino: Caballos y yeguas', 'id_categoria' => 1],
            ['id_subcategoria' => 6, 'nombre' => 'Cunicultura: Conejos', 'id_categoria' => 1],
            ['id_subcategoria' => 7, 'nombre' => 'Avicultura: Aves de corral', 'id_categoria' => 1],
            
            // Maquinaria (categoria 2)
            ['id_subcategoria' => 8, 'nombre' => 'Abonadora', 'id_categoria' => 2],
            ['id_subcategoria' => 9, 'nombre' => 'Cosechadora', 'id_categoria' => 2],
            ['id_subcategoria' => 10, 'nombre' => 'Desbrozadora', 'id_categoria' => 2],
            ['id_subcategoria' => 11, 'nombre' => 'Desgranadora', 'id_categoria' => 2],
            ['id_subcategoria' => 12, 'nombre' => 'Empacadora', 'id_categoria' => 2],
            ['id_subcategoria' => 13, 'nombre' => 'Fumigadora', 'id_categoria' => 2],
            ['id_subcategoria' => 14, 'nombre' => 'Motocultor', 'id_categoria' => 2],
            ['id_subcategoria' => 15, 'nombre' => 'Motor para riego', 'id_categoria' => 2],
            ['id_subcategoria' => 16, 'nombre' => 'Rodillo agrícola', 'id_categoria' => 2],
            ['id_subcategoria' => 17, 'nombre' => 'Sembradora', 'id_categoria' => 2],
            ['id_subcategoria' => 18, 'nombre' => 'Segadora', 'id_categoria' => 2],
            ['id_subcategoria' => 19, 'nombre' => 'Tractor', 'id_categoria' => 2],
            
            // Tubérculos (categoria 3)
            ['id_subcategoria' => 20, 'nombre' => 'Tubérculos radicales', 'id_categoria' => 3],
            ['id_subcategoria' => 21, 'nombre' => 'Tubérculos hidropónicos', 'id_categoria' => 3],
            ['id_subcategoria' => 22, 'nombre' => 'Tubérculos tropicales', 'id_categoria' => 3],
            ['id_subcategoria' => 23, 'nombre' => 'Tubérculos comestibles', 'id_categoria' => 3],
            
            // Pesticidas (categoria 4)
            ['id_subcategoria' => 24, 'nombre' => 'Insecticidas', 'id_categoria' => 4],
            ['id_subcategoria' => 25, 'nombre' => 'Acaricidas', 'id_categoria' => 4],
            ['id_subcategoria' => 26, 'nombre' => 'Fungicidas', 'id_categoria' => 4],
            ['id_subcategoria' => 27, 'nombre' => 'Nematocidas', 'id_categoria' => 4],
            ['id_subcategoria' => 28, 'nombre' => 'Desinfectantes de suelo', 'id_categoria' => 4],
            ['id_subcategoria' => 29, 'nombre' => 'Fumigantes', 'id_categoria' => 4],
            ['id_subcategoria' => 30, 'nombre' => 'Herbicidas', 'id_categoria' => 4],
            ['id_subcategoria' => 31, 'nombre' => 'Fitorreguladores', 'id_categoria' => 4],
            ['id_subcategoria' => 32, 'nombre' => 'Molusquicidas', 'id_categoria' => 4],
            ['id_subcategoria' => 33, 'nombre' => 'Rodenticidas', 'id_categoria' => 4],
            
            // Fertilizantes (categoria 5)
            ['id_subcategoria' => 34, 'nombre' => 'Fertilizantes orgánicos', 'id_categoria' => 5],
            ['id_subcategoria' => 35, 'nombre' => 'Fertilizantes químicos', 'id_categoria' => 5],
            ['id_subcategoria' => 36, 'nombre' => 'Biofertilizantes', 'id_categoria' => 5],
            ['id_subcategoria' => 37, 'nombre' => 'Bioestimulantes', 'id_categoria' => 5],
            ['id_subcategoria' => 38, 'nombre' => 'Fertilizante radicular o al suelo', 'id_categoria' => 5],
            ['id_subcategoria' => 39, 'nombre' => 'Fertilizante foliar', 'id_categoria' => 5],
            ['id_subcategoria' => 40, 'nombre' => 'Fertirrigación', 'id_categoria' => 5],
            
            // Pescados (categoria 6)
            ['id_subcategoria' => 41, 'nombre' => 'Peces pelágicos', 'id_categoria' => 6],
            ['id_subcategoria' => 42, 'nombre' => 'Pescado blanco', 'id_categoria' => 6],
            ['id_subcategoria' => 43, 'nombre' => 'Cefalópodos', 'id_categoria' => 6],
            ['id_subcategoria' => 44, 'nombre' => 'Crustáceos', 'id_categoria' => 6],
            ['id_subcategoria' => 45, 'nombre' => 'Mariscos', 'id_categoria' => 6],
            ['id_subcategoria' => 46, 'nombre' => 'Algas', 'id_categoria' => 6],
            ['id_subcategoria' => 47, 'nombre' => 'Equinodermos', 'id_categoria' => 6],
        ];

        foreach ($subcategorias as $subcategoria) {
            DB::table('subcategoria')->updateOrInsert(
                ['id_subcategoria' => $subcategoria['id_subcategoria']],
                $subcategoria
            );
        }
    }
}






