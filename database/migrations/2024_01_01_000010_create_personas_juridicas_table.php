<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona_juridica', function (Blueprint $table) {
            $table->char('ruc', 11)->primary();
            $table->string('razon_social', 300)->nullable();
            $table->string('domicilio_fiscal', 500)->nullable();
            $table->string('nombre_representante_legal', 300)->nullable();
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
        Schema::dropIfExists('persona_juridica');
    }
};

