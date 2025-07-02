<?php
namespace App\Http\Controllers;

use App\Models\EscalaMedicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EscalaMedicionController extends Controller
{
    public function index()
    {
        return view('escala_mediciones.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $escalaMediciones = EscalaMedicion::all();
        return response()->json($escalaMediciones);
    }

    public function show(EscalaMedicion $escalaMedicion)
    {
        if (Gate::denies('ver-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return response()->json($escalaMedicion);
    }
    
    public function create()
    {
        if (Gate::denies('crear-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('escala_mediciones.create');
    }

    public function edit(EscalaMedicion $escalaMedicion)
    {
        if (Gate::denies('editar-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('escala_mediciones.edit', compact('escalaMedicion'));
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'valor_menor'       => 'nullable|integer',
            'valor_mayor'       => 'nullable|integer',
            'name'              => 'required|string|unique:escala_mediciones,name',
            'rango_menor'       => 'nullable|string',
            'rango_mayor'       => 'nullable|string',
            'color'             => 'nullable|string',
            'interpretacion'    => 'nullable|string',
            'recomendacion'     => 'nullable|string',
            'estado'            => 'nullable|boolean',
        ],[],
        [
            'name' => 'Nombre',
            'interpretacion' => 'Interpretación',
            'recomendacion' => 'Recomendación',
        ]);        

        try {
            $escalaMedicion = EscalaMedicion::create($request->all());
            return response()->json($escalaMedicion, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Escala de Medición: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Escala de Medición'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'valor_menor' => 'nullable|integer',
            'valor_mayor' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'rango_menor' => 'nullable|string|max:255',
            'rango_mayor' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'interpretacion' => 'nullable|string',
            'recomendacion' => 'nullable|string',
            'estado' => 'nullable|boolean',
        ], [
            'name.required' => 'El campo Nombre es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $escalaMedicion = EscalaMedicion::findOrFail($id); // Encuentra el modelo por ID
            $escalaMedicion->update($request->all()); // Actualiza los atributos del modelo
            return response()->json($escalaMedicion, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Escala de Medición: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Escala de Medición'], 500);
        }

    }

    public function destroy($id)
    {
        $escalaMedicion = EscalaMedicion::findOrFail($id);

        if (Gate::denies('borrar-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $escalaMedicion->delete();
        return response()->json(null, 204);
    }
    
    public function restore($id)
    {
        $escalaMedicion = EscalaMedicion::withTrashed()->findOrFail($id);

        if (Gate::denies('restaurar-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $escalaMedicion->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        $escalaMedicion = EscalaMedicion::withTrashed()->findOrFail($id);

        if (Gate::denies('borrar-escala-medicion')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $escalaMedicion->forceDelete();
        return response()->json(null, 204);
    }

    
}