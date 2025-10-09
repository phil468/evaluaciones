<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Competencia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EncargadosPlanesDeAccion;
use App\Models\EstadosDePlanDeAccion;
use App\Models\Evaluacione;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\PlanesDeAccion;
use App\Models\PlanesDeMejoraHasEvidencia;
use App\Models\Proceso;
use App\Models\RangosDePlanDeAccion;
use App\Models\Respuesta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Livewire\WithFileUploads;

class EncargadosPlanesDeAccions extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $encargado_id, $empleado_id, $evaluacion_id, $realizado;
    public $updateMode = false;
    public $ingreso = null;
    public $empleado_ids = null;
    public $secciones;
    public $dashboard;
    public $nombreEmpleado;
    public $competencias;
    public $procesos;
    public $estados;
    public $gerencias;
    public $areas;
    public $personals;
    public $competencia_id;
    public $proceso_id;
    public $estado_id;
    public $name,$fecha_de_revision,$avance,$tipo_de_proceso_id,$gerencia_id,$area_id;
    public $valor_esperado = 7.5, $cantidad_requerida, $secciones_bajas = [], $mostrar_grafica = true;
    public $encargados_planes_de_accion_id;
    public $evaluacionPorCompetenciasFinalizada=false;
    public $secciones_ordenadas = [];
    public $primera_fase_activa, $segunda_fase_activa, $evaluador_has_evaluado, 
    $ingresado_opcional = false, $tieneObligatorioBajo = false, $secciones_opcionales_no_visibles = false;
    
    public $evidencias = [];
    public $evidenciasNombres = [];
    public $evidenciasGuardadas = [];

    public $campaniaFiltro = null; // campaña seleccionada
    public $campaniasDisponibles = [];

    public $objetivo,$alcanzado,$porcentaje_cumplimiento,$estado_cumplimiento,$tipo_objetivo='numerico';
    public $estado_aprobacion='borrador',$observacion_validacion;

    protected $listeners = [
        'setCompetenciaId' => 'setCompetenciaId'
        ,'setEstadoId' => 'setEstadoId'
        ,'setAvance' => 'setAvance'
        ,'setValues' => 'setValues'
        ,'setSeccionesBajas' => 'setSeccionesBajas'
    ];
    
    protected function rulesPlan() {
        return [
            'name'=>'required',
            'competencia_id'=>'required',
            'proceso_id'=>'required',
            'fecha_de_revision'=>'required|date',
            'estado_id'=>'required',
            'avance'=>'required|numeric|min:0|max:100',
            'objetivo'=>'nullable|numeric|min:0.01',
            'alcanzado'=>'nullable|numeric|min:0',
            'estado_aprobacion'=>'required|in:borrador,pendiente,validado,no_validado',
            'observacion_validacion'=>'nullable|string'
        ];
    }
    
    public function updatedObjetivo(){ $this->recalcularPct(); }
    public function updatedAlcanzado(){ $this->recalcularPct(); }
    
    public function recalcularPct(){
        if($this->objetivo && $this->objetivo>0 && $this->alcanzado !== null){
            $pct = ($this->alcanzado / $this->objetivo)*100;
            $this->porcentaje_cumplimiento = round($pct,2);
            if($pct < 50) $this->estado_cumplimiento='No cumplimiento';
            elseif($pct < 70) $this->estado_cumplimiento='Bajo cumplimiento';
            elseif($pct < 90) $this->estado_cumplimiento='Medio cumplimiento';
            else $this->estado_cumplimiento='Cumplimiento esperado';
        } else {
            $this->porcentaje_cumplimiento=null;
            $this->estado_cumplimiento=null;
        }
    }

    public function setCompetenciaId($competencia_id)
    {
        $this->competencia_id = $competencia_id;
    }

    public function setEstadoId($competencia_id)
    {
        $this->estado_id = $competencia_id;
    }

    public function setAvance($competencia_id)
    {
        $this->avance = $competencia_id;
    }

    public function setValues($seccion_id)
    {
        $this->competencia_id = $seccion_id;    
        $this->estado_id = 1;
        $this->avance = 0;
        $this->emit('openUpdatePlanDataModal');
    }

    public function setSeccionesBajas($seccion_id)
    {
        $this->secciones_bajas = $seccion_id;
    }
    
    public function evaluar_fases()
    {
        $this->primera_fase_activa = $this->evaluador_has_evaluado->plan_de_mejora->primera_fase_activa;
        $this->segunda_fase_activa = $this->evaluador_has_evaluado->plan_de_mejora->segunda_fase_activa;
    }

    public function openModal()
    {
        $this->estado_id = 1;
        $this->avance = 0;
        $this->emit('opencreatePlanDataModal');
    }

    public function mount($ingreso = null, $empleado_id = null, $dashboard = null)
    {

        $this->campaniasDisponibles = \App\Models\Campania::orderBy('id','desc')->pluck('name','id')->toArray();
        // si no se define, usar campania actual
        $this->campaniaFiltro = $this->campaniaFiltro ?? \App\Models\Campania::where('es_campania_actual',1)->value('id');

        $evaluaciones = Evaluacione::where('tipo_de_evaluacion_id', 1)->vigente()->get();

        if ($evaluaciones->count() > 0) {
            $this->evaluacionPorCompetenciasFinalizada = false;
        } else {
            $this->evaluacionPorCompetenciasFinalizada = true;
        }

        $this->competencias = Competencia::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->procesos 	= Proceso::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->estados 		= EstadosDePlanDeAccion::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->gerencias 	= Gerencia::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->areas 		= Area::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->personals 	= 
        Personal::orderBy('name','asc')->where('id',$empleado_id)
        ->orWhere('id',auth()->user()->personal->id)
        ->pluck('name','id');

        if ($ingreso == 'ingreso') {
            $this->ingreso = true;
            $this->empleado_ids = 
            EncargadosPlanesDeAccion::where('encargado_id', auth()->user()->personal->id)
            ->habilitado()
            ->pluck('empleado_id');
        } else {
            $this->ingreso = false;
            $this->encargado_id = auth()->user()->personal->id;
            $this->empleado_id = $empleado_id;
        }

        if ($dashboard == 'dashboard') {
            $this->dashboard = true;
            $this->empleado_id = $empleado_id;
            
		    $this->evaluador_has_evaluado = EncargadosPlanesDeAccion::where('empleado_id',$empleado_id)->get()->first();
            $this->valor_esperado =         EncargadosPlanesDeAccion::where('empleado_id', $this->empleado_id)->first()->valor_esperado;
            $this->cantidad_requerida =     EncargadosPlanesDeAccion::where('empleado_id', $this->empleado_id)->first()->cantidad_requerida;
            $this->encargados_planes_de_accion_id = EncargadosPlanesDeAccion::where('empleado_id', $this->empleado_id)->first()->id;
            
            if ($this->campaniaFiltro>=2)
            {
                $this->secciones = \App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia::with('competencia')
                    ->where('campania_id',$this->campaniaFiltro)
                    ->when(!empty($this->personal_id), fn($q)=>$q->whereIn('personal_id',$this->personal_id))
                    ->get()
                    ->groupBy('competencia_id')
                    ->map(function($g){
                        return (object) [
                            'seccion_id' => $g->first()->competencia_id,
                            'nombre' => $g->first()->competencia->name ?? 'COMP',
                            'valor_esperado' => $this->valor_esperado,
                            'promedio' => round($g->avg('puntaje_calibrado') ?: $g->avg('puntaje'),2),
                        ];
                    });
                
            } else {
                
                $this->secciones = Respuesta::with('pregunta.seccion')
                ->select(
                    'preguntas.seccion_id',
                    'secciones.name as nombre', 
                    DB::raw($this->valor_esperado.' as valor_esperado'),
                    DB::raw('ROUND(avg(valor_numerico), 2) as promedio')
                    )
                    ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
                    ->join('secciones', 'preguntas.seccion_id', '=', 'secciones.id')
                    ->groupBy('preguntas.seccion_id')
                    ->where('respuestas.evaluado_id', $this->empleado_id)
                    ->get();

            }

            $rangos = RangosDePlanDeAccion::where('estado', 1)->orderBy('rango_mayor')->get();
            $valores = $rangos->pluck('rango_mayor')->toArray();
            $colores = $rangos->pluck('color')->toArray();
            $this->secciones = $this->secciones->map(function ($respuesta) use ($valores, $colores) {
                for ($i = 0; $i < count($valores); $i++) {
                    if ($respuesta->promedio < $valores[$i]) {
                        $respuesta->color = $colores[$i];
                        break;
                    }
                }
            
                return $respuesta;
            });

            // if (count($this->secciones) > 0)
            // {
            //     // Calculate overall average
            //     $overallAverage = round($this->secciones->avg('promedio'), 2);
        
            //     // Add a row for overall average
            //     $overallRow = (object) [
            //         'seccion_id' => 0,
            //         'nombre' => 'PROMEDIO',
            //         'valor_esperado' => $this->valor_esperado,
            //         'promedio' => $overallAverage,
            //     ];
        
            //     $this->secciones->prepend($overallRow);
            // }

            $this->evaluar_fases();
        }
    }

    public function updatedCampaniaFiltro(){
        // refrescar colecciones dependientes
        $this->resetPage();
    }

    public function secciones_bajas($personal_id) 
    {
        if ($this->campaniaFiltro>=2)
        {
            $secciones = \App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia::with('competencia')
                ->where('campania_id',$this->campaniaFiltro)
                ->when(!empty($this->personal_id), fn($q)=>$q->whereIn('personal_id',$this->personal_id))
                ->get()
                ->groupBy('competencia_id')
                ->map(function($g){
                    return (object) [
                        'seccion_id' => $g->first()->competencia_id,
                        'nombre' => $g->first()->competencia->name ?? 'COMP',
                        'valor_esperado' => $this->valor_esperado,
                        'promedio' => round($g->avg('puntaje_calibrado') ?: $g->avg('puntaje'),2),
                    ];
                });
            
        } else {

            $respuestas = Respuesta::with('pregunta.seccion','evaluado')->whereNull('respuestas.deleted_at')->get();

            $personal_id = (array) $personal_id;

            $secciones = $respuestas
            ->when(!empty($personal_id), function ($collection) use($personal_id) {
                return $collection->filter(function ($respuesta) use($personal_id) {
                    return in_array($respuesta->evaluado->id, $personal_id);
                });
            })
            ->groupBy('pregunta.seccion_id')->map(function ($respuestasPorSeccion) {
                return [
                    'seccion_id' => $respuestasPorSeccion->first()->pregunta->seccion_id,
                    'nombre' => $respuestasPorSeccion->first()->pregunta->seccion->name,
                    'valor_esperado' => $this->valor_esperado,
                    'promedio' => round($respuestasPorSeccion->avg('valor_numerico'), 2),
                ];
            });  

        }      
        
        // if (count($secciones) > 0) {
        //     // Calculate overall average
        //     $overallAverage = round($secciones->avg('promedio'), 2);
            
        //     // Add a row for overall average
        //     $overallRow = (object) [
        //         'seccion_id' => 0,
        //         'nombre' => 'promedio',
        //         'valor_esperado' => $this->valor_esperado,
        //         'promedio' => $overallAverage,
        //     ];
            
        //     $secciones->prepend($overallRow);
        // }

        $rangos = RangosDePlanDeAccion::where('estado', 1)->orderBy('rango_mayor')->get();
        $valores = $rangos->pluck('rango_mayor')->toArray();
        $colores = $rangos->pluck('color')->toArray();
        $secciones = $secciones->map(function ($respuesta) use ($valores, $colores) {
            $respuesta = (object) $respuesta;
            for ($i = 0; $i < count($valores); $i++) {
                if ($respuesta->promedio < $valores[$i]) {
                    $respuesta->color = $colores[$i];
                    break;
                }
            }
            return $respuesta;
        });
// dd($secciones);
        // si el campo promedio es unico en la lista se ahgrega a su nombre la palbara obligatorio si es repetido se agraga lka palabra opcional
        $secciones = $secciones->map(function ($respuesta) use ($secciones) {
            // $respuesta->nombre = $respuesta->nombre . ' ' . ($secciones->where('promedio', $respuesta->promedio)->count() > 1 ? '(Opcional)' : '(Obligatorio)');
            $respuesta->obligatorio = ($secciones->where('PROMEDIO', $respuesta->promedio)->count() > 1 ? false : true);
            return $respuesta;
        });
// dd($secciones);
        //Encontrar los dos valores mas bajos y hacer una lsita de todas las secciones que esten por debajo de esos valores
        $valores = $secciones->pluck('promedio')->toArray();

        // Ordenar los valores de menor a mayor
        sort($valores);

        // Obtener los primeros $cantidad_requerida valores más bajos
        $valores_mas_bajos = array_slice($valores, 0, $this->cantidad_requerida);
        // dd($valores_mas_bajos);

        // Marcar las secciones con los $cantidad_requerida valores más bajos
        $secciones = $secciones->map(function ($respuesta) use ($valores_mas_bajos) {
            if (in_array($respuesta->promedio, $valores_mas_bajos)) {
                $respuesta->bajo = true;
                if ($respuesta->obligatorio && !$this->tieneObligatorioBajo) {
                    $this->tieneObligatorioBajo = true;
                }
                // $respuesta->color = 'red';
                //evaluar si $respuesta->promedio es unico en la lista de $respuesta->promedio si es unico se agreag a su nombre obligatorio sino es unico se agrega opcional
            } else {
                // Asegurarse de que 'bajo' no esté marcado si no es necesario
                $respuesta->bajo = false;
            }
            return $respuesta;
        });

        // Copia ordenada de las secciones por valor promedio
        $secciones_ordenadas = $secciones->sortBy('promedio');
        //ordenar secciones_ordenadas

        // ahora vamos a ver la lista de planes de acciones (mejoras)
        $planes_ingesados = PlanesDeAccion::latest()
                ->when($this->empleado_id, function ($query, $personal_id) {
                    return $query->where('empleado_id', $personal_id);
                })->get();
        
        // quiero que se agregue un campo a cada seccion que sea planes_de_accion la relación es seccion_id iagual al id de planes_ingresados, debe agregarse un campo ingresado =  true
        //Considera esta condición, sí es un campo obligatorio es falso el que es verdadero $planes_ingresados->count() > 0 entonces se agrega un campo ingresado = false, pero visible = false
        // $this->ingresado_opcional = false;
        $secciones_ordenadas = $secciones_ordenadas->map(function ($seccion) use ($planes_ingesados) {
            $planes_ingresados = $planes_ingesados->where('competencia_id', $seccion->seccion_id);
            $seccion->planes_de_accion = $planes_ingresados;
            $seccion->ingresado = $planes_ingresados->count() > 0;
            $seccion->visible = !($planes_ingresados->count() > 0);

            if (!$this->ingresado_opcional) {
                if($seccion->obligatorio == false && $seccion->ingresado == true){
                    $this->ingresado_opcional = true;
                }
            }
            // si esta sección el campo obligatorio es falso e ingresado = true, entonces se agrega un campo visible = false y todos los campos obligatorio = false se vuelven visible = false
            return $seccion;
        });

        if ($this->ingresado_opcional) {
            // verificar si $secciones_ordenadas tiene algun campo obligatorio = true

            if($this->tieneObligatorioBajo) {
                if (!$this->secciones_opcionales_no_visibles) {
                    $secciones_ordenadas = $secciones_ordenadas->map(function ($seccion) {
                        if (!$seccion->obligatorio) {
                            $seccion->visible = false;
                        }
                        return $seccion;
                    });
                    $this->secciones_opcionales_no_visibles = true;
                }
            }

        }

        if (count($secciones) > 0) {
            // Calculate overall average
            $overallAverage = round($secciones_ordenadas->avg('promedio'), 2);
            
            // Add a row for overall average
            $overallRow = (object) [
                'seccion_id' => 0,
                'nombre' => 'promedio',
                'valor_esperado' => $this->valor_esperado,
                'promedio' => $overallAverage,
                'color' => null,
                'obligatorio' => null,
                'bajo' => null,
            ];
            
            $secciones_ordenadas->prepend($overallRow);
        }

        // dd($secciones_ordenadas);
        return $secciones_ordenadas->values();
    }

    public function secciones_ingresadas($personal_id)
    {
        // necesido el ide de personal->planes_de_mejora
    }

    public function render()
    {
        if ($this->dashboard) { //Página en la que se muestra un personal en específico
            $this->proceso_id = 1;
            $this->nombreEmpleado = Personal::find($this->empleado_id)->name;

            if ($this->secciones) {
                $this->secciones_ordenadas = $this->secciones_bajas($this->empleado_id);
            }

            // dd(PlanesDeAccion::latest()
            //     ->when($this->empleado_id, function ($query, $empleado_id) {
            //         return $query->where('empleado_id', $empleado_id);
            //     })
            //     ->whereHas('encargados_planes_de_accion.plan_de_mejora', function($q){
            //         $q->where('campania_id',1);
            //     })
            //     ->get());

            return view('livewire.encargados-planes-de-accion.view', [
                'nombreEmpleado' => $this->nombreEmpleado,
                'planesDeAccions' => 
                PlanesDeAccion::latest()
                ->when($this->empleado_id, function ($query, $empleado_id) {
                    return $query->where('empleado_id', $empleado_id);
                })
                ->whereHas('encargados_planes_de_accion.plan_de_mejora', function($q){
                    $q->where('campania_id',$this->campaniaFiltro);
                })
                ->get()
            ]);
            $this->evaluar_fases();
        }

        if ($this->ingreso) { //Pagina principal en la que se muestran los planes de accion a cargao del personal logueado y los planes de acción ingresados para el personal logueado
            return view('livewire.encargados-planes-de-accion.view', [
                'encargadosPlanesDeAccions' => 
                EncargadosPlanesDeAccion::latest()
                ->where('encargado_id', auth()->user()->personal->id)
                ->habilitado()
                ->paginate(10),
                'planesDeAccions' => 
                // PlanesDeAccion::latest()
                // ->where('empleado_id', auth()->user()->personal->id)
                PlanesDeAccion::with(['encargados_planes_de_accion.plan_de_mejora.campania'])
                ->when($this->empleado_id, fn($q)=>$q->where('empleado_id',$this->empleado_id))
                ->whereHas('encargados_planes_de_accion.plan_de_mejora', function($q){
                    $q->where('campania_id',$this->campaniaFiltro);
                })
                ->paginate(10),
            ]);
        }

        return view('livewire.encargados-planes-de-accion.view', [
            'encargadosPlanesDeAccions' => EncargadosPlanesDeAccion::latest()
            ->when($this->empleado_ids, function ($query, $empleado_ids) {
                return $query->whereIn('empleado_id', $empleado_ids);
            })
            ->paginate(10),
        ]);
            
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
    
    public function cancel_plan()
    {
        $this->resetInput_plan();
        $this->updateMode = false;
        // return redirect()->route(Route::currentRouteName());
    }
	
    private function resetInput()
    {		
		// $this->encargado_id = null;
		// $this->empleado_id = null;
		$this->evaluacion_id = null;
		$this->realizado = null;
    }

	public function create() 
	{
	}

    
    public function updatedEvidencias()
    {
        if ($this->evidencias) {
            foreach ($this->evidencias as $evidencia) {
                $this->evidenciasNombres[] = $evidencia->getClientOriginalName();
            }

            foreach ($this->evidencias as $evidencia) {
                $name = pathinfo($evidencia->getClientOriginalName(), PATHINFO_FILENAME).'_' . time() . '.' . $evidencia->getClientOriginalExtension();
                $evidenciaName = $evidencia->store('evidencias_plan_de_mejora', 'public');
                // Guardar la ruta del archivo en la base de datos
                PlanesDeMejoraHasEvidencia::create([
                    'planes_de_accion_id' => $this->selected_id,
                    'ruta' => $evidenciaName,
                    'name' => $name,
                ]);
            }

            $this->loadEvidenciasGuardadas();

            $this->evidencias = [];

        }
    
    }
    
    public function loadEvidenciasGuardadas()
    {
        $plan = PlanesDeAccion::find($this->selected_id);
        if ($plan) {
            $this->evidenciasGuardadas = $plan->evidencias->toArray();
        }
    }

    public function removeEvidenciaGuardada($index)
    {
        $evidencia = $this->evidenciasGuardadas[$index];
        PlanesDeMejoraHasEvidencia::find($evidencia['id'])->delete();
        $this->loadEvidenciasGuardadas();
        // unset($this->evidenciasGuardadas[$index]);
        // $this->evidenciasGuardadas = array_values($this->evidenciasGuardadas);
    }

    public function store()
    {
        $this->evaluar_fases();
        $this->validate([
        ]);

        EncargadosPlanesDeAccion::create([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'evaluacion_id' => $this-> evaluacion_id,
			'realizado' => $this-> realizado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Encargados Planes De Mejora creado correctamente.');
    }

    public function store_plan()
    {
        $this->evaluar_fases();
        $this->validate($this->rulesPlan());
        // contar los planes y si es igual a la cantidad_requerida, entonces, no se puede ingresar más planes
        $contador_de_planes = PlanesDeAccion::latest()
        ->when($this->empleado_id, function ($query, $empleado_id) {
            return $query->where('empleado_id', $empleado_id);
        })->get()->count();

        if ($this->cantidad_requerida <= $contador_de_planes) {
            $this->emit('closeModal');
            session()->flash('message', 'No se puede ingresar mas planes de mejora. Se llegó a la cantidad_requerida.');
            return;
        }

        // $this->validate([
		// 	'name' => 'required',
		// 	'encargado_id' => 'required',
		// 	'empleado_id' => 'required',
		// 	'competencia_id' => 'required',
        //     'fecha_de_revision' =>'required',
		// 	// 'tipo_de_proceso_id' => 'required',
		// 	'proceso_id' => 'required',
		// 	'estado_id' => 'required',
		// 	// 'gerencia_id' => 'required',
		// 	// 'area_id' => 'required',
		// 	'avance' => 'required',
        //     // 'evidencias.*' => 'file|max:10240', // Validación para los archivos
		// 	]);

        $plan = PlanesDeAccion::create([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'competencia_id' => $this-> competencia_id,
			'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
			'proceso_id' => $this-> proceso_id,
			'fecha_de_revision' => $this-> fecha_de_revision,
			'estado_id' => $this-> estado_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'avance' => $this-> avance,
			'name' => $this-> name,
            'encargados_planes_de_accion_id' => $this->encargados_planes_de_accion_id,
            'objetivo'=>$this->objetivo,
            'alcanzado'=>$this->alcanzado,
            'porcentaje_cumplimiento'=>$this->porcentaje_cumplimiento,
            'estado_cumplimiento'=>$this->estado_cumplimiento,
            'tipo_objetivo'=>$this->tipo_objetivo,
            'estado_aprobacion'=>$this->estado_aprobacion,
            'observacion_validacion'=>$this->observacion_validacion,
            'encargados_planes_de_accion_id'=>$this->encargados_planes_de_accion_id,
        ]);

        $this->registrarHistorial($plan,null,$this->estado_aprobacion);
        // notificar si pasa a pendiente
        if($plan->estado_aprobacion==='pendiente') $this->enviarCorreoCambioEstado($plan);

        $this->resetInput_plan();
		$this->emit('closeModal');
		session()->flash('message', 'Planes De Mejora creado correctamente.');
        // return redirect()->route(Route::currentRouteName());
    }
    
    protected function registrarHistorial($plan,$anterior,$nuevo){
        \App\Models\PlanesDeAccionAprobacionHistorial::create([
            'planes_de_accion_id'=>$plan->id,
            'user_id'=>auth()->id(),
            'estado_anterior'=>$anterior,
            'estado_nuevo'=>$nuevo,
            'observacion'=>$this->observacion_validacion
        ]);
    }

    protected function enviarCorreoCambioEstado($plan){
        // Evitar spam si igual
        try{
            \Mail::to($plan->empleado->correo_empresa)
                ->queue(new \App\Mail\EstadoAprobacionPlanMail($plan));
            $plan->ultima_notificacion_aprobacion_at = now();
            if(in_array($plan->estado_aprobacion,['validado','no_validado'])){
                $plan->aprobado_revisado_at = now();
            }
            $plan->save();
        }catch(\Throwable $e){
            \Log::error('Error enviando correo plan: '.$e->getMessage());
        }
    }

    private function resetInput_plan()
    {		
		$this->competencia_id = null;
		$this->avance = null;
        $this->estado_id =null;
		$this->name = null;
        $this->evidencias = [];
        $this->evidenciasNombres = [];
    }
    
    public function edit_plan($id)
    {
        $this->evaluar_fases();
        $record = PlanesDeAccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->encargado_id = $record-> encargado_id;
		$this->empleado_id = $record-> empleado_id;
		$this->competencia_id = $record-> competencia_id;
		$this->tipo_de_proceso_id = $record-> tipo_de_proceso_id;
		$this->proceso_id = $record-> proceso_id;
		$this->fecha_de_revision = $record-> fecha_de_revision;
		$this->estado_id = $record-> estado_id;
		$this->gerencia_id = $record-> gerencia_id;
		$this->area_id = $record-> area_id;
		$this->avance = $record-> avance;
		$this->name = $record-> name;
        $this->objetivo = $record->objetivo;
        $this->alcanzado = $record->alcanzado;
        $this->porcentaje_cumplimiento = $record->porcentaje_cumplimiento;
        $this->estado_cumplimiento = $record->estado_cumplimiento;
        $this->tipo_objetivo = $record->tipo_objetivo;
        $this->estado_aprobacion = $record->estado_aprobacion;
        $this->observacion_validacion = $record->observacion_validacion;
        
        if ($record) {
            $this->evidenciasGuardadas = $record->evidencias->toArray();
        }
        // $this->evidencias = $record->evidencias;
		
        $this->updateMode = true;
        // return redirect()->route(Route::currentRouteName());
    }

    public function destroy_plan($id)
    {
        if ($id) {
            $record = PlanesDeAccion::where('id', $id);
            $record->delete();
        }
        // return redirect()->route(Route::currentRouteName());
    }

    public function update_plan()
    {
        $this->evaluar_fases();
    $this->validate($this->rulesPlan());
        // $this->validate([
		// 	'name' => 'required',
		// 	'encargado_id' => 'required',
		// 	'empleado_id' => 'required',
		// 	'competencia_id' => 'required',
        //     'fecha_de_revision' =>'required',
		// 	// 'tipo_de_proceso_id' => 'required',
		// 	'proceso_id' => 'required',
		// 	'estado_id' => 'required',
		// 	// 'gerencia_id' => 'required',
		// 	// 'area_id' => 'required',
		// 	'avance' => 'required',
        //     'evidencias.*' => 'file|max:10240', // Validación para los archivos
		// 	]);

        if ($this->selected_id) {
			$record = PlanesDeAccion::find($this->selected_id);
        $estadoAnterior = $record->estado_aprobacion;
            $record->update([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'competencia_id' => $this-> competencia_id,
			'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
			'proceso_id' => $this-> proceso_id,
			'fecha_de_revision' => $this-> fecha_de_revision,
			'estado_id' => $this-> estado_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'avance' => $this-> avance,
			'name' => $this-> name,
            'objetivo'=>$this->objetivo,
            'alcanzado'=>$this->alcanzado,
            'porcentaje_cumplimiento'=>$this->porcentaje_cumplimiento,
            'estado_cumplimiento'=>$this->estado_cumplimiento,
            'tipo_objetivo'=>$this->tipo_objetivo,
            'estado_aprobacion'=>$this->estado_aprobacion,
            'observacion_validacion'=>$this->observacion_validacion,
            ]);
        if($estadoAnterior !== $plan->estado_aprobacion){
            $this->registrarHistorial($plan,$estadoAnterior,$plan->estado_aprobacion);
            $this->enviarCorreoCambioEstado($plan);
        }

            // Procesar y guardar las evidencias
            if ($this->evidencias) {
                foreach ($this->evidencias as $evidencia) {
                    // dd($evidencia);
                    $name = pathinfo($evidencia->getClientOriginalName(), PATHINFO_FILENAME).'_' . time() . '.' . $evidencia->getClientOriginalExtension();
                    $evidenciaName = $evidencia->store('evidencias_plan_de_mejora', 'public');
                    // Guardar la ruta del archivo en la base de datos
                    PlanesDeMejoraHasEvidencia::create([
                        'planes_de_accion_id' => $this->selected_id,
                        'ruta' => $evidenciaName,
                        'name' => $name,
                    ]);
                }
            }

            $this->resetInput_plan();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Planes De Mejora actualizado correctamente.');
        }
    }

    public function plan($id)
    {
        if ($id) {
            $record = PlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }

    public function edit($id)
    {
        $record = EncargadosPlanesDeAccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->encargado_id = $record-> encargado_id;
		$this->empleado_id = $record-> empleado_id;
		$this->evaluacion_id = $record-> evaluacion_id;
		$this->realizado = $record-> realizado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = EncargadosPlanesDeAccion::find($this->selected_id);
            $record->update([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'evaluacion_id' => $this-> evaluacion_id,
			'realizado' => $this-> realizado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Encargados Planes De Mejora actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EncargadosPlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }

    public function ver($id)
    {
        $record = EncargadosPlanesDeAccion::findOrFail($id);
        $this->selected_id = $id; 
        $this->encargado_id = $record-> encargado_id;
        $this->empleado_id = $record-> empleado_id;
        $this->evaluacion_id = $record-> evaluacion_id;
        $this->realizado = $record-> realizado;
        $this->nombreEmpleado = Personal::find($this->empleado_id)->name;
        
        $this->updateMode = true;

        redirect()->route('planes-de-mejora', ['dashboard' => 'dashboard','empleado_id'=>$this->empleado_id]);
    }
}
