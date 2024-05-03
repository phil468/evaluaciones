<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\Competencia;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\EncargadosPlanesDeAccion;
use App\Models\EstadosDePlanDeAccion;
use App\Models\Gerencia;
use App\Models\Personal;
use App\Models\PlanesDeAccion;
use App\Models\Proceso;
use App\Models\Respuesta;
use Illuminate\Support\Facades\DB;

class EncargadosPlanesDeAccions extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $encargado_id, $empleado_id, $evaluacion_id, $realizado;
    public $updateMode = false;
    public $ingreso = null;
    public $empleado_ids = null;
    public $secciones;
    public $dashboard;
    public $nombreEmpleado;
public $competencias ;
public $procesos 	;
public $estados 		;
public $gerencias 	;
public $areas 		;
public $personals 	;
public $competencia_id;
public $proceso_id;
public $estado_id;
public $name,$fecha_de_revision,$avance,$tipo_de_proceso_id,$gerencia_id,$area_id;
// 'encargado_id' => $this-> encargado_id,
// 'empleado_id' => $this-> empleado_id,
// 'competencia_id' => $this-> competencia_id,
// 'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
// 'proceso_id' => $this-> proceso_id,
// 'fecha_de_revision' => $this-> fecha_de_revision,
// 'estado_id' => $this-> estado_id,
// 'gerencia_id' => $this-> gerencia_id,
// 'area_id' => $this-> area_id,
// 'avance' => $this-> avance,
// 'name' => $this-> name

protected $listeners = [
    'setCompetenciaId' => 'setCompetenciaId'
    ,'setEstadoId' => 'setEstadoId'
    ,'setAvance' => 'setAvance'
];

