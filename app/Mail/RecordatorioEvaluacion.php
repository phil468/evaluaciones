<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecordatorioEvaluacion extends Mailable
{
    use Queueable, SerializesModels;

    public $name_evaluador;
    public $primera_fase_activa;
    public $segunda_fase_activa;
    public $tipo_evaluacion_id;

    public function __construct(
        $name_evaluador,
        $primera_fase_activa,
        $segunda_fase_activa,
        $tipo_evaluacion_id = null
        )
    {
        $this->name_evaluador = $name_evaluador;
        $this->primera_fase_activa = $primera_fase_activa;
        $this->segunda_fase_activa = $segunda_fase_activa;
        $this->tipo_evaluacion_id = $tipo_evaluacion_id;
    }

    public function build()
    {
        //         @if ($tipo_evaluacion_id == 1)
        //     url('/evaluaciones-de-desempeno/1')
        // @endif
        // @if ($tipo_evaluacion_id == 2)
        //     url('/evaluaciones-de-desempeno/2')
        // @endif
        
        $url = url('/');
        
        if ($this->tipo_evaluacion_id == 1) {
            $url = url('/evaluaciones-de-desempeno/1');
        } elseif ($this->tipo_evaluacion_id == 2) {
            $url = url('/evaluaciones-de-desempeno/2');
        } else {
            $url = url('/');
        }

        return $this->markdown('emails.evaluaciones.recordatorio_evaluacion',[
            'url' => $url,
        ]);
    }
}
