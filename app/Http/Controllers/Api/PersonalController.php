<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    
    /**
     * Obtiene los datos de todos los registros de personal para la tabla
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        if (Gate::denies('ver-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $personal = Personal::with([
                'empresa', 'gerencia', 'subgerencia', 'sede', 
                'area', 'cargo', 'planilla', 'tipo_trabajador', 'tipo_personal', 'superior'
            ])
            ->where('cesado', false)
            ->get();

            return response()->json($personal);

        } catch (\Exception $e) {
            Log::error('Error al obtener datos de personal: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Error al obtener datos de personal',
                'error' => $e->getMessage(),
                'trace' => app()->environment('local') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Aplica filtros a la consulta
     */
    private function applyFilter($query, $field, $type, $value)
    {
        switch ($type) {
            case 'like':
                $query->where($field, 'like', "%{$value}%");
                break;
            case 'eq':
                $query->where($field, $value);
                break;
            case 'ne':
                $query->where($field, '!=', $value);
                break;
            case 'lt':
                $query->where($field, '<', $value);
                break;
            case 'lte':
                $query->where($field, '<=', $value);
                break;
            case 'gt':
                $query->where($field, '>', $value);
                break;
            case 'gte':
                $query->where($field, '>=', $value);
                break;
            default:
                $query->where($field, 'like', "%{$value}%");
        }
    }

    /**
     * Muestra los detalles de un personal específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if (Gate::denies('ver-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $personal = Personal::with([
                'empresa', 'gerencia', 'subgerencia', 'sede', 
                'area', 'cargo', 'planilla', 'tipo_trabajador', 'tipo_personal'
            ])->findOrFail($id);

            return response()->json($personal);
        } catch (\Exception $e) {
            Log::error('Error al mostrar personal: ' . $e->getMessage());
            return response()->json(['message' => 'No se encontró el personal'], 404);
        }
    }

    /**
     * Almacena un nuevo personal
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('crear-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'dni' => 'required|string|unique:personal,dni',
            'name' => 'required|string',
            'nombres' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'nullable|string',
            'empresa_id' => 'required|exists:empresas,id',
            'gerencia_id' => 'nullable|exists:gerencias,id',
            'subgerencia_id' => 'nullable|exists:subgerencias,id',
            'sede_id' => 'nullable|exists:sedes,id',
            'area_id' => 'nullable|exists:areas,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'correo_empresa' => 'nullable|email',
            'celular_empresa' => 'nullable|string',
            'correo_personal' => 'nullable|email',
            'telefono_personal' => 'nullable|string',
            'celular_personal' => 'nullable|string',
            'estado' => 'nullable|boolean',
            'genero' => 'nullable|string|in:M,F',
            'fecha_ingreso' => 'nullable|date',
            'tipo_de_trabajador_id' => 'nullable|exists:tipo_de_trabajador,id',
            'tipo_de_personal_id' => 'nullable|exists:tipo_de_personal,id',
            'planilla_id' => 'nullable|exists:planillas,id',
            'cesado' => 'nullable|boolean',
            'fecha_cese' => 'nullable|date',
            'reporta_a' => 'nullable|exists:personal,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $personal = Personal::create($request->all());
            return response()->json($personal, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear personal'], 500);
        }
    }

    /**
     * Actualiza un personal específico
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        if (Gate::denies('editar-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $personal = Personal::findOrFail($id);
    
        // Determinar si es una actualización parcial (solo seleccionado)
        $isPartialUpdate = $request->has('seleccionado') && count($request->all()) == 1;
        
        if ($isPartialUpdate) {
            // Para edición inline de seleccionado, solo validamos ese campo
            $validator = Validator::make($request->all(), [
                'seleccionado' => 'boolean',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'dni' => 'required|string|unique:personal,dni,' . $id,
                'name' => 'required|string',
                'nombres' => 'required|string',
                'apellido_paterno' => 'required|string',
                'apellido_materno' => 'nullable|string',
                'empresa_id' => 'required|exists:empresas,id',
                'gerencia_id' => 'nullable|exists:gerencias,id',
                'subgerencia_id' => 'nullable|exists:subgerencias,id',
                'sede_id' => 'nullable|exists:sedes,id',
                'area_id' => 'nullable|exists:areas,id',
                'cargo_id' => 'nullable|exists:cargos,id',
                'correo_empresa' => 'nullable|email',
                'celular_empresa' => 'nullable|string',
                'correo_personal' => 'nullable|email',
                'telefono_personal' => 'nullable|string',
                'celular_personal' => 'nullable|string',
                'estado' => 'nullable|boolean',
                'genero' => 'nullable|string|in:M,F',
                'fecha_ingreso' => 'nullable|date',
                'tipo_de_trabajador_id' => 'nullable|exists:tipo_de_trabajador,id',
                'tipo_de_personal_id' => 'nullable|exists:tipo_de_personal,id',
                'planilla_id' => 'nullable|exists:planillas,id',
                'cesado' => 'nullable|boolean',
                'fecha_cese' => 'nullable|date',
                'reporta_a' => 'nullable|exists:personal,id',
            ]);
            
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // $personal = Personal::findOrFail($id);
            // $personal->update($request->all());
            // return response()->json($personal);

            // Actualizar según el tipo de actualización
            if ($isPartialUpdate) {
                $personal->seleccionado = $request->seleccionado;
                // dd($personal->seleccionado, $request->seleccionado);
                // dd($personal);
                $personal->save();
                // dd($personal);
            } else {
                $personal->update($request->all());
            }
            
            return response()->json(['success' => true, 'message' => 'Personal actualizado correctamente']);

        } catch (\Exception $e) {
            Log::error('Error al actualizar personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar personal'], 500);
        }
    }

    /**
     * Elimina un personal específico
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('eliminar-personal')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        try {
            $personal = Personal::findOrFail($id);
            $personal->delete();
            return response()->json(['message' => 'Personal eliminado correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar personal: ' . $e->getMessage());
            return response()->json(['message' => 'Error al eliminar personal'], 500);
        }
    }
}
