<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'creada_por_user_id',
    'asignada_a_user_id',
    'instalacion_id',
    'tipo_tarea_id',
    'rutina_id',
    'titulo',
    'descripcion',
    'observaciones',
    'programada_para',
    'realizada_en',
    'estado',
])]
class Tarea extends Model
{
    protected $table = 'tareas';

    protected function casts(): array
    {
        return [
            'programada_para' => 'datetime',
            'realizada_en' => 'datetime',
        ];
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creada_por_user_id');
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'asignada_a_user_id');
    }

    public function instalacion(): BelongsTo
    {
        return $this->belongsTo(Instalacion::class);
    }

    public function tipoTarea(): BelongsTo
    {
        return $this->belongsTo(TipoTarea::class);
    }

    public function rutina(): BelongsTo
    {
        return $this->belongsTo(Rutina::class);
    }
}
