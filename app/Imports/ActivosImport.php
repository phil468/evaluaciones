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

class ActivosImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    
    // public $filas_no_cargadas = [];
    public $resultado;
    protected $contadorFila;

    function validarNumeros($cadena) {
        return preg_match('/^[0-9]+$/', $cadena);
    }

    public function collection(Collection $rows)
    {
        $this->contadorFila = 1;
        $array=[];
        foreach ($rows as $index=>$row) 
        {
            // try {

                $activo = null;
                $continuar=true;
                $this->contadorFila++;

                if(!empty(trim($row['numero_de_serie']))){
                    $activo = Activo::where('serial_number', $row['numero_de_serie'])->first(); // model or null
                }elseif(!empty(trim($row['imei1']))){
                    $activo = Activo::where('imei1', $row['imei1'])->first(); // model or null
                }
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
                    } elseif(!empty(trim($row['imei1']))) {
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
        $this->resultado = $array;
    }
    
    public function getResultado()
    {
        return $this->resultado;
    }

}
