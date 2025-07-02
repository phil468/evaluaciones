<?php

namespace App\Http\Controllers;

use App\Models\TipoRelacionJerarquica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoRelacionJerarquicoController extends Controller
{
    public function index()
    {
        return view('tipo_relacion_jerarquicas.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-tipo-relacion-jerarquica')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $tipos = TipoRelacionJerarquica::all();
        return response()->json($tipos);
    }

    public function show(TipoRelacionJerarquica $tipoRelacionJerarquica)
    {
        if (Gate::denies('ver-tipo-relacion-jerarquica')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($tipoRelacionJerarquica);
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-tipo-relacion-jerarquica')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'estado' => 'nullable|boolean'
        ], [], [
            'name' => 'Nombre'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipo = TipoRelacionJerarquica::create($request->all());
            return response()->json($tipo, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Tipo de Relación Jerárquica: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Tipo de Relación Jerárquica'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-tipo-relacion-jerarquica')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'estado' => 'nullable|boolean'
        ], [], [
            'name' => 'Nombre'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tipo = TipoRelacionJerarquica::findOrFail($id);
            $tipo->update($request->all());
            return response()->json($tipo, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Tipo de Relación Jerárquica: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Tipo de Relación Jerárquica'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-tipo-relacion-jerarquica')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $tipo = TipoRelacionJerarquica::findOrFail($id);
            $tipo->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar Tipo de Relación Jerárquica: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar Tipo de Relación Jerárquica'], 500);
        }
    }
}