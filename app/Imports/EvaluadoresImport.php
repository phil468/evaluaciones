<?php

namespace App\Imports;

use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithValidation;

class EvaluadoresImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public $message;
    
    public function rules(): array
    {
        return [
            'dni_evaluador' => 'required',
            'dni_evaluado' => 'required',
            'identificador' => 'required|exists:evaluaciones,identificador',
            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',
            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        // dd($rows);
        // mostrar en un mensaje de texto el resultado detallado de la importación de cada linea
        $message="";
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
                    [
                        'evaluador_id' => $evaluador->id,
                        'evaluado_id' => $evaluado->id,
                        'evaluacion_id' => $evaluacion->id
                    ],
                    [
                        'cargo_de_evaluador' => $cargo_de_evaluador,
                        'area_de_evaluador' => $area_de_evaluador,
                        'gerencia_sub_gerencia_de_evaluador' => $gerencia_sub_gerencia_de_evaluador,
                        'cargo_de_evaluado' => $cargo_de_evaluado,
                        'area_de_evaluado' => $area_de_evaluado,
                        'gerencia_sub_gerencia_de_evaluado' => $gerencia_sub_gerencia_de_evaluado,
                    ]
                );
                $message = $message . "<p>Evaluador - Evaluado creado correctamente: " . $evaluador->name ." - ". $evaluado->name . "</p>";
            }
        }
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
