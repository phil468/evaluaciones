<?php

namespace App\Imports;

use App\Models\EncargadosPlanesDeAccion;
use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Objetivo;
use App\Models\ObjetivosPrecargado;
use App\Models\Personal;
use App\Models\PlanesConfiguracion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithValidation;

class EncargadosPlanesImport implements ToCollection, WithHeadingRow, WithValidation
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
            'identificador' => 'required|exists:planes_de_accion_configuracion,identificador',
            'cargo_de_evaluador' => 'required',
            'area_de_evaluador' => 'required',
            'gerencia_sub_gerencia_de_evaluador' => 'required',
            'cargo_de_evaluado' => 'required',
            'area_de_evaluado' => 'required',
            'gerencia_sub_gerencia_de_evaluado' => 'required',
            'cantidad_requerida' => 'required',
            'valor_esperado' => 'required',
            'jerarquia' => 'required',
            // 'grupal' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
        // mostrar en un mensaje de texto el resultado detallado de la importación de cada linea
        $message="";

        foreach ($rows as $index=>$row) 
        {
            // dd(1);
            $dni_evaluador = trim($row["dni_evaluador"]);
            $dni_evaluado = trim($row["dni_evaluado"]);
            $evaluacion = trim($row["identificador"]);
            $cargo_de_evaluador =  trim($row['cargo_de_evaluador']);
            $area_de_evaluador =  trim($row['area_de_evaluador']);
            $gerencia_sub_gerencia_de_evaluador =  trim($row['gerencia_sub_gerencia_de_evaluador']);
            $cargo_de_evaluado =  trim($row['cargo_de_evaluado']);
            $area_de_evaluado =  trim($row['area_de_evaluado']);
            $gerencia_sub_gerencia_de_evaluado =  trim($row['gerencia_sub_gerencia_de_evaluado']);
            $cantidad_requerida =  trim($row['cantidad_requerida']);
            $valor_esperado =  isset($row['valor_esperado']) ? trim($row['valor_esperado']) : '' ;
            $jerarquia = isset($row['jerarquia']) ? trim($row['jerarquia']) : '' ;
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

            $plan = PlanesConfiguracion::where('identificador',$evaluacion)->first();

            //create or update

            /// si no trae vacio 
            if(!$evaluador || !$evaluado || !$plan){
                $message = $message . "Error en la linea " . $index . " " . "No se encontro el evaluador, evaluado o plan" . "\n";
                            
            // dd('paso 1');
            // dd($evaluado,$evaluador,$evaluacion);
            // pasar al siguiente registro del foreach 
            // continue;
            // return null;
            } else { 
                // dd('paso 2');
                $record = EncargadosPlanesDeAccion::updateOrCreate(
                    [
                        'encargado_id' => $evaluador->id,
                        'empleado_id' => $evaluado->id,
                        'planes_de_accion_configuracion_id' => $plan->id
                    ],
                    [
                        'cargo_de_evaluador' => $cargo_de_evaluador,
                        'area_de_evaluador' => $area_de_evaluador,
                        'gerencia_sub_gerencia_de_evaluador' => $gerencia_sub_gerencia_de_evaluador,
                        'cargo_de_evaluado' => $cargo_de_evaluado,
                        'area_de_evaluado' => $area_de_evaluado,
                        'gerencia_sub_gerencia_de_evaluado' => $gerencia_sub_gerencia_de_evaluado,
                        'cantidad_requerida' => $cantidad_requerida,
                        'valor_esperado' => $valor_esperado,
                        'jerarquia' => $jerarquia,
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
