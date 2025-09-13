<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
        $table->foreignId('sesion_id')->constrained('sesiones')->cascadeOnUpdate()->cascadeOnDelete();
        $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnUpdate()->cascadeOnDelete();
        $table->enum('estado', ['confirmada','en_espera','cancelada','asistida','ausente'])->default('confirmada');
        $table->unsignedSmallInteger('posicion_espera')->nullable(); // si en_espera
        $table->enum('fuente', ['web','app','admin'])->default('web');
        $table->timestamps();

        $table->unique(['sesion_id','usuario_id']); // evita doble reserva
        $table->index(['usuario_id','created_at']);
        $table->index(['sesion_id','estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
