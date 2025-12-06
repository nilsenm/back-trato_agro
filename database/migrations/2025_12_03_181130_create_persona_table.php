<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->string('numero_documento', 11)->primary(); // DNI (8) o RUC (11)
            $table->string('tipo_documento', 1); // 1=DNI, 6=RUC
            $table->string('nombres', 300)->nullable(); // Para DNI
            $table->string('apellido_paterno', 200)->nullable(); // Para DNI
            $table->string('apellido_materno', 200)->nullable(); // Para DNI
            $table->string('nombre_completo', 500)->nullable(); // Para DNI
            $table->string('razon_social', 500)->nullable(); // Para RUC
            $table->string('direccion', 500)->nullable();
            $table->string('ubigeo', 6)->nullable(); // Código ubigeo
            $table->string('distrito', 200)->nullable();
            $table->string('provincia', 200)->nullable();
            $table->string('departamento', 200)->nullable();
            $table->string('estado', 50)->nullable(); // Para RUC: ACTIVO, etc.
            $table->string('condicion', 50)->nullable(); // Para RUC: HABIDO, NO HABIDO
            $table->boolean('es_agente_retencion')->default(false); // Para RUC
            $table->boolean('es_buen_contribuyente')->default(false); // Para RUC
            $table->string('digito_verificador', 1)->nullable(); // Para DNI
            $table->json('datos_completos')->nullable(); // Todos los datos de la API en JSON
            $table->timestamps();
            
            $table->index('tipo_documento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
