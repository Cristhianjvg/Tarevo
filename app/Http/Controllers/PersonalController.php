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
}
