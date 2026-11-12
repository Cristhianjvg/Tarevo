<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use App\Models\Tarea;
use App\Models\TipoTarea;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TareaController extends Controller
{
    public function index(): View
    {
        $tareas = Tarea::with(['responsable', 'instalacion', 'tipoTarea'])
            ->orderByRaw('programada_para is null')
            ->orderBy('programada_para')
            ->orderBy('titulo')
            ->paginate(15);

        return view('tareas.index', [
            'tareas' => $tareas,
            'instalaciones' => Instalacion::orderBy('nombre')->get(['id', 'nombre']),
            'tiposTarea' => TipoTarea::orderBy('nombre')->get(['id', 'nombre']),
            'responsables' => $this->responsables(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Tarea::create([
            ...$this->validarTarea($request),
            'creada_por_user_id' => $request->user()->id,
        ]);

        return redirect()->route('tareas.index')->with('success', 'Tarea creada exitosamente.');
    }

    public function update(Request $request, Tarea $tarea): RedirectResponse
    {
        $datos = $this->validarTarea($request, true);
        $datos['realizada_en'] = $datos['estado'] === 'completada' ? ($tarea->realizada_en ?? now()) : null;

        $tarea->update($datos);

        return redirect()->route('tareas.index')->with('success', 'Tarea actualizada exitosamente.');
    }

    public function destroy(Request $request, Tarea $tarea): RedirectResponse
    {
        $tarea->delete();

        return redirect()->route('tareas.index')->with('success', 'Tarea eliminada exitosamente.');
    }

    /**
     * Encargados operativos, los únicos a quienes se puede asignar una tarea.
     *
     * @return Collection<int, User>
     */
    private function responsables()
    {
        return User::whereHas('roles', fn ($query) => $query->where('slug', 'encargado_operativo'))
            ->orderBy('nombre')
            ->get(['id', 'nombre']);
    }

    /**
     * @return array<string, mixed>
     */
    private function validarTarea(Request $request, bool $conEstado = false): array
    {
        $reglas = [
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'instalacion_id' => ['required', 'integer', Rule::exists('instalaciones', 'id')],
            'tipo_tarea_id' => ['required', 'integer', Rule::exists('tipos_tarea', 'id')],
            'asignada_a_user_id' => [
                'required', 'integer',
                Rule::exists('users', 'id')->where(
                    fn ($query) => $query->whereIn('id', User::whereHas(
                        'roles',
                        fn ($roles) => $roles->where('slug', 'encargado_operativo')
                    )->select('id'))
                ),
            ],
            'programada_para' => ['nullable', 'date'],
        ];

        if ($conEstado) {
            $reglas['estado'] = ['required', Rule::in(['pendiente', 'en_progreso', 'completada', 'cancelada'])];
        }

        return $request->validate($reglas, [
            'asignada_a_user_id.required' => 'Selecciona al responsable de la tarea.',
            'asignada_a_user_id.exists' => 'Selecciona un usuario con el rol de encargado operativo.',
        ]);
    }
}
