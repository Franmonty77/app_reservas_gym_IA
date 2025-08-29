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
        Schema::create('sesiones', function (Blueprint $table) {
            $table->foreignId('entrenador_id')->nullable()->change();
            $table->id();
            $table->foreignId('clase_id')->constrained('clases')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('entrenador_id')->nullable() ->constrained('usuarios')->cascadeOnUpdate()->restrictOnDelete();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedSmallInteger('aforo_max');
            $table->string('centro', 120)->nullable();
            $table->string('sala', 120)->nullable();
            $table->enum('estado', ['programada', 'completada', 'cancelada','finalizada'])->default('programada');
            $table->string('notas', 255)->nullable();
            $table->timestamps();


            $table->index(['clase_id','fecha_inicio']);
            $table->index(['entrenador_id','fecha_inicio']);
            $table->index(['estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesiones');
    }
};
