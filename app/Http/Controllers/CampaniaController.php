<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ImportarPreguntasService as ControllersImportarPreguntasService;
use App\Models\Campania;
use App\Models\CampaniaHasCompetencia;
use App\Models\DominioHasPregunta;
use App\Models\Peso;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// Removemos la importación del servicio de App\Services ya que usaremos el de App\Http\Controllers

class CampaniaController extends Controller
{
    public function index()
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        return view('campanias.index');
    }

    public function getData()
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campanias = Campania::with(['relacionadoAnterior'])->get();
        if ($campanias->isEmpty()) {
            return response()->json(['message' => 'No hay campañas registradas'], 404);
        }
        return response()->json($campanias);
    }
    
    public function getSelect()
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campanias = Campania::all();
        $selectOptions = $campanias->map(function($campania) {
            return [
                'id' => $campania->id,
                'name' => $campania->name,
                'estado' => $campania->estado ? 'Activo' : 'Inactivo'
            ];
        });

        return response()->json($selectOptions);
    }

    public function getAllCompetenciasCampaniaAnterior($campaniaActualId)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Validar que la campaña actual tenga una campaña anterior asociada
        $campaniaActual = Campania::find($campaniaActualId);
        if (!$campaniaActual || !$campaniaActual->relacionadoAnterior) {
            return response()->json(['message' => 'La campaña actual no tiene una campaña anterior asociada'], 404);
        }

        try {
            $campaniaHasCompetencias = CampaniaHasCompetencia::where('campania_id', $campaniaActual->relacionadoAnterior->id)
                ->with('competencia', 'campania', 'tipoCompetencia', 'tipoMedicion')
                ->get();

            if ($campaniaHasCompetencias->isEmpty()) {
                return response()->json(['message' => 'No hay competencias asociadas a la campaña'], 404);
            }

            return response()->json($campaniaHasCompetencias);
        } catch (\Exception $e) {
            Log::error('Error al obtener competencias de la campaña: ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener competencias de la campaña'], 500);
        }
    }

    public function show($id)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        return response()->json($campania);
    }
    
    public function create()
    {
        if (Gate::denies('crear-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        return view('campanias.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('crear-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:campanias,name',
            'estado' => 'nullable|boolean',
        ],[],
        [
            'name' => 'Nombre',
        ]);        

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        if ($request->es_campania_actual) {
            // Poner todas las demás campañas como no actual
            \App\Models\Campania::where('es_campania_actual', true)->update(['es_campania_actual' => false]);
        }

        try {
            $campania = Campania::create($request->all());
            return response()->json($campania, 201);
        } catch (\Exception $e) {
            Log::error('Error al crear Campaña: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear Campaña'], 500);
        }
    }

    public function edit($id)
    {
        if (Gate::denies('editar-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        return view('campanias.edit', compact('campania'));
    }

    public function update(Request $request, $id)
    {
        if (Gate::denies('editar-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:campanias,name,' . $id,
            'estado' => 'nullable|boolean',
        ], [
            'name.required' => 'El campo Nombre es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->es_campania_actual) {
            // Poner todas las demás campañas como no actual
            \App\Models\Campania::where('id', '!=', $id)->update(['es_campania_actual' => false]);
        }

        try {
            $campania->update($request->all());
            return response()->json($campania, 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar Campaña: ' . $e->getMessage());
            return response()->json(['message' => 'Error al actualizar Campaña'], 500);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('borrar-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        $campania->delete();
        return response()->json(null, 204);
    }
    
    public function restore($id)
    {
        if (Gate::denies('restaurar-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::withTrashed()->findOrFail($id);
        $campania->restore();
        return response()->json(null, 204);
    }

    public function forceDelete($id)
    {
        if (Gate::denies('borrar-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::withTrashed()->findOrFail($id);
        $campania->forceDelete();
        return response()->json(null, 204);
    }

    public function config($id)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        return view('campanias.config', compact('campania'));
    }

    public function getPreguntas($id)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $preguntas = Pregunta::with(['dominio', 'campaniaHasCompetencias.competencia'])
            ->whereHas('campaniaHasCompetencias', function($query) use ($id) {
                $query->where('campania_id', $id);
            })
            ->orderBy('dominio_id')
            ->orderBy('numero_orden')
            ->get();

        $preguntasData = $preguntas->map(function($pregunta) {
            return [
                'id' => $pregunta->id,
                'pregunta' => $pregunta->pregunta,
                'competencia' => $pregunta->campaniaHasCompetencias->competencia->name,
                'campania_has_competencia_id' => $pregunta->campaniaHasCompetencias->id,
                'dominio' => $pregunta->dominio->name,
                'dominio_id' => $pregunta->dominio->id,
                'numero_orden' => $pregunta->numero_orden,
                'estado' => $pregunta->estado
            ];
        });

        return response()->json(
            $preguntasData
        );
    }

    public function getPreguntasEstado($id)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $preguntas = Pregunta::with(['dominio', 'campaniaHasCompetencias.competencia'])
            ->whereHas('campaniaHasCompetencias', function($query) use ($id) {
                $query->where('campania_id', $id);
            })
            ->orderBy('dominio_id')
            ->orderBy('numero_orden')
            ->get();

        $dominios = $preguntas->groupBy('dominio.name');
        $ordenInfoPorDominio = [];

        foreach ($dominios as $dominio => $preguntasDominio) {
            $numerosOrden = $preguntasDominio->pluck('numero_orden')->toArray();
            $maxOrden = !empty($numerosOrden) ? max($numerosOrden) : 0;
            $numerosFaltantes = [];

            // Verificar números faltantes del 1 al máximo
            for ($i = 1; $i <= $maxOrden; $i++) {
                if (!in_array($i, $numerosOrden)) {
                    $numerosFaltantes[] = $i;
                }
            }

            $ordenInfoPorDominio[$dominio] = [
                'maxOrden' => $maxOrden,
                'numerosFaltantes' => $numerosFaltantes,
                'total' => count($preguntasDominio),
                'completo' => empty($numerosFaltantes)
            ];
        }

        return response()->json([
            'ordenInfoPorDominio' => $ordenInfoPorDominio
        ]);
    }

    public function getCompetencias($id)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        $competencias = $campania->campaniaHasCompetencias()->with('competencia')->get();

        return response()->json($competencias);
    }

    public function getAllCompetenciasByCampaniaId($id) {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        $competencias = $campania->campaniaHasCompetencias()
            ->with(['campania', 'competencia', 'tipoCompetencia', 'tipoMedicion', 'relacionadoAnterior', 'relacionadoAnterior.competencia', 'relacionadoAnterior.campania'])
            ->get();

        return response()->json($competencias);
    }

    public function getDominios($id)
    {
        if (Gate::denies('ver-campania')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $campania = Campania::findOrFail($id);
        $dominios = $campania->dominios()->get();

        return response()->json($dominios);
    }

    public function getPesos($id)
    {
        $pesos = Peso::with(['tipoRelacionJerarquica', 'grado'])
            ->where('campania_id', $id)
            ->get();
        
        return response()->json($pesos);
    }

    public function validatePreguntas(Request $request, $id)
    {
        try {
            // Validación inicial de la estructura de datos
            $validator = Validator::make($request->all(), [
                'preguntas' => 'required|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $campania = Campania::findOrFail($id);
            $service = new ControllersImportarPreguntasService($campania);
            
            $validationResults = [];
            $totalValid = 0;
            $totalErrors = 0;
           
            foreach ($request->preguntas as $index => $row) {
                // Validar que los campos requeridos existan
                $rowValidator = Validator::make($row, [
                    'pregunta' => 'required|string',
                    'competencia' => 'required|string',
                    'dominio' => 'required|string',
                    'campaña' => 'required|string',
                    'numero_orden' => 'required|integer',
                    // 'quitar' => 'nullable|string'
                ]);

                if ($rowValidator->fails()) {
                    $validationResults[] = [
                        'row' => $row,
                        'index' => $index,
                        'isValid' => false,
                        'messages' => $rowValidator->errors()->all(),
                        'action' => 'error',
                        'icon' => '❌'
                    ];
                    $totalErrors++;
                    continue;
                }

                // Validar con el servicio
                // return $response = $service->validateRow($row, $index);
                $status = $service->validateRow($row, $index);
                $validationResults[] = [
                    'row' => $row,
                    'index' => $index,
                    'isValid' => $status['valid'],
                    'messages' => $status['messages'],
                    'action' => $status['action'],
                    'icon' => $status['icon']
                ];

                if ($status['valid']) {
                    $totalValid++;
                } else {
                    $totalErrors++;
                }
            }

            return response()->json([
                'success' => true,
                'validation' => [
                    'total' => count($request->preguntas),
                    'valid' => $totalValid,
                    'errors' => $totalErrors
                ],
                'results' => $validationResults
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al validar las preguntas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function importarPreguntas(Request $request, $id)
    {
        try {
            $request->validate([
                'preguntas' => 'required|array',
                'preguntas.*.pregunta' => 'required|string',
                'preguntas.*.competencia' => 'required|string',
                'preguntas.*.dominio' => 'required|string',
                'preguntas.*.campaña' => 'required|string',
                'preguntas.*.numero_orden' => 'required|integer',
                'preguntas.*.quitar' => 'nullable|string'
            ]);

            DB::beginTransaction();

            $campania = Campania::findOrFail($id);
            $service = new ControllersImportarPreguntasService($campania);

            //ordenar las preguntas con quitar valor x al final
            $preguntas_mod = collect($request->preguntas)->sortBy(function ($item) {
                return $item['quitar'] === 'x' ? 1 : 0;
            })->values()->all();
            
            // Procesar cada pregunta
            foreach ($preguntas_mod as $row) {
                $service->procesarPregunta($row);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Preguntas importadas correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al importar las preguntas: ' . $e->getMessage()
            ], 500);
        }
    }
}
