<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Evaluacione;
use Illuminate\Support\Facades\Notification;

class Evaluaciones extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $eid, $title, $date, $status;
    public $updateMode = false;
    
	protected $listeners = [
        'edit',
		'selectedUpdated' => 'updateSelected'
    ];

    public function render()
    {
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
            'title' => 'required',
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
            'title' => 'required',
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
