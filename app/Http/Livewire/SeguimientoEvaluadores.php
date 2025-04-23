<?php

namespace App\Http\Livewire;

use App\Models\Evaluacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Respuesta;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SeguimientoEvaluadores extends Component
{
    use WithPagination;

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $pregunta_id, $opcion_id, $valor_numerico, $valor_texto, $evaluado_id;
    public $updateMode = false;

    public function render()
    {
        return view('livewire.seguimiento-evaluadores.view');
    }

    public function enviarCorreoEvaluadores()
    {
        // /evaluaciones/adm/seguimiento_evaluadores

        $evaluadores = EvaluadorHasEvaluado::all()
        ->filter(function ($evaluador) {
            return $evaluador->estado_pendiente;
        })->pluck('evaluacion_id','evaluador_id')->toArray();
        // dd($evaluadores);
        
        $correo_de_prueba = 'john.delacruz@vanguardfresh.pe';

        foreach ($evaluadores as $evaluador_id=>$evaluacion_id) {
            // Aquí puedes enviar el correo. Asegúrate de tener una clase de correo creada.
            $personal = Personal::find($evaluador_id);
            $user= $personal->user;
            $email = $user->email;
            $name = $user->name;

            $evaluacion = Evaluacione::find($evaluacion_id);

            $primera_fase_activa = $evaluacion->tipo_de_evaluacion_id == 1 ? true : $evaluacion->primera_fase_activa;
            $segunda_fase_activa = $evaluacion->segunda_fase_activa ?? false;
            $fecha_fin_segunda_fase = $evaluacion->fecha_fin_segunda_fase ?? '';

            Mail::to($email)->send(new \App\Mail\RecordatorioEvaluacion($name, $primera_fase_activa, $segunda_fase_activa, $evaluacion->tipo_de_evaluacion_id, $fecha_fin_segunda_fase));
            // Mail::to($correo_de_prueba)->send(new \App\Mail\RecordatorioEvaluacion($name, $primera_fase_activa, $segunda_fase_activa, $evaluacion->tipo_de_evaluacion_id, $fecha_fin_segunda_fase));
            \Log::info('Correo enviado', ['email' => $email]);
            //interrumpir foreach 
            // break;
        }

        $message = 'Correos enviados correctamente';
        session()->flash('message', $message);

    }
}
