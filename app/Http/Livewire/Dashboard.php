<?php

namespace App\Http\Livewire;

use App\Models\EvaluadorHasEvaluado;
use App\Models\RangosDePlanDeAccion;
use App\Models\Respuesta;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public
    $secciones,
    $area_de_evaluados=[],
    $gerencia_sub_gerencia_de_evaluados=[],
    $valor_esperado = 7.50,
    $area_de_evaluado=[],
    $gerencia_sub_gerencia_de_evaluado=[],
    $mostrar_grafica = null,
    $rangos=[],
    $personal_id=[],
    $vista_personal=false,
    $title=null;

    public function mount($personal_id=null, $vista_personal=false, $title=null)
    {
        if($personal_id) {
        $this->personal_id = [$personal_id];
        
        // dd($personal_id, $vista_personal, $title);
        }
        $this->vista_personal = $vista_personal;
        $this->title = $title;
        
        $this->gerencia_sub_gerencia_de_evaluados = 
        EvaluadorHasEvaluado::orderBy('gerencia_sub_gerencia_de_evaluado')->pluck('gerencia_sub_gerencia_de_evaluado', 'gerencia_sub_gerencia_de_evaluado')->toArray();
        
        $this->areas();

        $this->datos_promedio();

        $this->rangos = RangosDePlanDeAccion::where('estado', 1)->orderBy('rango_mayor')->get();       
    }

    public function render()
    {
        // dd($this->secciones);
        return view('livewire.dashboard.view');
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
        $this->secciones = Respuesta::with('pregunta.seccion')
            ->select(
            'preguntas.seccion_id as seccion_id',
            'secciones.name as nombre',
            DB::raw($this->valor_esperado . ' as valor_esperado'),
            DB::raw('ROUND(avg(valor_numerico), 2) as promedio')
            )
            ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
            ->join('secciones', 'preguntas.seccion_id', '=', 'secciones.id')
            ->join('evaluador_has_evaluados', 'respuestas.evaluado_id', '=', 'evaluador_has_evaluados.evaluado_id')
            ->groupBy('preguntas.seccion_id')
            ->when(($this->area_de_evaluado), function ($query, $area_de_evaluado) {
            $query->whereIn('evaluador_has_evaluados.area_de_evaluado', $this->area_de_evaluado);
            })
            ->when(($this->gerencia_sub_gerencia_de_evaluado), function ($query, $gerencia_sub_gerencia_de_evaluado) {
            $query->whereIn('evaluador_has_evaluados.gerencia_sub_gerencia_de_evaluado', $this->gerencia_sub_gerencia_de_evaluado);
            })
            ->when(($this->personal_id), function ($query, $personal_id) {
            $query->whereIn('respuestas.evaluado_id', $this->personal_id);
            })
            ->get();

            if (count($this->secciones) > 0)
            {
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
            for ($i = 0; $i < count($valores); $i++) {
                if ($respuesta->promedio < $valores[$i]) {
                    $respuesta->color = $colores[$i];
                    break;
                }
            }

            return $respuesta;
        });

        $this->mostrar_grafica = count($this->secciones) > 0;
    }
}
