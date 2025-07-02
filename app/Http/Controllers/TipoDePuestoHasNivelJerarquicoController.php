<?php

namespace App\Http\Controllers;

use App\Models\TipoDePuestoHasNivelJerarquico;
use App\Models\TipoDePuesto;
use App\Models\NivelJerarquico;
use App\Models\Campania;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoDePuestoHasNivelJerarquicoController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tiposDePuesto = TipoDePuesto::all();
        $nivelesJerarquicos = NivelJerarquico::all();
        $campanias = Campania::all();

        return view('tipo_de_puesto_has_nivel_jerarquicos.index', compact('tiposDePuesto', 'nivelesJerarquicos', 'campanias'));
    }

    public function getData()
    {
        if (Gate::denies('ver-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuestoHasNivelJerarquicos = TipoDePuestoHasNivelJerarquico::with('tipoDePuesto', 'nivelJerarquico', 'campania')->get();
        return response()->json($tipoDePuestoHasNivelJerarquicos);
    }

    public function show(TipoDePuestoHasNivelJerarquico $tipoDePuestoHasNivelJerarquico)
    {
        if (Gate::denies('ver-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($tipoDePuestoHasNivelJerarquico);
    }

    public function create()
    {
        if (Gate::denies('crear-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('tipo_de_puesto_has_nivel_jerarquicos.create');
    }

    public function edit(TipoDePuestoHasNivelJerarquico $tipoDePuestoHasNivelJerarquico)
    {
        if (Gate::denies('editar-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('tipo_de_puesto_has_nivel_jerarquicos.edit', compact('tipoDePuestoHasNivelJerarquico'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tipo_de_puesto_id' => 'required|exists:tipo_de_puestos,id',
            'nivel_jerarquico_id' => 'required|exists:nivel_jerarquicos,id',
            'campania_id' => 'nullable|exists:campanias,id',
        ],[],
        [
            'tipo_de_puesto_id' => 'Tipo de Puesto',
            'nivel_jerarquico_id' => 'Nivel Jerárquico',
            'campania_id' => 'Campaña',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoDePuestoHasNivelJerarquico = TipoDePuestoHasNivelJerarquico::create($request->all());
            return response()->json($tipoDePuestoHasNivelJerarquico, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Tipo de Puesto Has Nivel Jerárquico: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Tipo de Puesto Has Nivel Jerárquico'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tipo_de_puesto_id' => 'required|exists:tipo_de_puestos,id',
            'nivel_jerarquico_id' => 'required|exists:nivel_jerarquicos,id',
            'campania_id' => 'nullable|exists:campanias,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipoDePuestoHasNivelJerarquico = TipoDePuestoHasNivelJerarquico::findOrFail($id); // Encuentra el modelo por ID
            $tipoDePuestoHasNivelJerarquico->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($tipoDePuestoHasNivelJerarquico, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Tipo de Puesto Has Nivel Jerárquico: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Tipo de Puesto Has Nivel Jerárquico'], 500);
        }
    }

    public function destroy($id)
    {
        $tipoDePuestoHasNivelJerarquico = TipoDePuestoHasNivelJerarquico::findOrFail($id);

        if (Gate::denies('borrar-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuestoHasNivelJerarquico->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
    {
        $tipoDePuestoHasNivelJerarquico = TipoDePuestoHasNivelJerarquico::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuestoHasNivelJerarquico->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $tipoDePuestoHasNivelJerarquico = TipoDePuestoHasNivelJerarquico::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-tipo-de-puesto-has-nivel-jerarquico')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipoDePuestoHasNivelJerarquico->forceDelete();
        return response()->json(null, 204);
    }
}