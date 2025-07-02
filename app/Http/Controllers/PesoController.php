<?php

namespace App\Http\Controllers;

use App\Models\Peso;
use App\Models\Campania;
use App\Models\Grado;
use App\Models\TipoRelacionJerarquica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PesoController extends Controller
{
    /**
     * Obtener los pesos por campaña
     *
     * @param int $campania_id
     * @return \Illuminate\Http\Response
     */
    public function getByCampania($campania_id)
    {
        if (Gate::denies('ver-peso')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $pesos = Peso::with(['tipoRelacionJerarquica', 'grado'])
                ->where('campania_id', $campania_id)
                ->get();

            return response()->json($pesos);
        } catch (\Exception $e) {
            Log::error('Error al obtener pesos: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener los pesos'], 500);
        }
    }

    /**
     * Obtener datos para los selects del formulario
     *
     * @return \Illuminate\Http\Response
     */
    public function getSelects()
    {
        if (Gate::denies('ver-peso')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $tiposRelacion = TipoRelacionJerarquica::where('estado', 1)->get();
            $grados = Grado::where('estado', 1)->get();
            
            return response()->json([
                'tipos_relacion' => $tiposRelacion,
                'grados' => $grados
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener datos para selects: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener datos para los selects'], 500);
        }
    }

    /**
     * Crear un nuevo peso
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('crear-peso')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tipo_relacion_jerarquica_id' => 'required|exists:tipo_relacion_jerarquicas,id',
            'peso' => 'required|numeric|min:0|max:1',
            'grado_id' => 'required|exists:grados,id',
            'campania_id' => 'required|exists:campanias,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Verificar si ya existe un peso con mismas relaciones
            $existingPeso = Peso::where('tipo_relacion_jerarquica_id', $request->tipo_relacion_jerarquica_id)
                ->where('grado_id', $request->grado_id)
                ->where('campania_id', $request->campania_id)
                ->first();

            if ($existingPeso) {
                return response()->json([
                    'message' => 'Ya existe un peso para esta relación jerárquica, grado y campaña'
                ], 422);
            }

            $peso = Peso::create($request->all());
            return response()->json($peso, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear peso: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear el peso'], 500);
        }
    }

    /**
     * Mostrar un peso específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Gate::denies('ver-peso')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $peso = Peso::with(['tipoRelacionJerarquica', 'grado', 'campania'])->findOrFail($id);
            return response()->json($peso);
        } catch (\Exception $e) {
            Log::error('Error al obtener peso: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener el peso'], 500);
        }
    }

    /**
     * Actualizar un peso existente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-peso')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'tipo_relacion_jerarquica_id' => 'required|exists:tipo_relacion_jerarquicas,id',
            'peso' => 'required|numeric|min:0|max:1',
            'grado_id' => 'required|exists:grados,id',
            'campania_id' => 'required|exists:campanias,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $peso = Peso::findOrFail($id);

            // Verificar si ya existe otro peso con las mismas relaciones
            $existingPeso = Peso::where('tipo_relacion_jerarquica_id', $request->tipo_relacion_jerarquica_id)
                ->where('grado_id', $request->grado_id)
                ->where('campania_id', $request->campania_id)
                ->where('id', '!=', $id)
                ->first();

            if ($existingPeso) {
                return response()->json([
                    'message' => 'Ya existe otro peso para esta relación jerárquica, grado y campaña'
                ], 422);
            }

            $peso->update($request->all());
            return response()->json($peso);
        } catch (\Exception $e) {
            Log::error('Error al actualizar peso: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar el peso'], 500);
        }
    }

    /**
     * Eliminar un peso
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Gate::denies('borrar-peso')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $peso = Peso::findOrFail($id);
            $peso->delete();
            return response()->json(['message' => 'Peso eliminado correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar peso: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar el peso'], 500);
        }
    }
}