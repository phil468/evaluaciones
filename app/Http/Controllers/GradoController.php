<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GradoController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('grados.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $grados = Grado::all();
        return response()->json($grados);
    }

    public function show(Grado $grado)
    {
        if (Gate::denies('ver-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($grado);
    }

    public function create()
    {
        if (Gate::denies('crear-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('grados.create');
    }

    public function edit(Grado $grado)
    {
        if (Gate::denies('editar-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('grados.edit', compact('grado'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:grados,name',
            'estado' => 'nullable|boolean',
        ],[],
        [
            'name' => 'Nombre',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $grado = Grado::create($request->all());
            return response()->json($grado, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Grado: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Grado'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'estado' => 'nullable|boolean',
        ], [
            'name.required' => 'El campo Nombre es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $grado = Grado::findOrFail($id); // Encuentra el modelo por ID
            $grado->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($grado, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Grado: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Grado'], 500);
        }
    }

    public function destroy($id)
    {
        $grado = Grado::findOrFail($id);

        if (Gate::denies('borrar-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $grado->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $grado = Grado::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $grado->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $grado = Grado::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-grado')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $grado->forceDelete();
        return response()->json(null, 204);
    }
}