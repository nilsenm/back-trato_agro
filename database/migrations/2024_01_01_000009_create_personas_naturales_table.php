<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona_natural', function (Blueprint $table) {
            $table->char('dni', 8)->primary();
            $table->string('nombres', 300)->nullable();
            $table->string('apellidos', 300)->nullable();
            $table->string('direccion', 500)->nullable();
            $table->string('celular', 15)->nullable();
            $table->string('pais', 300)->nullable();
            $table->integer('departamento')->nullable();
            $table->integer('provincia')->nullable();
            $table->integer('distrito')->nullable();
            $table->bigInteger('id_usuario')->nullable();
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona_natural');
    }
};

