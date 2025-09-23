<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('unidad_medida');
            $table->integer('puntos');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};