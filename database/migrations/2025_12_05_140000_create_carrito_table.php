<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carrito', function (Blueprint $table) {
            $table->bigIncrements('id_carrito');
            $table->bigInteger('id_usuario');
            $table->bigInteger('id_stock');
            $table->integer('cantidad');
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('id_stock')->references('id_stock')->on('stock')->onDelete('cascade');
            
            // Un usuario no puede tener el mismo stock duplicado en el carrito
            $table->unique(['id_usuario', 'id_stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carrito');
    }
};

