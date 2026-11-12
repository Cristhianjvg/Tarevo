<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
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

        $roles = Rol::orderBy('nombre')->get(['id', 'nombre']);

        return view('personal.index', compact('personal', 'roles'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'nombre' => $validatedData['nombre'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        $user->roles()->attach($validatedData['rol_id']);

        return redirect()->route('personal.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $user)
    {
        abort_unless($request->user()->esSupervisor(), 403);

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id',
        ]);

        if ($user->is($request->user()) && ! $user->roles->contains('id', (int) $validatedData['rol_id'])) {
            return back()->withInput()->withErrors(['rol_id' => 'No puedes cambiar tu propio rol.']);
        }

        $user->nombre = $validatedData['nombre'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];

        if (! empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();
        $user->roles()->sync([$validatedData['rol_id']]);

        return redirect()->route('personal.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->esSupervisor(), 403);

        if ($user->is($request->user())) {
            return redirect()->route('personal.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        try {
            $user->delete();
        } catch (QueryException) {
            return redirect()->route('personal.index')
                ->with('error', 'No se puede eliminar: el usuario tiene tareas, rutinas o instalaciones asociadas.');
        }

        return redirect()->route('personal.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
