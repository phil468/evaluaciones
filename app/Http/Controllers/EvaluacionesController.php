<?php

namespace App\Http\Controllers;

use App\Models\CampaniaHasEvaluado;
use App\Models\Dominio;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\TipoDeEvaluacione;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Obtener evaluaciones por campaña.
     *
     * @param  int  $campaniaId
     * @return \Illuminate\Http\Response
     */
    public function getByCampania($campaniaId)
    {
        $evaluaciones = Evaluacione::where('campania_id', $campaniaId)
            ->with('tipoDeEvaluacion')
            ->get();
            
        return response()->json($evaluaciones);
    }

    /**
     * Obtener tipos de evaluación.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTiposEvaluacion()
    {
        $tiposEvaluacion = TipoDeEvaluacione::where('estado', 1)->get();
        return response()->json($tiposEvaluacion);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_para_mostrar' => 'required|string|max:255',
            'identificador' => 'required|string|unique:evaluaciones,identificador',
            'campania_id' => 'required|exists:campanias,id',
            'tipo_de_evaluacion_id' => 'required|exists:tipo_de_evaluaciones,id',
            // 'fecha_inicio' => 'required|date',
            // 'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',

            'fecha_corte' => 'nullable|date',

            'fecha_inicio' => 'required|before_or_equal:fecha_fin',
            'fecha_fin' => 'required|after_or_equal:fecha_inicio',
            'minimo' => 'required_if:tipo_de_evaluacion_id,2|exclude_unless:tipo_de_evaluacion_id,2|numeric|lt:maximo|gt:0',
            'maximo'=>'required_if:tipo_de_evaluacion_id,2|exclude_unless::tipo_de_evaluacion_id,2|numeric|gt:minimo|',
            'fecha_inicio_primera_fase_matricula' => 
            'required_if:tipo_de_evaluacion_id,2|exclude_unless:tipo_de_evaluacion_id,2|before_or_equal:fecha_fin|before_or_equal:fecha_fin_primera_fase_matricula|before_or_equal:fecha_inicio_segunda_fase|after_or_equal:fecha_inicio',
            'fecha_fin_primera_fase_matricula' => 
            'required_if:tipo_de_evaluacion_id,2|exclude_unless:tipo_de_evaluacion_id,2|after_or_equal:fecha_inicio_primera_fase_matricula|before_or_equal:fecha_fin|before_or_equal:fecha_inicio_segunda_fase|after_or_equal:fecha_inicio',
            'fecha_inicio_segunda_fase' => 
            'required_if:tipo_de_evaluacion_id,2|exclude_unless:tipo_de_evaluacion_id,2|after_or_equal:fecha_fin_primera_fase_matricula|before_or_equal:fecha_fin|after_or_equal:fecha_inicio|before_or_equal:fecha_fin_segunda_fase',
            'fecha_fin_segunda_fase' => 
            'required_if:tipo_de_evaluacion_id,2|exclude_unless:tipo_de_evaluacion_id,2|after_or_equal:fecha_inicio_segunda_fase|after_or_equal:fecha_fin_primera_fase_matricula|before_or_equal:fecha_fin|after_or_equal:fecha_inicio',
            'fecha_para_mostrar_resultados' =>
            'required_if:tipo_de_evaluacion_id,2|exclude_unless:tipo_de_evaluacion_id,2|before_or_equal:fecha_fin|after_or_equal:fecha_inicio',
        ]);
        
        $evaluacion = Evaluacione::create($request->all());
        
        return response()->json([
            'message' => 'Evaluación creada correctamente',
            'evaluation' => $evaluacion
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluacion = Evaluacione::with('tipoDeEvaluacion')->findOrFail($id);
        return response()->json($evaluacion);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evaluacion = Evaluacione::findOrFail($id);
        
        $request->validate([
            'nombre_para_mostrar' => 'required|string|max:255',
            'identificador' => [
                'required',
                'string',
                Rule::unique('evaluaciones')->ignore($id),
            ],
            'tipo_de_evaluacion_id' => 'required|exists:tipo_de_evaluaciones,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'fecha_corte' => 'nullable|date',
            'minimo' => 'required|numeric|min:0|max:100',
            'maximo' => 'required|numeric|min:0|max:100|gte:minimo',
            // Campos específicos para tipo de evaluación 2 (objetivos)
            'fecha_inicio_primera_fase_matricula' => 'nullable|date',
            'fecha_fin_primera_fase_matricula' => 'nullable|date|after_or_equal:fecha_inicio_primera_fase_matricula',
            'fecha_inicio_segunda_fase' => 'nullable|date',
            'fecha_fin_segunda_fase' => 'nullable|date|after_or_equal:fecha_inicio_segunda_fase',
            'fecha_para_mostrar_resultados' => 'nullable|date'
        ]);
        
        $evaluacion->update($request->all());
        
        return response()->json([
            'message' => 'Evaluación actualizada correctamente',
            'evaluation' => $evaluacion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluacion = Evaluacione::findOrFail($id);
        
        // Verificar si la evaluación tiene evaluadores
        if ($evaluacion->evaluadores->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar la evaluación porque tiene evaluadores asociados'
            ], 422);
        }
        
        $evaluacion->delete();
        
        return response()->json([
            'message' => 'Evaluación eliminada correctamente'
        ]);
    }

    public function pendientesData(Request $request)
    {
        $id_personal = auth()->user()->personal->id;
        $tipo_de_evaluacion_id = $request->input('tipo_evaluacion', 1); // Por defecto tipo 1 (competencias)
        $campania = $request->input('campania', '2'); // Por defecto el año actual
        
        // Obtener evaluaciones
        $evaluadorHasEvaluados = EvaluadorHasEvaluado::latest('evaluador_has_evaluados.created_at')
            ->select('evaluador_has_evaluados.*')
            ->where('evaluador_has_evaluados.evaluador_id', '=', $id_personal)
            ->where('evaluaciones.tipo_de_evaluacion_id', $tipo_de_evaluacion_id)
            ->where('evaluaciones.campania_id', $campania)
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->with(['evaluacion', 'evaluado', 'grado'])
            ->get();
        
        // Cargar manualmente la relación con CampaniaHasEvaluado para cada registro
        $evaluadorHasEvaluados->each(function($item) {
            $campaniaHasEvaluado = CampaniaHasEvaluado::where('campania_id', $item->campania_id)
                ->where('personal_id', $item->evaluado_id)
                ->with('puesto')
                ->first();

            $dominio = Dominio::where('grado_id', $item->grado_id)
                ->where('campania_id', $item->campania_id)
                ->first();
            
            $item->cargo_nombre = $campaniaHasEvaluado && $campaniaHasEvaluado->puesto 
                ? $campaniaHasEvaluado->puesto->name 
                : 'Sin cargo asignado';

            $item->dominio_nombre = $dominio ? $dominio->name : '';
        });
        
        // Calcular estadísticas
        if ($tipo_de_evaluacion_id == 1) {
            $realizados = $evaluadorHasEvaluados->where('realizado', 1)->count();
            $total = $evaluadorHasEvaluados->count();
        } else {
            $pendientes = $evaluadorHasEvaluados->filter(function($evaluador) {
                return $evaluador->estado_no_realizado;
            })->count();
            $total = $evaluadorHasEvaluados->count();
            $realizados = $total - $pendientes;
        }
        
        $porcentaje = $total == 0 ? 0 : round(($realizados / $total) * 100, 2);
        
        return response()->json([
            'evaluaciones' => $evaluadorHasEvaluados->toArray(),
            'estadisticas' => [
                'realizados' => $realizados,
                'total' => $total,
                'porcentaje' => $porcentaje,
                'label' => $porcentaje.'%'
            ]
        ]);
    }

}
