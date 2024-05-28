<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluacione;
use App\Models\TipoDeEvaluacione;
use Illuminate\Support\Facades\Notification;

class Evaluaciones extends Component
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
    $tipo_de_evaluacion_id,
    $minimo,
    $maximo,
    $fecha_inicio_primera_fase_matricula,
    $fecha_fin_primera_fase_matricula,
    $fecha_inicio_segunda_fase,
    $fecha_fin_segunda_fase,
    $tipos;

    // protected $rules = [
    //     'title' => 'required',

    // ];
    public $updateMode = false;
    
	protected $listeners = [
        'edit',
		'selectedUpdated' => 'updateSelected'
    ];

    public function mount()
    {
        $this->tipos = TipoDeEvaluacione::get();
    }

    public function render()
    {
        $this->tipos = TipoDeEvaluacione::get();
        $evaluadores = 
                Evaluacione::
                select('evaluaciones.title', 'personal.correo_empresa as correo')
                ->join('evaluador_has_evaluados', 'evaluaciones.id', '=', 'evaluador_has_evaluados.evaluacion_id')
                ->join('personal', 'evaluador_has_evaluados.evaluador_id', '=', 'personal.id')
        //        ->pluck('personal.correo_empresa,personal.correo_empresa')
                ->whereNull('evaluador_has_evaluados.realizado')
                ->whereNull('evaluador_has_evaluados.deleted_at')
                ->whereNull('evaluaciones.deleted_at')
                ->whereNull('personal.deleted_at')
                ->where('evaluaciones.status', 1)
                // ->where('evaluaciones.id', $recordatorio->id_evaluacion)
                ->groupBy('personal.correo_empresa')
                ->get()->pluck('correo_empresa');

                //enviar notificacion a todos estos correos
    
                // Aquí debes obtener los usuarios a los que quieres enviar la notificación
                // Por ejemplo, si tienes una relación en tu modelo Evaluacion que se llama usuarios:
                //$usuarios = $evaluacion->usuarios;
    
                
                foreach ($evaluadores as $correo) {
                    Notification::route('mail', $correo)->notify(new \App\Notifications\RecordatorioNotification());
                }
        // $recordatorios = \App\Models\Recordatorio::whereDate('fecha', '')->get();


		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.evaluaciones.view', [
            'evaluaciones' => Evaluacione::latest()
						->orWhere('eid', 'LIKE', $keyWord)
						->orWhere('title', 'LIKE', $keyWord)
						->orWhere('date', 'LIKE', $keyWord)
						->orWhere('status', 'LIKE', $keyWord)
						->paginate(10),
        ]);
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

    public function create() {
        // $this->resetInput();
        // $this->updateMode = false;
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
        $this->nombre_para_mostrar = $record->nombre_para_mostrar;
        $this->campania = $record->campania;
        $this->mes = $record->mes;
        $this->anio = $record->anio;
        $this->fecha_inicio = $record->fecha_inicio ? $record->fecha_inicio->format('Y-m-d') : '';
        $this->fecha_fin = $record->fecha_fin ? $record->fecha_fin->format('Y-m-d') : '';
        $this->identificador = $record->identificador;
        $this->tipo_de_evaluacion_id = $record->tipo_de_evaluacion_id;
        $this->minimo = $record->minimo;
        $this->maximo = $record->maximo;
        $this->fecha_inicio_primera_fase_matricula = $record->fecha_inicio_primera_fase_matricula ? $record->fecha_inicio_primera_fase_matricula->format('Y-m-d') :'';
        $this->fecha_fin_primera_fase_matricula = $record->fecha_fin_primera_fase_matricula ? $record->fecha_fin_primera_fase_matricula->format('Y-m-d') : '';
        $this->fecha_inicio_segunda_fase = $record->fecha_inicio_segunda_fase ? $record->fecha_inicio_segunda_fase->format('Y-m-d') : '';
        $this->fecha_fin_segunda_fase = $record->fecha_fin_segunda_fase ? $record->fecha_fin_segunda_fase->format('Y-m-d') : '';
        $this->tipos = $record->tipos;

        $this->updateMode = true;
    }

    public function update()
    {
        if($this->tipo_de_evaluacion_id == 2)
        {
            $this->validate([
                'tipo_de_evaluacion_id' => 'required',
                // 'eid' => 'required',
                'title' => 'required',
                // 'date' => 'required',
                'status' => 'required',
                'nombre_para_mostrar' => 'required',
                'campania' => 'required',
                // 'mes' => 'required',
                // 'anio' => 'required',
                //fecha inicio menor o igual fecha final
                //fechoa_fin mayor o igual a fecha inicio
                'fecha_inicio' => 'required|before_or_equal:fecha_fin',
                'fecha_fin' => 'required|after_or_equal:fecha_inicio',
                // 'identificador' => 'required|unique:evaluaciones,identificador,',$this->selected_id,
                'minimo'=>'required_if:tipo_de_evaluacion_id,2|numeric|lt:maximo|gt:0',
                'maximo'=>'required_if:tipo_de_evaluacion_id,2|numeric|gt:minimo|',
                'fecha_inicio_primera_fase_matricula' => 'required_if:tipo_de_evaluacion_id,2',
                'fecha_fin_primera_fase_matricula' => 'required_if:tipo_de_evaluacion_id,2',
                'fecha_inicio_segunda_fase' => 'required_if:tipo_de_evaluacion_id,2',
                'fecha_fin_segunda_fase' => 'required_if:tipo_de_evaluacion_id,2'
            ]);
        } else {
            $this->validate([
                'tipo_de_evaluacion_id' => 'required',
                // 'eid' => 'required',
                'title' => 'required',
                // 'date' => 'required',
                'status' => 'required',
                'nombre_para_mostrar' => 'required',
                'campania' => 'required',
                // 'mes' => 'required',
                // 'anio' => 'required',
                //fecha inicio menor o igual fecha final
                //fechoa_fin mayor o igual a fecha inicio
                'fecha_inicio' => 'required|before_or_equal:fecha_fin',
                'fecha_fin' => 'required|after_or_equal:fecha_inicio',
                // 'identificador' => 'required|unique:evaluaciones,identificador,',$this->selected_id,
            ]);
        }
        // $this->validate([
        //     'tipo_de_evaluacion_id' => 'required',
        //     // 'eid' => 'required',
        //     'title' => 'required',
        //     // 'date' => 'required',
        //     'status' => 'required',
        //     'nombre_para_mostrar' => 'required',
        //     'campania' => 'required',
        //     // 'mes' => 'required',
        //     // 'anio' => 'required',
        //     //fecha inicio menor o igual fecha final
        //     //fechoa_fin mayor o igual a fecha inicio
        //     'fecha_inicio' => 'required|before_or_equal:fecha_fin',
        //     'fecha_fin' => 'required|after_or_equal:fecha_inicio',
        //     // 'identificador' => 'required|unique:evaluaciones,identificador,',$this->selected_id,
        //     'minimo'=>'required_if:tipo_de_evaluacion_id,2|numeric|lt:maximo|gt:0',
        //     'maximo'=>'required_if:tipo_de_evaluacion_id,2|numeric|gt:minimo|',
        //     'fecha_inicio_primera_fase_matricula' => 'required_if:tipo_de_evaluacion_id,2',
        //     'fecha_fin_primera_fase_matricula' => 'required_if:tipo_de_evaluacion_id,2',
        //     'fecha_inicio_segunda_fase' => 'required_if:tipo_de_evaluacion_id,2',
        //     'fecha_fin_segunda_fase' => 'required_if:tipo_de_evaluacion_id,2'
        // ]);
    
        if ($this->selected_id) {
            $record = Evaluacione::find($this->selected_id);
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
                'tipo_de_evaluacion_id' => $this->tipo_de_evaluacion_id,
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
            session()->flash('message', 'Evaluacion actualizada correctamente.');
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
