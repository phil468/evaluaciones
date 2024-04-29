<?php

namespace App\Imports;

use App\Models\EncargadosPlanesDeAccion;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\EvaluadorHasEvaluadoObjetivo;
use App\Models\Personal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EvaluadoresObjetivosImport implements ToCollection, WithHeadingRow
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
        foreach ($rows as $index=>$row) 
        {
            $dni_evaluador = trim($row["dni_evaluador"]);
            $dni_evaluado = trim($row["dni_evaluado"]);
            $evaluacion = '004';//trim($row["identificador"]);
            $cargo_de_evaluador =  trim($row['cargo_de_evaluador']);
            $area_de_evaluador =  trim($row['area_de_evaluador']);
            $gerencia_sub_gerencia_de_evaluador =  trim($row['gerencia_sub_gerencia_de_evaluador']);
            $cargo_de_evaluado =  trim($row['cargo_de_evaluado']);
            $area_de_evaluado =  trim($row['area_de_evaluado']);
            $gerencia_sub_gerencia_de_evaluado =  trim($row['gerencia_sub_gerencia_de_evaluado']);

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
                
                //pasar al siguiente registro del foreach 
                // continue;
                // return null;
            } else { 
                $record = EvaluadorHasEvaluado::updateOrCreate(
                    ['evaluador_id' => $evaluador->id,
                    'evaluado_id' => $evaluado->id],
                    ['evaluacion_id' => $evaluacion->id,
                    'cargo_de_evaluador' => $cargo_de_evaluador,
                    'area_de_evaluador' => $area_de_evaluador,
                    'gerencia_sub_gerencia_de_evaluador' => $gerencia_sub_gerencia_de_evaluador,
                    'cargo_de_evaluado' => $cargo_de_evaluado,
                    'area_de_evaluado' => $area_de_evaluado,
                    'tipo_de_evaluacion_id' => '2',
                    'gerencia_sub_gerencia_de_evaluado' => $gerencia_sub_gerencia_de_evaluado,
                    'realizado' => null]
                );
                $record = EncargadosPlanesDeAccion::updateOrCreate(
                    ['encargado_id' => $evaluador->id,
                    'empleado_id' => $evaluado->id],
                    ['evaluacion_id' => $evaluacion->id,
                    'cargo_de_evaluador' => $cargo_de_evaluador,
                    'area_de_evaluador' => $area_de_evaluador,
                    'gerencia_sub_gerencia_de_evaluador' => $gerencia_sub_gerencia_de_evaluador,
                    'cargo_de_evaluado' => $cargo_de_evaluado,
                    'area_de_evaluado' => $area_de_evaluado,
                    'gerencia_sub_gerencia_de_evaluado' => $gerencia_sub_gerencia_de_evaluado,
                    'realizado' => null]
                );
                $message = $message . "Evaluador - Evaluado creado correctamente en la linea " . $index . "\n";

            }

        }
        return $message;
    }
}
