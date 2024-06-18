<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluacione;
use App\Models\PlanesConfiguracion as ModelsPlanesConfiguracion;
use App\Models\TipoDeEvaluacione;

class PlanesConfiguracion extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $eid, $title, $date, $status,
    $nombre_para_mostrar,
    $campania,
    $mes,
    $anio,
    $fecha_inicio,
    $fecha_fin,
    $identificador,
    $minimo,
    $maximo,
    $fecha_inicio_primera_fase_matricula,
    $fecha_fin_primera_fase_matricula,
    $fecha_inicio_segunda_fase,
    $fecha_fin_segunda_fase,
    $tipos;

    public $updateMode = false;
    
    protected $rules = [
        'title' => 'required',
        'status' => 'required',
        'nombre_para_mostrar' => 'required',
        'campania' => 'required',
        'fecha_inicio' => 'required|before_or_equal:fecha_fin',
        'fecha_fin' => 'required|after_or_equal:fecha_inicio',
        'fecha_inicio_primera_fase_matricula' => 
        'required|before_or_equal:fecha_fin|before_or_equal:fecha_fin_primera_fase_matricula|before_or_equal:fecha_inicio_segunda_fase|after_or_equal:fecha_inicio',
        'fecha_fin_primera_fase_matricula' => 
        'required|after_or_equal:fecha_inicio_primera_fase_matricula|before_or_equal:fecha_fin|before_or_equal:fecha_inicio_segunda_fase|after_or_equal:fecha_inicio',
        'fecha_inicio_segunda_fase' => 
        'required|after_or_equal:fecha_fin_primera_fase_matricula|before_or_equal:fecha_fin|after_or_equal:fecha_inicio|before_or_equal:fecha_fin_segunda_fase',
        'fecha_fin_segunda_fase' => 
        'required|after_or_equal:fecha_inicio_segunda_fase|after_or_equal:fecha_fin_primera_fase_matricula|before_or_equal:fecha_fin|after_or_equal:fecha_inicio'
    ];

    protected $validationAttributes = 
	[
        'title' => 'Título',
        'status' => 'Estado',
        'nombre_para_mostrar' => 'Nombre para mostrar',
        'campania' => 'Campaña',
        'fecha_inicio' => 'Fecha de inicio',
        'fecha_fin' => 'Fecha de fin',
        'minimo'=>'Mínimo',
        'maximo'=>'Máximo',
        'fecha_inicio_primera_fase_matricula' => 'Fecha de inicio de la primera fase (Matrícula)',
        'fecha_fin_primera_fase_matricula' => 'Fecha de fin de la primera fase (Matrícula)',
        'fecha_inicio_segunda_fase' => 'Fecha de inicio de la segunda fase',
        'fecha_fin_segunda_fase' => 'Fecha de fin de la segunda fase',
    
	];

    protected $messages = [
        'fecha_inicio.before_or_equal' => 'La fecha de inicio debe ser menor o igual a la fecha de fin.',
        'fecha_fin.after_or_equal' => 'La fecha de fin debe ser mayor o igual a la fecha de inicio.',
        'minimo.required_if' => 'El campo Resultado mínimo es obligatorio cuando el tipo de evaluación es "Evaluación de desempeño por objetivos"',
        'maximo.required_if' => 'El campo Resultado máximo es obligatorio cuando el tipo de evaluación es "Evaluación de desempeño por objetivos"',
        'fecha_inicio_primera_fase_matricula.required_if' => 'El campo Fecha de inicio de la primera fase (Matrícula) es obligatorio cuando el tipo de evaluación es "Evaluación de desempeño por objetivos"',
        'fecha_fin_primera_fase_matricula.required_if' => 'El campo Fecha de fin de la primera fase (Matrícula) es obligatorio cuando el tipo de evaluación es "Evaluación de desempeño por objetivos"',
        'fecha_inicio_segunda_fase.required_if' => 'El campo Fecha de inicio de la segunda fase es obligatorio cuando el tipo de evaluación es "Evaluación de desempeño por objetivos"',
        'fecha_fin_segunda_fase.required_if' => 'El campo Fecha de fin de la segunda fase es obligatorio cuando el tipo de evaluación es "Evaluación de desempeño por objetivos"',
    ];

	protected $listeners = [
        'editPlanes' => 'edit',
		'selectedUpdated' => 'updateSelected'
    ];

    public function mount()
    {
        $this->tipos = TipoDeEvaluacione::get();
    }

    public function render()
    {
        $this->tipos = TipoDeEvaluacione::get();
        
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.planes-configuracion.view');
    }
	
    public function cancel()
    {
        $this->resetInput();
		$this->resetValidation();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->eid = null;
		$this->title = null;
		$this->date = null;
		$this->status = null;
        $this->nombre_para_mostrar = null;
        $this->campania = null;
        $this->mes = null;
        $this->anio = null;
        $this->fecha_inicio = null;
        $this->fecha_fin = null;
        $this->identificador = null;
        $this->minimo = null;
        $this->maximo = null;
        $this->fecha_inicio_primera_fase_matricula = null;
        $this->fecha_fin_primera_fase_matricula = null;
        $this->fecha_inicio_segunda_fase = null;
        $this->fecha_fin_segunda_fase = null;
    }

    public function create() {
        // $this->resetInput();
        // $this->updateMode = false;
    }

    public function store()
    {
        $rules = $this->rules;
        $rules['identificador'] = 'required|unique:planes_de_accion_configuracion,identificador';
        $this->validate($rules);

        ModelsPlanesConfiguracion::create([ 
            'eid' => $this->eid,
            'title' => $this->title,
            'date' => $this->date,
            'status' => $this->status,
            'nombre_para_mostrar' => $this->nombre_para_mostrar,
            'campania' => $this->campania,
            'mes' => $this->mes,
            'anio' => $this->anio,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'identificador' => $this->identificador,
            'minimo' => $this->minimo,
            'maximo' => $this->maximo,
            'fecha_inicio_primera_fase_matricula' => $this->fecha_inicio_primera_fase_matricula,
            'fecha_fin_primera_fase_matricula' => $this->fecha_fin_primera_fase_matricula,
            'fecha_inicio_segunda_fase' => $this->fecha_inicio_segunda_fase,
            'fecha_fin_segunda_fase' => $this->fecha_fin_segunda_fase,
        ]);
    
        $this->resetInput();
        $this->updateMode = false;
        $this->emit('closeModal');
        $this->emit('refreshPlanes');
        session()->flash('message', 'Evaluacion creado correctamente.');

    }

    public function edit($id)
    {
        $this->emit('openUpdatePlanesConfiguracionModal');
		if ($id != 0) {
			$this->resetValidation();
			$this->resetInput();

            $record = ModelsPlanesConfiguracion::findOrFail($id);

            $this->selected_id = $id; 
            $this->eid = $record-> eid;
            $this->title = $record-> title;
            $this->date = $record-> date;
            $this->status = $record-> status;
            $this->nombre_para_mostrar = $record->nombre_para_mostrar;
            $this->campania = $record->campania;
            $this->mes = $record->mes;
            $this->anio = $record->anio;
            $this->fecha_inicio = $record->fecha_inicio ? 
            date('Y-m-d\TH:i', strtotime($record->fecha_inicio)) 
            : '';
            $this->fecha_fin = $record->fecha_fin ? 
            date('Y-m-d\TH:i', strtotime($record->fecha_fin)) 
            : '';
            $this->identificador = $record->identificador;
            $this->fecha_inicio_primera_fase_matricula = $record->fecha_inicio_primera_fase_matricula 
            ? date('Y-m-d\TH:i', strtotime($record->fecha_inicio_primera_fase_matricula)) :'';
            $this->fecha_fin_primera_fase_matricula = $record->fecha_fin_primera_fase_matricula 
            ? date('Y-m-d\TH:i', strtotime($record->fecha_fin_primera_fase_matricula)) : '';
            $this->fecha_inicio_segunda_fase = $record->fecha_inicio_segunda_fase 
            ? date('Y-m-d\TH:i', strtotime($record->fecha_inicio_segunda_fase)) : '';
            $this->fecha_fin_segunda_fase = $record->fecha_fin_segunda_fase 
            ? date('Y-m-d\TH:i', strtotime($record->fecha_fin_segunda_fase)) : '';
            $this->tipos = $record->tipos;
		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0; 
			$this->status=true;
		}

        $this->updateMode = true;
    }

    public function update()
    {
        $rules = $this->rules;
        $rules['identificador'] = 'required|unique:planes_de_accion_configuracion,identificador,'.$this->selected_id;
        $this->validate($rules);

    
        if ($this->selected_id) {
            $record = ModelsPlanesConfiguracion::find($this->selected_id);
            $record->update([ 
                'eid' => $this->eid,
                'title' => $this->title,
                'date' => $this->date,
                'status' => $this->status,
                'nombre_para_mostrar' => $this->nombre_para_mostrar,
                'campania' => $this->campania,
                'mes' => $this->mes,
                'anio' => $this->anio,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'identificador' => $this->identificador,
                'fecha_inicio_primera_fase_matricula' => $this->fecha_inicio_primera_fase_matricula,
                'fecha_fin_primera_fase_matricula' => $this->fecha_fin_primera_fase_matricula,
                'fecha_inicio_segunda_fase' => $this->fecha_inicio_segunda_fase,
                'fecha_fin_segunda_fase' => $this->fecha_fin_segunda_fase,
            ]);
    
            $this->resetInput();
            $this->updateMode = false;
            $this->emit('closeModal');
            $this->emit('refreshPlanes');
            session()->flash('message', 'Evaluacion actualizada correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ModelsPlanesConfiguracion::where('id', $id);
            $record->delete();
        }
    }

}
