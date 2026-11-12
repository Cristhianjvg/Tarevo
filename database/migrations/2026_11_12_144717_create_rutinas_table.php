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
        Schema::create('rutinas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('instalacion_id')
                ->constrained('instalaciones')
                ->restrictOnDelete();

            $table->foreignId('tipo_tarea_id')
                ->constrained('tipos_tarea')
                ->restrictOnDelete();

            $table->foreignId('creada_por_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('asignada_a_user_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();

            $table->enum('frecuencia', ['diaria', 'semanal', 'mensual', 'anual']);
            $table->unsignedSmallInteger('intervalo')->default(1);

            // Ejemplo: [1, 4] para martes y viernes, 0 = lunes.
            $table->json('dias_semana')->nullable();

            // Para rutinas mensuales o anuales.
            $table->unsignedTinyInteger('dia_mes')->nullable();
            $table->unsignedTinyInteger('mes')->nullable();

            $table->time('hora_programada')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->dateTime('proxima_ejecucion')->nullable();
            $table->dateTime('ultima_ejecucion')->nullable();

            $table->boolean('activa')->default(true);

            $table->timestamps();

            $table->unique(['instalacion_id', 'nombre']);
            $table->index(['activa', 'proxima_ejecucion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutinas');
    }
};
