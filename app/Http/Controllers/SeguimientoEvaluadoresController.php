<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\PlanesConfiguracion;
use App\Models\TipoDeEvaluacione;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        $campania_id = $request->campania_id;

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
        ->map(function($evaluador) use ($request) {
            // Agrupar estadísticas de competencias
            $competencias = $evaluador->evaluacionesComoEvaluador
                ->where('evaluacion.tipo_de_evaluacion_id', 1);
            // Agrupar estadísticas de objetivos
            $objetivos = $evaluador->evaluacionesComoEvaluador
                ->where('evaluacion.tipo_de_evaluacion_id', 2);
            
            // Agrupar estadísticas de planes
            $planes = $evaluador->planesComoEncargado;

            //hallando el área
            $area = $request->campania_id == 1 ? $evaluador->area->name : (
                CampaniaHasEvaluado::where('personal_id', $evaluador->id)
                    ->where('campania_id', $request->campania_id)
                    ->first()
                    ->area->name ?? 'Sin área');

            return [
                'id' => $evaluador->id,
                'evaluador' => $evaluador->name,
                'area' => $area,
                'competencias' => [
                    $competencias->where('realizado', 1)->where('cesado', 0)->count(),
                    $competencias->where('cesado', 0)->count()
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
        $totalCompetenciasCesadas = 0;

        foreach($competencias as $evaluacion) {
            $totalCompetencias += $evaluacion->evaluadores->where('cesado', 0)->count();
            $realizadasCompetencias += $evaluacion->evaluadores->where('realizado', 1)->where('cesado', 0)->count();
            $totalCompetenciasCesadas += $evaluacion->evaluadores->where('cesado', 1)->count();
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
                'porcentaje' => $totalCompetencias > 0 ? round(($realizadasCompetencias/$totalCompetencias)*100, 2) : 0,
                'cesadas' => $totalCompetenciasCesadas
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

    public function enviarCorreos(Request $request)
    {
        try {
            $evaluadores = EvaluadorHasEvaluado::whereHas('evaluacion', function($q) use ($request) {
                    $q->where('campania_id', $request->campania_id);
                })
                // ->groupBy(['evaluador_id','evaluacion_id'])
                ->with(['evaluador', 'evaluacion', 'evaluacion.tipoDeEvaluacion'])
                ->get()
                ->filter(function ($evaluador) {
                    return $evaluador->estado_pendiente;
                });

            // quiero agrupoar por evaluador y tipo de evaluacion para no enviar correos duplicados
            $evaluadoresAgrupados = $evaluadores->groupBy(function ($item) {
                return $item->evaluador_id . '-' . $item->evaluacion_id;
            });

            // dd(count($evaluadoresAgrupados));
            // dd($evaluador_8917);

            // $correo_de_prueba = 'john.delacruz@vanguardfresh.pe';

            foreach ($evaluadoresAgrupados as $grupo) {
                $evaluador = $grupo->first();
                
                $evaluacion = $evaluador->evaluacion;
                $personal = $evaluador->evaluador;
                $user = $personal->user;
                $email = $user->email;
                $name = $user->name;

                $evaluacion = $evaluador->evaluacion;
                $primera_fase_activa = $evaluacion->tipo_de_evaluacion_id == 1 ? true : $evaluacion->primera_fase_activa;
                $segunda_fase_activa = $evaluacion->segunda_fase_activa ?? false;
                $fecha_fin_segunda_fase = $evaluacion->fecha_fin_segunda_fase ?? '';

                // Mail::to($correo_de_prueba)->send(new \App\Mail\RecordatorioEvaluacion
                // ($name, $primera_fase_activa, $segunda_fase_activa, $evaluacion->tipo_de_evaluacion_id, $fecha_fin_segunda_fase));

                Mail::to($email)->send(new \App\Mail\RecordatorioEvaluacion(
                    $name, 
                    $primera_fase_activa, 
                    $segunda_fase_activa, 
                    $evaluacion->tipo_de_evaluacion_id, 
                    $fecha_fin_segunda_fase
                ));

                \Log::info('Correo enviado', ['email' => $email]);
                // interrumpir foreach 
                // break;
            }

            //enviar por correo el reporte detallado de los correos enviados
            // Mail::to($correo_de_prueba)->send(new \App\Mail\ReporteCorreosEnviados(
            //     $evaluadoresAgrupados
            // ));

            return response()->json([
                'success' => true,
                'message' => ''.$evaluadores->count(). ' correos enviados correctamente',
                'count' => $evaluadores->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al enviar correos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar los correos: ' . $e->getMessage()
            ], 500);
        }
    }

}