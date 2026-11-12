<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstalacionController extends Controller
{
    public function index(Request $request): View
    {
        $instalaciones = Instalacion::with('responsable')
            ->orderBy('nombre')
            ->paginate(15);

        $responsables = User::orderBy('nombre')->get(['id', 'nombre']);

        return view('instalaciones.index', compact('instalaciones', 'responsables'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'direccion' => 'nullable|string|max:350',
        ]);

        Instalacion::create($validatedData);

        return redirect()->route('instalaciones.index')->with('success', 'Instalación creada exitosamente.');
    }

    public function update(Request $request, Instalacion $instalacion): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'direccion' => 'nullable|string|max:350',
        ]);

        $instalacion->update($validatedData);

        return redirect()->route('instalaciones.index')->with('success', 'Instalación actualizada exitosamente.');
    }

    public function destroy(Request $request, Instalacion $instalacion): RedirectResponse
    {
        try {
            $instalacion->delete();
        } catch (QueryException) {
            return redirect()->route('instalaciones.index')
                ->with('error', 'No se puede eliminar: la instalación tiene tareas o rutinas asociadas.');
        }

        return redirect()->route('instalaciones.index')->with('success', 'Instalación eliminada exitosamente.');
    }
}
