<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EvaluadorHasEvaluado;

class EvaluadorHasEvaluados extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $evaluador_id, $evaluado_id, $evaluacion;
    public $updateMode = false;
    public $view_alternative = false;
    public $tipo_de_evaluacion_id;

    public function mount($tipo_de_evaluacion_id)
    {
        $this->tipo_de_evaluacion_id = $tipo_de_evaluacion_id;
    }
    
    public function render()
    {
        // dd(auth()->user()->personal->id);
		$keyWord = '%'.$this->keyWord .'%';

        $id_personal = auth()->user()->personal->id;
                $realizados = EvaluadorHasEvaluado::
                where('evaluador_has_evaluados.evaluador_id',$id_personal)
                ->where('evaluaciones.tipo_de_evaluacion_id',$this->tipo_de_evaluacion_id)
                ->where('realizado',1)
                ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                ->count();
                
                $total = EvaluadorHasEvaluado::
                where('evaluador_has_evaluados.evaluador_id',$id_personal)
                ->where('evaluaciones.tipo_de_evaluacion_id',$this->tipo_de_evaluacion_id)
                ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
                ->count();

                //mostrar una barra de progreso
                $porcentaje =  $total == 0 ? 0 : ($realizados/$total)*100;
                $porcentaje = round($porcentaje,2);
                
                if ($realizados == 0) {
                    $class = 'bg-secondary';
                    $porcentaje = 100;
                    $label = '0%';
                } else if ($total == $realizados) {
                    $class = 'bg-primary';
                    $label = $porcentaje.'%';
                } else {
                    $class = 'bg-primary';
                    $label = $porcentaje.'%';
                }

        return view('livewire.evaluador-has-evaluados.view', [
            'evaluadorHasEvaluados' => EvaluadorHasEvaluado::
            latest('evaluador_has_evaluados.created_at')
            ->select('evaluador_has_evaluados.*')
            ->where('evaluador_has_evaluados.evaluador_id', '=', $id_personal)
            ->where('evaluaciones.tipo_de_evaluacion_id',$this->tipo_de_evaluacion_id)
            ->join('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            ->with('evaluacion')						
            // ->orWhere('evaluador_id', 'LIKE', $keyWord)
						// ->orWhere('evaluado_id', 'LIKE', $keyWord)
						// ->orWhere('evaluacion_id', 'LIKE', $keyWord)
						->paginate(10),
            // 'barra' => $barra,
            'class' => $class,
            'porcentaje' => $porcentaje,
            'label' => $label
        ]);
    }
	
    public function changeView() {
        // dd('hola munda');
        $this->view_alternative = !$this->view_alternative;
    }
    
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->evaluador_id = null;
		$this->evaluado_id = null;
		$this->evaluacion = null;
    }

    public function store()
    {
        $this->validate([
        ]);

        EvaluadorHasEvaluado::create([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'EvaluadorHasEvaluado creado correctamente.');
    }

    public function edit($id)
    {
        $record = EvaluadorHasEvaluado::findOrFail($id);

        $this->selected_id = $id; 
		$this->evaluador_id = $record-> evaluador_id;
		$this->evaluado_id = $record-> evaluado_id;
		$this->evaluacion = $record-> evaluacion;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = EvaluadorHasEvaluado::find($this->selected_id);
            $record->update([ 
			'evaluador_id' => $this-> evaluador_id,
			'evaluado_id' => $this-> evaluado_id,
			'evaluacion' => $this-> evaluacion
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'EvaluadorHasEvaluado actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EvaluadorHasEvaluado::where('id', $id);
            $record->delete();
        }
    }
}
