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
        Schema::create('clases', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120)->unique();
            $table->text('descripcion')->nullable();
            $table->enum('intensidad', ['baja', 'media', 'alta'])->default('media');
            $table->unsignedSmallInteger('duracion_min');
            $table->unsignedSmallInteger('aforo_base')->default(20);
            $table->char('color_hex', 7)->nullable(); 
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'intensidad']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clases');
    }
};
