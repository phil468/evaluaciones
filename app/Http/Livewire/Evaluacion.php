<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Objetivo;
use App\Models\Personal;
use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\TiposDeObjetivo;

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

    protected $listeners = ['guardar' => 'guardar'];

    public function mount($evaluacion_id)
    {
            $this->evaluacion_id = $evaluacion_id;
        
            // Obtener el evaluadorHasEvaluado correspondiente a la evaluacion_id
            // $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('id',$evaluacion_id)->first();
            $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::find($evaluacion_id);

            // Obtener la evaluacion correspondiente al evaluadorHasEvaluado
                // $this->evaluacion = Evaluacione::where('id',$this->evaluadorHasEvaluado->evaluacion_id)->first();
                $this->evaluacion = Evaluacione::find($this->evaluadorHasEvaluado->evaluacion_id);
    // dd($this->evaluacion);
                if ($this->evaluacion->tipo_de_evaluacion_id == 2) {
                    $this->evaluacion_por_objetivos = true;
                    // $this->evaluado = Personal::where('id',$this->evaluadorHasEvaluado->evaluado_id)->first();
                    $this->evaluado = Personal::find($this->evaluadorHasEvaluado->evaluado_id);
                } else {
                    
                    if($this->evaluadorHasEvaluado->realizado == 1){
                        $this->aceptado = true;
                        $this->realizado = true;
                    }
            
                    // Obtener las preguntas de la evaluacion con sus respectivas secciones
                    $this->preguntas = Pregunta::where('evaluacion_id',$this->evaluacion->id)->with('seccion'
                    )->orderBy('preguntas.numero_orden')->get()->toArray();
            
                    // Inicializar los valores de las preguntas en 7
                    foreach ($this->preguntas as $key => $value) {
                        $this->preguntas[$key]['valor'] = null;
                    }
            
                    // Obtener el evaluador correspondiente al evaluadorHasEvaluado
                    // $this->evaluador = Personal::where('id',$this->evaluadorHasEvaluado->evaluador_id)->first();
                    $this->evaluador = Personal::find($this->evaluadorHasEvaluado->evaluador_id);
            
                    // Obtener el evaluado correspondiente al evaluadorHasEvaluado
                    // $this->evaluado = Personal::where('id',$this->evaluadorHasEvaluado->evaluado_id)->first();
                    $this->evaluado = Personal::find($this->evaluadorHasEvaluado->evaluado_id);
            
                    // Obtener las secciones unicas de la evaluacion
                    // $this->secciones =  Evaluacione::where('id', $this->evaluadorHasEvaluado->evaluacion_id)->first()->seccionesUnicas()->toArray();
                    $this->secciones =  Evaluacione::find($this->evaluadorHasEvaluado->evaluacion_id)->seccionesUnicas()->toArray();
                    // dd($this->secciones);
                    $this->secciones = (array) $this->secciones;
                    $this->seccion_indexs = array_keys($this->secciones);
                    //seccion_index_select, debe ser el tamaño de $this->seccion_indexs menos 1
                    // $this->seccion_index_select = count($this->seccion_indexs)-1;
                    $this->seccion_index_select = 0;
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
                $class = 'bg-secondary';
                $porcentaje = 100;
                $label = '0%';
            } else if ($totalPreguntas == $totalPreguntasNoNulas) {
                $class = 'bg-primary';
                $label = $porcentaje.'%';
            } else {
                $class = 'bg-primary';
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


        //$this->descripcion2,$this->cantidad2,$this->evaluado->id,$this->tipo_objetivo_id2 que no sean vacuio ni null 
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
        $preguntas = array_filter($this->preguntas, function ($pregunta) use ($seccion) {
            return $pregunta['seccion_id'] == $seccion;
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
        // $this->emit('confirmarGuardado');
    }
    
    public function volver_a_preguntas()
    {
        $this->emit('closeModal');
    }

    public function guardar()
    {
        // Validar que todas las preguntas hayan sido respondidas
        $this->validate([
            //valor tiene que ser entre 1 y 10
            'preguntas.*.valor' => 'required|between:1,10|Integer',
        ],
        [
            'preguntas.*.valor.required' => 'Debe responder todas las preguntas.',
        ]);

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
                    'valor_numerico' => $value['valor']
                ]);
            }
    
            // Cambiar el estado de la evaluacion a realizado = 1
            $this->evaluadorHasEvaluado->realizado = 1;
            $this->evaluadorHasEvaluado->save();        
    
            $this->emit('openGraciasModal');
        }
    }

    public function cancelar()
    {
        // Volver a /evaluaciones_de_desempeno
        return redirect()->to('/evaluaciones-de-desempeno/1');
    }
        
    public function aceptar()
    {        
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

    public function store()
    {
        $this->validate([
        ]);

        Evaluacione::create([ 
            'eid' => $this-> eid,
            'title' => $this-> title,
            'date' => $this-> date,
            'status' => $this-> status
        ]);
        
        $this->resetInput();
        $this->emit('closeModal');
        session()->flash('message', 'Evaluacione creado correctamente.');
    }

    public function edit($id)
    {
        $record = Evaluacione::findOrFail($id);

        $this->selected_id = $id; 
        $this->eid = $record-> eid;
        $this->title = $record-> title;
        $this->date = $record-> date;
        $this->status = $record-> status;
        
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
            $record = Evaluacione::find($this->selected_id);
            $record->update([ 
                'eid' => $this-> eid,
                'title' => $this-> title,
                'date' => $this-> date,
                'status' => $this-> status
            ]);

            $this->resetInput();
            $this->updateMode = false;
            $this->emit('closeModal');
            session()->flash('message', 'Evaluacione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Evaluacione::where('id', $id);
            $record->delete();
        }
    }
}
