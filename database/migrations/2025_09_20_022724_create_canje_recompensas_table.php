<?php
// database/migrations/xxxx_create_canje_recompensas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('canje_recompensas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recompensa_id')->constrained('recompensas')->onDelete('cascade');
            $table->integer('puntos_utilizados');
            $table->timestamp('fecha_canje');
            $table->enum('estado', ['pendiente', 'entregada', 'cancelada'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('canje_recompensas');
    }
};