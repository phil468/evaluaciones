<?php

namespace App\Http\Controllers;

use App\Models\TipoMedicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoMedicionController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return view('tipo_mediciones.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoMediciones = TipoMedicion::all();
        return response()->json($tipoMediciones);
    }

    public function show(TipoMedicion $tipoMedicion)
    {
        if (Gate::denies('ver-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($tipoMedicion);
    }
    
    public function create()
    {
        if (Gate::denies('crear-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('tipo_mediciones.create');
    }

    public function edit(TipoMedicion $tipoMedicion)
    {
        if (Gate::denies('editar-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('tipo_mediciones.edit', compact('tipoMedicion'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:tipo_mediciones,name',
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoMedicion = TipoMedicion::create($request->all());
            return response()->json($tipoMedicion, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Tipo de Medición: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Tipo de Medición'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:tipo_mediciones,name,'.$id,
            'estado' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoMedicion = TipoMedicion::findOrFail($id); // Encuentra el modelo por ID
            $tipoMedicion->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($tipoMedicion, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Tipo de Medición: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Tipo de Medición'], 500);
        }
    }

    public function destroy($id)
    {
        $tipoMedicion = TipoMedicion::findOrFail($id);

        if (Gate::denies('borrar-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoMedicion->delete();
        return response()->json(null, 204);
    }
    
    public function restore($id)
    {
        $tipoMedicion = TipoMedicion::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoMedicion->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $tipoMedicion = TipoMedicion::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-tipo-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoMedicion->forceDelete();
        return response()->json(null, 204);
    }
}