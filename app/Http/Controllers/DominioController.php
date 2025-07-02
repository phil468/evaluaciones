<?php

namespace App\Http\Controllers;

use App\Models\Dominio;
use App\Models\Grado;
use App\Models\NivelJerarquico;
use App\Models\Campania;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DominioController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $grados = Grado::all();
        $nivelesJerarquicos = NivelJerarquico::all();
        $campanias = Campania::all();

        return view('dominios.index', compact('grados', 'nivelesJerarquicos', 'campanias'));
    }

    public function getData()
    {
        if (Gate::denies('ver-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dominios = Dominio::with('grado', 'nivelJerarquico', 'campania')->get();
        return response()->json($dominios);
    }

    public function show(Dominio $dominio)
    {
        if (Gate::denies('ver-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($dominio);
    }

    public function create()
    {
        if (Gate::denies('crear-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('dominios.create');
    }

    public function edit(Dominio $dominio)
    {
        if (Gate::denies('editar-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('dominios.edit', compact('dominio'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'grado_id' => 'nullable|exists:grados,id',
            'nivel_jerarquico_id' => 'nullable|exists:nivel_jerarquicos,id',
            'campania_id' => 'nullable|exists:campanias,id',
            'estado' => 'nullable|boolean',
        ],[],
        [
            'name' => 'Nombre',
            'grado_id' => 'Grado',
            'nivel_jerarquico_id' => 'Nivel Jerárquico',
            'campania_id' => 'Campaña',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dominio = Dominio::create($request->all());
            return response()->json($dominio, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Dominio: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Dominio'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'grado_id' => 'nullable|exists:grados,id',
            'nivel_jerarquico_id' => 'nullable|exists:nivel_jerarquicos,id',
            'campania_id' => 'nullable|exists:campanias,id',
            'estado' => 'nullable|boolean',
        ], [
            'name.required' => 'El campo Nombre es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dominio = Dominio::findOrFail($id); // Encuentra el modelo por ID
            $dominio->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($dominio, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Dominio: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Dominio'], 500);
        }
    }

    public function destroy($id)
    {
        $dominio = Dominio::findOrFail($id);

        if (Gate::denies('borrar-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dominio->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $dominio = Dominio::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dominio->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $dominio = Dominio::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-dominio')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $dominio->forceDelete();
        return response()->json(null, 204);
    }
}