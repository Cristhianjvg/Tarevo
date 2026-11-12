<?php

namespace Database\Seeders;

use App\Models\TipoTarea;
use Illuminate\Database\Seeder;

class TiposTareaSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'Limpieza' => 'Limpieza y aseo de espacios.',
            'Mantenimiento' => 'Mantenimiento preventivo y correctivo.',
            'Inspección' => 'Revisión y verificación del estado de las instalaciones.',
            'Adecuación' => 'Adecuación y preparación de espacios.',
        ];

        foreach ($tipos as $nombre => $descripcion) {
            TipoTarea::updateOrCreate(['nombre' => $nombre], ['descripcion' => $descripcion]);
        }
    }
}
