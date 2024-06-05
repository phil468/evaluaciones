<?php

namespace App\Imports;

use App\Models\EncargadosPlanesDeAccion;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Objetivo;
use App\Models\ObjetivosPrecargado;
use App\Models\Personal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlanesDeMejoraImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        // dd($rows);
        // mostrar en un mensaje de texto el resultado detallado de la importación de cada linea
        $message="";
        
        // $objetivos_precargados_tipo_1 = ObjetivosPrecargado::where('tipo_de_jerarquia_id','=','1')->get();
        // $objetivos_precargados_tipo_2 = ObjetivosPrecargado::where('tipo_de_jerarquia_id','=','2')->get();

        foreach ($rows as $index=>$row) 
        {
            $dni_evaluador = trim($row["dni_evaluador"]);
            $dni_evaluado = trim($row["dni_evaluado"]);
            $evaluacion = trim($row["identificador"]);
            $cargo_de_evaluador =  trim($row['cargo_de_evaluador']);
            $area_de_evaluador =  trim($row['area_de_evaluador']);
            $gerencia_sub_gerencia_de_evaluador =  trim($row['gerencia_sub_gerencia_de_evaluador']);
            $cargo_de_evaluado =  trim($row['cargo_de_evaluado']);
            $area_de_evaluado =  trim($row['area_de_evaluado']);
            $gerencia_sub_gerencia_de_evaluado =  trim($row['gerencia_sub_gerencia_de_evaluado']);
            $cantidad_requerida =  isset($row['cantidad_requerida']) ? trim($row['cantidad_requerida']) : '' ;
            $valor_esperado =  isset($row['valor_esperado']) ? trim($row['valor_esperado']) : '' ;
            // $jerarquia = isset($row['jerarquia']) ? trim($row['jerarquia']) : '' ;
            // $grupal = isset($row['grupal']) ? trim($row['grupal']) : '' ;

            $evaluador = Personal::where('dni',$dni_evaluador)->first();
            if(!$evaluador){
                $res = app('App\Http\Controllers\PersonalController')->actualizarPersonalNisira($dni_evaluador);
                if($res['res']){
                    $message = $res['message'] . "\n";
                    $evaluador = Personal::where('dni',$dni_evaluador)->first();                    
                } else {
                    $message = $message . "Error en la linea " . $index . " " . $res['message'] . "\n";
                }
            }

            $evaluado = Personal::where('dni',$dni_evaluado)->first();            
            if(!$evaluado){
                $res = app('App\Http\Controllers\PersonalController')->actualizarPersonalNisira($dni_evaluado);
                if($res['res']){
                    $message = $res['message'] . "\n";
                    $evaluado = Personal::where('dni',$dni_evaluado)->first();
                } else {
                    $message = $message . "Error en la linea " . $index . " " . $res['message'] . "\n";
                }
            }

            $evaluacion = Evaluacione::where('identificador',$evaluacion)->first();

            //create or update

            /// si no trae vacio 
            if(!$evaluador || !$evaluado || !$evaluacion){
                $message = $message . "Error en la linea " . $index . " " . "No se encontro el evaluador, evaluado o evaluacion" . "\n";
                            
            // dd($evaluado,$evaluador,$evaluacion);
                //pasar al siguiente registro del foreach 
                // continue;
                // return null;
            } else { 

                $record = EncargadosPlanesDeAccion::updateOrCreate(
                    [
                        'encargado_id' => $evaluador->id,
                        'empleado_id' => $evaluado->id,
                        'evaluacion_id' => $evaluacion->id
                    ],
                    [
                        'cargo_de_evaluador' => $cargo_de_evaluador,
                        'area_de_evaluador' => $area_de_evaluador,
                        'gerencia_sub_gerencia_de_evaluador' => $gerencia_sub_gerencia_de_evaluador,
                        'cargo_de_evaluado' => $cargo_de_evaluado,
                        'area_de_evaluado' => $area_de_evaluado,
                        'gerencia_sub_gerencia_de_evaluado' => $gerencia_sub_gerencia_de_evaluado,
                        'cantidad_requerida' => $cantidad_requerida,
                        'valor_esperado' => $valor_esperado
                    ]
                );
                $message = $message . "Evaluador - Evaluado creado correctamente en la linea " . $index . "\n";

            }

        }
        return $message;
        // return $message;
    }
}
