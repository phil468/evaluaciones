<?php

namespace App\Http\Livewire;

use App\Exports\ActivosExport;
use App\Imports\ActivosImport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Activo;
use App\Models\ActivoTipo;
use App\Models\Area;
use App\Models\BajaMotivo;
use App\Models\Brand;
use App\Models\CamposTipoActivo;
use App\Models\Modelo;
use App\Models\Performance;
use App\Models\Personal;
use App\Models\Status;
use App\Models\Vigencium;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class Activos extends Component
{
    use WithPagination;
    use WithFileUploads;

	protected $paginationTheme = 'bootstrap';
    public $selected_id=null, $keyWord, $estado, $activo_tipo_id, $brand_id, $modelo_id, 
	$serial_number, $patrimonial_code, $status_id, $performance_id, $IMEI1, $IMEI2, $orden_compra, 
	$fecha_compra, $year, $personal_id, $fecha_asignacion, $vigencia_id, $baja_motivo_id, $created_by, $updated_by, $deleted_by, $observations, $file=null,
	$area_id,
	$regularizacion,
	$observaciones_no_asignacion,
	$ct_id,
	$notebook_id, 
	$mac,
	$mac_address,
	$campos_habilitados=[],
	$activo_tipos,
	$brands, 			
	$modelos, 			
	$statuss, 			
	$performances, 	
	$personals, 		
	$vigencias, 		
	$baja_motivos, 	
	$areas, 			
	$cargadores_laptop,
	$notebooks,
	$ct_id_add,

	$estado_stock,
	$estado_asignado,
	$estado_preasignado
	;

    public 
	$resultado=[],
	$registros_vacios=[],
	$campos_obligatorios_no_encontrados=[],
	$resultado_registros=[]
	;

	public $updateMode = false;
	public $cargando = false;
	public $actualizandoVista = true;

    protected $listeners = ['edit' => 'edit'];

	public function listarSelects() {
		$this->activo_tipos 	= 	ActivoTipo::	orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->brands 			= 	Brand::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->modelos 			= 	Modelo::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->statuss 			= 	Status::		orderBy('name')->where('estado',1)->select('name as label', 'id as value', 
		DB::raw('IF(name="Asignado",true,false) as disabled'))->get()->toArray();
		$this->performances 	=	Performance::	orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->personals 		=	Personal::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->vigencias 		=	Vigencium::		orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->baja_motivos 	=	BajaMotivo::	orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->areas 			=	Area::			orderBy('name')->where('estado',1)->select('name as label', 'id as value')->get()->toArray();
		$this->cargadores_laptop=	Activo::	orderBy('activos.serial_number')
										->where('activos.estado',1)
										->leftJoin('activo_tipos', 'activo_tipos.id', 'activos.activo_tipo_id')
										->where('activo_tipos.name','Cargador de laptop')
										->whereNull('notebook_id')
										->orWhere('activos.id', $this->ct_id)
										->select('activos.serial_number as label', 'activos.id as value')
										->get()->toArray();
		$this->notebooks 		=	Activo::	orderBy('activos.serial_number')
											->where('activos.estado',1)
											->leftJoin('activo_tipos', 'activo_tipos.id', 'activos.activo_tipo_id')
											->where('activo_tipos.name','Notebook')
											->whereNull('ct_id')
											->select('activos.serial_number as label', 'activos.id as value')
											->get()->toArray();
		$this->emit('listarSelects',
			$this->activo_tipos,
			$this->brands,
			$this->modelos,
			$this->statuss,
			$this->performances,
			$this->personals,
			$this->vigencias,
			$this->baja_motivos,
			$this->areas,
			$this->cargadores_laptop,
			// $this->notebooks,
		);
		$this->actualizarSelects();
	}
			
	public function actualizarSelects () {
		$this->emit('actualizarSelects',
			$this->activo_tipo_id,
			$this->brand_id,
			$this->modelo_id,
			$this->status_id,
			$this->performance_id,
			$this->personal_id,
			$this->vigencia_id,
			$this->baja_motivo_id,
			$this->area_id,
			$this->ct_id,
			// $this->notebook_id,
		);
	}

	public function mount() {
		
		$this->estado_stock = Status::where('name', '=', 'stock')->first()->id ?? null;
		if ($this->estado_stock == null) {
			session()->flash('message-danger', 'No se encuentra definido el estado "stock". Por favor revisar el nombre de los estados');
			$this->emit('alert-danger');
		}

		$this->estado_asignado = Status::where('name', '=', 'asignado')->first()->id ?? null;
		if ($this->estado_asignado == null) {
			session()->flash('message-danger', 'No se encuentra definido el estado asignado "asignado". Por favor revisar el nombre de los estados');
			$this->emit('alert-danger');
		}
		
		$this->estado_preasignado = Status::where('name', '=', 'preasignado')->first()->id ?? null;
		if ($this->estado_asignado == null) {
			session()->flash('message-danger', 'No se encuentra definido el estado asignado "asignado". Por favor revisar el nombre de los estados');
			$this->emit('alert-danger');
		}

		// $this->listar_selecciones();
	}

    public function render()
    {
		if($this->mac) {
			$this->mac = strtoupper(str_replace(':', '', $this->mac)); // Eliminar los dos puntos y convertir a mayúsculas
			$this->mac = chunk_split($this->mac, 2, ':'); // Agregar dos puntos cada 2 caracteres
			$this->mac = substr($this->mac, 0, strlen($this->mac) - 1);
		}
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.activos.view');
    }
	
    // public function updatedMac($value)
    // {
    //     $this->mac = strtoupper(str_replace(':', '', $value)); // Eliminar los dos puntos y convertir a mayúsculas
    //     $this->mac = chunk_split($this->mac, 2, ':'); // Agregar dos puntos cada 2 caracteres
    // }

	public function updatingActivoTipoId($id) {
	}

	public function updatedActivoTipoId($id) {
		$activo_campos = ActivoTipo::where('id',$id)->with('campos')->get();
		if(($activo_campos->count()) && ($activo_campos[0]->campos()->count())){
			$this->campos_habilitados = $activo_campos[0]->campos()->pluck('name','name')->toArray();
			isset($this->campos_habilitados['IMEI1']) 		? : $this-> IMEI1 	= null;
			isset($this->campos_habilitados['IMEI2']) 		? : $this-> IMEI2 	= null;
			isset($this->campos_habilitados['CT']) 			? : $this-> ct_id 	= null;
			isset($this->campos_habilitados['NOTEBOOK']) 	? : $this-> notebook_id = null;
			isset($this->campos_habilitados['MODELO']) 		? : $this-> modelo_id = null;
			isset($this->campos_habilitados['MAC']) 		? : $this-> mac = null;
		} else {
			$this->campos_habilitados = [];
			isset($this->campos_habilitados['IMEI1']) 		? : $this-> IMEI1 	= null;
			isset($this->campos_habilitados['IMEI2']) 		? : $this-> IMEI2 	= null;
			isset($this->campos_habilitados['CT']) 			? : $this-> ct_id 	= null;
			isset($this->campos_habilitados['NOTEBOOK']) 	? : $this-> notebook_id = null;
			isset($this->campos_habilitados['MODELO']) 		? : $this-> modelo_id = null;
			isset($this->campos_habilitados['MAC']) 		? : $this-> mac = null;
		}

		$this->actualizandoVista = true;
	}
	
    public function cancel()
    {
        $this->resetInput();
		$this->emit('limpiarDatos');
        $this->cargando = false;
        $this->actualizandoVista = true;
    }
	
    private function resetInput()
    {
		$this->selected_id = null;
		$this->estado = null;
		$this->activo_tipo_id = null;
		$this->brand_id = null;
		$this->modelo_id = null;
		$this->serial_number = null;
		$this->patrimonial_code = null;
		$this->status_id = null;
		$this->performance_id = null;
		$this->IMEI1 = null;
		$this->IMEI2 = null;
		$this->orden_compra = null;
		$this->fecha_compra = null;
		$this->year = null;
		$this->personal_id = null;
		$this->fecha_asignacion = null;
		$this->vigencia_id = null;
		$this->baja_motivo_id = null;
		$this->created_by = null;
		$this->updated_by = null;
		$this->deleted_by = null;
		$this->observations = null;
		$this->area_id = null;
		$this->regularizacion = null;
		$this->observaciones_no_asignacion = null;
		$this->ct_id = null;
		$this->notebook_id = null;
		$this->mac = null;
		$this->ct_id_add = null;
		
    }

	public function create() 
	{
		// $this->listar_selecciones();	
        $this->listarSelects();
		$this->estado=true;
        // $this->updateMode = false;
        $this->updateMode = true;
		
        $this->cargando = true;
	}

	public function cerrar_resultado()
	{
		$this->resultado=[];
	}

    public function store()
    {
		$this-> mac = isset($this->campos_habilitados['MAC']) ? $this-> mac = strtoupper(str_replace(':', '',  $this-> mac)) : null;

        $this->validate([
			'activo_tipo_id' => 'required',
			'brand_id' => 'required',			
			'modelo_id' => (isset($this->campos_habilitados['MODELO']) ? 'required' : '').'',
			'serial_number' => 'required|unique:activos,serial_number,' . $this->selected_id,
			'IMEI1' => (isset($this->campos_habilitados['IMEI1']) ? 'required' : 'nullable').'|unique:activos,imei1,' . $this->selected_id,
			'IMEI2' => 'nullable|unique:activos,imei2,' . $this->selected_id,
			
			// 'IMEI2' => 'nullable|unique:activos,imei2,' . $this->selected_id,
			'ct_id' => 'nullable|unique:activos,ct_id,' . $this->selected_id,
			// 'notebook_id' => 'nullable|unique:activos,notebook_id,' . $this->selected_id,
			'status_id' => 'required',
			'performance_id' => 'required',
			'mac' => 'nullable|size:12|unique:activos,mac,' . $this->selected_id,
        ], [], 
		[
			'activo_tipo_id'	=> 'Tipo de Activo',
			'modelo_id'			=> 'Modelo',
			'brand_id'			=> 'Marca',
			'serial_number'		=> 'Número de Serie',
			'status_id'			=> 'Estado de activo',
			'performance_id'	=> 'Condición',
			'IMEI1'				=> 'IMEI1',
			'IMEI2'				=> 'IMEI2',
		]
		);
	
		$this->mac_address = strtoupper(str_replace(':', '', $this->mac)); // Eliminar los dos puntos y convertir a mayúsculas
		$this->mac_address = chunk_split($this->mac_address, 2, ':'); // Agregar dos puntos cada 2 caracteres
		$this->mac_address = substr($this->mac_address, 0, strlen($this->mac_address) - 1);

		$this-> IMEI1 = isset($this->campos_habilitados['IMEI1']) ? $this-> IMEI1 : null;
		$this-> IMEI2 = isset($this->campos_habilitados['IMEI2']) ? $this-> IMEI2 : null;
		$this-> ct_id = isset($this->campos_habilitados['CT']) ? $this-> ct_id : null;
		$this-> modelo_id = isset($this->campos_habilitados['MODELO']) ? $this-> modelo_id : null;
		$this-> notebook_id = isset($this->campos_habilitados['NOTEBOOK']) ? $this-> notebook_id : null;

        $record = Activo::create([ 
			'estado' => $this-> estado,
			'activo_tipo_id' => $this-> activo_tipo_id,
			'brand_id' => $this-> brand_id,
			'modelo_id' => $this-> modelo_id,
			'serial_number' => $this-> serial_number,
			'patrimonial_code' => $this-> patrimonial_code,
			'status_id' => $this-> status_id,
			'performance_id' => $this-> performance_id,
			'imei1' => $this-> IMEI1,
			'imei2' => $this-> IMEI2,
			'orden_compra' => $this-> orden_compra,
			'fecha_compra' => !empty($this->fecha_compra) ? $this->fecha_compra : null, // $this-> fecha_compra,
			'year' => $this-> year,
			'personal_id' => ($this-> status_id == $this->estado_asignado || $this-> status_id == $this->estado_preasignado ) ? $this-> personal_id : null,
			'fecha_asignacion' => !empty($this->fecha_asignacion) ? $this->fecha_asignacion : null, // $this-> fecha_asignacion,
			'vigencia_id' => $this-> vigencia_id,
			'baja_motivo_id' => $this-> baja_motivo_id,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by,
			'observations' => $this-> observations,
			'area_id' => $this-> area_id,
			'regularizacion' => $this-> regularizacion,
			'observaciones_no_asignacion' => $this-> observaciones_no_asignacion,
			'ct_id' => 	$this-> ct_id,
			// 'notebook_id' => $this-> notebook_id,
			'mac' => $this-> mac,
			'mac_address' => $this-> mac_address,
        ]);

		if($this->ct_id) {
			$record2 = Activo::find($this-> ct_id);
            $record2->update([
				'notebook_id' => $record->id,
				'status_id' => $this-> status_id,
				'performance_id' => $this-> performance_id,
				'personal_id' => $this-> personal_id,
            ]);
		}
       
        $this->resetInput();
		$this->emit('limpiarDatos');
        $this->cargando = false;
		$this->actualizandoVista = true;
		$this->emit('closeModal');
		// session()->flash('message', 'Activo creado correctamente.');
		
		session()->flash('message-success', 'Activo creado correctamente.');
		$this->emit('alert-success');
    }

	public function agregar_ct() {
		$this->validate([
			'ct_id_add' => 'required|size:14'
		],[],['ct_id_add' => 'CT']);

		$activo = Activo::firstOrCreate(
			['serial_number' =>  $this->ct_id_add],
			[
				'activo_tipo_id' => 8,
				'estado' => 1,
				'brand_id' => 1,
				'status_id' => 1,
				'performance_id' => 1,
			]
		);

		if ($activo->activo_tipo_id == 8) {
			$this->cargadores_laptop=	Activo::	orderBy('activos.serial_number')
			->where('activos.estado',1)
			->leftJoin('activo_tipos', 'activo_tipos.id', 'activos.activo_tipo_id')
			->where('activo_tipos.name','Cargador de laptop')
			->whereNull('notebook_id')
			->orWhere('activos.id', $this->ct_id)
			->select('activos.serial_number as label', 'activos.id as value')
			->get()->toArray();
			
			$this->emit('listarCT',
				$this->cargadores_laptop,$activo->id
			// $this->notebooks,
			);
			$this->ct_id = $activo->id;
			$this->ct_id_add = null;
		} else {
			$error['ct_id_add'] = "Serial de Activo no es un cargador de laptop";
		}

	}
    public function edit($id)
    {
		if ($id != 0) {
			$this->resetValidation();
			$this->resetInput();		
			$record = Activo::findOrFail($id);

			$this->selected_id = $id; 
			$this->estado = $record-> estado;
			$this->activo_tipo_id = $record-> activo_tipo_id;
			$this->brand_id = $record-> brand_id;
			$this->modelo_id = $record-> modelo_id;
			$this->serial_number = $record-> serial_number;
			$this->patrimonial_code = $record-> patrimonial_code;
			$this->status_id = $record-> status_id;
			$this->performance_id = $record-> performance_id;
			$this->IMEI1 = $record-> imei1;
			$this->IMEI2 = $record-> imei2;
			$this->orden_compra = $record-> orden_compra;
			$this->fecha_compra = $record-> fecha_compra;
			$this->year = $record-> year;
			$this->personal_id = $record-> personal_id;
			$this->fecha_asignacion = $record-> fecha_asignacion;
			$this->vigencia_id = $record-> vigencia_id;
			$this->baja_motivo_id = $record-> baja_motivo_id;
			$this->created_by = $record-> created_by;
			$this->updated_by = $record-> updated_by;
			$this->deleted_by = $record-> deleted_by;
			$this->observations = $record-> observations;
			$this->area_id = $record-> area_id;
			$this->regularizacion = $record-> regularizacion;
			$this->observaciones_no_asignacion = $record-> observaciones_no_asignacion;
			$this->ct_id = $record-> ct_id;
			$this->notebook_id = $record-> notebook_id;
			$this->mac = $record-> mac;
		} else {
			$this->resetValidation();
			$this->resetInput();
			$this->selected_id = 0; 
			$this->estado=true;
		}

		$this->updatedActivoTipoId($this->activo_tipo_id);
		// $this->listar_selecciones();
        $this->listarSelects();
        $this->updateMode = true;
        $this->cargando = true;
        // $this->actualizandoVista = true;
    }

    public function update()
    {
		$this-> mac = isset($this->campos_habilitados['MAC']) ? $this-> mac = strtoupper(str_replace(':', '',  $this-> mac)) : null;

        $this->validate([
			'activo_tipo_id' => 'required',
			'brand_id' => 'required',
			// 'modelo_id' => 'required',
			'serial_number' => 'nullable|unique:activos,serial_number,' . $this->selected_id,
			'IMEI1' => 'nullable|unique:activos,imei1,' . $this->selected_id,
			'IMEI2' => 'nullable|unique:activos,imei2,' . $this->selected_id,
			'ct_id' => 'nullable|unique:activos,ct_id,' . $this->selected_id,
			// 'notebook_id' => 'nullable|unique:activos,notebook_id,' . $this->selected_id,
			'status_id' => 'required',
			'performance_id' => 'required',
			'mac' => 'nullable|size:12|unique:activos,mac,' . $this->selected_id,
        ],[],
			[
				'activo_tipo_id'	=> 'Tipo de Activo',
				'modelo_id'			=> 'Modelo',
				'brand_id'			=> 'Marca',
				'serial_number'		=> 'Número de Serie',
				'status_id'			=> 'Estado de activo',
				'performance_id'	=> 'Condición',
				'IMEI1'				=> 'IMEI1',
				'IMEI2'				=> 'IMEI2',
				'ct_id'				=> 'CT',
			]
		);
		
		$this->mac_address = strtoupper(str_replace(':', '', $this->mac)); // Eliminar los dos puntos y convertir a mayúsculas
		$this->mac_address = chunk_split($this->mac_address, 2, ':'); // Agregar dos puntos cada 2 caracteres
		$this->mac_address = substr($this->mac_address, 0, strlen($this->mac_address) - 1);

		$this-> IMEI1 = isset($this->campos_habilitados['IMEI1']) ? $this-> IMEI1 : null;
		$this-> IMEI2 = isset($this->campos_habilitados['IMEI2']) ? $this-> IMEI2 : null;
		// dd($this-> ct_id);
		$this-> ct_id = isset($this->campos_habilitados['CT']) ? $this-> ct_id : null;
		// dd($this-> ct_id);
		// $this-> mac = isset($this->campos_habilitados['MAC']) ? $this-> mac : null;
		// $this-> mac = isset($this->campos_habilitados['MAC']) ? $this-> mac = strtoupper(str_replace(':', '',  $this-> mac)) : null;
		$this-> modelo_id = isset($this->campos_habilitados['MODELO']) ? $this-> modelo_id : null;
		$this-> notebook_id = isset($this->campos_habilitados['NOTEBOOK']) ? $this-> notebook_id : null;

        if ($this->selected_id) {
			$record = Activo::find($this->selected_id);
            $record->update([ 
			'estado' => $this-> estado,
			'activo_tipo_id' => $this-> activo_tipo_id,
			'brand_id' => $this-> brand_id,
			'modelo_id' => $this-> modelo_id,
			'serial_number' => $this-> serial_number,
			'patrimonial_code' => $this-> patrimonial_code,
			'status_id' => $this-> status_id,
			'performance_id' => $this-> performance_id,
			'imei1' => $this-> IMEI1,
			'imei2' => $this-> IMEI2,
			'orden_compra' => $this-> orden_compra,
			'fecha_compra' => !empty($this->fecha_compra) ? $this->fecha_compra : null, // $this-> fecha_compra,
			// 'fecha_compra' => $this-> fecha_compra,
			'year' => $this-> year,			
			'personal_id' => ($this-> status_id == $this->estado_asignado || $this-> status_id == $this->estado_preasignado ) ? $this-> personal_id : null,
			// 'personal_id' => $this-> personal_id,
			'fecha_asignacion' => !empty($this->fecha_asignacion) ? $this->fecha_asignacion : null, // $this-> fecha_asignacion,
			'vigencia_id' => $this-> vigencia_id,
			'baja_motivo_id' => $this-> baja_motivo_id,
			'created_by' => $this-> created_by,
			'updated_by' => $this-> updated_by,
			'deleted_by' => $this-> deleted_by,
			'observations' => $this-> observations,
			'area_id' => $this-> area_id,
			'regularizacion' => $this-> regularizacion,
			'observaciones_no_asignacion' => $this-> observaciones_no_asignacion,
			'ct_id' => $this-> ct_id,
			// 'notebook_id' => $this-> notebook_id,
			'mac' => $this-> mac,
			'mac_address' => $this-> mac_address,
            ]);

			// dd($this-> ct_id);
			if($this->ct_id) {
				// dd($this-> ct_id);
				$record2 = Activo::find($this-> ct_id);
				
				// dd($record2);
				$record2->update([
					'notebook_id' => $this->selected_id,
					'status_id' => $this-> status_id,
					'performance_id' => $this-> performance_id,
					'personal_id' => $this-> personal_id,
				]);
			}

            $this->resetInput();
			$this->emit('limpiarDatos');
        	$this->cargando = false;
        	$this->actualizandoVista = true;
		    $this->emit('closeModal');
			// session()->flash('message', 'Activo actualizado correctamente.');
			session()->flash('message-success', 'Activo creado correctamente.');
			$this->emit('alert-success');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Activo::where('id', $id);
            $record->delete();
        }
    }

	public function importar()
    {
            $this->validate([
                'file' => 'required|mimes:xls,xlsx'    
            ]);

			
			$nombreArchivo = uniqid() . '_' . $this->file->getClientOriginalName();
			$ruta = $this->file->storeAs('importar_activos', $nombreArchivo);


				// $dir = 'temp'; // Directorio predeterminado

				// // Verifica si el directorio "temp" no existe
				// if (!Storage::exists($dir)) {
				// 	// Cambia el directorio a "tmp"
				// 	$dir = 'tmp';
				// }

				$importacion = new ActivosImport;
				// $ruta = $this->file->store($dir);
     
                Excel::import($importacion, $ruta);

				$this->resultado = $importacion->getResultado();

				// if($this->resultado) {
					$this->emit('openResultadoModal');
			
					$this->resetInput();
					$this->reset('file');
					
					// Storage::delete($ruta);
				   
					// session()->flash('message', 'Activo importado correctamente.');
					
					session()->flash('message-success', 'Activo importado correctamente.');
					$this->emit('alert-success');
	
					$this->emit('closeModal');
					$this->emit('alert');
					$this->emit('limpiarFile');
				// }				
    }

    public function exportar()
    {
        return Excel::download(new ActivosExport, 'activos.xlsx');
    }
}
