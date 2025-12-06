<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->bigIncrements('id_oferta');
            $table->bigInteger('id_stock'); // Producto/stock sobre el que se hace la oferta
            $table->bigInteger('id_usuario_ofertante'); // Usuario que hace la oferta
            $table->bigInteger('id_usuario_vendedor'); // Usuario dueño del producto
            $table->decimal('precio_ofertado', 10, 2); // Precio ofertado
            $table->integer('cantidad'); // Cantidad ofertada
            $table->string('tipo_moneda', 3)->default('PEN'); // PEN o USD
            $table->enum('estado', ['PENDIENTE', 'ACEPTADA', 'RECHAZADA', 'CANCELADA'])->default('PENDIENTE');
            $table->text('mensaje')->nullable(); // Mensaje opcional con la oferta
            $table->timestamp('fecha_respuesta')->nullable(); // Fecha de respuesta del vendedor
            $table->timestamps();
            
            $table->foreign('id_stock')->references('id_stock')->on('stock')->onDelete('cascade');
            $table->foreign('id_usuario_ofertante')->references('id_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('id_usuario_vendedor')->references('id_usuario')->on('usuario')->onDelete('cascade');
            
            // Índice para búsquedas rápidas
            $table->index(['id_stock', 'estado']);
            $table->index(['id_usuario_ofertante', 'estado']);
            $table->index(['id_usuario_vendedor', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};

