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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('creada_por_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('asignada_a_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('instalacion_id')
                ->constrained('instalaciones')
                ->restrictOnDelete();

            $table->foreignId('tipo_tarea_id')
                ->constrained('tipos_tarea')
                ->restrictOnDelete();

            $table->foreignId('rutina_id')
                ->nullable()
                ->constrained('rutinas')
                ->nullOnDelete();

            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->text('observaciones')->nullable();
            $table->dateTime('programada_para')->nullable();
            $table->dateTime('realizada_en')->nullable();
            $table->string('estado', 50)->default('pendiente'); // pendiente, en_progreso, completada, cancelada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tareas');
    }
};
