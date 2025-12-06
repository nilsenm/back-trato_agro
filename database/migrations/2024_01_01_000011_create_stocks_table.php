<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock', function (Blueprint $table) {
            $table->bigIncrements('id_stock');
            $table->decimal('precio', 10, 2)->nullable();
            $table->string('imagen', 500)->nullable();
            $table->bigInteger('id_usuario')->nullable();
            $table->bigInteger('id_producto')->nullable();
            $table->integer('cantidad');
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('id_producto')->references('id_producto')->on('producto')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};

