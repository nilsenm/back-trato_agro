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
        Schema::table('stock', function (Blueprint $table) {
            $table->bigInteger('id_unidad')->nullable()->after('cantidad');
            $table->string('tipo_moneda', 3)->default('PEN')->after('id_unidad'); // PEN o USD
            $table->boolean('recibe_ofertas')->default(false)->after('tipo_moneda');
            
            $table->foreign('id_unidad')->references('id_unidad')->on('unidad')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock', function (Blueprint $table) {
            $table->dropForeign(['id_unidad']);
            $table->dropColumn(['id_unidad', 'tipo_moneda', 'recibe_ofertas']);
        });
    }
};
