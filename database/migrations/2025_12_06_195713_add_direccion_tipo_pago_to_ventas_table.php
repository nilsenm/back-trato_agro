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
        Schema::table('venta', function (Blueprint $table) {
            $table->string('direccion', 500)->nullable()->after('id_distrito');
            $table->string('tipo_pago', 50)->nullable()->default('CONTRA_ENTREGA')->after('direccion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'tipo_pago']);
        });
    }
};
