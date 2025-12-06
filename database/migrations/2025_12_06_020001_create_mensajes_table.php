<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->bigIncrements('id_mensaje');
            $table->bigInteger('id_oferta')->nullable(); // Relación con oferta (opcional)
            $table->bigInteger('id_usuario_remitente'); // Usuario que envía el mensaje
            $table->bigInteger('id_usuario_destinatario'); // Usuario que recibe el mensaje
            $table->text('mensaje'); // Contenido del mensaje
            $table->boolean('leido')->default(false); // Si el mensaje fue leído
            $table->timestamp('fecha_leido')->nullable(); // Fecha de lectura
            $table->timestamps();
            
            $table->foreign('id_oferta')->references('id_oferta')->on('ofertas')->onDelete('cascade');
            $table->foreign('id_usuario_remitente')->references('id_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('id_usuario_destinatario')->references('id_usuario')->on('usuario')->onDelete('cascade');
            
            // Índice para búsquedas rápidas de conversaciones
            $table->index(['id_usuario_remitente', 'id_usuario_destinatario']);
            $table->index(['id_usuario_destinatario', 'leido']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};

