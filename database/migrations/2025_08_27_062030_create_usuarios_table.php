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
    Schema::create('usuarios', function (Illuminate\Database\Schema\Blueprint $table) {
        $table->id();
        $table->string('nombre', 120);
        $table->string('apellidos', 160)->nullable();
        $table->string('email', 190)->unique();
        $table->string('telefono', 30)->nullable();
        $table->string('password_hash', 255);
        $table->enum('rol', ['alumno','entrenador','admin'])->default('alumno');
        $table->enum('estado', ['activo','inactivo','baneado'])->default('activo');
        $table->timestamps();

        $table->index('rol');
        $table->index('estado');
    });
}
public function down(): void
{
    Schema::dropIfExists('usuarios');
}

};
