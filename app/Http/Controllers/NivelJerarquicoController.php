<?php

namespace App\Http\Controllers;

use App\Models\NivelJerarquico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NivelJerarquicoController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('nivel_jerarquicos.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $nivelJerarquicos = NivelJerarquico::all();
        return response()->json($nivelJerarquicos);
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $nivelJerarquico = NivelJerarquico::create($request->all());
            return response()->json($nivelJerarquico, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Nivel Jerárquico: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Nivel Jerárquico'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $nivelJerarquico = NivelJerarquico::findOrFail($id);
            $nivelJerarquico->update($request->all());
            return response()->json($nivelJerarquico, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Nivel Jerárquico: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Nivel Jerárquico'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $nivelJerarquico = NivelJerarquico::findOrFail($id);
            $nivelJerarquico->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Nivel Jerárquico: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar Nivel Jerárquico'], 500);
        }
    }
}