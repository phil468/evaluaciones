<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Pregunta;

class ImportarPreguntas extends Component
{
    use WithFileUploads;

    public $archivo;
    public $evaluacion_id;
    public $seccion_id;
    public $evaluaciones;
    public $secciones;

    protected $rules = [
        'archivo' => 'required|file|mimes:xlsx,xls',
        'evaluacion_id' => 'required|exists:evaluaciones,id',
        'seccion_id' => 'required|exists:secciones,id',
    ];

    public function mount()
    {
        $this->evaluaciones = \App\Models\Evaluacione::all();
        $this->secciones = \App\Models\Seccione::all();
    }

    public function import()
    {
        $this->validate();

        $resultado = Pregunta::importarPreguntas(
            $this->archivo,
            $this->evaluacion_id,
            $this->seccion_id
        );

        if ($resultado === true) {
            session()->flash('message', 'Preguntas importadas correctamente.');
            $this->emit('preguntasImportadas');
            $this->reset('archivo');
        } else {
            session()->flash('error', $resultado);
        }
    }

    public function render()
    {
        return view('livewire.importar-preguntas.view');
    }
}