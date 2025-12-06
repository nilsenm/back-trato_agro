<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Imagen por defecto
        $imagenDefault = '/prouctos/image.png';
        
        // Obtener un usuario existente o usar el primero disponible
        $usuario = DB::table('usuario')->first();
        $idUsuario = $usuario ? $usuario->id_usuario : 16; // Usar id 16 como fallback
        
        // Productos de semillas - usando diferentes categorías y subcategorías
        $productos = [
            // Semillas de Tubérculos (Categoria 3)
            [
                'nombre' => 'Semilla de Papa Amarilla',
                'descripcion' => 'Semilla de papa amarilla de alta calidad, ideal para siembra. Variedad resistente y productiva.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales
                'precio' => 15.50,
                'cantidad' => 500,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
            [
                'nombre' => 'Semilla de Camote',
                'descripcion' => 'Semilla de camote seleccionada, perfecta para cultivos orgánicos. Alto rendimiento garantizado.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales
                'precio' => 12.00,
                'cantidad' => 300,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => false,
            ],
            [
                'nombre' => 'Semilla de Yuca',
                'descripcion' => 'Semilla de yuca de excelente calidad, adaptada a diferentes tipos de suelo.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 22, // Tubérculos tropicales
                'precio' => 18.75,
                'cantidad' => 400,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
            [
                'nombre' => 'Semilla de Olluco',
                'descripcion' => 'Semilla de olluco nativo, ideal para zonas andinas. Producto certificado.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 23, // Tubérculos comestibles
                'precio' => 20.00,
                'cantidad' => 250,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
            [
                'nombre' => 'Semilla de Oca',
                'descripcion' => 'Semilla de oca andina, variedad tradicional peruana. Alta resistencia a heladas.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 23, // Tubérculos comestibles
                'precio' => 22.50,
                'cantidad' => 350,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => false,
            ],
            // Semillas relacionadas con otros cultivos (usando categoría de Fertilizantes como ejemplo de semillas mejoradas)
            [
                'nombre' => 'Semilla de Maíz Amarillo Duro',
                'descripcion' => 'Semilla de maíz amarillo duro mejorada, alta productividad y resistencia a plagas.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales (puede cambiarse si hay categoría de semillas)
                'precio' => 25.00,
                'cantidad' => 600,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
            [
                'nombre' => 'Semilla de Quinua',
                'descripcion' => 'Semilla de quinua orgánica certificada, superalimento andino de alta calidad nutricional.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales
                'precio' => 30.00,
                'cantidad' => 200,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
            [
                'nombre' => 'Semilla de Frijol',
                'descripcion' => 'Semilla de frijol seleccionada, variedad de alto rendimiento. Ideal para rotación de cultivos.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales
                'precio' => 16.50,
                'cantidad' => 450,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => false,
            ],
            [
                'nombre' => 'Semilla de Habas',
                'descripcion' => 'Semilla de habas de calidad superior, adaptada a climas templados y fríos.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales
                'precio' => 14.75,
                'cantidad' => 380,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
            [
                'nombre' => 'Semilla de Arveja',
                'descripcion' => 'Semilla de arveja mejorada, alta germinación y resistencia. Producto certificado.',
                'imagen' => $imagenDefault,
                'id_subcategoria' => 20, // Tubérculos radicales
                'precio' => 17.25,
                'cantidad' => 320,
                'id_unidad' => 1, // Kilogramo
                'tipo_moneda' => 'PEN',
                'recibe_ofertas' => true,
            ],
        ];

        foreach ($productos as $productoData) {
            // Crear el producto
            $idProducto = DB::table('producto')->insertGetId([
                'nombre' => $productoData['nombre'],
                'descripcion' => $productoData['descripcion'],
                'imagen' => $productoData['imagen'],
                'id_subcategoria' => $productoData['id_subcategoria'],
                'created_at' => now(),
                'updated_at' => now(),
            ], 'id_producto');

            // Crear el stock asociado
            DB::table('stock')->insert([
                'id_producto' => $idProducto,
                'id_usuario' => $idUsuario,
                'precio' => $productoData['precio'],
                'cantidad' => $productoData['cantidad'],
                'id_unidad' => $productoData['id_unidad'],
                'tipo_moneda' => $productoData['tipo_moneda'],
                'recibe_ofertas' => $productoData['recibe_ofertas'],
                'imagen' => $productoData['imagen'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Productos de semillas creados exitosamente con imagen por defecto.');
    }
}

