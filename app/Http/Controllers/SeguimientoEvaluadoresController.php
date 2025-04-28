<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\PlanesConfiguracion;
use App\Models\TipoDeEvaluacione;
use Illuminate\Http\Request;

class SeguimientoEvaluadoresController extends Controller
{

    public function index()
    {
        $campanias = Campania::orderBy('name', 'desc')->get();
        return view('seguimiento-evaluadores.index', compact('campanias'));
    }

    public function getData(Request $request)
    {
        if (!$request->has('campania_id') || $request->campania_id == '') {
            return response()->json([]);
        }

        // Obtener evaluadores únicos con sus evaluaciones y planes
        $evaluadores = 
        Personal::whereHas('evaluacionesComoEvaluador', function($query) use ($request) {
            $query->whereHas('evaluacion', function($q) use ($request) {
                $q->where('campania_id', $request->campania_id);
            });
        })
        ->orWhereHas('planesComoEncargado', function($query) use ($request) {
            $query->whereHas('plan_de_mejora', function($q) use ($request) {
                $q->where('campania_id', $request->campania_id);
            })->where('habilitado', 1);
        })
        ->with([
            'evaluacionesComoEvaluador' => function($query) use ($request) {
                $query->whereHas('evaluacion', function($q) use ($request) {
                    $q->where('campania_id', $request->campania_id);
                });
            },
            'evaluacionesComoEvaluador.objetivosRegistrados',
            'evaluacionesComoEvaluador.objetivosRealizados',
            'planesComoEncargado' => function($query) use ($request) {
                $query->where('habilitado', 1)
                    ->whereHas('plan_de_mejora', function($q) use ($request) {
                        $q->where('campania_id', $request->campania_id);
                    });
            },
            'planesComoEncargado.planesDeMejora'
        ])
        ->get()
        ->map(function($evaluador) {
            // Agrupar estadísticas de competencias
            $competencias = $evaluador->evaluacionesComoEvaluador
                ->where('evaluacion.tipo_de_evaluacion_id', 1);
            
            // Agrupar estadísticas de objetivos
            $objetivos = $evaluador->evaluacionesComoEvaluador
                ->where('evaluacion.tipo_de_evaluacion_id', 2);
            
            // Agrupar estadísticas de planes
            $planes = $evaluador->planesComoEncargado;

            return [
                'id' => $evaluador->id,
                'evaluador' => $evaluador->name,
                'area' => $evaluador->area->name ?? 'Sin área',
                'competencias' => [
                    $competencias->where('realizado', 1)->count(),
                    $competencias->count()
                ],
                'objetivos_fase1' => [
                    'registrados' => $objetivos->sum(function($eval) {
                        return $eval->objetivosRegistrados->count() >= $eval->cantidad_requerida;
                    }),
                    'requeridos' => $objetivos->count(),
                    'completo' => $objetivos->every(function($eval) {
                        return $eval->objetivosRegistrados->count() >= $eval->cantidad_requerida;
                    })
                ],
                'objetivos_fase2' => [
                    'realizados' => $objetivos->sum(function($eval) {
                        return $eval->objetivosRealizados->where('grupal', 0)->count() >= $eval->objetivosRegistrados->where('grupal', 0)->count();
                    }),
                    'total' => $objetivos->count(),
                    // $objetivos->sum(function($eval) {
                    //     return $eval->objetivosRegistrados->where('grupal', 0)->count();
                    // }),
                    'completo' => $objetivos->every(function($eval) {
                        return $eval->objetivosRealizados->where('grupal', 0)->count() >= 
                            $eval->objetivosRegistrados->where('grupal', 0)->count();
                    })
                ],
                'planes_fase1' => [
                    'registrados' => $planes->sum(function($plan) {
                        return $plan->planesDeMejora->count() >= $plan->cantidad_requerida;
                    }),
                    'requeridos' => $planes->count(),
                    'completo' => $planes->every(function($plan) {
                        return $plan->planesDeMejora->count() >= $plan->cantidad_requerida;
                    })
                ],
                'planes_fase2' => [
                    'realizados' => $planes->sum(function($plan) {
                        return $plan->planesDeMejora->where('estado_id',"<>",1)->count() >= $plan->planesDeMejora->count();
                    }),
                    'total' => $planes->count(),
                    'completo' => $planes->every(function($plan) {
                        return $plan->planesDeMejora->where('estado_id',"<>",1)->count() >= $plan->cantidad_requerida;
                    })
                ]
            ];
        });

        return response()->json($evaluadores);
    }

    // public function getData(Request $request)
    // {
    //     $evaluadores = [];

    //     // Filtrar por campaña si se proporciona
    //     if (!$request->has('campania_id') || $request->campania_id == '') {
    //         return response()->json($evaluadores);
    //     }

