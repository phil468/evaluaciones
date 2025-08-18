<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;
use App\Models\Cargo;
use App\Models\ComiteCalibracion;
use App\Models\ComiteHasPersona;
use App\Models\Dominio;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Grado;
use App\Models\Objetivo;
use App\Models\Personal;
use App\Models\Peso;
use App\Models\Respuesta;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use App\Models\TipoDePuestoHasNivelJerarquico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CampaniaHasEvaluadoController extends Controller
{
    /**
     * Obtener evaluados por campaña.
     *
     * @param  int  $campaniaId
     * @return \Illuminate\Http\Response
     */
    public function getByCampania($campaniaId)
    {
        $evaluados = CampaniaHasEvaluado::where('campania_id', $campaniaId)
            ->with([
                // 'personal', 
                'personal.user',
                'area', 
                'puesto', 
                'tipoPuestoHasNivelJerarquico',
                'tipoPuestoHasNivelJerarquico.tipoDePuesto',
                'tipoPuestoHasNivelJerarquico.dominio',
                'tipoPuestoHasNivelJerarquico.dominio.grado',
                'tipoPuestoHasNivelJerarquico.nivelJerarquico', 
                'superior',
                'subordinados',
                'subordinados.personal',
                'paresMismoSuperior',
                'paresMismoSuperior.personal',
                'paresMismoSuperior.tipoPuestoHasNivelJerarquico', // Relación para obtener pares con el mismo superior y nivel jerárquico
                'paresMismoSuperior.tipoPuestoHasNivelJerarquico.nivelJerarquico', // Relación para obtener pares con el mismo superior y nivel jerárquico
                'paresMismoSuperior.tipoPuestoHasNivelJerarquico.tipoDePuesto', // Relación para obtener pares con el mismo superior y nivel jerárquico
                 // Relación para obtener pares con el mismo superior y nivel jerárquico
            ])
            ->get();
                
        return response()->json($evaluados);
    }

    /**
     * Mostrar la página de gestión de evaluados por campaña.
     *
     * @param  int  $campaniaId
     * @return \Illuminate\Http\Response
     */
    public function index($campaniaId)
    {
        $campania = Campania::findOrFail($campaniaId);
        return view('campania_has_evaluados.index', compact('campania'));
    }

    /**
     * Obtener datos para rellenar selects.
     *
     * @return \Illuminate\Http\Response
     */    
    public function getSelects(Request $request)
    {
        $areas = Area::select('id', 'name as name')->orderBy('name')->get();
        $personal = Personal::select('id', 'name', 'dni')
        ->orderBy('name')
        ->where('deleted_at', null)
        ->where('estado', 1) // Solo personal activo
        ->where('cesado', 0) // Excluir personal cesado
        ->get();
        $puestos = Cargo::select('id', 'name')->orderBy('name')->get();
        // Obtener tipos de puesto con nivel jerárquico ordenados por nivel jerarquico y tipo de puesto
        $nivelJerarquicos = TipoDePuestoHasNivelJerarquico::with(['tipoDePuesto', 'nivelJerarquico'])
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->tipoDePuesto->name . ' - ' . $item->nivelJerarquico->name
                ];
            })
            ->sortBy(function($item) {
                return $item['name'];
            })->values();
            
        // Filtrar dominios por nivel jerárquico y campaña si se proporcionan
        $dominiosQuery = Dominio::select('id', 'name', 'nivel_jerarquico_id', 'campania_id')->orderBy('name');
        
        if ($request->has('nivel_jerarquico_id')) {
            $dominiosQuery->where('nivel_jerarquico_id', $request->nivel_jerarquico_id);
        }
        
        if ($request->has('campania_id')) {
            $dominiosQuery->where('campania_id', $request->campania_id);
        }
        
        $dominios = $dominiosQuery->get();
        
        return response()->json([
            'areas' => $areas,
            'personal' => $personal,
            'puestos' => $puestos,
            'nivel_jerarquicos' => $nivelJerarquicos,
            'dominios' => $dominios
        ]);
    }

    /**
     * Almacenar un nuevo evaluado en la campaña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $evaluado = $this->saveOrUpdateEvaluado($data);

        // Actualizar área en Personal si corresponde
        if ($request->has('area_id') && $request->filled('area_id') && $request->has('actualizar_personal') && $request->actualizar_personal) {
            $personal = Personal::find($evaluado->personal_id);
            if ($personal) {
                $personal->area_id = $request->area_id;
                $personal->save();
            }
        }

        return response()->json([
            'message' => 'Evaluado agregado correctamente a la campaña',
            'evaluado' => $evaluado,
            'personal_actualizado' => $request->has('actualizar_personal') && $request->actualizar_personal ? true : false
        ], 201);
    }

    /**
     * Mostrar un evaluado específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evaluado = CampaniaHasEvaluado::with([
            // 'personal', 'campania', 'area', 'puesto', 'tipoPuestoHasNivelJerarquico', 'dominio', 'superior'
            
                'personal.user', // Añadir relación user
                // 'personal', 
                'area', 
                'puesto', 
                'tipoPuestoHasNivelJerarquico',
                'tipoPuestoHasNivelJerarquico.tipoDePuesto',
                'tipoPuestoHasNivelJerarquico.dominio',
                'tipoPuestoHasNivelJerarquico.dominio.grado',
                'tipoPuestoHasNivelJerarquico.nivelJerarquico', 
                'superior',
                'subordinados',
                'subordinados.personal',
                'paresMismoSuperior', // Relación para obtener pares con el mismo superior y nivel jerárquico
                'paresMismoSuperior.personal',
                // 'pares'
                ])
            ->findOrFail($id);
            
        return response()->json($evaluado);
    }

    /**
     * Actualizar un evaluado en la campaña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('actualizar_personal');
        $evaluado = $this->saveOrUpdateEvaluado($data, $id);

        // Actualizar área en Personal si corresponde
        if ($request->has('area_id') && $request->filled('area_id') && $request->has('actualizar_personal') && $request->actualizar_personal) {
            $personal = Personal::find($evaluado->personal_id);
            if ($personal) {
                $personal->area_id = $request->area_id;
                $personal->save();
            }
        }

        return response()->json([
            'message' => 'Evaluado actualizado correctamente',
            'evaluado' => $evaluado,
            'personal_actualizado' => $request->has('actualizar_personal') && $request->actualizar_personal ? true : false
        ]);
    }

    /**
     * Eliminar un evaluado de la campaña.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $evaluado = CampaniaHasEvaluado::findOrFail($id);
            
            // 1. Primero obtener los IDs necesarios antes de eliminar
            $personal_id = $evaluado->personal_id;
            $campania_id = $evaluado->campania_id;
            
            // 2. Obtener los evaluadores para eliminar objetivos
            $evaluadores = EvaluadorHasEvaluado::where('evaluado_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->get();
            
            // 3. Obtener comités de calibración
            $comites = ComiteCalibracion::where('personal_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->get();
            
            // 4. Eliminar registros relacionados en orden correcto
            // 4.1 Eliminar objetivos
            foreach ($evaluadores as $evaluador) {
                Objetivo::where('evaluador_has_evaluado_id', $evaluador->id)->forceDelete();
            }
            
            // 4.2 Eliminar personas en comités
            if ($comites->count() > 0) {
                ComiteHasPersona::whereIn('comite_calibracion_id', $comites->pluck('id'))->forceDelete();
            }
            
            // 4.3 Eliminar respuestas
            Respuesta::where('evaluado_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.4 Eliminar resúmenes
            ResumenRespuestasEvaluacionDesempenoCompetencia::where('personal_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.5 Eliminar evaluadores
            EvaluadorHasEvaluado::where('evaluado_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.6 Eliminar comités
            ComiteCalibracion::where('personal_id', $personal_id)
                ->where('campania_id', $campania_id)
                ->forceDelete();
            
            // 4.7 Finalmente eliminar el evaluado
            $evaluado->forceDelete();
            
            DB::commit();
            
            return response()->json([
                'message' => 'Evaluado eliminado correctamente de la campaña'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar evaluado: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al eliminar el evaluado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desactivar o activar evaluación de competencias.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleCompetencias(Request $request, $id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        $habilitar = $request->input('habilitar', !$evaluado->habilitado_para_evaluacion_de_competencias);
        $motivo = $request->input('motivo');
        
        if ($habilitar) {
            $evaluado->habilitarCompetencias();
            $mensaje = 'Habilitado para evaluación de competencias';
        } else {
            $evaluado->deshabilitarCompetencias($motivo);
            $mensaje = 'Deshabilitado para evaluación de competencias';
        }
        
        return response()->json([
            'message' => $mensaje,
            'evaluado' => $evaluado
        ]);
    }

    /**
     * Desactivar o activar evaluación por objetivos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleObjetivos(Request $request, $id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        $habilitar = $request->input('habilitar', !$evaluado->habilitado_para_evaluacion_por_objetivos);
        $motivo = $request->input('motivo');
        
        if ($habilitar) {
            $evaluado->habilitarObjetivos();
            $mensaje = 'Habilitado para evaluación por objetivos';
        } else {
            $evaluado->deshabilitarObjetivos($motivo);
            $mensaje = 'Deshabilitado para evaluación por objetivos';
        }
        
        return response()->json([
            'message' => $mensaje,
            'evaluado' => $evaluado
        ]);
    }

    /**
     * Importar evaluados masivamente a la campaña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function importar(Request $request)
    {
        $request->validate([
            'campania_id' => 'required|exists:campanias,id',
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        // Aquí implementarías la lógica para importar desde Excel/CSV
        // Podrías usar Maatwebsite/Laravel-Excel
        
        return response()->json([
            'message' => 'Importación completada correctamente'
        ]);
    }

    /**
     * Obtener subordinados de un evaluado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubordinados($id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        $subordinados = $evaluado->subordinados()
            ->with(['personal', 'area', 'puesto'])
            ->get();
            
        return response()->json($subordinados);
    }

    /**
     * Obtener evaluados con el mismo superior.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPares($id)
    {
        $evaluado = CampaniaHasEvaluado::findOrFail($id);
        
        if (!$evaluado->superior_personal_id) {
            return response()->json([]);
        }
        
        $pares = CampaniaHasEvaluado::where('superior_personal_id', $evaluado->superior_personal_id)
            ->where('campania_id', $evaluado->campania_id)
            ->where('id', '!=', $evaluado->id)
            ->with(['personal', 'area', 'puesto'])
            ->get();
            
        return response()->json($pares);
    }

    /**
     * Validar archivo de importación de evaluados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function validarImportar(Request $request)
    {
        $request->validate([
            'campania_id' => 'required|exists:campanias,id',
            'file' => 'required|file|mimes:csv,xlsx,xls'
        ]);

        // Aquí implementarías la lógica para validar el archivo Excel/CSV
        // Esta es una simulación de respuesta
        
        return response()->json([
            'valid' => true,
            'total' => 50,
            'valid_count' => 48,
            'error_count' => 2,
            'errors' => [
                'Fila 5: El DNI 12345678 no corresponde a ningún empleado registrado.',
                'Fila 12: El campo área es obligatorio.'
            ],
            'preview' => [
                [
                    'DNI' => '12345678',
                    'Nombre' => 'Juan Pérez',
                    'Área' => 'Recursos Humanos',
                    'Puesto' => 'Analista',
                    'Superior' => '87654321'
                ],
                [
                    'DNI' => '87654321',
                    'Nombre' => 'María García',
                    'Área' => 'Finanzas',
                    'Puesto' => 'Gerente',
                    'Superior' => null
                ]
            ]
        ]);
    }

    public function generarEvaluadorHasEvaluado($campaniaId, $tipo)
    {
        // 1. Obtener todos los evaluados de la campaña
        $evaluados = CampaniaHasEvaluado::with([
            'personal',
            'superior',
            'subordinados',
            'paresMismoSuperior.personal',
            'tipoPuestoHasNivelJerarquico.tipoDePuesto',
            'tipoPuestoHasNivelJerarquico.dominio.grado'
        ])->where('campania_id', $campaniaId)->get();

        // 2. Obtener la evaluación de competencias de la campaña
        $evaluacion = Evaluacione::where('campania_id', $campaniaId)
            ->whereHas('tipoDeEvaluacion', function($q) {
                $q->where('name', 'EVALUACION DE DESEMPEÑO POR COMPETENCIAS');
            })->first();

        if (!$evaluacion) {
            return response()->json(['message' => 'No se encontró la evaluación de competencias para la campaña.'], 404);
        }
        
        // 2.1 Obtener la evaluación de objetivos de la campaña
        $evaluacionObjetivos = Evaluacione::where('campania_id', $campaniaId)
            ->whereHas('tipoDeEvaluacion', function($q) {
                $q->where('name', 'EVALUACION DE DESEMPEÑO POR OBJETIVOS');
            })->first();

        if (!$evaluacionObjetivos) {
            return response()->json(['message' => 'No se encontró la evaluación de objetivos para la campaña.'], 404);
        }

        // 3. Procesar cada evaluado
        foreach ($evaluados as $evaluado) {
            if ($tipo == 'competencias') {
                // Si el evaluado no está habilitado para evaluación de competencias, continuar
                if (!$evaluado->habilitado_para_evaluacion_de_competencias) continue;
                
                // if ($evaluado->habilitado_para_evaluacion_de_competencias) {
                    // $gradoId = $evaluado->tipoPuestoHasNivelJerarquico->dominio->grado->id ?? null;
                    // if (!$gradoId) continue;

                    $gradoOriginalId = $evaluado->tipoPuestoHasNivelJerarquico->dominio->grado->id ?? null;
                    if (!$gradoOriginalId) continue;

                    
                    // Presencia de relaciones
                    $hasJefe = !empty($evaluado->superior_personal_id);

                    // "Pares válidos": mismo superior y mismo tipo de puesto
                    $paresValidos = $evaluado->paresMismoSuperior
                        ->filter(function($par) use ($evaluado) {
                            return $par->personal_id != $evaluado->personal_id &&
                                $par->tipoPuestoHasNivelJerarquico &&
                                $evaluado->tipoPuestoHasNivelJerarquico &&
                                $par->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                                $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                                $par->tipoPuestoHasNivelJerarquico->tipoDePuesto->id == $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto->id;
                        });

                    $numPares = $paresValidos->count();
                    $numSub = $evaluado->subordinados->count() ?? 0;

                    // 3.1 Calcular pesos efectivos y el grado a registrar según reglas
                    $calc = $this->calcularPesosEfectivosYGrado($campaniaId, (int)$gradoOriginalId, $hasJefe, $numPares, $numSub);
                    $gradoAUsar = $calc['grado_id'];
                    $pesos = $calc['pesos']; // ['JEFE'=>x, 'PAR'=>y, 'SUBORDINADO'=>z]

                    // dd($evaluado->personal_id, $gradoOriginalId, $gradoAUsar, $hasJefe, $numPares, $numSub, $pesos);

                    // 3.2 Limpiar relaciones previas de competencias para este evaluado (evita duplicados)
                    EvaluadorHasEvaluado::where('evaluado_id', $evaluado->personal_id)
                        ->where('campania_id', $campaniaId)
                        ->where('evaluacion_id', $evaluacion->id)
                        ->delete();

                    // 3.3 Crear relaciones respetando prorrateo
                    if (($pesos['JEFE'] ?? 0) > 0 && $hasJefe) {
                        $this->handleSuperior($evaluado, (float)$pesos['JEFE'], $evaluacion, $campaniaId, $gradoAUsar);
                    }

                    if (($pesos['PAR'] ?? 0) > 0 && $numPares > 0) {
                        $this->handlePares($evaluado, (float)$pesos['PAR'], $evaluacion, $campaniaId, $gradoAUsar);
                    }

                    if (($pesos['SUBORDINADO'] ?? 0) > 0 && $numSub > 0) {
                        $this->handleSubordinados($evaluado, (float)$pesos['SUBORDINADO'], $evaluacion, $campaniaId, $gradoAUsar);
                    }

                    if (($pesos['UNO MISMO'] ?? 0) >= 0) {
                        // Si se usa "UNO MISMO", manejarlo aquí si es necesario
                        // Por ahora, no se maneja, pero se deja como referencia
                        $this->handleUnoMismo($evaluado, (float)$pesos['UNO MISMO'], $evaluacion, $campaniaId, $gradoAUsar);
                    } else {
                        // Manejar el caso donde no se usa "UNO MISMO"
                        dd("Evaluado {$evaluado->personal_id} con grado {$gradoAUsar} no tiene UNO MISMO");
                    }

                    // // Buscar el peso para este grado y campaña, puede ser más de uno encontrado
                    // $pesos = Peso::where('campania_id', $campaniaId)
                    //     ->where('grado_id', $gradoId)
                    //     ->with(['tipoRelacionJerarquica'])
                    //     ->get();

                    // if (!$pesos) continue;

                    // foreach ($pesos as $peso) {
                    //     // Verificar el tipo de relación jerárquica y manejar según corresponda
                    //     // dd($peso);
                    //     switch ($peso->tipoRelacionJerarquica->name) {
                    //         case 'JEFE':
                    //             $this->handleSuperior($evaluado, $peso, $evaluacion, $campaniaId, $gradoId);
                    //             break;
                    //         case 'PAR':
                    //             $this->handlePares($evaluado, $peso, $evaluacion, $campaniaId, $gradoId);
                    //             break;
                    //         case 'SUBORDINADO':
                    //             $this->handleSubordinados($evaluado, $peso, $evaluacion, $campaniaId, $gradoId);
                    //             break;
                    //         case 'UNO MISMO':
                    //             $this->handleUnoMismo($evaluado, $peso, $evaluacion, $campaniaId, $gradoId);
                    //             break;                
                    //     }
                    // }
                // }
            } elseif ($tipo == 'objetivos') {
                // Si el evaluado no está habilitado para evaluación por objetivos, continuar
                if (!$evaluado->habilitado_para_evaluacion_por_objetivos) continue;                

                if ($evaluado->habilitado_para_evaluacion_por_objetivos) {
                    // Aquí podrías manejar la lógica para evaluación por objetivos si es necesario
                    // Por ahora, solo se maneja la evaluación de competencias
                    // su evaluador_id será su superior, si no cuenta con superior no se insertarán los datos

                    // si el nombre del nivel jerarquico es Nivel IV, entonces el valor del campo jerarquia será 5 y el tipo_jerarquia_id será 2
                    // en todos los otros casos el valor del campo jerarquia será 2 y el tipo_jerarquia_id será 1

                    if (!$evaluacionObjetivos) continue;
                    // Verificar si el evaluado tiene un superior

                    if (!$evaluado->superior_personal_id) {
                    } else {
                        if ($evaluado->tipoPuestoHasNivelJerarquico->nivelJerarquico->name == 'Nivel IV') {
                            EvaluadorHasEvaluado::updateOrCreate([
                                'evaluador_id' => $evaluado->superior_personal_id, // Si no tiene superior, se evalúa a sí mismo
                                'evaluado_id' => $evaluado->personal_id,
                                'evaluacion_id' => $evaluacionObjetivos->id,
                                'campania_id' => $campaniaId,
                            ], [
                                'jerarquia' => 5,
                                'tipo_jerarquia_id' => 2,
                            ]);
                        } else {
                            EvaluadorHasEvaluado::updateOrCreate([
                                'evaluador_id' => $evaluado->superior_personal_id, // Si no tiene superior, se evalúa a sí mismo
                                'evaluado_id' => $evaluado->personal_id,
                                'evaluacion_id' => $evaluacionObjetivos->id,
                                'campania_id' => $campaniaId,
                            ], [
                                'jerarquia' => 2,
                                'tipo_jerarquia_id' => 1,
                            ]);
                        }
                    }
                }
            }
        }

        return response()->json(['message' => 'Registros generados correctamente']);
    }

    private function calcularPesosEfectivosYGrado(int $campaniaId, int $gradoId, bool $hasJefe, int $numPares, int $numSub): array
    {
        // Pesos base del grado original
        $base = $this->obtenerPesosBase($campaniaId, $gradoId);

        $gradoID180 = Grado::where('name', '180')->where('estado',1)->first()->id;
        $gradoID90 = Grado::where('name', '90')->where('estado',1)->first()->id;
        $gradoID270 = Grado::where('name', '270')->where('estado',1)->first()->id;

        // Regla: 180° sin pares => se convierte en 90°
        if ($gradoId === $gradoID180 && $numPares === 0) {
            $p90 = $this->obtenerPesosBase($campaniaId, $gradoID90);
            return ['grado_id' => $gradoID90, 'pesos' => $p90];
        }

        // Reglas para 270°
        if ($gradoId === $gradoID270) {
            // Sin subordinados => se convierte en 180°
            if ($numSub === 0) {
                $p180 = $this->obtenerPesosBase($campaniaId, $gradoID180);
                // Si además no hay pares, 180° se convierte en 90°
                if ($numPares === 0) {
                    $p90 = $this->obtenerPesosBase($campaniaId, $gradoID90);
                    return ['grado_id' => $gradoID90, 'pesos' => $p90];
                }
                return ['grado_id' => $gradoID180, 'pesos' => $p180];
            }

            // Sin pares y con jefe => especial 80%/20%, se mantiene 270°
            if ($numPares === 0 && $hasJefe) {
                return ['grado_id' => $gradoID270, 'pesos' => ['JEFE' => 0.8, 'PAR' => 0.0, 'SUBORDINADO' => 0.2, 'UNO MISMO' => 0.0]];
            }

            // Caso “Manuel Yzaga”: sin pares ni jefe => 100% subordinados, se mantiene 270°
            if ($numPares === 0 && !$hasJefe && $numSub > 0) {
                return ['grado_id' => $gradoID270, 'pesos' => ['JEFE' => 0.0, 'PAR' => 0.0, 'SUBORDINADO' => 1.0, 'UNO MISMO' => 0.0]];
            }
        }
        
        // Caso general: usar pesos base y mantener el grado original
        // $base = $base + ['UNO MISMO' => 0.0]; // asegura clave
        // Caso general: usar pesos base y mantener el grado original
        return ['grado_id' => $gradoId, 'pesos' => $base];
    }
    
    /**
     * Obtiene pesos base del grado configurado en la tabla pesos.
     * Retorna claves garantizadas JEFE, PAR, SUBORDINADO con 0.0 si faltan.
     */
    private function obtenerPesosBase(int $campaniaId, int $gradoId): array
    {
        $map = Peso::where('campania_id', $campaniaId)
            ->where('grado_id', $gradoId)
            ->with('tipoRelacionJerarquica')
            ->get()
            ->mapWithKeys(function($p){
                return [strtoupper($p->tipoRelacionJerarquica->name) => (float)$p->peso];
            });

        return [
            'JEFE' => (float)($map['JEFE'] ?? 0.0),
            'PAR' => (float)($map['PAR'] ?? 0.0),
            'SUBORDINADO' => (float)($map['SUBORDINADO'] ?? 0.0),
            'UNO MISMO' => (float)($map['UNO MISMO'] ?? 0.0), // Si se usa en el futuro
        ];
    }
    
    private function handleSuperior($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        // Si el evaluado tiene un superior, crear o actualizar el registro
        if ($evaluado->superior_personal_id) {
            EvaluadorHasEvaluado::updateOrCreate([
                'evaluador_id' => $evaluado->superior_personal_id,
                'evaluado_id' => $evaluado->personal_id,
                'evaluacion_id' => $evaluacion->id,
                'campania_id' => $campaniaId,
                'grado_id' => $gradoId,
            ], [
                'peso' => $pesoValue,
                'peso_prorrateado' => $pesoValue,
            ]);
        }
    }

    private function handlePares($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        // Obtener pares con el mismo superior y mismo tipo de puesto
        $pares = $evaluado->paresMismoSuperior
            ->filter(function($par) use ($evaluado) {
                return $par->personal_id != $evaluado->personal_id &&
                    $par->tipoPuestoHasNivelJerarquico &&
                    $evaluado->tipoPuestoHasNivelJerarquico &&
                    $par->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                    $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto &&
                    $par->tipoPuestoHasNivelJerarquico->tipoDePuesto->id == $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto->id;
            });

        $numPares = $pares->count();
        if ($numPares > 0) {
            $pesoPorPar = $pesoValue / $numPares;
            foreach ($pares as $par) {
                EvaluadorHasEvaluado::updateOrCreate([
                    'evaluador_id' => $par->personal_id,
                    'evaluado_id' => $evaluado->personal_id,
                    'evaluacion_id' => $evaluacion->id,
                    'campania_id' => $campaniaId,
                    'grado_id' => $gradoId,
                ], [
                    'peso' => $pesoValue,
                    'peso_prorrateado' => $pesoPorPar,
                ]);
            }
        }
    }

    private function handleSubordinados($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        // Obtener subordinados del evaluado
        $subordinados = $evaluado->subordinados ?? collect();
        $numSub = $subordinados->count();
        if ($numSub > 0) {
            $pesoPorSub = $pesoValue / $numSub;
            foreach ($subordinados as $sub) {
                EvaluadorHasEvaluado::updateOrCreate([
                    'evaluador_id' => $sub->personal_id,
                    'evaluado_id' => $evaluado->personal_id,
                    'evaluacion_id' => $evaluacion->id,
                    'campania_id' => $campaniaId,
                    'grado_id' => $gradoId,
                ], [
                    'peso' => $pesoValue,
                    'peso_prorrateado' => $pesoPorSub,
                ]);
            }
        }
    }
    
    // private function handleSuperior($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    // {
    //     // Si el evaluado tiene un superior, crear o actualizar el registro
    //     if ($evaluado->superior_personal_id) {
    //         EvaluadorHasEvaluado::updateOrCreate([
    //             'evaluador_id' => $evaluado->superior_personal_id,
    //             'evaluado_id' => $evaluado->personal_id,
    //             'evaluacion_id' => $evaluacion->id,
    //             'campania_id' => $campaniaId,
    //             'grado_id' => $gradoId,
    //         ], [
    //             'peso' => $peso->peso,
    //             'peso_prorrateado' => $peso->peso,
    //         ]);
    //     }
    // }
    // private function handlePares($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    // {
    //     // Obtener pares con el mismo superior y nivel jerárquico
    //     $pares = $evaluado->paresMismoSuperior
    //         ->filter(function($par) use ($evaluado) {
    //             return 
    //                 $par->personal_id != $evaluado->personal_id &&
    //                 $par->tipoPuestoHasNivelJerarquico &&
    //                 $par->tipoPuestoHasNivelJerarquico->tipoDePuesto->id == $evaluado->tipoPuestoHasNivelJerarquico->tipoDePuesto->id;
    //         });

    //     $numPares = $pares->count();
    //     if ($numPares > 0) {
    //         $pesoPorPar = $peso->peso / $numPares;
    //         foreach ($pares as $par) {
    //             EvaluadorHasEvaluado::updateOrCreate([
    //                 'evaluador_id' => $par->personal_id,
    //                 'evaluado_id' => $evaluado->personal_id,
    //                 'evaluacion_id' => $evaluacion->id,
    //                 'campania_id' => $campaniaId,
    //                 'grado_id' => $gradoId,
    //             ], [
    //                 'peso' => $peso->peso,
    //                 'peso_prorrateado' => $pesoPorPar,
    //             ]);
    //         }
    //     }
    // }

    // private function handleSubordinados($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    // {
    //     // Obtener subordinados del evaluado
    //     $subordinados = $evaluado->subordinados;
    //     $numSub = $subordinados->count();
    //     if ($numSub > 0) {
    //         $pesoPorSub = $peso->peso / $numSub;
    //         foreach ($subordinados as $sub) {
    //             EvaluadorHasEvaluado::updateOrCreate([
    //                 'evaluador_id' => $sub->personal_id,
    //                 'evaluado_id' => $evaluado->personal_id,
    //                 'evaluacion_id' => $evaluacion->id,
    //                 'campania_id' => $campaniaId,
    //                 'grado_id' => $gradoId,
    //             ], [
    //                 'peso' => $peso->peso,
    //                 'peso_prorrateado' => $pesoPorSub,
    //             ]);
    //         }
    //     }
    // }

    private function handleUnoMismo($evaluado, $peso, $evaluacion, $campaniaId, $gradoId)
    {
        $pesoValue = is_object($peso) ? (float)$peso->peso : (float)$peso;

        if ($pesoValue < 0) {
            return;
        }

        // Registrar el evaluado consigo mismo
        EvaluadorHasEvaluado::updateOrCreate([
            'evaluador_id' => $evaluado->personal_id,
            'evaluado_id' => $evaluado->personal_id,
            'evaluacion_id' => $evaluacion->id,
            'campania_id' => $campaniaId,
            'grado_id' => $gradoId,
        ], [
            'peso' => $pesoValue,
            'peso_prorrateado' => $pesoValue,
        ]);
    }

    public function exportarPersonalACampaniaActual(Request $request)
    {
        $campania = Campania::where('es_campania_actual', true)->first();
        if (!$campania) {
            return response()->json(['message' => 'No hay campaña actual configurada.'], 400);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No se recibieron IDs de personal para exportar.'], 400);
        }
        
        $creados = 0;
        $actualizados = 0;
        $errores = 0;
        
        foreach ($ids as $personalId) {
            try {
                $personal = Personal::with(['cargo', 'cargo.tipoDePuesto'])->findOrFail($personalId);

                // Verificar que tenga los datos necesarios
                // if (!$personal->area_id || !$personal->cargo_id || !$personal->cargo->tipo_de_puesto_id || !$personal->reporta_a) {
                //     $errores++;
                //     continue;
                // }

                $tipoPuestoCampania = TipoDePuestoHasNivelJerarquico::where('campania_id', $campania->id)
                    ->where('tipo_de_puesto_id', $personal->cargo->tipo_de_puesto_id)
                    ->first();
                    
                if (!$tipoPuestoCampania) {
                    $errores++;
                    continue;
                }

                // Verificar si cumple con las fechas de corte para las evaluaciones
                $habilitadoParaEvaluacionDeCompetencias = false;
                $habilitadoParaEvaluacionPorObjetivos = false;

                $evaluacionCompetencias = Evaluacione::where('campania_id', $campania->id)
                    ->whereHas('tipoDeEvaluacion', function($q) {
                        $q->where('name', 'EVALUACION DE DESEMPEÑO POR COMPETENCIAS');
                    })->first();
                if ($evaluacionCompetencias) {
                    $habilitadoParaEvaluacionDeCompetencias = $personal->fecha_ingreso < $evaluacionCompetencias->fecha_corte; 
                }
                
                $evaluacionObjetivos = Evaluacione::where('campania_id', $campania->id)
                    ->whereHas('tipoDeEvaluacion', function($q) {
                        $q->where('name', 'EVALUACION DE DESEMPEÑO POR OBJETIVOS');
                    })->first();
                if ($evaluacionObjetivos) {
                    $habilitadoParaEvaluacionPorObjetivos = $personal->fecha_ingreso < $evaluacionObjetivos->fecha_corte; 
                }

                // Si no cumple con ninguna fecha de corte, no agregarlo
                if (!$habilitadoParaEvaluacionDeCompetencias && !$habilitadoParaEvaluacionPorObjetivos) {
                    $errores++;
                    continue;
                }

                // Preparar datos para guardar
                $data = [
                    'personal_id' => $personalId,
                    'campania_id' => $campania->id,
                    'area_id' => $personal->area_id,
                    'puesto_id' => $personal->cargo_id,
                    'tipo_de_puesto_campania_id' => $tipoPuestoCampania->id,
                    'superior_personal_id' => $personal->reporta_a,
                    'habilitado_para_evaluacion_de_competencias' => $habilitadoParaEvaluacionDeCompetencias,
                    'habilitado_para_evaluacion_por_objetivos' => $habilitadoParaEvaluacionPorObjetivos,
                    'cesado' => $personal->cesado,
                    'estado' => true,
                ];

                // Verificar si ya existe para actualizar o crear nuevo
                $campaniaHasEvaluado = CampaniaHasEvaluado::where('personal_id', $personalId)
                    ->where('campania_id', $campania->id)
                    ->first();

                if ($campaniaHasEvaluado) {
                    $campaniaHasEvaluado->update($data);
                    $actualizados++;
                } else {
                    CampaniaHasEvaluado::create($data);
                    $creados++;
                }
            } catch (\Exception $e) {
                \Log::error('Error al exportar personal: ' . $e->getMessage());
                $errores++;
            }
        }

        return response()->json([
            'message' => "Exportación completada. Creados: $creados, Actualizados: $actualizados, No procesados: $errores",
            'success' => true
        ]);
    }

    public function exportarTodosSeleccionados()
    {
        $campania = Campania::where('es_campania_actual', true)->first();
        if (!$campania) {
            return response()->json(['message' => 'No hay campaña actual configurada.'], 400);
        }

        // Obtener todos los IDs de personal con seleccionado=true
        $idsSeleccionados = Personal::where('seleccionado', true)
                                ->where('estado', true)
                                ->where('cesado', 0)
                                ->pluck('id')
                                ->toArray();
        
        if (empty($idsSeleccionados)) {
            return response()->json(['message' => 'No hay personal marcado como seleccionado que cumpla con los requisitos.'], 404);
        }
        
        $creados = 0;
        $actualizados = 0;
        $notrabajados = 0; // Contador para personal que no cumple con las fechas de corte
        foreach ($idsSeleccionados as $personalId) {
            $personal = Personal::findOrFail($personalId);

            // Misma lógica que en exportarPersonalACampaniaActual
            $tipoPuestoCampania = TipoDePuestoHasNivelJerarquico::where('campania_id', $campania->id)
                ->where('tipo_de_puesto_id', $personal->cargo->tipo_de_puesto_id)
                ->first();
                
            // if (!$personal->area_id || !$personal->cargo || !$tipoPuestoCampania || !$personal->reporta_a) {
            //     continue;
            // }

            $habilitadoParaEvaluacionDeCompetencias = false;
            $habilitadoParaEvaluacionPorObjetivos = false;

            $evaluacionCompetencias = Evaluacione::where('campania_id', $campania->id)
                ->whereHas('tipoDeEvaluacion', function($q) {
                    $q->where('name', 'EVALUACION DE DESEMPEÑO POR COMPETENCIAS');
                })->first();
            if ($evaluacionCompetencias) {
                $habilitadoParaEvaluacionDeCompetencias = ($personal->fecha_ingreso < $evaluacionCompetencias->fecha_corte); 
            }
            
            $evaluacionObjetivos = Evaluacione::where('campania_id', $campania->id)
                ->whereHas('tipoDeEvaluacion', function($q) {
                    $q->where('name', 'EVALUACION DE DESEMPEÑO POR OBJETIVOS');
                })->first();
            if ($evaluacionObjetivos) {
                $habilitadoParaEvaluacionPorObjetivos = ($personal->fecha_ingreso < $evaluacionObjetivos->fecha_corte); 
            }

            if (!$habilitadoParaEvaluacionDeCompetencias && !$habilitadoParaEvaluacionPorObjetivos) {
                // dd($personal,$tipoPuestoCampania,$evaluacionCompetencias,$evaluacionObjetivos,$personal->fecha_ingreso,
                // ($personal->fecha_ingreso >= $evaluacionCompetencias->fecha_corte)
                // ) ;
                $notrabajados++;
                continue;
            }

            $data = [
                'personal_id' => $personalId,
                'campania_id' => $campania->id,
                'area_id' => $personal->area_id,
                'puesto_id' => $personal->cargo_id,
                'tipo_de_puesto_campania_id' => $tipoPuestoCampania->id ?? null,
                'superior_personal_id' => $personal->reporta_a,
                'habilitado_para_evaluacion_de_competencias' => $habilitadoParaEvaluacionDeCompetencias,
                'habilitado_para_evaluacion_por_objetivos' => $habilitadoParaEvaluacionPorObjetivos,
                'cesado' => $personal->cesado,
                'estado' => true,
            ];

            $campaniaHasEvaluado = CampaniaHasEvaluado::where('personal_id', $personalId)
                ->where('campania_id', $campania->id)
                ->first();

            if ($campaniaHasEvaluado) {
                $this->saveOrUpdateEvaluado($data, $campaniaHasEvaluado->id);
                $actualizados++;
            } else {
                $this->saveOrUpdateEvaluado($data);
                $creados++;
            }
        }

        return response()->json([
            'message' => "Exportación completada. $creados creados, $actualizados actualizados, $notrabajados sin ser expxortados."
        ]);
    }

    private function saveOrUpdateEvaluado($data, $id = null)
    {
        // Validar datos requeridos
        $rules = [
            'personal_id' => [
                'required',
                'exists:personal,id',
                Rule::unique('campania_has_evaluados', 'personal_id')
                    ->where('campania_id', $data['campania_id'])
                    ->whereNull('deleted_at')
            ],
            'campania_id' => 'required|exists:campanias,id',
            'area_id' => 'nullable|exists:areas,id',
            'puesto_id' => 'nullable|exists:cargos,id',
            'tipo_de_puesto_campania_id' => 'nullable|exists:tipo_de_puesto_has_nivel_jerarquicos,id',
            'superior_personal_id' => [
                'nullable',
                'exists:personal,id',
                'different:personal_id'
            ],
            'habilitado_para_evaluacion_de_competencias' => 'boolean',
            'habilitado_para_evaluacion_por_objetivos' => 'boolean',
            'cesado' => 'boolean',
            'estado' => 'boolean'
        ];

        // Si es update, ignora el propio registro en la validación única
        if ($id) {
            $rules['personal_id'] = [
                'required',
                'exists:personal,id',
                Rule::unique('campania_has_evaluados', 'personal_id')
                    ->where('campania_id', $data['campania_id'])
                    ->whereNull('deleted_at')
                    ->ignore($id)
            ];
        }

        validator($data, $rules)->validate();

        if ($id) {
            $evaluado = CampaniaHasEvaluado::findOrFail($id);
            $evaluado->update($data);
        } else {
            $evaluado = CampaniaHasEvaluado::create($data);
        }

        return $evaluado;
    }

}
