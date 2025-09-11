<?php
namespace App\Http\Controllers;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\Evaluacione;
use App\Models\Grado;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EvaluadorHasEvaluadoController extends Controller
{
    public function index(Request $request, $campania_id)
    {
        $query = 
        EvaluadorHasEvaluado::with(['evaluador', 'evaluado', 'evaluacion' , 'grado'])
            ->where('campania_id', $campania_id);

        return response()->json($query->get());
    }

    public function show($id)
    {
        $item = EvaluadorHasEvaluado::with(['evaluador', 'evaluado', 'evaluacion' , 'grado'])
        ->findOrFail($id);

        return response()->json($item);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'evaluador_id' => [
                'required',
                Rule::unique('evaluador_has_evaluados')->where(function ($q) use ($request) {
                    return $q->where('evaluador_id', $request->evaluador_id)
                        ->where('evaluado_id', $request->evaluado_id)
                        ->where('evaluacion_id', $request->evaluacion_id);
                }),
            ],
            'evaluado_id' => 'required',
            'evaluacion_id' => 'required',
            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',
            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',
            // 'jerarquia' => 'required_if:tipo_de_evaluacion_id,2|min:1',
        ]);

        $item = EvaluadorHasEvaluado::create($request->all());
        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = EvaluadorHasEvaluado::findOrFail($id);

        //evaluar si es evaluacion por competencias
        $esEvaluacionPorCompetencias = $item->evaluacion->tipo_de_evaluacion_id === 1;
        $esEvaluacionPorObjetivos = $item->evaluacion->tipo_de_evaluacion_id === 2;

        if ($esEvaluacionPorCompetencias) {
            $this->validate($request, [
                'evaluador_id' => [
                    'required',
                    Rule::unique('evaluador_has_evaluados')->where(function ($q) use ($request, $id) {
                        return $q->where('evaluador_id', $request->evaluador_id)
                            ->where('evaluado_id', $request->evaluado_id)
                            ->where('evaluacion_id', $request->evaluacion_id)
                            ->where('id', '!=', $id);
                    }),
                ],
                'evaluado_id' => 'required',
                'grado_id' => 'required',
                'peso' => 'required',
                'peso_prorrateado' => 'required',
                'cesado' => 'required',
                // 'jerarquia' => 'required_if:tipo_de_evaluacion_id,2|min:1',
            ]);
        } else if ($esEvaluacionPorObjetivos) {
            $this->validate($request, [
                'evaluador_id' => [
                    'required',
                    Rule::unique('evaluador_has_evaluados')->where(function ($q) use ($request, $id) {
                        return $q->where('evaluador_id', $request->evaluador_id)
                            ->where('evaluado_id', $request->evaluado_id)
                            ->where('evaluacion_id', $request->evaluacion_id)
                            ->where('id', '!=', $id);
                    }),
                ],
                'evaluado_id' => 'required',
                'evaluacion_id' => 'required',
                'cargo_de_evaluador' => 'required',
                'area_de_evaluador' => 'required',
                'gerencia_sub_gerencia_de_evaluador' => 'required',
                'cargo_de_evaluado' => 'required',
                'area_de_evaluado' => 'required',
                'gerencia_sub_gerencia_de_evaluado' => 'required',
                // 'jerarquia' => 'required_if:tipo_de_evaluacion_id,2|min:1',
            ]);
        } else {
            return response()->json(['error' => 'Tipo de evaluación no válido'], 400);
        }

        $item->update($request->all());
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = EvaluadorHasEvaluado::findOrFail($id);
        $item->delete();
        return response()->json(['success' => true]);
    }

    public function selects(Request $request)
    {
        $campania_id = $request->campania_id;
        return response()->json([
            'evaluadores' => Personal::orderBy('name')->select('id', 'name')->get(),
            'evaluados' => Personal::orderBy('name')->select('id', 'name')->get(),
            'evaluaciones' => Evaluacione::where('campania_id', $campania_id)->select('id', 'nombre_para_mostrar')->get(),
            'grados' => Grado::orderBy('name')->select('id', 'name')->get()
        ]);
    }
}