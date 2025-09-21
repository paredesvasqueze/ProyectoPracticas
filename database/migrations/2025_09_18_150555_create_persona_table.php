<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id('idpersona'); // PK
            $table->string('cnombre', 100);
            $table->string('capellido', 100);
            $table->string('cdni', 20)->unique();
            $table->string('ccorreo', 100)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};



