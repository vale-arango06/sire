<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registros_reciclaje', function (Blueprint $table) {
            $table->id();
            
            // Usar unsignedBigInteger en lugar de foreignId para mayor control
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('tipo_id');
            $table->unsignedBigInteger('grupo_id');
            
            $table->decimal('cantidad', 8, 2);
            $table->dateTime('fecha');
            $table->decimal('cantidad_kg', 8, 2);
            $table->integer('puntos_ganados');
            $table->timestamps();

            // Índices
            $table->index('usuario_id');
            $table->index('material_id');
            $table->index('tipo_id');
            $table->index('grupo_id');
            $table->index('fecha');
        });

        // Agregar las foreign keys DESPUÉS de crear la tabla
        Schema::table('registros_reciclaje', function (Blueprint $table) {
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materiales')->onDelete('cascade');
            $table->foreign('tipo_id')->references('id')->on('tipo_reciclaje')->onDelete('cascade');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Eliminar las foreign keys primero
        Schema::table('registros_reciclaje', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['material_id']);
            $table->dropForeign(['tipo_id']);
            $table->dropForeign(['grupo_id']);
        });

        Schema::dropIfExists('registros_reciclaje');
    }
};