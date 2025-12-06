<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->bigIncrements('id_venta');
            $table->date('fecha')->nullable();
            $table->time('hora')->nullable();
            $table->bigInteger('id_usuario_compra')->nullable();
            $table->integer('id_distrito');
            $table->timestamps();
            
            $table->foreign('id_usuario_compra')->references('id_usuario')->on('usuario')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venta');
    }
};

