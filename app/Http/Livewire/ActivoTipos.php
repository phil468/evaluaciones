<?php

namespace App\Http\Livewire;

use App\Models\Accesorio;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ActivoTipo;
use App\Models\CamposTipoActivo;
use Illuminate\Support\Facades\DB;

class ActivoTipos extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $name, $estado
    , $accesorio=[]
    , $campos_tipo_activo=[]
    ;
    public $updateMode = false;

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.activo-tipos.view', [
            'activoTipos' => ActivoTipo::latest()->with('accesorios')
						->orWhere('name', 'LIKE', $keyWord)
						->orWhere('estado', 'LIKE', $keyWord)
						->paginate(10),						
			'accesorios' => Accesorio::get(),
			'campos_tipo_activos' => CamposTipoActivo::get(),

        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->name = null;
		$this->estado = null;
        $this->accesorio = [];
        $this->campos_tipo_activo = [];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required'
        ]);

        $record = ActivoTipo::create([ 
			'name' => $this-> name,
			'estado' => $this-> estado
        ]);
        
        $record->accesorios()->attach($this->accesorio);
        $record->campos()->attach($this->campos_tipo_activo);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Activo Tipo creado correctamente.');
    }

    public function edit($id)
    {
        $this->accesorio
        = DB::table("accesorio_has_activo_tipos")->where("accesorio_has_activo_tipos.activo_tipo_id",$id)
        ->pluck('accesorio_has_activo_tipos.accesorio_id')
        ->all();
        
        $this->campos_tipo_activo
        = DB::table("activo_tipo_has_campo")->where("activo_tipo_has_campo.activo_tipo_id",$id)
        ->pluck('activo_tipo_has_campo.campo_id')
        ->all();

        $record = ActivoTipo::findOrFail($id);

        $this->selected_id = $id; 
		$this->name = $record-> name;
		$this->estado = $record-> estado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required'
        ]);

        if ($this->selected_id) {
			$record = ActivoTipo::find($this->selected_id);
            $record->update([ 
			'name' => $this-> name,
			'estado' => $this-> estado
            ]);

            $record->accesorios()->detach();
            $record->accesorios()->attach($this->accesorio);

            $record->campos()->detach();
            $record->campos()->attach($this->campos_tipo_activo);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Activo Tipo actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = ActivoTipo::where('id', $id);
            $record->delete();
        }
    }
}
