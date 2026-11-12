<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nombre', 'descripcion'])]
class TipoTarea extends Model
{
    protected $table = 'tipos_tarea';

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    public function rutinas(): HasMany
    {
        return $this->hasMany(Rutina::class);
    }
}
