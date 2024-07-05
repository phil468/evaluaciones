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
use Maatwebsite\Excel\Concerns\WithValidation;

class EvaluadoresObjetivosImport implements ToCollection, WithHeadingRow, WithValidation
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
            'tipo_de_jerarquia_de_objetivos' => 'required',
        ];
    }

    public function collection(Collection $rows)
    {
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
            $jerarquia = isset($row['tipo_de_jerarquia_de_objetivos']) ? trim($row['tipo_de_jerarquia_de_objetivos']) : '' ;

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

            if(!$evaluador || !$evaluado || !$evaluacion){
                $message = $message . "Error en la linea " . $index . " " . "No se encontro el evaluador, evaluado o evaluacion" . "\n";
            } else { 
                if(!$evaluacion->activa) {
                    $message = $message . "<p class='bg-danger'>Error en la linea " . $index . " " . "La evaluación no se encuentra activa, no se realizaron cambios</p>";
                } else {
                    $record = EvaluadorHasEvaluado::where([
                        'evaluador_id' => $evaluador->id,
                        'evaluado_id' => $evaluado->id,
                        'evaluacion_id' => $evaluacion->id,
                    ])->first();
    
                    $jerarquia_anterior = $record->tipo_jerarquia_id ?? null;
    
                    EvaluadorHasEvaluado::updateOrCreate(
                        [
                            'evaluador_id' => $evaluador->id,
                            'evaluado_id' => $evaluado->id,
                            'evaluacion_id' => $evaluacion->id,
                        ],
                        [
                            'cargo_de_evaluador' => $cargo_de_evaluador,
                            'area_de_evaluador' => $area_de_evaluador,
                            'gerencia_sub_gerencia_de_evaluador' => $gerencia_sub_gerencia_de_evaluador,
                            'cargo_de_evaluado' => $cargo_de_evaluado,
                            'area_de_evaluado' => $area_de_evaluado,
                            'gerencia_sub_gerencia_de_evaluado' => $gerencia_sub_gerencia_de_evaluado,
                            'tipo_jerarquia_id' => $jerarquia,
                        ]
                    );
                   
                        if ($jerarquia_anterior != $jerarquia) {
                            if($evaluacion->antes_primera_fase_activa) {
                                // Si se ha cambiado de jerarquia se eliminan 
                                // y se crean los objetivos con la información 
                                // de los objetivos precargados de este nuevo tipo de jerarquía
                                Objetivo::where('evaluador_has_evaluado_id', $record->id)->delete();
        
                                $objetivos_precargados = 
                                ObjetivosPrecargado::
                                where('tipo_de_jerarquia_id', $jerarquia)
                                ->where('evaluacion_id', $evaluacion->id)
                                ->get();
        
                                foreach ($objetivos_precargados as $objetivo_precargado) {
                                    Objetivo::updateOrCreate(
                                        [
                                            'evaluado_id' => $record-> evaluado_id,
                                            'evaluador_id' => $record-> evaluador_id,
                                            'objetivo_precargado_id' => $objetivo_precargado->id,
                                        ],
                                        [
                                            'evaluador_has_evaluado_id' => $record->id,
                                            'meta' => $objetivo_precargado-> meta,
                                            'grupal' => $objetivo_precargado-> grupal,
                                            'porcentaje_de_participacion' => $objetivo_precargado-> porcentaje_de_participacion,
                                            'tipo_objetivo_id' => $objetivo_precargado-> tipo_objetivo_id,
                                            'resultado_anterior_o_esperado' => $objetivo_precargado-> resultado_anterior_o_esperado,
                                            'minimo' => $objetivo_precargado-> minimo,
                                            'maximo' => $objetivo_precargado-> maximo,
                                            'valor' => $objetivo_precargado-> valor,
                                            'porcentaje_de_logro_STI' => $objetivo_precargado-> porcentaje_de_logro_STI,
                                            'peso_ponderado' => $objetivo_precargado-> peso_ponderado,
                                            'evaluacion_id' => $objetivo_precargado->evaluacion_id, // por defecto
                                            'estado_id' => $objetivo_precargado-> grupal ? 1 : null,
                                        ]
                                    );
                                }
                                $message = $message 
                                . "<p>Evaluador - Evaluado creado correctamente: " 
                                . $evaluador->name ." - ". $evaluado->name . "</p>";

                                $message = $message 
                                . "<p class='bg-success'>Objetivos Eliminados e ingresados correctamente para: " . 
                                $evaluador->name ." - ". $evaluado->name . "</p>";
                            
                            } else {
                                $message = $message 
                                . "<p>Evaluador - Evaluado creado correctamente: " 
                                . $evaluador->name ." - ". $evaluado->name . "</p>";
                                
                                $message = $message 
                                . "<p class='bg-warning'>Objetivos no cambiados para: " 
                                . $evaluador->name ." - ". $evaluado->name 
                                . ", porque la evaluación es posterior a inicio de primera fase, si desea hacer cambio 
                                a objetivos hágalos de manera manual</p>";
                            }
                        } else {
                            $message = $message . "<p>Evaluador - Evaluado creado correctamente: " . $evaluador->name ." - ". $evaluado->name . "</p>";
                        }
                }
            }
        }
        $this->message = $message;
    }    
    
    public function getMessage()
    {
        return $this->message;
    }
}
