<?php

namespace App\Http\Controllers\Api\ws;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PersonalController;
use App\Models\Asistencium;
use App\Models\Capacitacione;
use App\Models\CapacitacionHasPersonal;
use App\Models\Personal;
use App\Models\Sesione;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsistenciasController extends Controller
{
    public function getUpdatesFromServer(Request $request)
    {
        
        $tipo_user = $request['tipoUser'];
        $user_id = $request['userId'];
        $lastUpdate = $request['lastUpdate'];

        //
        $response = [];
        try {

            $datamodel = [];
            $datamodel = DB::select("call sp_get_asistencias_updates ($tipo_user,$user_id,'$lastUpdate')");

            $data = [];
            foreach ($datamodel as $item) {
                $data[] = $item;
            }

            $response = $data;

        } catch (Exception $ex) {
            $response["success"] = "false";
            $response["message"] = "";
            $response["error"] = array("code" => "2", "message" => $ex->getMessage(), "errors" => []);
        }

        return response()->json(
            $response,
            200,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

    }

    public function store(Request $request) {
          
        $response = [];
        
        $asistencia = null;

        try {
            $data = $request->all();
            
            $capacitacion_id = Capacitacione::where('id',trim($data['capacitacion_id']))->first()->id;

            $sesion = Sesione::where('id',trim($data['sesion_id']))->first();

            if ($sesion == null) {
                $sesion = Sesione::updateOrcreate(
                    [
                        'capacitacion_id' => $capacitacion_id,
                        'numero_de_sesion' => $data['numero_de_sesion'],
                    ],
                    [
                        'synced' => true,
                    ]
                );
            }

            if($data['capacitacion_has_personal_id'] == 0)
            {
                // buscar personal por dni
                $data['dni'] = trim($data['dni']);
                $personal_id = null;
                $personal = Personal::where('dni', $data['dni'])->first();

                if($personal == null){
                    $personalController = new PersonalController();
                    $res = $personalController->actualizarPersonalNisira($data['dni']);
                    if($res['res'])
                    {
                        $personal = Personal::where('dni', $data['dni'])->first();
                        $personal_id = $personal->id;
                    }
                    else
                    {
                        $res = $personalController->ingresarDNI($data['dni']);
                        $personal_id = $res;
                    }
                } else {
                    $personal_id = $personal->id;
                }

                //Obtenemos el personal ID

                $personal = Personal::where('id',$personal_id)->select(
                    // 'nombres',
                    'empresa_id',
                    'gerencia_id',
                    'area_id',
                    'cargo_id',
                    'planilla_id',
                    'sede_id',
                    'tipo_de_trabajador_id',
                    'tipo_de_personal_id'
                )->first()->toArray();

                $capacitacion_has_personal = Capacitacione::find($capacitacion_id)
                ->capacitacion_has_personal()
                ->where('personal_id', $personal_id)
                ->first();
    
            //traer los campos necesarios para capacitacion_has_personal
    
            $personal = Personal::where('id',$personal_id)->select(
                // 'nombres',
                'empresa_id',
                'gerencia_id',
                'area_id',
                'cargo_id',
                'planilla_id',
                'sede_id',
                'tipo_de_trabajador_id',
                'tipo_de_personal_id'
            )->first()->toArray();
    
            // dd($personal);
    
            if (!$capacitacion_has_personal) {
                $capacitacion = Capacitacione::find($capacitacion_id);
                $capacitacion->personal()->syncWithoutDetaching([$personal_id => $personal]);
    
                $capacitacion_has_personal = Capacitacione::find($capacitacion_id)
                    ->capacitacion_has_personal()
                    ->where('personal_id', $personal_id)
                    ->first();
            }
    
            // $asistencia = Asistencium::where('sesion_id', $sesion->id)
            //     ->where('capacitacion_has_personal_id', $capacitacion_has_personal->id)
            //     ->first();
    
            // if ($asistencia) {
            //     $asistencia->active = 1;
            //     $asistencia->save();
            // } else {
            //     Asistencium::create([
            //         'sesion_id' => $sesion->id,
            //         'capacitacion_has_personal_id' => $capacitacion_has_personal->id,
            //         'active' => 1,
            //     ]);
            // }
    
            // $personal_id = null;
            } else {
                $capacitacion_has_personal = CapacitacionHasPersonal::find($data['capacitacion_has_personal_id']);
                // $sesion = Sesione::where('id',trim($data['sesion_id']))->first();
            }

            $asistencia = Asistencium::where('sesion_id', $sesion->id)
                ->where('capacitacion_has_personal_id', $capacitacion_has_personal->id)
                ->first();

            if ($asistencia) {
                $asistencia->active = $data['active']??0;
                $asistencia->save();
            } else {
                $asistencia = Asistencium::create([
                    'sesion_id' => $sesion->id,
                    'capacitacion_has_personal_id' => $capacitacion_has_personal->id,
                    'active' =>$data['active']??0,
                ]);
            }

            // $capacitacion_id = Sesione::find($data['sesion_id'])->capacitacion_id;

                // $asistencia = Asistencium::updateOrcreate(
                //     [
                //         'sesion_id' => $data['sesion_id'],
                //         'capacitacion_has_personal_id' => $data['capacitacion_has_personal_id'] ,
                //     ],
                //     [
                //         'active' => $data['active'],
                //         'synced' => true,                    
                //     ]
                // );

            $response["message"] = "Asistencia actualizada correctamente.";

            $datamodel = [];
            // $datamodel = $asistencia->get();

            // $datamodel = DB::select("call sp_get_asistencia_id ($asistencia->id)");
    
            $data = [];
            foreach ($datamodel as $item) {
                $data[] = $item;
            }

            // $data["id"] = $asistencia->id;
    
            $response["data"] = $data;
            $response["id"] = $asistencia->id;
            $response["capacitacion_has_personal_id"] = $asistencia->capacitacion_has_personal_id;            
            $response["personal"] = $asistencia->capacitacion_has_personal->personal->name ?? '';
                
            $response["success"] = true;

        } catch (Exception $ex) {
            $response["success"] = false;
            $response["message"] = "";
            $response["error"] = array("code" => "2", "message" => $ex->getMessage(), "errors" => []);
        }
    
        return response()->json(
            $response,
            200,
            [
                'Content-Type' => 'application/json;charset=UTF-8',
                'Charset' => 'utf-8'
            ],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    
    }
}
