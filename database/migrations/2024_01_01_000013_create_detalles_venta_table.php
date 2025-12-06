<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->bigIncrements('id_detalle_venta');
            $table->integer('cantidad')->nullable();
            $table->bigInteger('id_stock')->nullable();
            $table->bigInteger('id_venta')->nullable();
            $table->timestamps();
            
            $table->foreign('id_stock')->references('id_stock')->on('stock')->onDelete('cascade');
            $table->foreign('id_venta')->references('id_venta')->on('venta')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_venta');
    }
};

