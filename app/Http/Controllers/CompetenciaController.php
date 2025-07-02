<?php

namespace App\Http\Controllers;

use App\Models\Seccione;
use App\Models\TipoCompetencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompetenciaController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-competencias')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tiposCompetencia = TipoCompetencia::all();
        return view('competencias.index', compact('tiposCompetencia'));
    }

    public function getData()
    {
        if (Gate::denies('ver-competencias')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $secciones = Seccione::with('tipoCompetencia')->get();
        return response()->json($secciones);
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-competencias')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'tipo_competencia_id' => 'nullable|exists:tipo_competencias,id',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $seccion = Seccione::create($request->all());
            return response()->json($seccion, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Sección: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Sección'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-competencias')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'tipo_competencia_id' => 'nullable|exists:tipo_competencias,id',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $seccion = Seccione::findOrFail($id);
            $seccion->update($request->all());
            return response()->json($seccion, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Sección: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Sección'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-competencias')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $seccion = Seccione::findOrFail($id);
            $seccion->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Sección: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar Sección'], 500);
        }
    }
}