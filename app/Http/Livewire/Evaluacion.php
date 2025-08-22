<?php

namespace App\Http\Livewire;

use App\Models\CampaniaHasEvaluado;
use App\Models\Dominio;
use App\Models\EscalaMedicion;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Objetivo;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use App\Models\TiposDeObjetivo;
use App\Models\EvaluadorHasEvaluadoComentario;

class Evaluacion extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $eid, $title, $date,
    $status, $evaluacion_id, $evaluacion, $evaluador,
    $evaluado, $evaluadorHasEvaluado,$preguntas, $secciones, $seccion_index_select, $seccion_indexs;
    public $updateMode = false;
    public $aceptado = false;
    public $realizado = false;
    public $evaluacion_por_objetivos = false;

    public
    $descripcion1,
    $cantidad1,
    $tipo_objetivo_id1,
    $descripcion2,
    $cantidad2,
    $tipo_objetivo_id2;

    // Añade la propiedad
    public $acepto_escala = false;
    public $escalaMediciones = [];
    public $escalasArray = []; // Para acceder fácilmente por valor
    
    public $comentarios = [];              // [campania_has_competencia_id => 'texto']
    public $comentarioObligatorio = true;  // configurable si lo deseas
    
    protected $listeners = ['guardar' => 'guardar'];

    public function mount($evaluacion_id)
    {
            $this->evaluacion_id = $evaluacion_id;
        
            // Obtener el evaluadorHasEvaluado correspondiente a la evaluacion_id
            $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::find($evaluacion_id);
            // buscaremos el campaniaHasEvaluado correspondiente al evaluadorHasEvaluado
            if ($this->evaluadorHasEvaluado) {
                $this->evaluadorHasEvaluado->campaniaHasEvaluado;
            }

                // Obtener la evaluacion correspondiente al evaluadorHasEvaluado
                $this->evaluacion = Evaluacione::find($this->evaluadorHasEvaluado->evaluacion_id);
                // dd($this->evaluacion);
                if ($this->evaluacion->tipo_de_evaluacion_id == 2) {
                    $this->evaluacion_por_objetivos = true;
                    $this->evaluado = Personal::find($this->evaluadorHasEvaluado->evaluado_id);
                } else {
                    
                    // Cargar las escalas de medición
                    $this->escalaMediciones = EscalaMedicion::where('estado', true)
                        ->orderBy('valor_menor', 'asc')
                        ->get();
                   
                    // Crear un array asociativo para facilitar el acceso por valor
                    foreach ($this->escalaMediciones as $escala) {
                        for ($i = $escala->valor_menor; $i <= $escala->valor_mayor; $i++) {
                            $this->escalasArray[$i] = [
                                // name con mayuscula la primera letra y minúscula el resto
                                
                                'name' => $escala->name,
                                // 'name' => ucfirst(strtolower($escala->name)),
                                'color' => $escala->color,
                                'interpretacion' => $escala->interpretacion
                            ];
                        }
                    }
                                        
                    // Verificar si ya aceptó la escala previamente
                    if ($this->evaluadorHasEvaluado && $this->evaluadorHasEvaluado->acepto_escala) {
                        $this->acepto_escala = true;
                        $this->aceptado = true;
                    }
                    
                    if($this->evaluadorHasEvaluado->realizado == 1){
                        $this->aceptado = true;
                        $this->realizado = true;
                    }
            
                    if ($this->evaluacion->id < 5) {
                        // Obtener las preguntas de la evaluacion con sus respectivas secciones
                        $this->preguntas = Pregunta::where('evaluacion_id',$this->evaluacion->id)->with('seccion'
                        )->orderBy('preguntas.numero_orden')->get()->toArray();
                    } else {
                        // Si el id de la evaluacion es mayor o igual a 5, obtener las preguntas con sus respectivas secciones
                        // y ordenarlas por numero_orden de la seccion
                        // y luego por numero_orden de la pregunta
                        // a partir de la $this->evaluadorHasEvaluado vasmoa al grado y del grado llegamos al dominio, consultadno el campania_id y el grado_id
                        // y luego llegamos a las preguntas
                        $dominio_id = Dominio::where('campania_id',$this->evaluadorHasEvaluado->campania_id)
                        ->where('grado_id',$this->evaluadorHasEvaluado->grado_id)
                        ->first()->id;

                        $this->evaluadorHasEvaluado->evaluado->campaniaHasGrado;
                        
                        $this->preguntas = Pregunta::where('dominio_id',$dominio_id)
                        ->with('campaniaHasCompetencias.competencia')
                        ->orderBy('preguntas.numero_orden')->get()->toArray();

                    }
            
                    // Inicializar los valores de las preguntas en 7
                    foreach ($this->preguntas as $key => $value) {
                        $this->preguntas[$key]['valor'] = null;
                    }
            
                    // Obtener el evaluador correspondiente al evaluadorHasEvaluado
                    $this->evaluador = Personal::find($this->evaluadorHasEvaluado->evaluador_id);
            
                    // Obtener el evaluado correspondiente al evaluadorHasEvaluado
                    $this->evaluado = Personal::find($this->evaluadorHasEvaluado->evaluado_id);
            
                    // Obtener las secciones unicas de la evaluacion
                    if ($this->evaluacion->id < 5) {
                        $this->secciones =  Evaluacione::find($this->evaluadorHasEvaluado->evaluacion_id)->seccionesUnicas()->toArray();
                    } else {
                        // si la evaluacion es mayor o igual a 5, obtenemo)s las secciones
                        // a traves de las preguntas, sin repetirlas
                        // la lista de las secciones las vamos a obtener a partir del modelo Pregunta
                        // y vamos a obtener las secciones unicas
                        // pero en esta caso a traves de la relacion  Pregunta->CampaniaHasCompetencia->competencia->name
                        $dominio_id = Dominio::where('campania_id',$this->evaluadorHasEvaluado->campania_id)
                        ->where('grado_id',$this->evaluadorHasEvaluado->grado_id)
                        ->first()->id;

                        $this->secciones = Dominio::find($dominio_id)->campaniaHasCompetencias()->get()->map(function ($chc) {
                            return [
                                'id' => $chc->id,
                                'name' => $chc->competencia->name,
                                'descripcion' => $chc->competencia->descripcion,
                                'color' => $chc->color,
                            ];
                        })->unique('id')->values()->toArray();
                    }
                    $this->secciones = (array) $this->secciones;
                    $this->seccion_indexs = array_keys($this->secciones ?? []);
                    $this->seccion_index_select = 0;
            }
        //borrar los comentarios
        $this->comentarios = [];

        // Cargar comentarios con NUEVAS llaves (por sección)
        if ($this->evaluadorHasEvaluado) {
            $this->comentarios = EvaluadorHasEvaluadoComentario::where('evaluado_id', $this->evaluadorHasEvaluado->evaluado_id)
                ->where('campania_id', $this->evaluadorHasEvaluado->campania_id)
                ->where('tipo_relacion_jerarquica_id', $this->evaluadorHasEvaluado->relacion_jerarquica_id)
                ->pluck('comentario', 'campania_has_competencia_id')
                ->toArray();
        } else {
            $this->comentarios = [];
        }
    }

    private function currentSeccionId(): ?int
    {
        $key = $this->seccion_indexs[$this->seccion_index_select] ?? null;
        return $key !== null ? ($this->secciones[$key]['id'] ?? null) : null;
    }

    private function validarComentarioSeccionActual(): void
    {
        if (!$this->comentarioObligatorio) return;

        $seccionId = $this->currentSeccionId();
        $texto = trim((string)($this->comentarios[$seccionId] ?? ''));
        if ($seccionId && $texto === '') {
            $this->addError("comentarios.$seccionId", 'Este comentario es obligatorio.');
            throw new \RuntimeException('Comentario de sección requerido.');
        }
    }

    public function render()
    {
        // return redirect()->to('/evaluaciones-de-desempeno/1');
        if ($this->evaluacion_por_objetivos) {
            return view('livewire.objetivos.index',
            [
                'tipos_objetivo' => TiposDeObjetivo::all(),
            ]
        );
        } else {
            // contar el total de preguntas: preguntas.*.valor
            // contar el total de preguntas cuyo valor no sea nulo
            $totalPreguntas = count($this->preguntas);
            $totalPreguntasNoNulas = count(array_filter($this->preguntas, function ($pregunta) {
                return $pregunta['valor'] !== null;
            }));

            //mostrar una barra de progreso
            $porcentaje =  $totalPreguntas == 0 ? 0 : ($totalPreguntasNoNulas/$totalPreguntas)*100;
            $porcentaje = round($porcentaje,2);
            
            if ($totalPreguntasNoNulas == 0) {
                $class = 'bg-primary';
                $porcentaje = 100;
                $label = '0%';
            } else if ($totalPreguntas == $totalPreguntasNoNulas) {
                $class = 'bg-vanguard';
                $label = $porcentaje.'%';
            } else {
                $class = 'bg-vanguard';
                $label = $porcentaje.'%';
            }

            // $keyWord = '%'.$this->keyWord .'%';        
            return view('livewire.evaluacion.view', [
                'class' => $class,
                'porcentaje' => $porcentaje,
                'label' => $label
            ]);
        }
        
        if ($this->redirectTo) {
            return redirect($this->redirectTo);
        }
    }

    public function guardar_objetivos()
    {
        $this->validate([
            'descripcion1' => 'required|string',
            'tipo_objetivo_id1' => 'required|numeric',
        ]);

        Objetivo::create([
            'descripcion' => $this->descripcion1,
            'evaluador_id' => $this->evaluadorHasEvaluado->evaluador_id,
            'evaluado_id' => $this->evaluado->id,
            'tipo_objetivo_id' => $this->tipo_objetivo_id1,
        ]);


        if($this->descripcion2 != null && $this->descripcion2 != '' && $this->tipo_objetivo_id2 != null && $this->tipo_objetivo_id2 != '' ){
            Objetivo::create([
                'descripcion' => $this->descripcion2,
                'evaluador_id' => $this->evaluadorHasEvaluado->evaluador_id,
                'evaluado_id' => $this->evaluado->id,
                'tipo_objetivo_id' => $this->tipo_objetivo_id2,
            ]);
        }

        $this->evaluadorHasEvaluado->realizado = 1;
        $this->evaluadorHasEvaluado->save();
        
        $this->emit('openGraciasModal');
    }

    public function anterior() {
        if ($this->seccion_index_select > 0) {
            $this->seccion_index_select = $this->seccion_index_select - 1;
        }
        return;
    }

    public function siguiente() {
        $seccion = $this->secciones[$this->seccion_indexs[$this->seccion_index_select]]['id'];
        // Obtener el array de preguntas cuando la seccion_id de la pregunta sea igual a la variable seccion
        if ($this->evaluacion->id < 5) {
            $preguntas = array_filter($this->preguntas, function ($pregunta) use ($seccion) {
                return $pregunta['seccion_id'] == $seccion;
            });
        }

        $preguntas = array_filter($this->preguntas, function ($pregunta) use ($seccion) {
            return $pregunta['campania_has_competencia_id'] == $seccion;
        });

        $rules = [];
        foreach ($preguntas as $key => $value) {
            $rules['preguntas.'.$key.'.valor'] = 'required|between:1,10|Integer';
        }

        $this->validate(
            $rules,
        [
            'preguntas.*.valor.required' => 'Debe responder todas las preguntas.',
        ]);
        
        try {
            $this->validarComentarioSeccionActual();
        } catch (\RuntimeException $e) {
            return; // corta el flujo si falta comentario
        }

        if ($this->seccion_index_select < count($this->seccion_indexs)-1) {
            $this->seccion_index_select = $this->seccion_index_select + 1;
        }
    }

    public function marcarValor($index,$valor)
    {
        $this->preguntas[$index]['valor'] = $valor;
    }

    public function confirmarGuardado()
    {
        // Validar que todas las preguntas hayan sido respondidas
        $this->validate([
            'preguntas.*.valor' => 'required|between:1,10|Integer',
        ],
        [
            'preguntas.*.valor.required' => 'Debe responder todas las preguntas.',
        ]);

        // Validación de COMENTARIOS por sección (obligatorios)
        if ($this->comentarioObligatorio) {
            $rulesComentarios = [];
            foreach ($this->secciones as $sec) {
                $sid = $sec['id'];
                $rulesComentarios["comentarios.$sid"] = 'required|string|min:3';
            }
            $this->validate($rulesComentarios, [
                'comentarios.*.required' => 'Debe justificar la puntuación en cada competencia.',
            ]);
        }

        // Verificar que todas las preguntas hayan sido respondidas
        foreach ($this->preguntas as $key => $value) {
            if ($value['valor'] == null) {
                session()->flash('message-danger', 'Debe responder todas las preguntas.');
                return;
            }
        }
        $this->emit('confirmacionModal');
    }
    
    public function volver_a_preguntas()
    {
        $this->emit('closeModal');
    }

    public function guardar()
    {
        // Validar que todas las preguntas hayan sido respondidas
        $this->validate([
            'preguntas.*.valor' => 'required|between:1,10|Integer',
        ],
        [
            'preguntas.*.valor.required' => 'Debe responder todas las preguntas.',
        ]);

        // Validación de COMENTARIOS por sección (obligatorios)
        if ($this->comentarioObligatorio) {
            $rulesComentarios = [];
            foreach ($this->secciones as $sec) {
                $sid = $sec['id'];
                $rulesComentarios["comentarios.$sid"] = 'required|string|min:3';
            }
            $this->validate($rulesComentarios, [
                'comentarios.*.required' => 'Debe justificar la puntuación en cada competencia.',
            ]);
        }

        // Verificar que todas las preguntas hayan sido respondidas
        foreach ($this->preguntas as $key => $value) {
            if ($value['valor'] == null) {
                session()->flash('message-danger', 'Debe responder todas las preguntas.');
                return;
            }
        }
        // validamos si EvaluadorHasEvaluado no está realizado
        $evaluacionRealizada = EvaluadorHasEvaluado::where('id', $this->evaluadorHasEvaluado->id)->first();
        if ($evaluacionRealizada->realizado == 1) {
            session()->flash('message-danger', 'Esta evaluación ya fue realizada y guardada anteriormente.');
            return redirect()->to('/evaluaciones-de-desempeno/1');
        } else {
            // Guardar las respuestas en el modelo Respuesta
            foreach ($this->preguntas as $key => $value) {
                // crear o actualizar respuesta siendo claves unicas : evaluado_id, pregunta_id y valor = calor_numerico
                Respuesta::create([
                    'evaluado_id' => $this->evaluado->id,
                    'pregunta_id' => $value['id'],
                    'valor_numerico' => $value['valor'],
                    'peso' => $this->evaluadorHasEvaluado->peso_prorrateado,
                    'campania_id' => $this->evaluadorHasEvaluado->campania_id,
                    // 'tipo_relacion_jerarquica_id' => $this->evaluadorHasEvaluado->relacion_jerarquica_id,
                ]);
            }
            // Guardar COMENTARIOS (al final)
            $campaniaId = $this->evaluadorHasEvaluado->campania_id;
            $evaluadoId = $this->evaluadorHasEvaluado->evaluado_id;
            $tipoRelacionId = $this->evaluadorHasEvaluado->relacion_jerarquica_id; // JEFE, PAR, etc.

            foreach ($this->secciones as $sec) {
                $sid = $sec['id'];
                $texto = trim((string)($this->comentarios[$sid] ?? ''));
                EvaluadorHasEvaluadoComentario::updateOrCreate(
                    [
                        'evaluado_id' => $evaluadoId,
                        'campania_id' => $campaniaId,
                        'campania_has_competencia_id' => $sid,
                        'tipo_relacion_jerarquica_id' => $tipoRelacionId,
                    ],
                    [
                        'comentario' => $texto,
                    ]
                );
            }
    
            // Cambiar el estado de la evaluacion a realizado = 1
            $this->evaluadorHasEvaluado->realizado = 1;
            $this->evaluadorHasEvaluado->save();
            
            // voy a revisar si en EvaluadorHasEvaluado , el evaluado_id de este $this->evaluadorHasEvaluado tiene tdos sus evaluaciones realizadas(en esta campaña)
            // luego voy a correr la función de resumen de respuestas
            $evaluadoId = $this->evaluadorHasEvaluado->evaluado_id;
            $campaniaId = $this->evaluadorHasEvaluado->campania_id;
            $evaluacionesRealizadas = EvaluadorHasEvaluado::where('evaluado_id', $evaluadoId)
                ->where('campania_id', $campaniaId)
                ->where('realizado', 1)
                ->count();
            $totalEvaluaciones = EvaluadorHasEvaluado::where('evaluado_id', $evaluadoId)
                ->where('campania_id', $campaniaId)
                ->count();
            // si las evaluaciones realizadas son iguales al total de evaluaciones, entonces se puede correr la función de resumen de respuestas
            if ($evaluacionesRealizadas == $totalEvaluaciones) {
                // correr la función de resumen de respuestas
                // ResumenRespuestasEvaluacionDesempenoCompetencia::actualizarResumen($campaniaId, $evaluadoId);
                // Actualizar el resumen de respuestas
                $this->actualizarResumenRespuestas($campaniaId, $evaluadoId);
            }
            // Emitir el evento para abrir el modal de gracias

    
            $this->emit('openGraciasModal');
        }
    }

    public function actualizarResumenRespuestas($campaniaId, $evaluadoId)
    {
        // Limpia la tabla resumen
        ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
            ->where('personal_id', $evaluadoId)
            ->delete();

        // Agrupa por campania, competencia y pregunta
        $respuestas = Respuesta::with('pregunta')
            ->where('campania_id', $campaniaId)
            ->where('evaluado_id', $evaluadoId)
            ->get()
            ->groupBy(function($item) {
                return $item->campania_id . '-' . $item->evaluado_id . '-' . $item->pregunta->seccion_id . '-' . $item->pregunta_id;
            });

        foreach ($respuestas as $key => $grupo) {
            $primera = $grupo->first();
            $competencia_id = $primera->pregunta->seccion_id ?? null;
            $area_id = $primera->area_de_evaluado ?? null;

            $total_peso = $grupo->sum('peso');
            $puntaje = $total_peso > 0 ? $grupo->sum(function($r) { return $r->valor_numerico * $r->peso; }) / $total_peso : null;

            ResumenRespuestasEvaluacionDesempenoCompetencia::updateOrCreate(
                [
                    'personal_id' => $primera->evaluado_id,
                    'competencia_id' => $competencia_id,
                    'pregunta_id' => $primera->pregunta_id,
                    'area_id' => $area_id,
                    'campania_id' => $primera->campania_id,
                ],
                [
                    'puntaje' => $puntaje,
                ]
            );
        }

        // actualizar el campaniaHasEvaluado con el puntaje promedio
        $puntajePromedio = ResumenRespuestasEvaluacionDesempenoCompetencia::where('campania_id', $campaniaId)
            ->where('personal_id', $evaluadoId)
            ->avg('puntaje');
        $campaniaHasEvaluado = CampaniaHasEvaluado::where('campania_id', $campaniaId)
            ->where('personal_id', $evaluadoId)
            ->first();
        if ($campaniaHasEvaluado) {
            $campaniaHasEvaluado->puntaje_de_evaluacion_de_competencias = $puntajePromedio;
            $campaniaHasEvaluado->evaluacion_de_competencias_completada = true;
            $campaniaHasEvaluado->save();
        }

    }

    public function cancelar()
    {
        // Volver a /evaluaciones_de_desempeno
        return redirect()->to('/evaluaciones-de-desempeno/1');
    }
        
    // Modificar el método aceptar para guardar el estado del checkbox
    public function aceptar()
    {
        $this->validate([
            'acepto_escala' => 'required|accepted',
        ], [
            'acepto_escala.required' => 'Debes leer y entender la definición de la escala.',
            'acepto_escala.accepted' => 'Debes confirmar que has leído y entendido la escala.',
        ]);
        
        $this->evaluadorHasEvaluado->acepto_escala = true;
        $this->evaluadorHasEvaluado->save();
        
        $this->aceptado = true;
    }

    public function volver()
    {
        // Volver a /evaluaciones_de_desempeno
        return redirect()->to('/evaluaciones-de-desempeno/1');
    }
    
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
    
    private function resetInput()
    {       
        $this->eid = null;
        $this->title = null;
        $this->date = null;
        $this->status = null;
    }

}
