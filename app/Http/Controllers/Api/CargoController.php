<?php

namespace App\Http\Controllers\API;

use App\Models\Cargo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CargoController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $cargos = Cargo::with('tipoDePuesto')->get();
        
        return response()->json($cargos);
    }

    public function show(Cargo $cargo)
    {
        if (Gate::denies('ver-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $cargo->load('tipoDePuesto');
        return response()->json($cargo);
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'tipo_de_puesto_id' => 'nullable|exists:tipo_de_puestos,id',
            'estado' => 'nullable|boolean',
            'idcargo_nisira' => 'nullable|string|max:50',
            'fechacreacion_nisira' => 'nullable|date',
            'empresa_id' => 'nullable|integer',
        ], [], [
            'name' => 'Nombre',
            'tipo_de_puesto_id' => 'Tipo de puesto',
            'idcargo_nisira' => 'ID Cargo Nisira',
            'fechacreacion_nisira' => 'Fecha Creación Nisira',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $cargo = Cargo::create($request->all());
            return response()->json($cargo, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Cargo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Cargo'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'tipo_de_puesto_id' => 'nullable|exists:tipo_de_puestos,id',
            'estado' => 'nullable|boolean',
            'idcargo_nisira' => 'nullable|string|max:50',
            'fechacreacion_nisira' => 'nullable|date',
            'empresa_id' => 'nullable|integer',
        ], [], [
            'name' => 'Nombre',
            'tipo_de_puesto_id' => 'Tipo de puesto',
            'idcargo_nisira' => 'ID Cargo Nisira',
            'fechacreacion_nisira' => 'Fecha Creación Nisira',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $cargo = Cargo::findOrFail($id);
            $cargo->update($request->all());
            return response()->json($cargo, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Cargo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Cargo'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-cargo')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $cargo = Cargo::findOrFail($id);
            $cargo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Cargo: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar Cargo'], 500);
        }
    }
}
