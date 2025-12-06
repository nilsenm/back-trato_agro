<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distrito', function (Blueprint $table) {
            $table->bigInteger('id_distrito')->primary();
            $table->string('nombre', 100)->nullable();
            $table->bigInteger('id_provincia')->nullable();
            $table->timestamps();
            
            $table->foreign('id_provincia')->references('id_provincia')->on('provincia')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distrito');
    }
};

