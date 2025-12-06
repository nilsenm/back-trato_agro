<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provincia', function (Blueprint $table) {
            $table->bigInteger('id_provincia')->primary();
            $table->string('nombre', 100)->nullable();
            $table->bigInteger('id_departamento')->nullable();
            $table->timestamps();
            
            $table->foreign('id_departamento')->references('id_departamento')->on('departamento')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provincia');
    }
};