public function setCompetenciaId($competencia_id)
{
    $this->competencia_id = $competencia_id;
}
public function setEstadoId($competencia_id)
{
    $this->estado_id = $competencia_id;
}
public function setAvance($competencia_id)
{
    $this->avance = $competencia_id;
}

    public function mount($ingreso = null, $empleado_id = null, $dashboard = null)
    {
        $this->competencias 	= Competencia::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->procesos 		= Proceso::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->estados 			= EstadosDePlanDeAccion::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->gerencias 		= Gerencia::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->areas 			= Area::orderBy('name','asc')->where('estado',1)->pluck('name','id');
        $this->personals 		= Personal::orderBy('name','asc')->where('estado',1)->pluck('name','id');

        if ($ingreso == 'ingreso') {
            $this->ingreso = true;
            $this->empleado_ids = EncargadosPlanesDeAccion::where('encargado_id', auth()->user()->personal->id)->pluck('empleado_id');
            $this->secciones = Respuesta::with('pregunta.seccion')
            ->select('preguntas.seccion_id as seccion_id','secciones.name as nombre', DB::raw('avg(valor_numerico) as promedio'))
            ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
            ->join('secciones', 'preguntas.seccion_id', '=', 'secciones.id')
            ->groupBy('preguntas.seccion_id')
            ->get();
        } else {
            $this->ingreso = false;
            $this->encargado_id = auth()->user()->personal->id;
            $this->empleado_id = $empleado_id;
        }

        if ($dashboard == 'dashboard') {
            $this->dashboard = true;
            $this->empleado_id = $empleado_id;
        }
    }

    public function render()
    {
        if ($this->dashboard) {
            $this->secciones = Respuesta::with('pregunta.seccion')
            ->select('preguntas.seccion_id','secciones.name as nombre', DB::raw('avg(valor_numerico) as promedio'))
                ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
                ->join('secciones', 'preguntas.seccion_id', '=', 'secciones.id')
                ->groupBy('preguntas.seccion_id')
                ->where('respuestas.evaluado_id', $this->empleado_id)
                ->get();

                $this->proceso_id = 1;
            $this->nombreEmpleado = Personal::find($this->empleado_id)->name;

            return view('livewire.encargados-planes-de-accion.view', [
                'nombreEmpleado' => $this->nombreEmpleado,
                'planesDeAccions' => PlanesDeAccion::latest()
                // ->when($this->encargado_id, function ($query, $encargado_id) {
                //     return $query->where('encargado_id', $encargado_id);
                // })
                ->when($this->empleado_id, function ($query, $empleado_id) {
                    return $query->where('empleado_id', $empleado_id);
                })
                ->get()
            ]);
        }

        if ($this->ingreso) {
            // dd(auth()->user()->personal->id);
            // $encargadosPlanesDeAccions = EncargadosPlanesDeAccion::latest()
            // ->where('encargado_id', auth()->user()->personal->id)->get();
            return view('livewire.encargados-planes-de-accion.view', [
                'encargadosPlanesDeAccions' => 
                EncargadosPlanesDeAccion::latest()
            ->where('encargado_id', auth()->user()->personal->id)
            // $encargadosPlanesDeAccions
                            // ->when($this->empleado_ids, function ($query, $empleado_ids) {
                            //     return $query->whereIn('empleado_id', $empleado_ids);
                            // })
                            ->paginate(10)
                            ,
                'planesDeAccions' => PlanesDeAccion::latest()
                            ->where('empleado_id', auth()->user()->personal->id)
                            // ->when($this->encargado_id, function ($query, $encargado_id) {
                            //     return $query->where('encargado_id', $encargado_id);
                            // })
                            // ->when($this->empleado_id, function ($query, $empleado_id) {
                            //     return $query->where('empleado_id', $empleado_id);
                            // })						
                            // ->orWhere('encargado_id', 'LIKE', $keyWord)
                            // ->orWhere('empleado_id', 'LIKE', $keyWord)
                            // ->orWhere('competencia_id', 'LIKE', $keyWord)
                            // ->orWhere('tipo_de_proceso_id', 'LIKE', $keyWord)
                            // ->orWhere('proceso_id', 'LIKE', $keyWord)
                            // ->orWhere('fecha_de_revision', 'LIKE', $keyWord)
                            // ->orWhere('estado_id', 'LIKE', $keyWord)
                            // ->orWhere('gerencia_id', 'LIKE', $keyWord)
                            // ->orWhere('area_id', 'LIKE', $keyWord)
                            // ->orWhere('avance', 'LIKE', $keyWord)
                            // ->orWhere('name', 'LIKE', $keyWord)
                            ->paginate(10)
                            ,
            ]);
        }

        return view('livewire.encargados-planes-de-accion.view', [
            'encargadosPlanesDeAccions' => EncargadosPlanesDeAccion::latest()
                        ->when($this->empleado_ids, function ($query, $empleado_ids) {
                            return $query->whereIn('empleado_id', $empleado_ids);
                        })
                        ->paginate(10),
        ]);
            
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
    
    public function cancel_plan()
    {
        $this->resetInput_plan();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->encargado_id = null;
		$this->empleado_id = null;
		$this->evaluacion_id = null;
		$this->realizado = null;
    }


	public function create() 
	{
	}
    
    public function store()
    {
        $this->validate([
        ]);

        EncargadosPlanesDeAccion::create([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'evaluacion_id' => $this-> evaluacion_id,
			'realizado' => $this-> realizado
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Encargados Planes De Mejora creado correctamente.');
    }

    public function store_plan()
    {
        $this->validate([
			'name' => 'required',
			'encargado_id' => 'required',
			'empleado_id' => 'required',
			'competencia_id' => 'required',
            'fecha_de_revision' =>'required',
			// 'tipo_de_proceso_id' => 'required',
			'proceso_id' => 'required',
			'estado_id' => 'required',
			// 'gerencia_id' => 'required',
			// 'area_id' => 'required',
			'avance' => 'required',
			]);

        PlanesDeAccion::create([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'competencia_id' => $this-> competencia_id,
			'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
			'proceso_id' => $this-> proceso_id,
			'fecha_de_revision' => $this-> fecha_de_revision,
			'estado_id' => $this-> estado_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'avance' => $this-> avance,
			'name' => $this-> name
        ]);
        
        $this->resetInput_plan();
		$this->emit('closeModal');
		session()->flash('message', 'Planes De Mejora creado correctamente.');
    }

    private function resetInput_plan()
    {		
		$this->competencia_id = null;
		$this->avance = null;
        $this->estado_id =null;
		$this->name = null;
    }
    
    public function edit_plan($id)
    {
        $record = PlanesDeAccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->encargado_id = $record-> encargado_id;
		$this->empleado_id = $record-> empleado_id;
		$this->competencia_id = $record-> competencia_id;
		$this->tipo_de_proceso_id = $record-> tipo_de_proceso_id;
		$this->proceso_id = $record-> proceso_id;
		$this->fecha_de_revision = $record-> fecha_de_revision;
		$this->estado_id = $record-> estado_id;
		$this->gerencia_id = $record-> gerencia_id;
		$this->area_id = $record-> area_id;
		$this->avance = $record-> avance;
		$this->name = $record-> name;
		
        $this->updateMode = true;
    }

    

    public function destroy_plan($id)
    {
        if ($id) {
            $record = PlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }

    public function update_plan()
    {
        $this->validate([
			'name' => 'required',
			'encargado_id' => 'required',
			'empleado_id' => 'required',
			'competencia_id' => 'required',
            'fecha_de_revision' =>'required',
			// 'tipo_de_proceso_id' => 'required',
			'proceso_id' => 'required',
			'estado_id' => 'required',
			// 'gerencia_id' => 'required',
			// 'area_id' => 'required',
			'avance' => 'required',
			]);

        if ($this->selected_id) {
			$record = PlanesDeAccion::find($this->selected_id);
            $record->update([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'competencia_id' => $this-> competencia_id,
			'tipo_de_proceso_id' => $this-> tipo_de_proceso_id,
			'proceso_id' => $this-> proceso_id,
			'fecha_de_revision' => $this-> fecha_de_revision,
			'estado_id' => $this-> estado_id,
			'gerencia_id' => $this-> gerencia_id,
			'area_id' => $this-> area_id,
			'avance' => $this-> avance,
			'name' => $this-> name
            ]);

            $this->resetInput_plan();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Planes De Mejora actualizado correctamente.');
        }
    }
    

    public function plan($id)
    {
        if ($id) {
            $record = PlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }

    public function edit($id)
    {
        $record = EncargadosPlanesDeAccion::findOrFail($id);

        $this->selected_id = $id; 
		$this->encargado_id = $record-> encargado_id;
		$this->empleado_id = $record-> empleado_id;
		$this->evaluacion_id = $record-> evaluacion_id;
		$this->realizado = $record-> realizado;
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
        ]);

        if ($this->selected_id) {
			$record = EncargadosPlanesDeAccion::find($this->selected_id);
            $record->update([ 
			'encargado_id' => $this-> encargado_id,
			'empleado_id' => $this-> empleado_id,
			'evaluacion_id' => $this-> evaluacion_id,
			'realizado' => $this-> realizado
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Encargados Planes De Mejora actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = EncargadosPlanesDeAccion::where('id', $id);
            $record->delete();
        }
    }

    public function ver($id)
    {
        $record = EncargadosPlanesDeAccion::findOrFail($id);
        $this->selected_id = $id; 
        $this->encargado_id = $record-> encargado_id;
        $this->empleado_id = $record-> empleado_id;
        $this->evaluacion_id = $record-> evaluacion_id;
        $this->realizado = $record-> realizado;
        $this->nombreEmpleado = Personal::find($this->empleado_id)->name;
        
        $this->updateMode = true;

        $this->secciones = Respuesta::with('pregunta.seccion')
            ->select('preguntas.seccion_id', DB::raw('avg(valor_numerico) as promedio'))
            ->join('preguntas', 'respuestas.pregunta_id', '=', 'preguntas.id')
            ->groupBy('preguntas.seccion_id')
            ->where('evaluado_id', $this->empleado_id)
            ->get();


            // dd($secciones);
        redirect()->route('planes-de-mejora', ['dashboard' => 'dashboard','empleado_id'=>$this->empleado_id]);

        // return view('livewire.encargados-planes-de-accion.ver', [
        //     'encargadosPlanesDeAccions' => EncargadosPlanesDeAccion::latest()
        //                 ->where('id', $id)
        //                 ->paginate(10),
        //     'secciones' => $this->secciones

        // ]);
    }
}
