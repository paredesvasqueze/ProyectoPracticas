<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('idusuario'); // PK
            $table->unsignedBigInteger('idpersona'); // FK a persona
            $table->string('cusuario', 50)->unique();
            $table->string('ccontrasenia', 100);
            $table->timestamps();

            $table->foreign('idpersona')->references('idpersona')->on('persona')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};


