<?php

namespace App\Imports;

use App\Http\Livewire\Activos;
use App\Models\Activo;
use App\Models\ActivoTipo;
use App\Models\BajaMotivo;
use App\Models\Brand;
use App\Models\Modelo;
use App\Models\Performance;
use App\Models\Personal;
use App\Models\Status;
use App\Models\Vigencium;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use function PHPUnit\Framework\isNull;

class ActivosV2Import implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    // public $filas_no_cargadas = [];
    public 
    
    $resultado,
    $registros_vacios = [],
    $campos_obligatorios_no_encontrados = [],
    $resultado_registros = [],
    $nuevos_registros_tablas = [];
    
    protected $contadorFila;

    function validarNumeros($cadena) {
        return preg_match('/^[0-9]+$/', $cadena);
    }

    public function collection(Collection $rows)
    {
        dd(count($rows));
        
		$estado_stock = Status::where('name', '=', 'stock')->first()->id ?? null;
		$estado_asignado = Status::where('name', '=', 'asignado')->first()->id ?? null;
		$estado_baja = Status::where('name', '=', 'baja')->first()->id ?? null;
		$estado_preasignado = Status::where('name', '=', 'preasignado')->first()->id ?? null;

        if (count($rows)) {

            $this->registros_vacios[]='No se encontraron registros';

        } else {
            
            $this->contadorFila = 1;
            
            $array = [];

            $campos_obligatorios =  false;

            $res = [
                // 1 => 'Fila cargado con exito', 
                1 => 'Fila cargada: Nuevo registro', 
                2 => 'Fila cargada: Registros Actualizados',
                3 => 'Fila no cargada', //ya está
                4 => 'Campo(s) obligatorio(s) no encontrados', //ya está
            ];

            $nuevos_registros_tablas = [];

            foreach ($rows as $index => $row) 
            {
                $activo = null;
    
                $continuar = true;
    
                $this->contadorFila++;

                $id_res = 0;

                $registro_estado = 0;
                
                //Verificando la existencia de los cambios obligatorios
                if ( $this->contadorFila == 2) {
                    // dd($row['habilitado']);
                    
                    $info='';

                    $activo_tipo_id             = $row['id_tipo_de_activo'] ?? null;
                    $activo_tipo_name           = $row['tipo_de_activo'] ?? null;
                    $brand_id                   = $row['id_marca'] ?? null;
                    $brand_name                 = $row['marca'] ?? null;
                    $serial_number              = $row['numero_de_serie'] ?? null;
                    $status_id                  = $row['id_estado_de_activo'] ?? null;
                    $status_name                = $row['estado_de_activo'] ?? null;
                    $performance_id             = $row['id_condicion'] ?? null;
                    $performance_name           = $row['condicion'] ?? null;
    
                    // Campos Obligatorios
    
                    if (
                        (!isset($serial_number))
                        || (!isset($activo_tipo_id) && !isset($activo_tipo_name))
                        || (!isset($status_id) && !isset($status_name))
                        || (!isset($brand_id) && !isset($brand_name))
                        // (!isset($modelo_id) && !isset($modelo_codigo)) ||
                        || (!isset($performance_id) && !isset($performance_name))
                        //|| (!isset($habilitado))
                    ) {
   
                        $campos_obligatorios =  false;
    
                        if(!isset($serial_number)){
                            $info = $info.' El campo "Numero de Serie" es obligatorio, campo no encontrado.\n';
                        }
                        
                        if(!isset($activo_tipo_id) && !isset($activo_tipo_name)){
                            $info = $info.' El campo "ID Tipo de Activo" o "Tipo de Activo" son obligatorios, campos no encontrados.\n';
                        } 
            
                        if(!isset($status_id) && !isset($status_name)){
                            $info = $info.' El campo "ID Estado de Activo" o "Estado de Activo" son obligatorios, campos no encontrados.\n';
                        } 
            
                        if(!isset($brand_id) && !isset($brand_name)){
                            $info = $info.' El campo "ID Marca" o "Marca" son obligatorios, campos no encontrados.\n';
                        } 
                        
                        if(!isset($performance_id) && !isset($performance_name)){
                            $info = $info.' El campo "ID Condicion" o "Condicion" son obligatorios, campos no encontrados.\n';
                        }                 
                        
                        $this->campos_obligatorios_no_encontrados[] = ['info' =>$info];

                        $id_res = 4;

                        break;

                    } else {
                        $campos_obligatorios =  true;
                    }
                }
                    
                if ($campos_obligatorios) {
                        
                    // try {
    
                        $info = ''; //Para agregar la información de lo realizado en la fila

                    /**INICIO - Cargamos cada campo en una variable. Si el campo existe, le quitamos los espacios en blanco*/

                        /**Campos obligatorios */
                            $serial_number              = $row['numero_de_serie'] ?? null;
                            isset($serial_number) ? $serial_number = trim($serial_number) : null ;
                            $activo_tipo_id             = $row['id_tipo_de_activo'] ?? null;
                            isset($activo_tipo_id) ? $activo_tipo_id = trim($activo_tipo_id) : null ;
                            $activo_tipo_name           = $row['tipo_de_activo'] ?? null;
                            isset($activo_tipo_name) ? $activo_tipo_name = trim($activo_tipo_name) : null ;
                            $brand_id                   = $row['id_marca'] ?? null;
                            isset($brand_id) ? $brand_id = trim($brand_id) : null ;
                            $brand_name                 = $row['marca'] ?? null;
                            isset($brand_name) ? $brand_name = trim($brand_name) : null ;
                            $status_id                  = $row['id_estado_de_activo'] ?? null;
                            isset($status_id) ? $status_id = trim($status_id) : null ;
                            $status_name                = $row['estado_de_activo'] ?? null;
                            isset($status_name) ? $status_name = trim($status_name) : null ;
                            $performance_id             = $row['id_condicion'] ?? null;
                            isset($performance_id) ? $performance_id = trim($performance_id) : null ;
                            $performance_name           = $row['condicion'] ?? null;
                            isset($performance_name) ? $performance_name = trim($performance_name) : null ;

                        /**Campos opcionales */
                            $modelo_id                  = $row['id_modelo'] ?? null;
                            isset($modelo_id) ? $modelo_id = trim($modelo_id) : null ;
                            $modelo_codigo              = $row['codigo_de_modelo'] ?? null;
                            isset($modelo_codigo) ? $modelo_codigo = trim($modelo_codigo) : null ;
                            $modelo_name                = $row['nombre_de_modelo'] ?? null;
                            isset($modelo_name) ? $modelo_name = trim($modelo_name) : null ;
                            
                            $personal_id                = $row['personal_id'] ?? null;
                            isset($personal_id) ? $personal_id = trim($personal_id) : null ;
                            $personal_dni               = $row['dni'] ?? null;
                            isset($personal_dni) ? $personal_dni = trim($personal_dni) : null ;
                            $personal_name              = $row['nombre_de_personal'] ?? null;
                            isset($personal_name) ? $personal_name = trim($personal_name) : null ;

                            $vigencia_id                = $row['id_vigencia'] ?? null;
                            isset($vigencia_id) ? $vigencia_id = trim($vigencia_id) : null ;
                            $vigencia_name              = $row['vigencia'] ?? null;
                            isset($vigencia_name) ? $vigencia_name = trim($vigencia_name) : null ;

                            $baja_motivo_id             = $row['id_motivo_de_baja'] ?? null;
                            isset($baja_motivo_id) ? $baja_motivo_id = trim($baja_motivo_id) : null ;
                            $baja_motivo_name           = $row['motivo_de_baja'] ?? null;
                            isset($baja_motivo_name) ? $baja_motivo_name = trim($baja_motivo_name) : null ;

                            $area_id                    = $row['id_de_area'] ?? null;
                            isset($area_id) ? $area_id = trim($area_id) : null ;
                            $area_name                       = $row['area'] ?? null;
                            isset($area_name) ? $area_name = trim($area_name) : null ;

                            $ct_id                      = $row['id_de_ct'] ?? null;                        
                            isset($ct_id) ? $ct_id = trim($ct_id) : null ;
                            $ct_serial_number                    = $row['ct'] ?? null;
                            isset($ct_serial_number) ? $ct_serial_number = trim($ct_serial_number) : null ;

                            $notebook_id                = $row['id_de_notebook'] ?? null;
                            isset($notebook_id) ? $notebook_id = trim($notebook_id) : null ;
                            $notebook_serial_number                   = $row['notebook'] ?? null;
                            isset($notebook_serial_number) ? $notebook_serial_number = trim($notebook_serial_number) : null ;

                            $estado                     = $row['habilitado'] ?? null;
                            isset($estado) ? $estado = trim($estado) : null ;

                            $imei1                      = $row['imei1'] ?? null;
                            isset($imei1) ? $imei1 = trim($imei1) : null ;
                            $imei2                      = $row['imei2'] ?? null;
                            isset($imei2) ? $imei2 = trim($imei2) : null ;

                            $orden_compra               = $row['orden_de_compra'] ?? null;
                            isset($orden_compra) ? $orden_compra = trim($orden_compra) : null ;
                            $fecha_compra               = $row['fecha_de_compra'] ?? null;
                            isset($fecha_compra) ? $fecha_compra = trim($fecha_compra) : null ;
                            $year                       = $row['año'] ?? null;
                            isset($year) ? $year = trim($year) : null ;
                            $fecha_asignacion           = $row['fecha_de_asignacion'] ?? null;
                            isset($fecha_asignacion) ? $fecha_asignacion = trim($fecha_asignacion) : null ;
                            $fecha_de_vigencia          = $row['fecha_de_vigencia'] ?? null;
                            isset($fecha_de_vigencia) ? $fecha_de_vigencia = trim($fecha_de_vigencia) : null ;
                            $fecha_devolucion           = $row['fecha_de_devolucion'] ?? null;
                            isset($fecha_devolucion) ? $fecha_devolucion = trim($fecha_devolucion) : null ;
                            $fecha_baja                 = $row['fecha_de_baja'] ?? null;
                            isset($fecha_baja) ? $fecha_baja = trim($fecha_baja) : null ;
                            
                            $observations               = $row['observaciones'] ?? null;
                            isset($observations) ? $observations = trim($observations) : null ;

                            $regularizacion             = $row['regularizacion'] ?? null;
                            isset($regularizacion) ? $regularizacion = trim($regularizacion) : null ;

                            
                            $mac                        = $row['mac'] ?? null;//sin puntos
                            isset($mac) ? $mac = trim($mac) : null ;
                            $mac_address                = $row['mac_address'] ?? null;//con puntos
                            isset($mac_address) ? $mac_address = trim($mac_address) : null ;

                            
                            // $patrimonial_code           = $row['codigo_patrimonial'] ?? null;
                            // isset($patrimonial_code) ? $patrimonial_code = trim($patrimonial_code) : null ;
                            // $created_by                 = $row['created_by']??null;
                            // isset($serial_number) ? $serial_number = trim($serial_number) : null ;
                            // $updated_by                 = $row['updated_by']??null;
                            // isset($serial_number) ? $serial_number = trim($serial_number) : null ;
                            // $deleted_by                 = $row['deleted_by']??null;
                            // isset($serial_number) ? $serial_number = trim($serial_number) : null ;
                            // $asignacion_has_activo_id   = $row['asignacion_has_activo_id']??null;
                            // isset($serial_number) ? $serial_number = trim($serial_number) : null ;


                            // $observaciones_no_asignacion= $row['observaciones_no_asignacion'];
                            // isset($serial_number) ? $serial_number = trim($serial_number) : null ;

                    /**FIN - Cargamos cada campo en una variable. Si el campo existe, le quitamos los espacios en blanco*/


                        $activo = null;
                        $activo_tipo = null;
                        $brand = null;
                        $status = null;
                        $performance = null;

                        $modelo = null;
                        $personal = null;
                        $vigencia = null;
                        $baja_motivo = null;
                        $area = null;
                        $ct = null;
                        $notebook = null;

                        // $ct_id = null;
                        /**INICIO - Evaluamos que los campos obligatorios no estén en blanco*/

                            if(!empty($serial_number)){
                                $activo = Activo::firstOrNew(['serial_number' => $serial_number]); // model or null                     
                            } else {
                                $id_res = 3;
                                $info = $info.' Número de serie en blanco |';
                            }

                            if (isset($activo_tipo_id) && !empty($activo_tipo_id)) {
                                $activo_tipo = ActivoTipo::where('id', $activo_tipo_id)->first(); // model or null
                                if (!$activo_tipo) {
                                    $id_res = 3;
                                    $info = $info.' Id tipo de activo no encontrado en la base de datos |';
                                }
                            } else {
                                if (isset($activo_tipo_name) && !empty($activo_tipo_name)) {
                                    $activo_tipo = ActivoTipo::where('name', $activo_tipo_name)->first(); // model or null
                                    if (!$activo_tipo) {
                                        $activo_tipo = ActivoTipo::create([
                                            'name' => $activo_tipo_name,
                                            'estado' => 1
                                        ]);
                                        $nuevos_registros_tablas[] = ['tabla' => 'Tipo de Activo', 'nombre'=>$activo_tipo->name, 'fila'=>$this->contadorFila];
                                        $info = $info.' Nuevo Tipo de activo ingresado |';
                                    }
                                } else {
                                    $id_res = 3;
                                    $info = $info.' Tipo de activo en blanco |';
                                }
                            }

                            if(isset($brand_id) && !empty($brand_id)){
                                $brand = Brand::where('id', $brand_id)->first(); // model or null
                                if (!$brand) {
                                    $id_res = 3;
                                    $info = $info.' Id Marca no encontrado en la base de datos |';
                                }
                            } else {
                                if(isset($brand_name) && !empty($brand_name)){
                                    $brand = Brand::where('name', $brand_name)->first(); // model or null
                                    if (!$brand) {
                                        $brand = Brand::create([
                                            'name' => $brand_name,
                                            'estado' => 1
                                        ]);
                                        $nuevos_registros_tablas[] = ['tabla' => 'Marca', 'nombre'=>$brand->name, 'fila'=>$this->contadorFila];
                                        $info = $info.' Nueva Marca ingresada |';
                                    }
                                } else {
                                    $id_res = 3;
                                    $info = $info.' Marca en blanco |';
                                }
                            }
                            
                            if(isset($status_id) && !empty($status_id)){
                                $status = Status::where('id', $status_id)->first(); // model or null
                                if (!$status) {
                                    $id_res = 3;
                                    $info = $info.' Id Estado de Activo no encontrado en la base de datos |';
                                }
                            } else {
                                if(isset($status_name) && !empty($status_name)){
                                    $status = Status::where('name', $status_name)->first(); // model or null
                                    if (!$status) {
                                        $status = Status::create([
                                            'name' => $status_name,
                                            'estado' => 1
                                        ]);
                                        $nuevos_registros_tablas[] = ['tabla' => 'Estado de Activo', 'nombre'=>$status->name, 'fila'=>$this->contadorFila];
                                        $info = $info.' Nuevo Estado de Activo ingresado |';
                                    }
                                } else {
                                    $id_res = 3;
                                    $info = $info.' Estado de activo en blanco |';
                                }
                            }
                            
                            if(isset($performance_id) && !empty($performance_id)){
                                $performance = Performance::where('id', $performance_id)->first(); // model or null
                                if (!$performance) {
                                    $id_res = 3;
                                    $info = $info.' Id Condición no encontrada en la base de datos |';
                                }
                            } else {
                                if(isset($performance_name) && !empty($performance_name)){
                                    $performance = Performance::where('name', $performance_name)->first(); // model or null
                                    if (!$performance) {
                                        $performance = Performance::create([
                                            'name' => $performance_name,
                                            'estado' => 1
                                        ]);
                                        $nuevos_registros_tablas[] = ['tabla' => 'Condición', 'nombre'=>$performance->name, 'fila'=>$this->contadorFila];
                                        $info = $info.' Nueva Condición ingresada |';
                                    }
                                } else {
                                    $id_res = 3;
                                    $info = $info.' Condición en blanco |';
                                }
                            }

                        /**INICIO - Evaluamos los campos opcionales si son null no se agregan, si no son null se evalua si no están en blanco*/
                        if ($id_res == 3) {                            
                            $array[] = ['info' => $res[$id_res].' | '.substr(trim($info), 0 , -2) ,'fila'=>$this->contadorFila];
                            continue;
                        } else {
                            $activo -> activo_tipo_id = $activo_tipo -> id;
                            $activo -> brand_id = $brand -> id;
                            $activo -> status_id = $status -> id;
                            $activo -> performance_id = $performance -> id;

                            if ($activo) {
                                # Actualizar o insertar

                                // Actualiza el modelo solo si está presente en el archivo Excel
                                if (
                                    $modelo_id !== null ||
                                    $modelo_codigo !== null ||
                                    $modelo_name !== null
                                ) {
                                    if(isset($modelo_id) && !empty($modelo_id)) {
                                        $modelo = Modelo::where('id', $modelo_id)->first(); // model or null
                                        if (!$modelo) {
                                            $info = $info.' Id Modelo no encontrada en la base de datos |';
                                        }
                                    } else {
                                        if(isset($modelo_name) && !empty($modelo_name)){
                                            $modelo = Modelo::where('name', $modelo_name)->first(); // model or null
                                            if (!$modelo) {
                                                $modelo = Modelo::create([
                                                    'name' => $modelo_name,                                                
                                                    'codigo' => $modelo_codigo??$modelo_name,
                                                    'estado' => 1
                                                ]);
                                                $nuevos_registros_tablas[] = ['tabla' => 'Modelo', 'nombre'=>$modelo->name, 'fila'=>$this->contadorFila];
                                                $info = $info.' Nuevo Modelo ingresado |';
                                            }
                                        } else {
                                            if(isset($modelo_codigo) && !empty($modelo_codigo)){
                                                $modelo = Modelo::where('name', $modelo_codigo)->first(); // model or null
                                                if (!$modelo) {
                                                    $modelo = Modelo::create([
                                                        'name' => $modelo_codigo,
                                                        'codigo' => $modelo_codigo,
                                                        'estado' => 1
                                                    ]);
                                                    $nuevos_registros_tablas[] = ['tabla' => 'Modelo', 'nombre'=>$modelo->name, 'fila'=>$this->contadorFila];
                                                    $info = $info.' Nuevo Modelo ingresado |';
                                                }
                                            } else {
                                                $info = $info.' Modelo en blanco |';
                                            }
                                        }
                                    }
                                    $activo->modelo_id = $modelo -> id ?? null;
                                }

                                
                                if (
                                    $vigencia_id !== null ||
                                    $vigencia_name !== null
                                ) {
                                    if(isset($vigencia_id) && !empty($vigencia_id)) {
                                        $vigencia = Vigencium::where('id', $vigencia_id)->first(); // model or null
                                        if (!$vigencia) {
                                            $info = $info.' Id Modelo no encontrada en la base de datos |';
                                        }
                                    } else {
                                        if(isset($vigencia_name) && !empty($vigencia_name)){
                                            $vigencia = Vigencium::where('name', $vigencia_name)->first(); // model or null
                                            if (!$vigencia) {
                                                $vigencia = Vigencium::create([
                                                    'name' => $vigencia_name,
                                                    'estado' => 1
                                                ]);
                                                $nuevos_registros_tablas[] = ['tabla' => 'Vigencia', 'nombre'=>$vigencia->name, 'fila'=>$this->contadorFila];
                                                $info = $info.' Nueva Vigencia ingresado |';
                                            }
                                        }
                                    }
                                    }
                                    $activo->vigencia_id = $vigencia -> id ?? null;
                                }

                                if ($estado !== null) {
                                    $activo->estado = (strtoupper(trim($estado)) == "SI" ? 1 : 0) ;
                                    $registro_estado = $activo->estado;
                                }

                                //se puede mejorar logia de campos obligatorios por tipo de activo
                                if ($imei1 !== null) {
                                    $activo->imei1 = $imei1;
                                }

                                if ($imei2 !== null) {
                                    $activo->imei2 = $imei2;
                                }

                                if ($orden_compra !== null) {
                                    $activo->orden_compra = $orden_compra;
                                }

                                if ($fecha_compra !== null) {
                                    $activo->fecha_compra = 
                                    (
                                        $fecha_compra == '' 
                                        ? NULL 
                                        : date('Y-m-d',(Date::excelToTimestamp($fecha_compra,'America/Lima')))
                                    );
                                }

                                if ($year !== null) {
                                    $activo->year = $year;
                                }
                                
                                if ($regularizacion !== null) {
                                    $activo->regularizacion = (strtoupper(trim($regularizacion)) == 'SI' ? 1 : 0);
                                }

                                /**Se coloca fecah de asignación solo si el estado actual es  */
                                if ($fecha_asignacion !== null) {
                                    if ( 
                                        (
                                            $activo->status_id == $estado_stock
                                            || $activo->status_id == $estado_preasignado
                                        )  && $status->id == $estado_preasignado
                                        ) {
                                        $activo->fecha_asignacion = 
                                        (
                                            $fecha_asignacion == '' 
                                            ? NULL 
                                            : date('Y-m-d',(Date::excelToTimestamp($fecha_asignacion,'America/Lima')))
                                        );
                                        // $activo->fecha_asignacion = $fecha_asignacion;
                                    }
                                }

                                if ($fecha_de_vigencia !== null) {
                                    if (
                                        ( $activo->status_id == $estado_stock 
                                            || $activo->status_id == $estado_baja
                                            ) && 
                                            $status->id == $estado_preasignado
                                        )
                                        {
                                        $activo->fecha_de_vigencia = 
                                        (
                                            $fecha_de_vigencia == '' 
                                            ? NULL 
                                            : date('Y-m-d',(Date::excelToTimestamp($fecha_de_vigencia,'America/Lima')))
                                        );
                                        // $activo->fecha_de_vigencia = $fecha_de_vigencia;
                                    }
                                }

                                if ($fecha_devolucion !== null) {
                                    $activo->fecha_devolucion = 
                                    (
                                        $fecha_devolucion == '' 
                                        ? NULL 
                                        : date('Y-m-d',(Date::excelToTimestamp($fecha_devolucion,'America/Lima')))
                                    );
                                    // $activo->fecha_devolucion = $fecha_devolucion;
                                }

                                if ($fecha_baja !== null) {
                                    
                                    if ($activo->status_id == $estado_stock && $status->id == $estado_baja) {
                                        $activo->fecha_baja = 
                                        (
                                            $fecha_baja == '' 
                                            ? NULL 
                                            : date('Y-m-d',(Date::excelToTimestamp($fecha_baja,'America/Lima')))
                                        );
                                    }
                                }
                                if ($observations !== null) {
                                    $activo->observations = $observations;
                                }

                                if ($mac !== null) {
                                    $activo->mac = $mac;
                                }

                                if ($mac_address !== null) {
                                    $activo->mac_address = $mac_address;
                                }

                                // $modelo = null;
                                // $personal = null;
                                // $vigencia = null;
                                // $baja_motivo = null;
                                // $area = null;
                                // $ct = null;
                                // $notebook = null;

                                // modelo_id
                                // modelo_codigo
                                // modelo_name
                                // personal_id
                                // personal_dni
                                // personal_name
                                // vigencia_id
                                // vigencia_name
                                // baja_motivo_id
                                // baja_motivo_name
                                // area_id
                                // area_name
                                // ct_id
                                // ct_serial_number
                                // notebook_id
                                // notebook_serial_number    



                                $activo->save();
                                
                                $id_res = 2;

                            } else {
                                # Crear
                                Activo::create([
                                    //todos los campos
                                ]);
                                $id_res = 1;
                            }

                        }     
                        
                        if(isset($modelo_id) && !empty($modelo_id)){
                            $modelo = Modelo::where('id', $modelo_id)->first(); // model or null
                            if ($modelo) {
                                # Sigue
                            } else {
                                $id_res = 3;
                                $info = $info.' Id Modelo no encontrado en la base de datos |';
                            }
                        } else {
                            if(isset($status_name) && !empty($status_name)){
                                $status = Status::where('name', $status_name)->first(); // model or null
                                if ($status) {
                                    # Sigue
                                } else {                                    
                                    $status = Status::create([
                                        'name' => $status_name,
                                        'estado' => 1
                                    ]);
                                    $nuevos_registros_tablas[] = ['tabla' => 'Modelo', 'nombre'=>$status->name, 'fila'=>$this->contadorFila];
                                    $info = $info.' Nuevo Modelo ingresado |';
                                }
                            } else {
                                $info = $info.' Modelo en blanco |';
                            }
                        }

                        // $modelo = null;
                        // $personal = null;
                        // $vigencia = null;
                        // $baja_motivo = null;
                        // $area = null;
                        // $ct = null;
                        // $notebook = null;

                        // modelo_id
                        // modelo_codigo
                        // modelo_name
                        // personal_id
                        // personal_dni
                        // personal_name
                        // vigencia_id
                        // vigencia_name
                        // baja_motivo_id
                        // baja_motivo_name
                        // area_id
                        // area_name
                        // ct_id
                        // ct_serial_number
                        // notebook_id
                        // notebook_serial_number

                        // elseif(!empty(trim($row['imei1']))){
                        //     $activo = Activo::where('imei1', $row['imei1'])->first(); // model or null
                        // }
                        
                        if (!empty($activo)) {
                            if($activo->asignacion_has_activo_id) {
                                $continuar=false;
                                if(!empty(trim($row['numero_de_serie']))){
                                    $array[] = ['dato' => 'Número de Serie','valor'=>$row['numero_de_serie'],'id'=>$activo->id,'fila'=>$this->contadorFila];
                                }elseif(!empty(trim($row['imei1']))){
                                    $array[] = ['dato' => 'IMEI1','valor'=>$row['numero_de_serie'],'id'=>$activo->id,'fila'=>$this->contadorFila];
                                }
                            } else {
                                $continuar=true;
                            }
                        } else {                
                            $continuar=true;
                        }
    
                        if($continuar) {
            
                            $activo_tipo = null;
                            $marca = null;
                            $modelo = null;
                            $estado_de_activo = null;
                            $condicion = null;
                            $personal = null;
                            $vigencia = null;
                            $motivo_de_baja = null;
                            $regularizacion = null;
                            $ct_id = null;
                
                            //Recuperando o insertando tipo de activo por id 
                            //de no existir el id
                            //recuperando o insertando tipo de activo por nombre 
                            if(!empty(trim($row['id_tipo_de_activo']))){
                                $activo_tipo = ActivoTipo::firstOrCreate(
                                    ['id' => trim($row['id_tipo_de_activo'])],
                                    ['name' => trim($row['tipo_de_activo']) , 'estado' => 1]
                                );
                                } elseif(!empty(trim($row['tipo_de_activo']))) {
                                    $activo_tipo = ActivoTipo::firstOrCreate(
                                        ['name' => trim($row['tipo_de_activo'])],
                                        ['estado' => 1]
                                    );
                                }
                                
                            //Recuperando o insertando marca por id 
                            //de no existir el id
                            //recuperando o insertando marca por nombre 
                            if(!empty(trim($row['id_marca']))){
                                $marca = Brand::firstOrCreate(
                                    ['id' => trim($row['id_marca'])],
                                    ['name' => trim($row['marca']) , 'estado' => 1]
                                );
                                } elseif(!empty(trim($row['marca']))) {
                                    $marca = Brand::firstOrCreate(
                                        ['name' => trim($row['marca'])],
                                        ['estado' => 1]
                                    );
                                }
                            //Recuperando o insertando modelo por id 
                            //de no existir el id
                            //recuperando o insertando modelo por nombre 
                            if(!empty(trim($row['id_modelo']))){
                                $modelo = Modelo::firstOrCreate(
                                    ['id' => trim($row['id_modelo'])],
                                    [
                                        'name' => trim($row['nombre_de_modelo']), 
                                        'codigo' => trim($row['codigo_de_modelo']) , 
                                        'marca_id' => $marca->id ?? null , 
                                        'estado' => 1]
                                );
                                } elseif(!empty(trim($row['codigo_de_modelo']))) {
                                    $modelo = Modelo::firstOrCreate(
                                        ['codigo' => trim($row['codigo_de_modelo'])],
                                        [
                                            'name' => trim($row['nombre_de_modelo']) == '' ? trim($row['codigo_de_modelo']) : trim($row['nombre_de_modelo']),
                                            'marca_id' => $marca->id ?? null , 
                                            'estado' => 1
                                        ]
                                    );
                                }
                
                                //Recuperando o insertando estado de activo por id 
                                //de no existir el id
                                //recuperando o insertando estado de activo por nombre 
                                if(!empty(trim($row['id_estado_de_activo']))){
                                    $estado_de_activo = Status::firstOrCreate(
                                        ['id' => trim($row['id_estado_de_activo'])],
                                        ['name' => trim($row['estado_de_activo']) , 'estado' => 1]
                                    );
                                    } elseif(!empty(trim($row['estado_de_activo']))) {
                                        $estado_de_activo = Status::firstOrCreate(
                                            ['name' => trim($row['estado_de_activo'])],
                                            ['estado' => 1]
                                        );
                                    }
                
                                    //Recuperando o insertando condicion por id 
                                    //de no existir el id
                                    //recuperando o insertando condicion por nombre 
                                    if(!empty(trim($row['id_condicion']))){
                                        $condicion = Performance::firstOrCreate(
                                            ['id' => trim($row['id_condicion'])],
                                            ['name' => trim($row['condicion']) , 'estado' => 1]
                                        );
                                        } elseif(!empty(trim($row['condicion']))) {
                                            $condicion = Performance::firstOrCreate(
                                                ['name' => trim($row['condicion'])],
                                                ['estado' => 1]
                                            );
                                        }
                
                                        //Recuperando o insertando vigencia por id 
                                        //de no existir el id
                                        //recuperando o insertando vigencia por nombre 
                                        if(!empty(trim($row['id_vigencia']))){
                                            $vigencia = Vigencium::firstOrCreate(
                                                ['id' => trim($row['id_vigencia'])],
                                                ['name' => trim($row['vigencia']) , 'estado' => 1]
                                            );
                                            } elseif(!empty(trim($row['vigencia']))) {
                                                $vigencia = Vigencium::firstOrCreate(
                                                    ['name' => trim($row['vigencia'])],
                                                    ['estado' => 1]
                                                );
                                            }
                
                                            //Recuperando o insertando motivo de baja por id 
                                            //de no existir el id
                                            //recuperando o insertando motivo de baja por nombre 
                                            if(!empty(trim($row['id_motivo_de_baja']))){
                                                $motivo_de_baja = BajaMotivo::firstOrCreate(
                                                    ['id' => trim($row['id_motivo_de_baja'])],
                                                    ['name' => trim($row['motivo_de_baja']) , 'estado' => 1]
                                                );
                                                } elseif(!empty(trim($row['motivo_de_baja']))) {
                                                    $motivo_de_baja = BajaMotivo::firstOrCreate(
                                                        ['name' => trim($row['motivo_de_baja'])],
                                                        ['estado' => 1]
                                                    );
                                                }
                
                                            //Recuperando o insertando usuario
                                            if(
                                                strlen((trim($row['dni']))) >= 8 
                                                && $this->validarNumeros(!empty(trim($row['dni'])))
                                                ){
                                                if(!empty(trim($row['dni']))){
                                                    $estado_de_activo = Status::firstOrCreate(
                                                        ['name' => 'Preasignado'],
                                                        ['estado' => 1]
                                                    );
                                                    $personal = Personal::firstOrCreate(
                                                        ['dni' => trim($row['dni'])],
                                                        ['name' => trim($row['nombre_de_personal']) , 'estado' => 1]
                                                    );
                                                    $regularizacion = strtoupper(trim($row['regularizacion'])) == 'SI' ? 1 : null;
                                                } else {
                                                    $regularizacion = null;
                                                }
                                            }
                
                
                            //Recuperando o insertando tipo de activo por id 
                            //de no existir el id
                            //recuperando o insertando tipo de activo por nombre 
                            if(!empty(trim($row['ct']))){
                                $act = null;
                                $act = Activo::where('serial_number',trim($row['ct']))->firstOr(function () {               
                                    return null;
                                });
                                if(!isNull($act )) {
                                    $ct_id = $act->id;
                                } else {
                                    $ct_id = null;
                                }
                            }
                
                            // dd(trim($row['estado']));
                            //Recuperando o insertando usuario
                            if(!empty(trim($row['numero_de_serie']))){
                                $record = Activo::updateOrCreate(
                                    [
                                        'serial_number' => trim($row['numero_de_serie']),
                                    ],
                                    [
                                        'imei1'             => trim($row['imei1'])==''?null:trim($row['imei1']),
                                        'imei2'             => trim($row['imei2'])==''?null:trim($row['imei2']),
                
                                        'estado'            => strtoupper(trim($row['estado'])) == "ACTIVO" ? 1 : 0,
                                        'orden_compra'      => trim($row['orden_de_compra']),
                                        'fecha_compra'      => trim($row['fecha_de_compra']) == '' 
                                            ? NULL : date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_de_compra']),'America/Lima'))),
                                        'fecha_asignacion'  => trim($row['fecha_de_asignacion']) == '' 
                                            ? NULL : date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_de_asignacion']),'America/Lima'))),
                                        'fecha_vigencia'  => trim($row['fecha_de_vigencia'])=='' 
                                            ? NULL : date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_de_vigencia']),'America/Lima'))),
                                        'observations'      => trim($row['observaciones']),
                
                                        'activo_tipo_id'    => $activo_tipo->id ?? null,
                                        'brand_id'          => $marca->id ?? null,
                                        'modelo_id'         => $modelo->id ?? null,
                                        'status_id'         => $estado_de_activo->id ?? null,
                                        'performance_id'    => $condicion->id ?? null,
                                        'personal_id'       => $personal->id ?? null,
                                        'vigencia_id'       => $vigencia->id ?? null,
                                        'baja_motivo_id'    => $motivo_de_baja->id ?? null,
                                        'regularizacion'    => $regularizacion ?? null,
                                        'ct_id'             => $ct_id ?? null,
                                    ]
                                );
                                // dd($record);
                            } 
                            elseif(!empty(trim($row['imei1']))) {
                                $record = Activo::updateOrCreate(
                                    [
                                        'imei1'             => trim($row['imei1'])==''?null:trim($row['imei1']),
                                    ],
                                    [
                                        'serial_number'     => trim($row['numero_de_serie']),
                                        'imei2'             => trim($row['imei2']) == '' ? null:trim($row['imei2']),
                
                                        'estado'            => strtoupper(trim($row['estado'])) == "ACTIVO" ? 1 : 0,
                                        'orden_compra'      => trim($row['orden_de_compra']),
                                        'fecha_compra'      => trim($row['fecha_de_compra']) == '' 
                                            ? NULL : date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_de_compra']),'America/Lima'))),
                                        'fecha_asignacion'  => trim($row['fecha_de_asignacion']) == '' 
                                            ? NULL : date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_de_asignacion']),'America/Lima'))),
                                        'fecha_vigencia'  => trim($row['fecha_de_vigencia'])=='' 
                                            ? NULL : date('Y-m-d',(Date::excelToTimestamp(trim($row['fecha_de_vigencia']),'America/Lima'))),
                                        'observations'      => trim($row['observaciones']),
                
                                        'activo_tipo_id'    => $activo_tipo->id ?? null,
                                        'brand_id'          => $marca->id ?? null,
                                        'modelo_id'         => $modelo->id ?? null,
                                        'status_id'         => $estado_de_activo->id ?? null,
                                        'performance_id'    => $condicion->id ?? null,
                                        'personal_id'       => $personal->id ?? null,
                                        'vigencia_id'       => $vigencia->id ?? null,
                                        'baja_motivo_id'    => $motivo_de_baja->id ?? null,
                                        'regularizacion'    => $regularizacion ?? null,
                                        'ct_id'             => $ct_id ?? null,
                                    ]
                                );
                            }
                        }
                // } catch (\Throwable $th) {
                //     dd($index);
                // }
                
                }
    
            }

            $this->resultado_registros = $array;

            $this->resultado = $array;
        }
        
    }
    
    public function getResultado()
    {
        return //$this->resultado;
        [
            $this->registros_vacios,
            $this->campos_obligatorios_no_encontrados,
            $this->resultado_registros
        ];
    }

}
