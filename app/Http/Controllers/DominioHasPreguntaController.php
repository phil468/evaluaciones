<?php

namespace App\Http\Controllers;

use App\Models\DominioHasPregunta;
use App\Models\Dominio;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DominioHasPreguntaController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-dominio-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dominios = Dominio::all();
        $preguntas = Pregunta::all();

        return view('dominio_has_pregunta.index', compact('dominios', 'preguntas'));
    }

    public function getData()
    {
        if (Gate::denies('ver-dominio-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dominioHasPreguntas = DominioHasPregunta::with('dominio', 'pregunta')->get();
        return response()->json($dominioHasPreguntas);
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-dominio-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'dominio_id' => 'required|exists:dominios,id',
            'pregunta_id' => 'required|exists:preguntas,id',
            'numero_orden' => 'nullable|integer',
            'estado' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dominioHasPregunta = DominioHasPregunta::create($request->all());
            return response()->json($dominioHasPregunta, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear DominioHasPregunta: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear DominioHasPregunta'], 500);
        }
    }

    public function show(DominioHasPregunta $dominioHasPregunta)
    {
        if (Gate::denies('ver-dominio-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($dominioHasPregunta);
    }

    public function update(Request $request, DominioHasPregunta $dominioHasPregunta)
    {
        if (Gate::denies('editar-dominio-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'dominio_id' => 'required|exists:dominios,id',
            'pregunta_id' => 'required|exists:preguntas,id',
            'numero_orden' => 'nullable|integer',
            'estado' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dominioHasPregunta->update($request->all());
            return response()->json($dominioHasPregunta, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar DominioHasPregunta: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar DominioHasPregunta'], 500);
        }
    }

    public function destroy(DominioHasPregunta $dominioHasPregunta)
    {
        if (Gate::denies('borrar-dominio-pregunta')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $dominioHasPregunta->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar DominioHasPregunta: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar DominioHasPregunta'], 500);
        }
    }
}