    //     $query = EvaluadorHasEvaluado::query()
    //         ->select('evaluador_has_evaluados.*')
    //         ->groupBy('evaluador_has_evaluados.evaluador_id')
    //         ->whereNull('evaluador_has_evaluados.deleted_at')
    //         ->leftJoin('personal', 'personal.id', '=', 'evaluador_has_evaluados.evaluador_id')
    //         ->leftJoin('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
    //         ->with(['evaluador.area', 'objetivosRegistrados', 'objetivosRealizados', 'planesDeMejora']); // Cargar las relaciones
            
    //     // Filtrar por campaña si se proporciona
    //     if ($request->has('campania_id') && $request->campania_id != '') {
    //         $query->where('evaluaciones.campania_id', $request->campania_id);
    //     }
    //         // ->get()
            
    //     $evaluadores = $query->get()
    //     ->map(function($evaluador) {
    //         // Obtener conteos de forma segura
    //         $objetivosRegistrados = $evaluador->objetivosRegistrados ? $evaluador->objetivosRegistrados->count() : 0;
    //         $objetivosRealizados = $evaluador->objetivosRealizados ? $evaluador->objetivosRealizados->where('grupal', 0)->count() : 0;
    //         $totalObjetivosRegistrados = $evaluador->objetivosRegistrados ? $evaluador->objetivosRegistrados->where('grupal', 0)->count() : 0;
    //         $planesMejora = $evaluador->planesDeMejora ? $evaluador->planesDeMejora->count() : 0;
    //         $planesRealizados = $evaluador->planesDeMejora ? $evaluador->planesDeMejora->where('estado_id', 2)->count() : 0;
            
    //         return [
    //             'id' => $evaluador->id,
    //             'evaluador' => $evaluador->evaluador->name ?? 'No asignado',
    //             'area' => $evaluador->evaluador->area->nombre ?? 'Sin área',
    //             'competencias' => $this->getEstadisticasCompetencias($evaluador->evaluador_id),
    //             'objetivos_fase1' => [
    //                 'registrados' => $objetivosRegistrados,
    //                 'requeridos' => $evaluador->cantidad_requerida ?? 0,
    //                 'completo' => $objetivosRegistrados >= ($evaluador->cantidad_requerida ?? 0)
    //             ],
    //             'objetivos_fase2' => [
    //                 'realizados' => $objetivosRealizados,
    //                 'total' => $totalObjetivosRegistrados,
    //                 'completo' => $objetivosRealizados >= $totalObjetivosRegistrados
    //             ],
    //             'planes_fase1' => [
    //                 'registrados' => $planesMejora,
    //                 'requeridos' => $evaluador->cantidad_requerida_planes ?? 0,
    //                 'completo' => $planesMejora >= ($evaluador->cantidad_requerida_planes ?? 0)
    //             ],
    //             'planes_fase2' => [
    //                 'realizados' => $planesRealizados,
    //                 'total' => $planesMejora,
    //                 'completo' => $planesRealizados >= ($evaluador->cantidad_requerida_planes ?? 0)
    //             ]
    //         ];
    //     });

    //     // $evaluadores = $query->get()
    //     //     ->map(function($evaluador) {
                
    //     //     return [
    //     //         'id' => $evaluador->id,
    //     //         'evaluador' => $evaluador->evaluador->name ?? 'No asignado',
    //     //         'area' => $evaluador->evaluador->area->nombre ?? 'Sin área',
    //     //         'competencias' => $this->getEstadisticasCompetencias($evaluador->evaluador_id),
    //     //         'objetivos_fase1' => [
    //     //             'registrados' => $evaluador->objetivosRegistrados->count(),
    //     //             'requeridos' => $evaluador->cantidad_requerida,
    //     //             'completo' => $evaluador->objetivosRegistrados->count() >= $evaluador->cantidad_requerida
    //     //         ],
    //     //         'objetivos_fase2' => [
    //     //             'realizados' => $evaluador->objetivosRealizados->where('grupal', 0)->count(),
    //     //             'total' => $evaluador->objetivosRegistrados->where('grupal', 0)->count(),
    //     //             'completo' => $evaluador->objetivosRealizados->where('grupal', 0)->count() >= $evaluador->objetivosRegistrados->where('grupal', 0)->count()
    //     //         ],
    //     //         'planes_fase1' => [
    //     //             'registrados' => $evaluador->planesDeMejora->count(),
    //     //             'requeridos' => $evaluador->cantidad_requerida_planes,
    //     //             'completo' => $evaluador->planesDeMejora->count() >= $evaluador->cantidad_requerida_planes
    //     //         ],
    //     //         'planes_fase2' => [
    //     //             'realizados' => $evaluador->planesDeMejora->where('estado_id', 2)->count(),
    //     //             'total' => $evaluador->planesDeMejora->count(),
    //     //             'completo' => $evaluador->planesDeMejora->where('estado_id', 2)->count() >= $evaluador->cantidad_requerida_planes
    //     //         ]
    //     //     ];
    //     //         // return [
    //     //         //     'id' => $evaluador->id,
    //     //         //     'evaluador' => $evaluador->evaluador->name ?? 'No asignado',                    
    //     //         //     'avance' => $this->generarBarrasProgreso($evaluador->evaluador_id),
    //     //         //     'competencias' => $this->getEstadisticasCompetencias($evaluador->evaluador_id),
    //     //         //     'resultados' => $this->getEstadisticasResultados($evaluador->evaluador_id)
    //     //         // ];
    //     //     });

