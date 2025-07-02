<?php

namespace App\Http\Controllers;

use App\Models\CampaniaHasCompetencia;
use App\Models\Seccione;
use App\Models\Campania;
use App\Models\TipoCompetencia;
use App\Models\TipoMedicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CampaniaHasCompetenciaController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-campania-has-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $competencias = Seccione::all();
        $campanias = Campania::all();
        $tiposCompetencia = TipoCompetencia::all();
        $tiposMedicion = TipoMedicion::all();
        $campaniaHasCompetencias = CampaniaHasCompetencia::with('competencia', 'campania', 'tipoCompetencia', 'tipoMedicion')->get();
        // if ($campaniaHasCompetencias->isEmpty()) {
        //     return response()->json(['message' => 'No hay competencias asociadas a campañas'], 404);
        // }
        // Return the view with the necessary data
        return view('campania_has_competencias.index', compact('competencias', 'campanias', 'tiposCompetencia', 'tiposMedicion', 'campaniaHasCompetencias'));

        // return view('campania_has_competencias.index', compact('competencias', 'campanias', 'tiposCompetencia', 'tiposMedicion'));
    }

    public function getData()
    {
        if (Gate::denies('ver-campania-has-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campaniaHasCompetencias = CampaniaHasCompetencia::with('competencia', 'campania', 'tipoCompetencia', 'tipoMedicion', 'relacionadoAnterior', 'relacionadoAnterior.competencia', 'relacionadoAnterior.campania')->get();
        return response()->json($campaniaHasCompetencias);
    }

    public function show($id)
    {
        if (Gate::denies('ver-campania-has-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $campaniaHasCompetencia = 
            CampaniaHasCompetencia::with(
                'competencia', 
                'campania', 
                'tipoCompetencia', 
                'tipoMedicion'
                )->findOrFail($id);
            return response()->json($campaniaHasCompetencia);
        } catch (\Exception $e) {
            Log::error('Error al obtener CampaniaHasCompetencia: ' . $e->getMessage());
            return response()->json(['message' => 'CampaniaHasCompetencia no encontrada'], 404);
        }
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-campania-has-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'competencia_id' => 'required|exists:secciones,id',
            'campania_id' => 'required|exists:campanias,id',
            'relacionado_anterior_id' => 'nullable|exists:campania_has_competencias,id',
            'estado' => 'nullable|boolean',
            'tipo_competencia_id' => 'nullable|exists:tipo_competencias,id',
            'tipo_medicion_id' => 'nullable|exists:tipo_mediciones,id',
            'color' => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $campaniaHasCompetencia = CampaniaHasCompetencia::create($request->all());
            return response()->json($campaniaHasCompetencia, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear CampaniaHasCompetencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear CampaniaHasCompetencia'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-campania-has-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'competencia_id' => 'required|exists:secciones,id',
            'campania_id' => 'required|exists:campanias,id',
            'relacionado_anterior_id' => 'nullable|exists:campania_has_competencias,id',
            'estado' => 'nullable|boolean',
            'tipo_competencia_id' => 'nullable|exists:tipo_competencias,id',
            'tipo_medicion_id' => 'nullable|exists:tipo_mediciones,id',
            'color' => 'nullable|string|max:7',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $campaniaHasCompetencia = CampaniaHasCompetencia::findOrFail($id);
            $campaniaHasCompetencia->update($request->all());
            return response()->json($campaniaHasCompetencia, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar CampaniaHasCompetencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar CampaniaHasCompetencia'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-campania-has-competencia')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $campaniaHasCompetencia = CampaniaHasCompetencia::findOrFail($id);
            $campaniaHasCompetencia->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar CampaniaHasCompetencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar CampaniaHasCompetencia'], 500);
        }
    }

}