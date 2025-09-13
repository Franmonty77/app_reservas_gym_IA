<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('entrenadores');
    }

    public function down(): void
    {
        Schema::create('entrenadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('bio_corta', 255)->nullable();
            $table->text('especialidades')->nullable();
            $table->timestamps();
        });
    }
};
