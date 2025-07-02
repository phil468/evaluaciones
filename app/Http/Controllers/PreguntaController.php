<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PreguntaController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $preguntas = Pregunta::all();
        return view('preguntas.index', compact('preguntas'));
    }

    public function getData()
    {
        if (Gate::denies('ver-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $preguntas = Pregunta::with('tipoCompetencia', 'tipoMedicion')->get();
        return response()->json($preguntas);
        
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'pregunta' => 'required|string|max:600',
            'campania_has_competencia_id' => 'nullable|exists:campania_has_competencias,id',
            'dominio_id' => 'nullable|exists:dominios,id',
            'estado' => 'nullable|boolean',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $pregunta = Pregunta::create($request->all());
            return response()->json($pregunta, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Pregunta: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Pregunta'], 500);
        }
    }

    public function show(Pregunta $pregunta)
    {
        if (Gate::denies('ver-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($pregunta);
    }

    public function update(Request $request, Pregunta $pregunta)
    {
        if (Gate::denies('editar-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'pregunta' => 'required|string|max:600',
            'campania_has_competencia_id' => 'nullable|exists:campania_has_competencias,id',
            'dominio_id' => 'nullable|exists:dominios,id',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $pregunta->update($request->all());
            return response()->json($pregunta, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Pregunta: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Pregunta'], 500);
        }
        
    }

    public function destroy(Pregunta $pregunta)
    {
        if (Gate::denies('borrar-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            // $pregunta->delete();
            $pregunta->forceDelete(); // Eliminar permanentemente
            return response()->json(['message' => 'Pregunta eliminada correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Pregunta: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar Pregunta'], 500);
        }
        
    }
}