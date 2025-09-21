<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rol_usuario', function (Blueprint $table) {
            $table->id('idrolusuario'); // PK
            $table->unsignedBigInteger('idrol');     // FK a rol
            $table->unsignedBigInteger('idusuario'); // FK a usuario
            $table->timestamps();

            $table->foreign('idrol')->references('idrol')->on('rol')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('idusuario')->references('idusuario')->on('usuario')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rol_usuario');
    }
};





