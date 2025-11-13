<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemorandumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin Empresa']);
    }

    public function index()
    {
        return view('company.memorandums.index');
    }

    public function create()
    {
        return view('company.memorandums.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        // Aquí deberías guardar el memorándum en la base de datos cuando exista el modelo correspondiente

        return redirect()
            ->route('company.memorandums.index')
            ->with('status', 'Memorándum registrado correctamente (demo).');
    }
}
