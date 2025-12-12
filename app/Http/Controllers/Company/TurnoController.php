<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnoController extends Controller
{
    public function index()
    {
        $companyId = Auth::user()->company_id;
        $turnos = Turno::where('company_id', $companyId)
            ->orderBy('id')
            ->get();

        return response()->json($turnos);
    }

    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;
        if (Turno::where('company_id', $companyId)->count() >= 20) {
            return response()->json(['message' => 'sin espacio para crear turnos, edita una o elimina uno'], 422);
        }

        $data = $request->validate([
            'code' => 'required|string|max:2',
            'description' => 'required|string|max:255',
            'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        $turno = Turno::create([
            'company_id' => $companyId,
            'code' => strtoupper($data['code']),
            'description' => $data['description'],
            'color' => $data['color'] ?? '#22d3ee',
        ]);

        return response()->json($turno, 201);
    }

    public function update(Request $request, Turno $turno)
    {
        $this->authorizeTurno($turno);

        $data = $request->validate([
            'code' => 'required|string|max:2',
            'description' => 'required|string|max:255',
            'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        $turno->update([
            'code' => strtoupper($data['code']),
            'description' => $data['description'],
            'color' => $data['color'] ?? '#22d3ee',
        ]);

        return response()->json($turno);
    }

    public function destroy(Turno $turno)
    {
        $this->authorizeTurno($turno);
        $turno->delete();
        return response()->json(['message' => 'Eliminado']);
    }

    protected function authorizeTurno(Turno $turno): void
    {
        if ($turno->company_id !== Auth::user()->company_id) {
            abort(403);
        }
    }
}
