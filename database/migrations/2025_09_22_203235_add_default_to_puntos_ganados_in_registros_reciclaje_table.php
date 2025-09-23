<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('registros_reciclaje', function (Blueprint $table) {
            $table->integer('puntos_ganados')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('registros_reciclaje', function (Blueprint $table) {
            $table->integer('puntos_ganados')->default(null)->change();
        });
    }
};