    //     return response()->json($evaluadores);
    // }

    private function generarBarrasProgreso($evaluadorId)
    {
        $barra = '';
        for ($i = 1; $i < 3; $i++) {
            if ($i == 2) {
                list($realizados, $total) = $this->getEstadisticasResultados($evaluadorId);
            } else {
                list($realizados, $total) = $this->getEstadisticasCompetencias($evaluadorId);
            }

            if ($total > 0) {
                $barra .= $this->generarBarraProgreso($realizados, $total, $i);
            }
        }
        return $barra;
    }

    private function getEstadisticasCompetencias($evaluadorId)
    {
        $realizados = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $evaluadorId)
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', 1)
            ->where('evaluador_has_evaluados.realizado', 1)
            ->count();
            
        $total = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $evaluadorId)
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', 1)
            ->count();
            
        return [$realizados, $total];
    }

    private function getEstadisticasResultados($evaluadorId)
    {
        $pendientes = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $evaluadorId)
            ->select('evaluador_has_evaluados.*')
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', 2)
            ->get()
            ->filter(function ($evaluador) {
                return $evaluador->estado_no_realizado;
            })->count();

        $total = EvaluadorHasEvaluado::where('evaluador_has_evaluados.evaluador_id', $evaluadorId)
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', 2)
            ->count();

        return [$total - $pendientes, $total];
    }

    private function generarBarraProgreso($realizados, $total, $tipoEvaluacion)
    {
        $porcentaje = ($realizados/$total)*100;
        $porcentaje = round($porcentaje, 2);
        
        $class = $realizados == 0 ? 'bg-white' : 
                ($total == $realizados ? 'bg-secondary' : 'bg-primary');
                
        if ($realizados == 0) {
            $porcentaje = 100;
        }
        
        $tipo = TipoDeEvaluacione::find($tipoEvaluacion);
        
        return "
            <h5 class=\"\">" . ucfirst(mb_strtolower($tipo->name)) . "</h5>
            <div class=\"mb-3 rounded-xl progress\" style=\"height: 25px;\">
                <div class=\"rounded-xl progress-bar {$class}\" 
                    role=\"progressbar\" 
                    style=\"width: {$porcentaje}%;\" 
                    aria-valuenow=\"{$porcentaje}\" 
                    aria-valuemin=\"0\" 
                    aria-valuemax=\"100\">
                    {$realizados} de {$total}
                </div>
            </div>";
    }

    public function getResumen(Request $request)
    {
        $campania_id = $request->campania_id;
        
        // Evaluaciones por competencias
        $competencias = Evaluacione::where('campania_id', $campania_id)
            ->where('tipo_de_evaluacion_id', 1)
            ->with('evaluadores')
            ->get();
            
        $totalCompetencias = 0;
        $realizadasCompetencias = 0;
        
        foreach($competencias as $evaluacion) {
            $totalCompetencias += $evaluacion->evaluadores->count();
            $realizadasCompetencias += $evaluacion->evaluadores->where('realizado', 1)->count();
        }

        // Evaluaciones por objetivos
        $objetivos = Evaluacione::where('campania_id', $campania_id)
            ->where('tipo_de_evaluacion_id', 2)
            ->with(['evaluadores.objetivos'])
            ->get();
            
        $totalObjetivos = 0;
        $primeraFaseObjetivos = 0;
        $segundaFaseObjetivos = 0;
        
        foreach($objetivos as $evaluacion) {
            foreach($evaluacion->evaluadores as $evaluador) {
                $totalObjetivos++;
                
                // Primera fase - objetivos registrados
                if($evaluador->objetivosRegistrados->count() >= $evaluador->cantidad_requerida) {
                    $primeraFaseObjetivos++;
                }
                
                // Segunda fase - objetivos realizados y no grupales
                if($evaluador->objetivosRealizados->where('grupal', 0)->count() >= $evaluador->objetivosRegistrados->where('grupal', 0)->count()) {
                    $segundaFaseObjetivos++;
                }
            }
        }

        // Planes de mejora
        $planes = PlanesConfiguracion::where('campania_id', $campania_id)
            ->with(['encargadoPlanesDeAccion' => function($query) {
                $query->where('habilitado', 1); // Solo encargados habilitados
            }])
            ->get();

        // dd($planes);
            
        $totalPlanes = 0;
        $primeraFasePlanes = 0;
        $segundaFasePlanes = 0;
        
        foreach($planes as $plan) {
            foreach($plan->encargadoPlanesDeAccion as $encargado) {
                $totalPlanes++;
                
                // Primera fase - cantidad de planes requerida
                if($encargado->planesDeMejora->count() >= $encargado->cantidad_requerida) {
                    $primeraFasePlanes++;
                }
                
                // Segunda fase - planes realizados
                if($encargado->planesDeMejora->where('estado_id', 2)->count() >= $encargado->cantidad_requerida) {
                    $segundaFasePlanes++;
                }
            }
        }

        return response()->json([
            'competencias' => [
                'total' => $totalCompetencias,
                'realizadas' => $realizadasCompetencias,
                'porcentaje' => $totalCompetencias > 0 ? round(($realizadasCompetencias/$totalCompetencias)*100, 2) : 0
            ],
            'objetivos' => [
                'total' => $totalObjetivos,
                'primera_fase' => $primeraFaseObjetivos,
                'segunda_fase' => $segundaFaseObjetivos,
                'porcentaje_primera' => $totalObjetivos > 0 ? round(($primeraFaseObjetivos/$totalObjetivos)*100, 2) : 0,
                'porcentaje_segunda' => $totalObjetivos > 0 ? round(($segundaFaseObjetivos/$totalObjetivos)*100, 2) : 0
            ],
            'planes' => [
                'total' => $totalPlanes,
                'primera_fase' => $primeraFasePlanes,
                'segunda_fase' => $segundaFasePlanes,
                'porcentaje_primera' => $totalPlanes > 0 ? round(($primeraFasePlanes/$totalPlanes)*100, 2) : 0,
                'porcentaje_segunda' => $totalPlanes > 0 ? round(($segundaFasePlanes/$totalPlanes)*100, 2) : 0
            ]
        ]);
    }

    public function getPlanesNoResueltos(Request $request)
    {
        $campania_id = $request->campania_id;
        $planesNoResueltos = [];

        $planes = PlanesConfiguracion::where('campania_id', $campania_id)
            ->with(['encargadoPlanesDeAccion' => function($query) {
                $query->where('habilitado', 1);
            }, 'encargadoPlanesDeAccion.personal', 'encargadoPlanesDeAccion.planesDeMejora'])
            ->get();

        foreach($planes as $plan) {
            foreach($plan->encargadoPlanesDeAccion as $encargado) {
                if($encargado->planesDeMejora->count() < $encargado->cantidad_requerida) {
                    $planesNoResueltos[] = [
                        'encargado' => $encargado->personal->name,
                        'registrados' => $encargado->planesDeMejora->count(),
                        'requeridos' => $encargado->cantidad_requerida,
                        'faltantes' => $encargado->cantidad_requerida - $encargado->planesDeMejora->count()
                    ];
                }
            }
        }

        return response()->json($planesNoResueltos);
    }

    public function getResumenObjetivos(Request $request)
    {
        if (!$request->has('campania_id') || $request->campania_id == '') {
            return response()->json([]);
        }

        $evaluadores = EvaluadorHasEvaluado::with(['evaluador', 'evaluado', 'objetivos'])
            ->whereHas('evaluacion', function($q) use ($request) {
                $q->where('campania_id', $request->campania_id)
                    ->where('tipo_de_evaluacion_id', 2); // Solo evaluaciones por objetivos
            })
            ->get()
            ->map(function($evaluacion) {
                $subtotal = $evaluacion->objetivos->sum('peso_ponderado');
                $total = 0;
                
                if ($subtotal >= $evaluacion->evaluacion->maximo) {
                    $total = $evaluacion->evaluacion->maximo;
                } elseif ($subtotal >= $evaluacion->evaluacion->minimo) {
                    $total = $subtotal;
                }

                return [
                    'evaluado' => $evaluacion->evaluado->name,
                    'evaluador' => $evaluacion->evaluador->name,
                    'area_evaluado' => $evaluacion->evaluado->area->nombre ?? 'Sin área',
                    'subtotal' => number_format($subtotal, 2),
                    'total' => number_format($total, 2),
                    'minimo' => $evaluacion->evaluacion->minimo,
                    'maximo' => $evaluacion->evaluacion->maximo
                ];
            });

        return response()->json($evaluadores);
    }

}