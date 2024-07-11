<?php

namespace App\Http\Livewire;

use App\Models\EncargadosPlanesDeAccion;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\RangosDePlanDeAccion;
use App\Models\Respuesta;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public
    $secciones,
    $secciones_ordenadas,
    $area_de_evaluados=[],
    $gerencia_sub_gerencia_de_evaluados=[],
    $valor_esperado = 7.50,
    $cantidad_requerida = 0,
    $area_de_evaluado=[],
    $gerencia_sub_gerencia_de_evaluado=[],
    $mostrar_grafica = null,
    $rangos=[],
    $personal_id=[],
    $vista_personal=false,
    $ingresar_plan=false,
    $title=null,
    $showHeader=true,
    $empleado_id=null,
    $evaluacionPorCompetenciasFinalizada=false,
    $campania,
    $evaluaciones_no_completadas;

    public function mount($personal_id=null, $vista_personal=false, $title=null, $ingresar_plan=false, $showHeader=true, $campania=2024)
    {
        $evaluaciones = Evaluacione::
        where('tipo_de_evaluacion_id', 1)
        ->where('campania', $campania)
        ->vigente()->get();

        if ($evaluaciones->count() > 0) {
            $this->evaluacionPorCompetenciasFinalizada = false;
        } else {
            $this->evaluacionPorCompetenciasFinalizada = true;
        }

        if($personal_id) {

            $this->evaluaciones_no_completadas = Auth::user()->personal->evaluaciones()
            ->join('evaluaciones', 'evaluador_has_evaluados.evaluacion_id', '=', 'evaluaciones.id')
            ->select('evaluador_has_evaluados.id')
            ->where('evaluaciones.tipo_de_evaluacion_id', TipoDeEvaluacione::COMPETENCIAS)
            ->where('evaluaciones.campania', $campania)
            ->where('evaluador_has_evaluados.realizado',null)
            ->get();

            $this->personal_id = [$personal_id];
            $this->empleado_id = $personal_id;
            $this->valor_esperado = EncargadosPlanesDeAccion::where('empleado_id', $this->empleado_id)->first()->valor_esperado ?? 0.00;
            $this->cantidad_requerida = EncargadosPlanesDeAccion::where('empleado_id', $this->empleado_id)->first()->cantidad_requerida ?? 0.00;
            $this->campania = $campania;

        }

        $this->vista_personal = $vista_personal;
        $this->title = $title;
        $this->ingresar_plan = $ingresar_plan;
        $this->showHeader = $showHeader;
        
        $this->gerencia_sub_gerencia_de_evaluados = 
        EvaluadorHasEvaluado::
            orderBy('gerencia_sub_gerencia_de_evaluado')
            ->pluck('gerencia_sub_gerencia_de_evaluado', 'gerencia_sub_gerencia_de_evaluado')
            ->toArray();
        
        $this->areas();

        $this->datos_promedio();

        $this->rangos = RangosDePlanDeAccion::where('estado', 1)->orderBy('rango_mayor')->get();       
    }

    public function render()
    {
        if ($this->vista_personal)
        {
            if ($this->evaluaciones_no_completadas->isEmpty())
                return view('livewire.dashboard.view');
            else
                return view('livewire.dashboard.evaluaciones_no_completadas');
        }
        else
        {
            return view('livewire.dashboard.view');
        }   
    }

    public function updatedGerenciaSubGerenciaDeEvaluado()
    {
        $this->areas();
        $this->actualizarAreaSelects();
    }

    public function actualizarAreaSelects() {
        $this->emit('actualizarAreas',
            $this->area_de_evaluados,
        );
	}

    public function generar_grafica()
    {   
        $this->datos_promedio();
        $this->emit('dataUpdated', $this->secciones->pluck('promedio')->toArray(), $this->secciones->pluck('nombre')->toArray(), $this->secciones->pluck('color')->toArray());
    }

    public function areas() {
        $this->area_de_evaluados = 
        EvaluadorHasEvaluado::select('area_de_evaluado as label', 'area_de_evaluado as value')->distinct()->orderBy('area_de_evaluado')      
        ->when(($this->gerencia_sub_gerencia_de_evaluado), function ($query, $gerencia_sub_gerencia_de_evaluado) {
            $query->whereIn('gerencia_sub_gerencia_de_evaluado', $this->gerencia_sub_gerencia_de_evaluado);
        })
        ->get()->toArray();
    }

    public function datos_promedio()
    {
        $respuestas = Respuesta::with('pregunta.seccion','evaluado')->whereNull('respuestas.deleted_at')->get();
            
        $this->secciones = $respuestas
        ->when(!empty($this->area_de_evaluado), function ($collection) {
            return $collection->filter(function ($respuesta) {
                return in_array($respuesta->evaluado->area_de_evaluado, $this->area_de_evaluado);
            });
        })
        ->when(!empty($this->gerencia_sub_gerencia_de_evaluado), function ($collection) {
            return $collection->filter(function ($respuesta) {
                return in_array($respuesta->evaluado->gerencia_sub_gerencia_de_evaluado, $this->gerencia_sub_gerencia_de_evaluado);
            });
        })
        ->when(!empty($this->personal_id), function ($collection) {
            return $collection->filter(function ($respuesta) {
                return in_array($respuesta->evaluado->id, $this->personal_id);
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

        if (count($this->secciones) > 0) {
            // Calculate overall average
            $overallAverage = round($this->secciones->avg('promedio'), 2);
            
            // Add a row for overall average
            $overallRow = (object) [
                'seccion_id' => 0,
                'nombre' => 'PROMEDIO',
                'valor_esperado' => $this->valor_esperado,
                'promedio' => $overallAverage,
            ];
            
            $this->secciones->prepend($overallRow);
        }

        $rangos = RangosDePlanDeAccion::where('estado', 1)->orderBy('rango_mayor')->get();
        $valores = $rangos->pluck('rango_mayor')->toArray();
        $colores = $rangos->pluck('color')->toArray();

        $this->secciones = $this->secciones->map(function ($respuesta) use ($valores, $colores) {
            $respuesta = (object) $respuesta;
            for ($i = 0; $i < count($valores); $i++) {
                if ($respuesta->promedio < $valores[$i]) {
                    $respuesta->color = $colores[$i];
                    break;
                }
            }
            return $respuesta;
        });

        // si el campo promedio es unico en la lista se ahgrega a su nombre la palbara obligatorio si es repetido se agraga lka palabra opcional
        $this->secciones = $this->secciones->map(function ($respuesta) {
            // $respuesta->nombre = $respuesta->nombre . ' ' . ($this->secciones->where('promedio', $respuesta->promedio)->count() > 1 ? '(Opcional)' : '(Obligatorio)');
            $respuesta->obligatorio = ($this->secciones->where('promedio', $respuesta->promedio)->count() > 1 ? false : true);
            return $respuesta;
        });


        //Encontrar los dos valores mas bajos y hacer una lsita de todas las secciones que esten por debajo de esos valores
        $valores = $this->secciones->pluck('promedio')->toArray();

        // Ordenar los valores de menor a mayor
        sort($valores);

        // Obtener los primeros $cantidad_requerida valores más bajos
        $valores_mas_bajos = array_slice($valores, 0, $this->cantidad_requerida);
        // dd($valores_mas_bajos);

        // Marcar las secciones con los $cantidad_requerida valores más bajos
        $this->secciones = $this->secciones->map(function ($respuesta) use ($valores_mas_bajos) {
            if (in_array($respuesta->promedio, $valores_mas_bajos)) {
                $respuesta->bajo = true;
                // $respuesta->color = 'red';
                //evaluar si $respuesta->promedio es unico en la lista de $respuesta->promedio si es unico se agreag a su nombre obligatorio sino es unico se agrega opcional
            } else {
                // Asegurarse de que 'bajo' no esté marcado si no es necesario
                $respuesta->bajo = false;
            }
            return $respuesta;
        });

        // Copia ordenada de las secciones por valor promedio
        $this->secciones_ordenadas = $this->secciones->sortBy('promedio');
        //ordenar secciones_ordenadas
        $this->secciones_ordenadas = $this->secciones_ordenadas->values();

        // if($this->evaluacionPorCompetenciasFinalizada) {
        if(1) {
            $this->mostrar_grafica = count($this->secciones) > 0;
            // $this->mostrar_grafica = false; //momentaneamente no mmuestra grafica
        } else {
            $this->mostrar_grafica = false;
        }
            // If there are sections to show, display the chart (otherwise, hide it
    }

}
