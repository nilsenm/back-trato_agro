<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->bigIncrements('id_producto');
            $table->string('nombre', 300)->nullable();
            $table->string('imagen', 250)->default('-');
            $table->bigInteger('id_subcategoria')->nullable();
            $table->timestamps();
            
            $table->foreign('id_subcategoria')->references('id_subcategoria')->on('subcategoria')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};

