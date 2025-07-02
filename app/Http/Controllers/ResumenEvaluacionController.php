<?php
// app/Http/Controllers/ResumenEvaluacionController.php
namespace App\Http\Controllers;

use App\Notifications\ComiteFormadoNotification;
use Illuminate\Http\Request;
use App\Models\ResumenRespuestasEvaluacionDesempenoCompetencia;
use App\Models\Personal;
use App\Models\Seccion; // O Competencia según tu modelo
use App\Models\ComiteCalibracion; // Debes crear este modelo y tabla
use App\Models\ComiteHasPersona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ResumenEvaluacionController extends Controller
{
    public function index()
    {
        return view('calibraciones.resumen_evaluacion');
    }

    public function data(Request $request)
    {
        // Puedes agregar filtros por campaña, etc.
        $resumen = ResumenRespuestasEvaluacionDesempenoCompetencia::
            with(['personal', 'competencia', 'pregunta', 'area', 'campania', 'comite'])
            ->when($request->input('campania_id'), function($query) use ($request) {
                return $query->where('campania_id', $request->input('campania_id'));
            })
            ->get()
            ->groupBy(function($item) {
                return $item->personal_id . '-' . $item->competencia_id. '-' . $item->campania_id;
            })
            ->map(function($items) {

                $first = $items->first();
                
                // Obtener las personas del comité si existe
                $comite = ComiteCalibracion::where([
                    'personal_id' => $first->personal_id,
                    // 'competencia_id' => $first->competencia_id,
                    'campania_id' => $first->campania_id
                ])->with(['personal', 'campania'])->first();

                $comite_personas = [];
                if ($comite) {
                    $comite_personas = ComiteHasPersona::where('comite_calibracion_id', $comite->id)
                        ->with('personal')
                        ->get()
                        ->map(function($p) {
                            return [
                                'id' => $p->personal->id,
                                'name' => $p->personal->name
                            ];
                        });
                }

                return [

                    'personal_id' => $first->personal_id,
                    'competencia_id' => $first->competencia_id,
                    'campania_id' => $first->campania_id,
                    'persona' => optional($first->personal)->name ?? '',
                    'competencia' => optional($first->competencia)->name ?? '',
                    'comite_personas' => $comite_personas,
                    'puntaje' => round($items->avg('puntaje'), 2),
                    'puntaje_calibrado' => round($items->avg('puntaje_calibrado'), 2) ?: '',
                    'area' => optional($first->area)->name ?? '',
                    'nivel_jerarquico' => $first->nivel_jerarquico ?? '',
                    'campania' => optional($first->campania)->name ?? '',
                    'comite' => $first->comite_calibracion_id,
                    'comentario' => optional($first->comite)->comentario ?? '',
                    'fecha' => $first->comite ? $first->comite->created_at->format('d/m/Y h:i A') : '',
                    
                    'detalle_url' => $this->getDetalleUrl($first->personal_id, $first->competencia_id, $first->campania_id),
                    'calibracion_url' => $this->getCalibracionUrl($first->personal_id, $first->competencia_id, $first->campania_id),

                ];
            })
            ->values();

        return response()->json($resumen);
    }

    private function getDetalleUrl($personal_id, $competencia_id, $campania_id)
    {
        return route('detalle.competencia', [
            'personal_id' => $personal_id,
            'competencia_id' => $competencia_id,
            'campania_id' => $campania_id
        ]);
    }

    private function getCalibracionUrl($personal_id, $competencia_id, $campania_id)
    {
        return route('calibracion.competencia', [
            'personal_id' => $personal_id,
            'competencia_id' => $competencia_id,
            'campania_id' => $campania_id
        ]);
    }

    public function detalle(Request $request)
    {
        $data = ResumenRespuestasEvaluacionDesempenoCompetencia::with(['personal', 'competencia', 'pregunta', 'area'])
            ->where('personal_id', $request->input('personal_id'))
            ->where('competencia_id', $request->input('competencia_id'))
            ->where('campania_id', $request->input('campania_id'))
            ->get();

        return view('calibraciones.detalle_evaluacion', compact('data'));
    }

    public function guardarComite(Request $request)
    {
        DB::beginTransaction();
        try {
            // Guardar comité

            $comite = ComiteCalibracion::where([
                'personal_id' => $request->personal_id,
                'campania_id' => $request->campania_id
            ])->first();

            if ($comite) {
                // actualiza los datos si ha habido cambios, primero evalua si ha habido cambios
                
                $comite->personal_id = $request->personal_id;
                $comite->campania_id = $request->campania_id;
                $comite->comentario = $request->comentario;
                // $comite->estado = 'pendiente';
                // Verifica si hay cambios en los datos
                if ($comite->isDirty()) {
                    // Si hay cambios, actualiza el registro
                    $comite->save();
                }

            } else {
                // Si no existe, crea uno nuevo
                $comite = ComiteCalibracion::create([
                    'personal_id' => $request->personal_id,
                    // 'competencia_id' => $request->competencia_id,
                    'campania_id' => $request->campania_id,
                    'comentario' => $request->comentario,
                    'area' => $request->area,
                    'nivel_jerarquico' => $request->nivel_jerarquico,
                    // 'estado' => 'pendiente'
                ]);

                // Guardar miembros
                foreach ($request->personas as $persona_id) {
                    ComiteHasPersona::create([
                        'comite_calibracion_id' => $comite->id,
                        'personal_id' => $persona_id
                    ]);
                    // enviar notificación por correo
                    $persona = Personal::find($persona_id);
                    $this->enviarCorreo($persona,$comite);
                }

            }
            // Guardar puntajes calibrados
            foreach ($request->puntaje_calibrado as $id => $puntaje) {
                ResumenRespuestasEvaluacionDesempenoCompetencia::where('id', $id)
                    ->update([
                        'puntaje_calibrado' => $puntaje,
                        'comite_calibracion_id' => $comite->id
                    ]);
            }

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al guardar el comité: ' . $e);
            \Log::error('Personas' . $request->personas);
            // Opcional: Registrar el error en un log
            return response()->json(['success' => false, 'error' => $e->getMessage()]);

        }
    }

    public function enviarCorreo($persona,$comite)
    {
        try {            
            if ($persona) {
                $persona->notify(new ComiteFormadoNotification($comite));
                // Opcional: Registrar el envío exitoso en un log
                \Log::info('Correo enviado a ' . $persona);
            } else {
                \Log::error('No se encontró personal con correo ' . $persona);
            }
            // Mail::to($correo)->send(new ComiteFormadoNotification($comite));
            // // Opcional: Registrar el envío exitoso en un log
            // \Log::info('Correo enviado a ' . $correo);
        } catch (\Exception $e) {
            // Registrar el error en un log
            \Log::error('Error al enviar correo a ' . $persona . ': ' . $e->getMessage());
            // Opcional: Lanzar una excepción o retornar un error
            // throw $e;
        }
    }

    public function obtenerDatosCalibracion($personal_id, $competencia_id, $campania_id)
    {
        try {
            $data = ResumenRespuestasEvaluacionDesempenoCompetencia::with(['personal', 'competencia', 'pregunta', 'area'])
                ->where('personal_id', $personal_id)
                ->where('competencia_id', $competencia_id)
                ->where('campania_id', $campania_id)
                ->get();

            $comite = ComiteCalibracion::where([
                'personal_id' => $personal_id,
                'campania_id' => $campania_id
            ])->first();

            return response()->json(['datos' => $data, 'comite' => $comite]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}