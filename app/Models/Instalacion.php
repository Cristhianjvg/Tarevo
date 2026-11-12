<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'nombre', 'descripcion', 'direccion'])]
class Instalacion extends Model
{
    protected $table = 'instalaciones';

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    public function rutinas(): HasMany
    {
        return $this->hasMany(Rutina::class);
    }
}
