<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // En la migración de users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // AGREGAR LA FOREIGN KEY DESPUÉS, en una migración separada o al final
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
        });
            }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['grupo_id']);
        });
        
        Schema::dropIfExists('users');
    }
};