<?php

namespace App\Http\Controllers;

use App\Models\Instalacion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstalacionController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $instalaciones = Instalacion::with('responsable')
            ->orderBy('nombre')
            ->paginate(15);

        $responsables = User::orderBy('nombre')->get(['id', 'nombre']);

        return view('instalaciones.index', compact('instalaciones', 'responsables'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'direccion' => 'nullable|string|max:350',
        ]);

        Instalacion::create($validatedData);

        return redirect()->route('instalaciones.index')->with('success', 'Instalación creada exitosamente.');
    }
}
