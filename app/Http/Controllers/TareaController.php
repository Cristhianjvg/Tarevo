<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{
    //

    public function index(Request $request)
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $tareas = Tarea::with('responsable')
            ->orderBy('titulo')
            ->paginate(15);

        return view('tareas.index', compact('tareas'));
    }
}
