<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subcategoria', function (Blueprint $table) {
            $table->bigIncrements('id_subcategoria');
            $table->string('nombre', 300)->nullable();
            $table->bigInteger('id_categoria')->nullable();
            $table->timestamps();
            
            $table->foreign('id_categoria')->references('id_categoria')->on('categoria')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subcategoria');
    }
};

