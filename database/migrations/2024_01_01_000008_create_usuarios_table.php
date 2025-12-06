<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');
            $table->string('documento', 11);
            $table->string('nombre', 300)->nullable();
            $table->string('correo', 300)->nullable();
            $table->string('clave', 16)->nullable();
            $table->char('estado', 1)->nullable();
            $table->string('tipo_vendedor', 3);
            $table->char('tipo_persona', 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};

