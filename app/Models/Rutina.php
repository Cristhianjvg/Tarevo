<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'instalacion_id',
    'tipo_tarea_id',
    'creada_por_user_id',
    'asignada_a_user_id',
    'nombre',
    'descripcion',
    'frecuencia',
    'intervalo',
    'dias_semana',
    'dia_mes',
    'mes',
    'hora_programada',
    'fecha_inicio',
    'fecha_fin',
    'proxima_ejecucion',
    'ultima_ejecucion',
    'activa',
])]
class Rutina extends Model
{
    protected $table = 'rutinas';

    protected function casts(): array
    {
        return [
            'dias_semana' => 'array',
            'dia_mes' => 'integer',
            'mes' => 'integer',
            'intervalo' => 'integer',
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'proxima_ejecucion' => 'datetime',
            'ultima_ejecucion' => 'datetime',
            'activa' => 'boolean',
        ];
    }

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function tipoTarea(): BelongsTo
    {
        return $this->belongsTo(TipoTarea::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por_user_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignada_a_user_id');
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }
}
