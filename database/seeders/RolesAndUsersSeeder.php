<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class RolesAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEEDER_PASSWORD');

        if (! is_string($password) || $password === '') {
            throw new RuntimeException(
                'Define SEEDER_PASSWORD en el entorno antes de ejecutar este seeder.'
            );
        }

        $roles = [
            [
                'nombre' => 'Supervisor',
                'slug' => 'supervisor',
                'descripcion' => 'Supervisa la gestión de las instalaciones.',
            ],
            [
                'nombre' => 'Encargado operativo',
                'slug' => 'encargado_operativo',
                'descripcion' => 'Gestiona las tareas operativas.',
            ],
        ];

        foreach ($roles as $roleData) {
            Rol::updateOrCreate(
                ['slug' => $roleData['slug']],
                [
                    'nombre' => $roleData['nombre'],
                    'descripcion' => $roleData['descripcion'],
                ],
            );
        }

        foreach (Rol::query()->get() as $role) {
            $user = User::firstOrCreate(
                ['email' => "{$role->slug}@example.test"],
                [
                    'nombre' => "Usuario {$role->nombre}",
                    'username' => "usuario_{$role->slug}",
                    'password' => $password,
                ],
            );

            $user->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
