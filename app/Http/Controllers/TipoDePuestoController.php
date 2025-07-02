<?php

namespace App\Http\Controllers;

use App\Models\TipoDePuesto;
use App\Models\NivelJerarquico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoDePuestoController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $nivelesJerarquicos = NivelJerarquico::all();

        return view('tipo_de_puestos.index', compact('nivelesJerarquicos'));
    }

    public function getData()
    {
        if (Gate::denies('ver-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuestos = TipoDePuesto::with('nivelJerarquico')->get();
        return response()->json($tipoDePuestos);
    }

    public function show(TipoDePuesto $tipoDePuesto)
    {
        if (Gate::denies('ver-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($tipoDePuesto);
    }

    public function create()
    {
        if (Gate::denies('crear-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('tipo_de_puestos.create');
    }

    public function edit(TipoDePuesto $tipoDePuesto)
    {
        if (Gate::denies('editar-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('tipo_de_puestos.edit', compact('tipoDePuesto'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nivel_jerarquico_id' => 'nullable|exists:nivel_jerarquicos,id',
            'estado' => 'nullable|boolean',
        ],[],
        [
            'name' => 'Nombre',
            'nivel_jerarquico_id' => 'Nivel Jerárquico',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoDePuesto = TipoDePuesto::create($request->all());
            return response()->json($tipoDePuesto, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Tipo de Puesto: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Tipo de Puesto'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nivel_jerarquico_id' => 'nullable|exists:nivel_jerarquicos,id',
            'estado' => 'nullable|boolean',
        ], [
            'name.required' => 'El campo Nombre es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoDePuesto = TipoDePuesto::findOrFail($id); // Encuentra el modelo por ID
            $tipoDePuesto->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($tipoDePuesto, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Tipo de Puesto: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Tipo de Puesto'], 500);
        }
    }

    public function destroy($id)
    {
        $tipoDePuesto = TipoDePuesto::findOrFail($id);

        if (Gate::denies('borrar-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuesto->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $tipoDePuesto = TipoDePuesto::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuesto->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $tipoDePuesto = TipoDePuesto::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-tipo-de-puesto')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuesto->forceDelete();
        return response()->json(null, 204);
    }
}