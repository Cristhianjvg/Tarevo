<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonalController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $personal = User::with('roles')
            ->orderBy('nombre')
            ->paginate(15);

        return view('personal.index', compact('personal'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nombre' => $validatedData['nombre'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $user->assignRole('encargado_operativo');

        return redirect()->route('personal.index')->with('success', 'Usuario creado exitosamente.');
    }
}
