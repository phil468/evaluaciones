<?php

namespace App\Http\Controllers;

use App\Models\TipoCompetencia;
use App\Models\TipoMedicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoCompetenciaController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $tipoMediciones = TipoMedicion::all();
        // return view('tipo_competencias.create', compact('tipoMediciones'));
        return view('tipo_competencias.index', compact('tipoMediciones'));
    }

    public function getData()
    {
        if (Gate::denies('ver-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoCompetencias = TipoCompetencia::with('tipoMedicion')->get();
        return response()->json($tipoCompetencias);
    }

    public function show(TipoCompetencia $tipoCompetencia)
    {
        if (Gate::denies('ver-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($tipoCompetencia);
    }
    
    public function create()
    {
        if (Gate::denies('crear-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoMediciones = TipoMedicion::all();
        return view('tipo_competencias.create', compact('tipoMediciones'));
    }

    public function edit(TipoCompetencia $tipoCompetencia)
    {
        if (Gate::denies('editar-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoMediciones = TipoMedicion::all();
        return view('tipo_competencias.edit', compact('tipoCompetencia', 'tipoMediciones'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tipo_competencias,name',
            'medicion_id' => 'required|exists:tipo_mediciones,id',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoCompetencia = TipoCompetencia::create($request->all());
            return response()->json($tipoCompetencia, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Tipo de Competencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Tipo de Competencia'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tipo_competencias,name,'.$id,
            'medicion_id' => 'required|exists:tipo_mediciones,id',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoCompetencia = TipoCompetencia::findOrFail($id); // Encuentra el modelo por ID
            $tipoCompetencia->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($tipoCompetencia, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Tipo de Competencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Tipo de Competencia'], 500);
        }
    }

    public function destroy($id)
    {
        $tipoCompetencia = TipoCompetencia::findOrFail($id);

        if (Gate::denies('borrar-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoCompetencia->delete();
        return response()->json(null, 204);
    }
    
    public function restore($id)
    {
        $tipoCompetencia = TipoCompetencia::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoCompetencia->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $tipoCompetencia = TipoCompetencia::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-tipo-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoCompetencia->forceDelete();
        return response()->json(null, 204);
    }
}